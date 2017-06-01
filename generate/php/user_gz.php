<?php

function userGzReadAndProcess($sql,$url_gz, $file_local, &$stats_added)
{
	if (!userGzAreWeCurrentlyRunning($sql))							// check the -1 start marker
	{
		$status = writeStatus($sql, SQL_USER_ERROR_COUNT, -1);	// mark start
		if ($status)
		{
			$time_stamp_sql = userGzTimestamp($sql ,$url_gz);	// get time stamp from database	
			$status = downloadUrlGz($sql,$url_gz, $file_local, $time_stamp_sql, $timestamp_url, SQL_USER_FILE_READ_DURATION);	
			if ($status == STATUS_OK)
			{
				$date = new DateTime("now"); 
				$time_stamp = $date->getTimestamp();
				writeStatus($sql, SQL_USER_START_TIME, $time_stamp);				
				
				writeStatus($sql, SQL_USER_PROCESSED_DURATION, "");
				writeStatus($sql, SQL_USER_ADD_DURATION, "");				
			
				$status = userGzProcess($sql,$file_local, $timestamp_url);
				if ($status == STATUS_OK)
				{
			//		$sql_stats = connectSqlSetiStats();
			//		userStatsAdd($sql, $sql_stats, $timestamp_url);	
			//		mysqli_close($sql_stats);					
					
					$sql_stats	= connectSqlSetiStatsC();
					if ($sql_stats === false)
					{
						return false;
					}
					userStatsAdd($sql, $sql_stats, $timestamp_url);
					mysqli_close($sql_stats);
					
					$stats_added = true;
				}
			}
		}
		else
		{
			LoggingAddError("Unable to write: ".SQL_USER_ERROR_COUNT);				
		}
	}
	else
	{
		LoggingAdd("(userGzReadAndProcess) another instance is already running, aborting", true);
		SendEmail("fred@efmer.com","(userGzReadAndProcess )database: another instance is already running, or an error needs to be cleared");
		return false;		
	}
	return true;	
}

// read gz data file and extract users
function userGzProcess($sql,$file_local, $timestamp_url)
{
	$s_data_block = "";
	$s_data_read = "";
	$status = STATUS_OK;
	$users_end = FALSE;	
	$i_block_read = 0;
	$i_record_read = 0;
	$i_record_team_read = 0;
	
	$timeStart = new DateTime("now"); 	
	
	$fp_r = gzopen($file_local, "r");
	if (!$fp_r)	{
		LoggingAddError("Unable to read file: ".$file_local);	
		return ERR_FILE_READ;
	}
//	LoggingAdd("(userGzProcess) Processing user data: ".$file_local." Timestamp: ".glb::get(GLOBAL_USER_GZ_TIMESTAMP),TRUE);

	set_time_limit(72000);	// 2 hour
	
	while (1)
	{
		$i_len = strlen($s_data_block);
		if ($i_len < 4096)
		{
			// get more data
			if (!feof($fp_r)) {
				$s_data_read = gzread($fp_r, 4096);
				if (strlen($s_data_read) > 0)
				{
					$i_block_read = $i_block_read + 1;					
				}
				$s_data_block.= $s_data_read;			
			}
		}
		if ($i_len > 32000) {
			LoggingAddError("Memory problem: ".strlen($s_data_block));	
		}

		if ($i_block_read == 1)
		{
			if (xmlFindTag($s_data_block, XML_USERS, FALSE) != STATUS_OK)
			{
				LoggingAdd("Missing <users> tag",TRUE);		
				return ERR_TAG_MISMATCH;			
			}
		}
		
		$block = xmlExtract($s_data_block, XML_USER, XML_USER_END, TRUE);	// extract user block
		$status = userGzExtract($sql, $block, $timestamp_url);			
		if ($status == ERR_EMPTY)
		{
			LoggingAdd("empty: ".$block."block len: ".strlen($s_data_block)."<<<<<>>>>".$s_data_block,TRUE);			
			break;		// this is the way out not more valid user items
		}
		
		if ($status == STATUS_OK) {
			$i_record_team_read = $i_record_team_read + 1;
		}
		
		$i_record_read = $i_record_read + 1;
	}
	
	if (xmlFindTag($s_data_block, XML_USERS_END, FALSE) == STATUS_OK)
	{
		$users_end = TRUE;		
	}
		
	LoggingAdd("Record read: ".$i_record_team_read.", Blocks read: ".$i_block_read,TRUE);
	gzclose($fp_r);	
	
	if ($users_end != TRUE) 
	{
		LoggingAdd("Missing </users> end tag",TRUE);	
		return ERR_TAG_MISMATCH;
	}
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	
	$interval_string = $interval->format('%h H, %i M, %s S');	
	
	writeStatus($sql, SQL_USER_PROCESSED_DURATION, $interval_string);
	LoggingAdd("(userGzProcess) Finished processing users: ". $interval_string,TRUE);		
	LoggingAdd("",TRUE);		
	userGzSetNewTimestamp($sql,$timestamp_url);
	
	return STATUS_OK;	
}

