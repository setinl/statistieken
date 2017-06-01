<?php

function listSnlTeam($sql)
{
	set_time_limit(7200);	// 60 minutes
	
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_LIST_SNL);	
	
	$table_name = SQL_TABLE_LIST_SNL_TEAM;
	
	$url_time = readStatus($sql ,SQL_USER_URL_FILE_TIME);
	if ($url_time === false)
	{
		LoggingAddError("(listSnlTeam) unable to read url time: ".mysqli_error($sql));		
		return ERR_DATABASE;
	}
	
	$status = sqlCreateTableListSnlTeam($sql,SQL_TABLE_LIST_SNL_TEAM);	
	if ($status === TRUE)
	{
		$timeStart = new DateTime("now"); 
		LoggingAdd("(listSnlTeam) Start updating SnlTeam list",TRUE);
		
		$sqlCommand = "UPDATE ".$table_name." SET ".SQL_LIST_USED."=-1";		// set everthing to unused = -1
		$result = $sql->query($sqlCommand);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("(listSnlTeam): set use to -1 ".mysqli_error($sql));
			return ERR_DATABASE;	
		}		
		if (listSnlTeamBuild($sql, $table_name, $url_time) == STATUS_OK)
		{
			if (listSnlTeamRac($sql, $table_name, SQL_RAC, SQL_RANK_RAC) == true)
			{
				if (listSnlTeamRac($sql, $table_name, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == true)
				{
				
				}
			}
		}
		
		listSnlTeamCleanup($sql);
		
		$timeStop = new DateTime("now"); 
		$interval = $timeStart->diff($timeStop);
		$interval_string = $interval->format('%h H, %i M, %s S');	
		LoggingAdd("(listSnlTeam) Finished after: ". $interval->format($interval_string),TRUE);		
		LoggingAdd("",TRUE);			
	}
	
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_NONE);
	
//	echo "ready";
}

function listSnlTeamBuild($sql, $table_name, $url_time)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$team = SNL_TEAM_ID;
	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_ID.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 100";	// only our own team
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listSnlTeamBuild 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$status = listSnlTeamInsertOrUpdate($sql, $table_name, $row, $url_time);
				if ($status !== true)
				{
					LoggingAddError("(listSnlTeamBuild) abort: ".mysqli_error($sql).$status);					
					return $status;
				}
			}
		}
		else
		{
			// empty
			$b_fetch_next = FALSE;
		}		
		$result->close();		
		$i_start = $i_start + 100;
	}
		
	return STATUS_OK;
}

// use 1 = update, 2 = insert
function listSnlTeamInsertOrUpdate($sql, $table_name, $row, $url_time)
{
	$id = $row[SQL_ID];
	$user_name = $sql->real_escape_string($row[SQL_USER_NAME]);
	$country = $row[SQL_COUNTRY];
	$credit = round($row[SQL_TOTAL_CREDIT]);
	$rac = round($row[SQL_RAC],2);
	$time_stamp = $url_time;
	if ($credit > 100 || $rac > 2)
	{
		$sqlCommand = "SELECT ".SQL_LIST_TIME.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".$table_name." WHERE ".SQL_ID."='$id' LIMIT 1";
		$result_check_there = $sql->query($sqlCommand);LoadBallance();
		if ($result_check_there === false)
		{
			LoggingAddError("listSnlTeamInsertOrUpdate 1: " . mysqli_error($sql));
			return ERR_DATABASE;		
		}
		$row_cnt = $result_check_there->num_rows;
		if ($row_cnt > 0)
		{
			$row_check_here = mysqli_fetch_array($result_check_there);
			if ($row_check_here)
			{
				$time_in_snl_list = $row_check_here[SQL_LIST_TIME];
				$rac_in_snl_list = $row_check_here[SQL_RAC];
				$credit_in_snl_list = $row_check_here[SQL_TOTAL_CREDIT];
				if ($time_in_snl_list > $url_time )
				{
					$credit = $credit_in_snl_list;
					$rac = $rac_in_snl_list;
					$status = "Update SKIPPED data newer: ".$id." ".$user_name;
				}
				else
				{
					$status = "Update data: ".$id." ".$user_name;
				}
				// already there, update
				$sqlCommand = "UPDATE ".$table_name." SET ".SQL_LIST_TIME."='$time_stamp',".SQL_USER_NAME."='$user_name',".SQL_COUNTRY."='$country',".SQL_TOTAL_CREDIT."='$credit',".SQL_RAC."='$rac',".SQL_LIST_USED."=1 WHERE ".SQL_ID."='$id' LIMIT 1";
				$resultUpdate = $sql->query($sqlCommand);LoadBallance();
				if ($resultUpdate === FALSE)
				{
					LoggingAddError("listSnlTeamInsertOrUpdate 2: ".mysqli_error($sql));
					return ERR_DATABASE;
				}
			}
			else
			{
				LoggingAddError("listSnlTeamInsertOrUpdate 3: ".mysqli_error($sql));
				return ERR_DATABASE;
			}
		}
		else
		{
			// not found, insert
			$sqlCommand = "INSERT INTO ".$table_name." (".SQL_ID.",".SQL_LIST_TIME.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_LIST_USED.") VALUES ('$id', '$time_stamp', '$user_name', '$country', '$credit', '$rac', 2)";
			$resultInsert = $sql->query($sqlCommand);LoadBallance();
			if ($resultInsert === FALSE)
			{
				LoggingAddError("listSnlTeamInsertOrUpdate 4: ".mysqli_error($sql));
				return ERR_DATABASE;
			}
			$status = "Insert: ".$id." ".$user_name;					
		}
//		LoggingAdd($status,true);
		$result_check_there->close();					
	}
	return true;
}

function listSnlTeamRac($sql, $table_name, $order, $item)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$i_rank = 1;
	while($b_fetch_next)
	{	
		$result = $sql->query("SELECT ".SQL_ID." FROM ".$table_name." ORDER BY ".$order." DESC LIMIT $i_start, 100");LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listSnlTeamRac 1: ".mysqli_error($sql));
			return false;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$id = $row[SQL_ID];			
				$result_update = $sql->query( "UPDATE ".$table_name." SET ".$item."='$i_rank' WHERE ".SQL_ID."='$id' LIMIT 1");LoadBallance();
				if ($result_update === FALSE)
				{
					LoggingAddError("listSnlTeamRac 2: ".mysqli_error($sql));
					return false;	
				}
				$i_rank++;				
			}
			$result->close();
		}
		else
		{
			// empty
			$b_fetch_next = FALSE;
		}		
		$i_start = $i_start + 100;
	
	}
	return true;
}

function listSnlTeamCleanup($sql)
{
	$sqlCommand = "SELECT ".SQL_ID." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_LIST_USED."='-1'";
	$result = $sql->query($sqlCommand);LoadBallance();
	if ($result === false)
	{
		LoggingAddError("listSnlTeamCleanup 1: " . mysqli_error($sql));
		return ERR_DATABASE;	
	}
	while($row = mysqli_fetch_array($result))
	{	
		$id = $row[SQL_ID];
		$sqlCommand = "DELETE FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id'";
		$result_delete = $sql->query($sqlCommand);LoadBallance();
		if ($result_delete === false)
		{
			LoggingAddError("listSnlTeamCleanup delete: " . mysqli_error($sql));
			return ERR_DATABASE;	
		}
	}
	
	return true;
}

?>