<?php

define ("ZERO_DAY_DEBUG", 0);	// 0 of 1 (1 = debug mode)

//http://setiathome.berkeley.edu/userw.php?id=8906489

function zeroDayStats()
{
	global $gi_error_count;	
	
	date_default_timezone_set("UTC"); 

	set_time_limit(6000);	// max 100 min, runs every 120 min.

	$timeStart = new DateTime();	
	$time_stamp = $timeStart->getTimestamp();
	
	LoggingOpen(LOCAL_LOGGING_FOLDER."zero_day");

	$retry_count = 3;				// try 2 times to get the data
	while ($retry_count > 0)
	{
		$failed = true;
		$sql = connectSqlSeti();
		if ($sql)
		{
			if (sqlCreateTableZeroDayStatus($sql))
			{
				writeStatusZeroDay($sql, SQL_ZERO_DAY_START_TIME, $time_stamp);
				writeStatusZeroDay($sql, SQL_ZERO_DAY_DURATION, "");
				writeStatusZeroDay($sql, SQL_ZERO_DAY_STATUS, 1);
				writeStatusZeroDay($sql, SQL_ZERO_PROCESSED_COUNT, 0);
				if (zeroDayUpdateUsers($sql,$timeStart) === true)
				{
					$retry_count = 0;
					$failed = false;
				}
			}
		}
		$retry_count--;
		if ($failed && $retry_count > 0)
		{
			if ($sql)
			{
				mysqli_close($sql);	
			}
			sleep(300);		// wait 5 min, for a retry
			LoggingAdd("(zeroDayStats) Backoff for 300 seconds.",TRUE);				
		}
	}
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	LoggingAdd("(zeroDayStats) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);	
	
	if ($sql)
	{
		writeStatusZeroDay($sql, SQL_ZERO_DAY_DURATION, $interval_string);
		writeStatusZeroDay($sql, SQL_ZERO_ERROR_COUNT, $gi_error_count);
		writeStatusZeroDay($sql, SQL_ZERO_USER_NAME, "");
		writeStatusZeroDay($sql, SQL_ZERO_DAY_STATUS, -1);		
		mysqli_close($sql);	
	}
	
	LoggingAreThereErrors();
	LoggingClose();

}

function zeroDayUpdateUsers($sql,$timeStart)
{
	$table_name = SQL_TABLE_LIST_SNL_TEAM;	
	$b_fetch_next = TRUE;
	$i_start = 0;
	$credit = 0;
	$rac = 0;
	$total_users = 0;
	$total_users_skipped = 0;
	
	$time_start = round($timeStart->getTimestamp()/3600);	
	
	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_ID.",".SQL_USER_NAME.",".SQL_LIST_TIME.",".SQL_RAC." FROM ".$table_name." LIMIT $i_start, 10";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("zeroDayUpdateUsers 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$time = $row[SQL_LIST_TIME];				
				if ($time_start > $time)
				{	
					$id = $row[SQL_ID];
					$name = $row[SQL_USER_NAME];
					$rac_sql = $row[SQL_RAC];
					if ($rac_sql > MINIMUM_RAC_FOR_ZERO_DAY_UPDATE)
					{
						sleep(5);
						$timeStop = new DateTime("now"); 
						$interval = $timeStart->diff($timeStop);
						$interval_string = $interval->format('%h H, %i M, %s S');						
						writeStatusZeroDay($sql, SQL_ZERO_USER_NAME, $name);					
						if (zeroDayGetUser($id, $credit, $rac) === true)
						{
							$status = zeroDayUpdateUserInDatabase($sql,$time_start,$id,$credit,$rac);
							if ($status !== true)
							{
								LoggingAdd("zeroDayUpdateUsers abort", TRUE);
								return $status;	
							}
							// here we fetched our first data.
							$time_last_fetch = $timeStop->getTimestamp();
							writeStatusZeroDay($sql, SQL_ZERO_DAY_TIME_FETCH, $time_last_fetch);
						}
						else
						{
							LoggingAdd("zeroDayUpdateUsers abort", TRUE);
							return false;
						}
						$total_users++;
						writeStatusZeroDay($sql, SQL_ZERO_PROCESSED_COUNT, $total_users);
						writeStatusZeroDay($sql, SQL_ZERO_DAY_DURATION, $interval_string);						
					}
					else
					{
						$total_users_skipped++;
					}
				}
				else
				{
					$total_users_skipped++;
				}				
			}
		}
		else
		{
			// empty
			$b_fetch_next = FALSE;
		}		
		$result->close();		
		$i_start = $i_start + 10;
	}
		
	LoggingAdd("(zeroDayUpdateUsers) Updated : ".$total_users." skipped: ".$total_users_skipped ,TRUE);		
	
	return true;
}

