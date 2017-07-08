<?php

function listAllTeams($sql)
{
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_LIST_ALL_TEAMS);	
	
	if (!sqlCreateTableListTeamTemp($sql, SQL_TABLE_LIST_SNL_TEAM_TEMP)) 
	{
		LoggingAddError("(listAllTeams) unable to create temp: ".mysqli_error($sql));		
		return ERR_DATABASE;		
	}
	sqlEmptyTable($sql, SQL_TABLE_LIST_SNL_TEAM_TEMP);
	activeTeamMembersCreateList($sql);

	set_time_limit(7200);	// 120 minutes
	$table_name = SQL_TABLE_LIST_ALL_TEAMS;
	
	$url_time = readStatus($sql ,SQL_USER_URL_FILE_TIME);
	if ($url_time === false)
	{
		LoggingAddError("(listAllTeams) unable to read url time: ".mysqli_error($sql));		
		return ERR_DATABASE;
	}
	
	$status = sqlCreateTableListTeam($sql,SQL_TABLE_LIST_ALL_TEAMS);	
	if ($status === TRUE)
	{
		$timeStart = new DateTime("now"); 
		LoggingAdd("(listAllTeams) Start updating Team list",TRUE);

		$sqlCommand = "UPDATE ".$table_name." SET ".SQL_LIST_USED."=-1";		// set everthing to unused = -1
		$result = $sql->query($sqlCommand);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("(listSnlTeam): set use to -1 ".mysqli_error($sql));
			return ERR_DATABASE;	
		}		
		
		if (listAllTeamsBuild($sql, $table_name, $url_time) == STATUS_OK)
		{
			if (listAllTeamsRac($sql, $table_name, SQL_RAC, SQL_RANK_RAC) == true)
			{
				if (listAllTeamsRac($sql, $table_name, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == true)
				{
				
				}
			}
		}
		
		sqlEmptyTable($sql, SQL_TABLE_LIST_SNL_TEAM_TEMP);	// no longer needed.
		
		$timeStop = new DateTime("now"); 
		$interval = $timeStart->diff($timeStop);
		$interval_string = $interval->format('%h H, %i M, %s S');	
//		writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
		LoggingAdd("(listAllTeams) Finished after: ". $interval->format($interval_string),TRUE);		
		LoggingAdd("",TRUE);			
	}
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_NONE);	
}

