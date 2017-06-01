<?php

function userStatsAdd($sql, $sql_stats, $timestamp_url)
{
	$min_rac_addition;
	$added = 0;
	$skipped = 0;	
	
	$timeStart = new DateTime("now"); 		

	
	$team_to_add = 0;	
	$data_array = userStatsAddTeam($sql, $sql_stats, $timestamp_url, $team_to_add, MINIMUM_RAC_FOR_ZERO_ADDITION);	
	if ($data_array === false)
	{
		LoggingAddError("userStatsAdd abort 1: ".mysqli_error($sql));
		return false ;
	}
	$added += $data_array[0];
	$skipped += $data_array[1];	 

	
	$min_rac = 200000;
	$command = "SELECT ".SQL_TEAM_ID." FROM ".SQL_TABLE_TEAMS." WHERE ".SQL_RAC.">'$min_rac' LIMIT 1000";	// only the top teams
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listAllTeamsBuild 1: ".mysqli_error($sql)." <> ".$command);
		return ERR_DATABASE;	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt > 0)
	{
		while($row = mysqli_fetch_array($result))
		{	
			$team_to_add = $row[SQL_TEAM_ID];
			if ($team_to_add == SNL_TEAM_ID)
			{
				$min_rac_addition = MINIMUM_RAC_FOR_SNL_TEAM_ADDITION;
			}
			else
			{
				$min_rac_addition = MINIMUM_RAC_FOR_ADDITION;				
			}
			
			$data_array = userStatsAddTeam($sql, $sql_stats, $timestamp_url, $team_to_add, $min_rac_addition);	
			if ($data_array === false)
			{
				LoggingAddError("userStatsAdd abort 2: ".mysqli_error($sql));
				return false ;
			}
		
			$added += $data_array[0];
			$skipped += $data_array[1];	
		}
	}
		
/*	
	$team_array = array(138578,115396,30187,112230,30195,30200,112338,135021,30208, 113336,30205,145273,30289,113513,30300,30194,134826,30230,30192, 112468,143169,30267,30222,110052,102296,101574,30228,30191,30188, 26483,30199,15,101567,30189,30214,30347,31781,112267,30642);
	$team_len = count($team_array);	

	for ($i=0; $i < $team_len; $i++)
	{
		$team_to_add = $team_array[$i];
		$data_array = userStatsAddTeam($sql, $sql_stats, $timestamp_url, $team_to_add, MINIMUM_RAC_FOR_ADDITION);	
		if ($data_array === false)
		{
			LoggingAddError("userStatsAdd abort 2: ".mysqli_error($sql));
			return false ;
		}
		
		$added += $data_array[0];
		$skipped += $data_array[1];
	}
 
 */
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_USER_ADD_DURATION, $interval_string);		
	LoggingAdd("(userStatsAddTeam) Added: ".$added." skipped: ".$skipped,TRUE);		
	LoggingAdd("(userStatsAddTeam) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

function userStatsAddTeam($sql, $sql_stats, $timestamp_url, $team_to_add, $min_rac)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$added = 0;
	$skipped = 0;	
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour

	LoggingAdd("(userStatsAddTeam) Start adding user results, for team: ".$team_to_add,TRUE);	
	while($b_fetch_next)
	{
		$command = "SELECT ".SQL_ID.",".SQL_DATA_TABLE.",".SQL_TIME_STATS.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team_to_add' LIMIT $i_start, 1000";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("userStatsAddTeam 1: ".mysqli_error($sql));
			return false ;	
		}
	
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$unpacked_rac = $row[SQL_RAC];	
				if ($unpacked_rac > $min_rac)
				{	
					$packed_rac = PackBase36($unpacked_rac);
					$status = statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, "user");
					if ($status == false)
					{
						break;
					}
					if ($status == "add") {$added++;}
					if ($status == "time") {$skipped++;}					
				}
			}
			$result->close();
		}					
		else
		{
			// empty
//			LoggingAdd("End of user database",TRUE);
			$b_fetch_next = FALSE;
		}
		$i_start = $i_start + 1000;
	}
	
	return array($added,$skipped);
}


