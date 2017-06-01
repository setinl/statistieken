<?php

function userStatsAdd($sql, $sql_stats, $timestamp_url)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour
	
	$timeStart = new DateTime("now"); 	
	LoggingAdd("(statsAdd) Start adding user results",TRUE);	
	$team = SNL_TEAM_ID;
	while($b_fetch_next)
	{
		$command = "SELECT ".SQL_ID.", time_stats,".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 10"; // only our team
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("statsAdd 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
	
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$i_id = $row[SQL_ID];
				$packed_rac = $row[SQL_RAC];	
				$unpacked_rac = UnPackBase32($packed_rac);
				$name = "user_"."$i_id"	;
				// prevent double entries on errors.
				if ($unpacked_rac > MINIMUM_RAC_FOR_SNL_TEAM_ADDITION)
				{
					$packed_credit = $row[SQL_TOTAL_CREDIT];
					
					$time_stats = $row['time_stats'];	
					if ($timestamp_url > $time_stats)
					{
						if (!sqlCreateTableUserId($sql_stats, $name))	// create table if needed
						{
							return ERR_DATABASE;
						}
					
						$resultInsert = $sql_stats->query("INSERT INTO ".$name. "(time,".SQL_TOTAL_CREDIT.",".SQL_RAC.") VALUES ('$timestamp_url', '$packed_credit', '$packed_rac')");LoadBallance();
						if ($resultInsert === FALSE)
						{
							LoggingAddError("statsAdd 2: ".$name.mysqli_error($sql_stats));
							return ERR_DATABASE;
						}
					
						// now that we added the total_credit, update the time_stats, $timestamp_url, to prevent double entries.xxxxxx
						$sqlCommand = "UPDATE users SET time_stats='$timestamp_url' WHERE ".SQL_ID."='$i_id' LIMIT 1";			
						$resultUpdate = $sql->query($sqlCommand);LoadBallance();
						if (!$resultUpdate)
						{
							LoggingAddError("statsAdd 3: " . mysqli_error($sql));
							return ERR_DATABASE;
						}					
					
//						LoggingAdd("table ".$name." added data: ".$packed_credit ,TRUE);
					}
					else
					{
//						LoggingAdd("table ".$name." timestamp_url ==: ".$timestamp_url." ".$time_stats ,TRUE);						
					}
				}
			}
			$result->close();
		}					
		else
		{
			// empty
			LoggingAdd("End of user database",TRUE);
			$b_fetch_next = FALSE;
		}
		$i_start = $i_start + 10;
	}
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_USER_ADD_DURATION, $interval_string);		
	
	LoggingAdd("(statsAdd) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

function teamStatsAdd($sql, $sql_stats, $timestamp_url)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour
	
	$timeStart = new DateTime("now"); 	
	LoggingAdd("(statsAdd) Start adding team results",TRUE);	
	while($b_fetch_next)
	{
		$result = $sql->query("SELECT ".SQL_ID.", time_stats,".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM teams LIMIT $i_start, 10");LoadBallance();
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
				$i_id = $row[SQL_ID];
				$packed_rac = $row[SQL_RAC];	
				$unpacked_rac = UnPackBase32($packed_rac);
				$name = "team_"."$i_id"	;
				if ($unpacked_rac > MINUMUM_RAC_FOR_TEAM_ADDITION)
				{
					$packed_credit = $row[SQL_TOTAL_CREDIT];
					
					$time_stats = $row['time_stats'];	
					if ($timestamp_url > $time_stats)
					{
						if (!sqlCreateTableTeamId($sql_stats, $name))	// create table if needed
						{
							return ERR_DATABASE;
						}
					
						$resultInsert = $sql_stats->query("INSERT INTO ".$name. "(time, ".SQL_TOTAL_CREDIT.",".SQL_RAC.") VALUES ('$timestamp_url', '$packed_credit', '$packed_rac')");LoadBallance();
						if ($resultInsert === FALSE)
						{
							LoggingAddError("statsAdd T2: ".$name.mysqli_error($sql_stats));
							return ERR_DATABASE;
						}
					
						// now that we added the total_credit, update the time_stats, timestamp_url, to prevent double entries.xxxxxx
						$sqlCommand = "UPDATE teams SET time_stats='$timestamp_url' WHERE ".SQL_ID."='$i_id' LIMIT 1";			
						$resultUpdate = $sql->query($sqlCommand);LoadBallance();
						if (!$resultUpdate)
						{
							LoggingAddError("statsAdd T3: " . mysqli_error($sql));
							return ERR_DATABASE;
						}					
					
//						LoggingAdd("table ".$name." added data: ".$packed_credit ,TRUE);
					}
					else
					{
//						LoggingAdd("table ".$name." timestamp_url ==: ".$timestamp_url." ".$time_stats ,TRUE);						
					}
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
		$i_start = $i_start + 10;
	}
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
	LoggingAdd("(statsAdd) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

/////////////////////////////////////////////////////////////////////////////////////////////// C new combined tables

function userStatsAddC($sql, $sql_stats, $timestamp_url)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour
	
	$timeStart = new DateTime("now"); 	
	LoggingAdd("(userStatsAddC) Start adding user results",TRUE);	
	$team = SNL_TEAM_ID;
	while($b_fetch_next)
	{
		$command = "SELECT ".SQL_ID.",".SQL_DATA_TABLE.", time_stats,".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 10"; // only our team
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("userStatsAddC 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
	
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$packed_rac = $row[SQL_RAC];	
				$unpacked_rac = UnPackBase32($packed_rac);
				
				if ($unpacked_rac > MINIMUM_RAC_FOR_SNL_TEAM_ADDITION)
				{					
					statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, "user");
				}
			}
			$result->close();
		}					
		else
		{
			// empty
			LoggingAdd("End of user database",TRUE);
			$b_fetch_next = FALSE;
		}
		$i_start = $i_start + 10;
	}
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_USER_ADD_DURATION, $interval_string);		
	
	LoggingAdd("(userStatsAddC) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

function teamStatsAddC($sql, $sql_stats, $timestamp_url)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	
	set_time_limit(3600);	// does nothing in safe mode	1 hour
	
	$timeStart = new DateTime("now"); 	
	LoggingAdd("(teamStatsAddC) Start adding team results",TRUE);	
	while($b_fetch_next)
	{
		$result = $sql->query("SELECT ".SQL_ID.", time_stats,".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM teams LIMIT $i_start, 10");LoadBallance();
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
				$packed_rac = $row[SQL_RAC];	
				$unpacked_rac = UnPackBase32($packed_rac);
				if ($unpacked_rac > MINUMUM_RAC_FOR_TEAM_ADDITION)
				{
					statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, "team");
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
		$i_start = $i_start + 10;
	}
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
	LoggingAdd("(teamStatsAddC) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);		
}

function statsAddItem($sql, $sql_stats, $row, $packed_rac, $timestamp_url, $user_or_team)
{
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
	$data_table = $row[SQL_DATA_TABLE];
	if ($data_table == -1)
	{
		$data_table = statsAddGetDataTable($sql, $sql_stats, $sql_table, $sql_table_data);
		if ($data_table === false)
		{
			LoggingAddError("statsAddItem 1: ".mysqli_error($sql));
			return ERR_DATABASE;						
		}
	}
	$table_name = $sql_table_data.$data_table;
				
	// prevent double entries on errors.

	$packed_credit = $row[SQL_TOTAL_CREDIT];
	$time_stats = $row[SQL_TIME_STATS];	
	if ($timestamp_url > $time_stats)
	{
		$resultInsert = $sql_stats->query("INSERT INTO ".$table_name. "(".SQL_ID.",".SQL_TIME_DATA.",".SQL_TOTAL_CREDIT.",".SQL_RAC.") VALUES ('$id', $timestamp_url', '$packed_credit', '$packed_rac')");LoadBallance();
		if ($resultInsert === FALSE)
		{
			LoggingAddError("statsAddItem 2: ".$table_name.mysqli_error($sql_stats));
			return ERR_DATABASE;
		}
				
		// now that we added the total_credit, update the time_stats, $timestamp_url, to prevent double entries.xxxxxx
		$sqlCommand = "UPDATE ".$sql_table." SET ".SQL_TIME_STATS."='$timestamp_url' WHERE ".SQL_ID."='$id' LIMIT 1";			
		$resultUpdate = $sql->query($sqlCommand);LoadBallance();
		if (!$resultUpdate)
		{
			LoggingAddError("statsAddItem 3: " . mysqli_error($sql));
			return ERR_DATABASE;
		}					
		LoggingAdd("table ".$table_name." added data: ".$packed_credit ,TRUE);
	}
	else
	{
		LoggingAdd("table ".$table_name." timestamp_url ==: ".$timestamp_url." ".$time_stats ,TRUE);						
	}	
}

function statsAddGetDataTable($sql, $sql_stats, $sql_table, $sql_table_data)
{
	$last = findDataTableLast($sql, $sql_table);
	if (is_array($last))
	{
		$table_number = $last[0];
		if ($last[1] > TABLE_DATA_MAX)
		{
			$table_number++;
			$table = $sql_table_data.$table_number;			
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

	$row_cnt = $result_last->num_rows;
	if ($row_cnt > 0)
	{
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
	}
	return $table_last;
}