function zeroDayUpdateUserInDatabase($sql,$time_now,$id,$credit,$rac)
{
	$table_name = SQL_TABLE_LIST_SNL_TEAM;
	if (ZERO_DAY_DEBUG == 1) {loggingAdd("update: ".$id,true);}
	$sqlCommand = "UPDATE ".$table_name." SET ".SQL_LIST_TIME."='$time_now',".SQL_TOTAL_CREDIT."='$credit',".SQL_RAC."='$rac' WHERE ".SQL_ID."='$id' LIMIT 1";
	$result = $sql->query($sqlCommand);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("zeroDayUpdateUserInDatabase: ".mysqli_error($sql));
		return ERR_DATABASE;
	}
	return true;
}
	
function zeroDayGetUser($id, &$credits, &$rac)
{
	$delay_next = 5;
	$ok = false;	
	while (!$ok)
	{	
		$data_user = zeroDayReadId($id);
		if ($data_user !== false)
		{
			$credits = zeroDayReadValue($data_user, ZERO_DAY_USER_CREDITS);
			if ($credits !== false)
			{
				$rac = zeroDayReadValue($data_user, ZERO_DAY_USER_RAC);		
				if ($rac !== false)
				{				
					return true;
				}
			}
		}
		sleep($delay_next);		// the delay may cause sql to timeout.
		$delay_next += 10;
		if ($delay_next > 30)
		{
			LoggingAdd("(zeroDayGet) Not able to read, server seems down", TRUE);
			break;
		}		
	}
	return false;
}

function zeroDayReadValue($data_user, $tag)
{
	$pos = strpos($data_user, $tag);
	if ($pos === false)
	{
		LoggingAdd("(zeroDayReadValue) no data: ".$tag, TRUE);		
		return false;
	}
	$pos += strlen($tag);
	$pos_end = strpos($data_user, "<br/>", $pos);
	if ($pos_end === false)
	{
		LoggingAddError("(zeroDayReadValue) missing <br/>: ".$tag);	
		return false;
	}
	
	$data = substr($data_user,$pos,$pos_end-$pos);
	$data_strip = str_replace(",","",$data);
//	echo "strip ".$data_strip."<br>";
	$data_double = doubleval($data_strip);
	return $data_double;
	
}

function zeroDayReadId($id)
{
	$url_id = ZERO_DAY_STATS_URL.$id;
	
	$fp_r = @fopen($url_id, "rb");
	if (!$fp_r)	{
		LoggingAddError("(zeroDayReadId) Unable to read file: ".$url_id);	
		return false;
	}	

	$data_read_total = "";
	
	while (!feof($fp_r)) {
		$data_read = fread($fp_r, 8192);
		if (strlen($data_read) == 0) {
			break;	// 0 not necessarily an error but it is the EOF
		}
		$data_read_total = $data_read_total.$data_read;
	}
	
	fclose($fp_r);
	
	if (strlen($data_read_total) < 30)
	{
		LoggingAddError("(zeroDayReadId) No data: ".$url_id);	
		return false;
	}
	
	return $data_read_total;	
}

?>