function teamStatsAdd($sql, $sql_stats, $timestamp_url)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$added = 0;
	$skipped = 0;
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour
	
	$timeStart = new DateTime("now"); 	
	LoggingAdd("(teamStatsAdd) Start adding team results",TRUE);	
	while($b_fetch_next)
	{
		$result = $sql->query("SELECT ".SQL_ID.",".SQL_DATA_TABLE.",".SQL_TIME_STATS.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_TEAMS." LIMIT $i_start, 1000");LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("statsAdd T1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
	
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$unpacked_rac = $row[SQL_RAC];	
				if ($unpacked_rac > MINUMUM_RAC_FOR_TEAM_ADDITION)
				{
					$packed_rac = PackBase36($unpacked_rac);
					$status = statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, "team");
					if ($status == false)
					{
						break;
					}
					if ($status == "add") {$added++;}
					if ($status == "time") {$skipped++;}					
				}
			}
			$result->close();
		}					
		else
		{
			// empty
			LoggingAdd("End of team database",TRUE);
			$b_fetch_next = FALSE;
		}
		$i_start = $i_start + 1000;
	}
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
	LoggingAdd("(teamStatsAdd) Added: ".$added." skipped: ".$skipped,TRUE);		
	LoggingAdd("(teamStatsAdd) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

function statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, $user_or_team)
{
	$new_table_entry = false;
	if ($user_or_team == "user")
	{
		$sql_table_data = SQL_TABLE_USER_DATA;
		$sql_table = SQL_TABLE_USERS;
	}
	else
	{
		$sql_table_data = SQL_TABLE_TEAM_DATA;	
		$sql_table = SQL_TABLE_TEAMS;		
	}
	
	$id = $row[SQL_ID];
	$data_table_nr = $row[SQL_DATA_TABLE];
	if ($data_table_nr == -1)
	{
		$data_table_nr = statsAddGetDataTable($sql, $sql_stats, $sql_table, $sql_table_data);
		if ($data_table_nr === false)
		{
			LoggingAddError("statsAddItem 1: ".mysqli_error($sql));
			return false;						
		}
		$new_table_entry = true;
	}
	$table_name = $sql_table_data.$data_table_nr;
				
	// prevent double entries on errors.

	$packed_credit = PackBase36($row[SQL_TOTAL_CREDIT]);
	$time_stats = $row[SQL_TIME_STATS];	
	if ($timestamp_url > $time_stats)
	{
		$resultInsert = $sql_stats->query("INSERT INTO ".$table_name."(".SQL_ID.",".SQL_TIME_DATA.",".SQL_TOTAL_CREDIT.",".SQL_RAC.") VALUES ('$id', '$timestamp_url', '$packed_credit', '$packed_rac')");LoadBallance();
		if ($resultInsert === FALSE)
		{
			LoggingAddError("statsAddItem 2: ".$table_name.mysqli_error($sql_stats));
			return false;
		}
				
		// now that we added the total_credit, update the time_stats, $timestamp_url, to prevent double entries.xxxxxx
		
		if ($new_table_entry)
		{
			// add the data table info as well
			$sqlCommand = "UPDATE ".$sql_table." SET ".SQL_TIME_STATS."='$timestamp_url',".SQL_DATA_TABLE."='$data_table_nr' WHERE ".SQL_ID."='$id' LIMIT 1";					
		}
		else
		{
			$sqlCommand = "UPDATE ".$sql_table." SET ".SQL_TIME_STATS."='$timestamp_url' WHERE ".SQL_ID."='$id' LIMIT 1";					
		}
	
		$resultUpdate = $sql->query($sqlCommand);LoadBallance();
		if (!$resultUpdate)
		{
			LoggingAddError("statsAddItem 3: " . mysqli_error($sql));
			return false;
		}					
//		LoggingAdd("table ".$table_name." added data: ".$packed_credit ,TRUE);
		return "add";
	}
	else
	{
//		LoggingAdd("table ".$table_name." timestamp_url ==: ".$timestamp_url." ".$time_stats ,TRUE);
		return "time";
	}	
}

function statsAddGetDataTable($sql, $sql_stats, $sql_table, $sql_table_data)
{
	$last = findDataTableLast($sql, $sql_table);
	if (is_array($last))
	{
		LoggingAdd("New data table entry, tableLast: ".$last[0]." ".$last[1], TRUE);
		
		$table_number = $last[0];
		if ($last[1] > TABLE_DATA_MAX)
		{
			$table_number++;
			$table = $sql_table_data.$table_number;			
			LoggingAdd("TABLE_DATA_MAX, adding new table: ".$table, TRUE);
			if (!sqlCreateTableData($sql_stats, $table))
			{
				return false;
			}
		}
		return $table_number;
	}
	return false;
}


function findDataTableLast($sql, $table)
{
	$table_last = array(0, 0);
	$command = "SELECT MAX(".SQL_DATA_TABLE.") FROM ".$table ;
	$result_last = $sql->query($command);LoadBallance();
	if ($result_last === FALSE)
	{
		LoggingAddError("findDataTableMax 1: ".mysqli_error($sql));
		return ERR_DATABASE;	
	}

	$row = mysqli_fetch_array($result_last);
	if ($row)
	{	
		$table_last[0] = $row[0];
		$command = "SELECT ".SQL_DATA_TABLE." FROM ".$table." WHERE ".SQL_DATA_TABLE."='$table_last[0]'";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("findDataTableMax 2: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			$table_last[1] = $row_cnt;
		}
		$result->close();
	}
	return $table_last;
}

?>