function userGzExtract($sql,$block, $timestamp_url)
{
	if (strlen($block) < 4)
	{
		return ERR_EMPTY;
	}
	$data_id = xmlExtract($block, XML_ID, XML_ID_END, FALSE);
	$team = xmlExtract($block, XML_TEAM_ID, XML_TEAM_ID_END, FALSE);
	$data_name = xmlExtract($block, XML_NAME, XML_NAME_END, FALSE);
	$data_name = $sql->real_escape_string($data_name);		
	$data_country = xmlExtract($block, XML_COUNTRY, XML_COUNTRY_END, FALSE);
	$data_credit = round(xmlExtract($block, XML_TOTAL_CREDIT, XML_TOTAL_CREDIT_END, FALSE),2);
	$data_rac = round(xmlExtract($block, XML_RAC, XML_RAC_END, FALSE),3);
	if ($data_rac > MINUMUM_RAC_FOR_ACTIVE_MEMBER) $data_active = 1;
	else $data_active = -1;
	
	$country = CountryShortString($data_country);	
	
	$result = $sql->query("SELECT ".SQL_TOTAL_CREDIT." FROM users WHERE ".SQL_ID."='$data_id' LIMIT 1");LoadBallance();
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			$result->close();
			
			if ($team == SNL_TEAM_ID)
			{
				$action = "Update, ";
				$sqlCommand = "UPDATE users SET time_last='$timestamp_url',".SQL_USER_TEAM."='$team',".SQL_USER_NAME."='$data_name',".SQL_COUNTRY."='$country', ".SQL_TOTAL_CREDIT."='$data_credit',".SQL_RAC."='$data_rac',".SQL_ACTIVE."='$data_active' WHERE ".SQL_ID."='$data_id' LIMIT 1";	
			}
			else
			{
				$action = "NoTeamMemb, ";
				$sqlCommand = "UPDATE users SET ".SQL_USER_TEAM."='$team',".SQL_USER_NAME."='$data_name', ".SQL_COUNTRY."='$country', ".SQL_TOTAL_CREDIT."='$data_credit',".SQL_RAC."='$data_rac',".SQL_ACTIVE."='$data_active' WHERE ".SQL_ID."='$data_id' LIMIT 1";	// don't update time_last timestamp_url
			}
			
			$result = $sql->query($sqlCommand);	LoadBallance();
			if (!$result)
			{
				LoggingAddError("userGzExtract 2: " . mysqli_error($sql));
				return ERR_DATABASE;
			}
		}
		else
		{
			$bAdd =false;
			// user not found, add record
			if ($team == SNL_TEAM_ID)
			{
				$bAdd = true;
			}
			else
			{
				if ($data_credit > MINIMUM_CREDIT_FOR_USER_ADDITION)				
				{
					if ($team == 0)
					{
						if ($data_rac > MINIMUM_RAC_FOR_USER_ADDITION)
						{				
							$bAdd = true;
						}
					}
					else
					{
						$bAdd = true;				
					}
				}
			}
			if ($bAdd == true)
			{
				$action = "Insert, ";
				$result = $sql->query("INSERT INTO users(".SQL_ID.", time_first, time_last,".SQL_USER_TEAM.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_ACTIVE.") VALUES ('$data_id','$timestamp_url','$timestamp_url','$team','$data_name','$country','$data_credit','$data_rac','$data_active')");LoadBallance();
				if ($result === FALSE)
				{
					LoggingAddError("userGzExtract 3: ".$team." ".$data_id.mysqli_error($sql));
					return ERR_DATABASE;
				}			
			}
			else
			{			
				return STATUS_OK;
				// skip if not in Team and not enough RAC or CREDITS
			}
		}
	}
	if (!$result)
	{
		LoggingAddError("userGzExtract 3: " . mysqli_error($sql));
		return ERR_DATABASE;		
	}	
	
//	LoggingAdd($action."Id: ".$data_id." Name: ".$data_name. " Country: ".$country." Credits: ".$data_credit." | ".$data_credit,TRUE);
//	LoggingAdd($action."Id: ".$data_id." N: ".$data_name. " C: ".$country,TRUE);
	LoadBallance();
	return STATUS_OK;
}

function userGzTimestamp($sql ,$file)
{
	$status = readStatus($sql ,SQL_USER_URL_FILE_TIME);
	if ($status === FALSE)
	{
		$status = -1;
	}
	if ($status == "")
	{
		$status = -1;
	}
	return $status;
}

function userGzSetNewTimestamp($sql, $timestamp_url)
{
	$status = writeStatus($sql, SQL_USER_URL_FILE_TIME, $timestamp_url);
	return $status;
}

function userGzAreWeCurrentlyRunning($sql)
{
	$status = readStatus($sql ,SQL_USER_ERROR_COUNT);
	if ($status === FALSE) return true;
	if ($status == "") return true;
	if ($status == -1) return true;				// running
	return false;
}

?>