function listAllTeamsBuild($sql, $table_name, $url_time)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
//	$team = SNL_TEAM_ID;
	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_TEAM_ID.",".SQL_TEAM_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_TEAMS." LIMIT $i_start, 100";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listAllTeamsBuild 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$status = listAllTeamsInsertOrUpdate($sql, $table_name, $row, $url_time);
				if ($status !== true)
				{
					LoggingAddError("(listAllTeamsBuild) abort: ".mysqli_error($sql).$status);					
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

function listAllTeamsInsertOrUpdate($sql, $table_name, $row, $url_time)
{
	$id = $row[SQL_TEAM_ID];
	$team_name = $sql->real_escape_string($row[SQL_TEAM_NAME]);
	$country = $row[SQL_COUNTRY];
	if ($country == C_INTERNATIONAL)
	{
		$country = "INT";
	}
	$credit = round($row[SQL_TOTAL_CREDIT]);
	$rac = round($row[SQL_RAC],2);
	
	if ($credit > MINIMUM_CREDIT_LIST_ALL_TEAM || $rac > MINIMUM_RAC_LIST_ALL_TEAM)
	{
		$sqlCommand = "SELECT ".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".$table_name." WHERE ".SQL_TEAM_ID."='$id' LIMIT 1";
		$result_check_there = $sql->query($sqlCommand);LoadBallance();
		if ($result_check_there === false)
		{
			LoggingAddError("listAllTeamsInsertOrUpdate 1: " . mysqli_error($sql));
			return ERR_DATABASE;
		}
		$active = activeTeamMembersGet($sql, $id);		
		$row_cnt = $result_check_there->num_rows;
		if ($row_cnt > 0)
		{
			$row_check_here = mysqli_fetch_array($result_check_there);
			if ($row_check_here)
			{
				$rac_in_snl_list = $row_check_here[SQL_RAC];
				$credit_in_snl_list = $row_check_here[SQL_TOTAL_CREDIT];
				$status = "Update data: ".$id." ".$team_name;

				// already there, update
				$sqlCommand = "UPDATE ".$table_name." SET ".SQL_TEAM_NAME."='$team_name',".SQL_COUNTRY."='$country',".SQL_TOTAL_CREDIT."='$credit',".SQL_RAC."='$rac',".SQL_LIST_ACTIVE."='$active',".SQL_LIST_USED."=1 WHERE ".SQL_TEAM_ID."='$id' LIMIT 1";
				$resultUpdate = $sql->query($sqlCommand);LoadBallance();
				if ($resultUpdate === FALSE)
				{
					LoggingAddError("listAllTeamsInsertOrUpdate 2: ".mysqli_error($sql));
					return ERR_DATABASE;
				}
			}
			else
			{
				LoggingAddError("listAllTeamsInsertOrUpdate 3: ".mysqli_error($sql));
				return ERR_DATABASE;
			}
		}
		else
		{
			// not found, insert
			$sqlCommand = "INSERT INTO ".$table_name." (".SQL_TEAM_ID.",".SQL_TEAM_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_LIST_ACTIVE.",".SQL_LIST_USED.") VALUES ('$id', '$team_name', '$country', '$credit', '$rac','$active', 2)";
			$resultInsert = $sql->query($sqlCommand);LoadBallance();
			if ($resultInsert === FALSE)
			{
				LoggingAddError("listAllTeamsInsertOrUpdate 4: ".$table_name." ".mysqli_error($sql));
				return ERR_DATABASE;
			}
			$status = "Insert: ".$id." ".$team_name;					
		}
	//	LoggingAdd($status,true);
		$result_check_there->close();			
	}
	return true;
}

function listAllTeamsRac($sql, $table_name, $order, $item)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$i_rank = 1;
	while($b_fetch_next)
	{	
		$result = $sql->query("SELECT ".SQL_ID." FROM ".$table_name." ORDER BY ".$order." DESC LIMIT $i_start, 100");LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listAllTeamsRac 1: ".mysqli_error($sql));
			return false;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$id = $row[SQL_ID];			
				$result_update = $sql->query( "UPDATE ".$table_name." SET ".$item."='$i_rank' WHERE ".SQL_TEAM_ID."='$id' LIMIT 1");LoadBallance();
				if ($result_update === FALSE)
				{
					LoggingAddError("listAllTeamsRac 2: ".mysqli_error($sql));
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

function activeTeamMembersCreateList($sql)
{
	$result = $sql->query("SELECT ".SQL_USER_TEAM.",".SQL_ID." FROM ".SQL_TABLE_USERS." WHERE ".SQL_ACTIVE."= 1" );LoadBallance();
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$team_id = $row[SQL_USER_TEAM];
				$user_id = $row[SQL_ID];				
				$sqlCommand = "INSERT INTO ".SQL_TABLE_LIST_SNL_TEAM_TEMP." (".SQL_USER_TEAM.",".SQL_ID.") VALUES ('$team_id','$user_id')";
				$resultInsert = $sql->query($sqlCommand);LoadBallance();
				if ($resultInsert === FALSE)
				{
					LoggingAddError("activeTeamMembersCreateList: ".mysqli_error($sql));
					return ERR_DATABASE;
				}
			}
		}
		$result->close();
	}	
}

function activeTeamMembersGet($sql, $team)
{
	$active = 0;

	$result = $sql->query("SELECT ".SQL_ID." FROM ".SQL_TABLE_LIST_SNL_TEAM_TEMP." WHERE ".SQL_USER_TEAM."='$team'" );LoadBallance();
	if ($result)
	{
		$active = $result->num_rows;
		$result->close();
	}
	return $active;
}

?>