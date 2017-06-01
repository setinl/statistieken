<?php

function listOtherTeam($sql)
{
	set_time_limit(3600);	// 60 minutes
	$table_name = SQL_TABLE_LIST_OTHER_TEAM;
	$min_rac = MINIMUM_RAC_LIST_OTHER_TEAM;
	
	$url_time = readStatus($sql ,SQL_USER_URL_FILE_TIME);
	if ($url_time === false)
	{
		LoggingAddError("(listOtherTeam) unable to read url time: ".mysqli_error($sql));		
		return ERR_DATABASE;
	}
	
	$status = sqlCreateTableListOtherTeam($sql,SQL_TABLE_LIST_OTHER_TEAM);	
	if ($status === TRUE)
	{
		$timeStart = new DateTime("now"); 
		LoggingAdd("(listOtherTeam) Start updating Other Team list",TRUE);
		
		$sqlCommand = "UPDATE ".$table_name." SET ".SQL_LIST_USED."=-1";		// set everthing to unused = -1
		$result = $sql->query($sqlCommand);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("(listOtherTeam): set use to -1 ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		
		$b_fetch_next = TRUE;
		$i_start = 0;

		while($b_fetch_next)
		{		
			$command = "SELECT ".SQL_ID." FROM ".SQL_TABLE_LIST_ALL_TEAMS." WHERE ".SQL_RAC.">'$min_rac' LIMIT $i_start, 100";
			$result = $sql->query($command);LoadBallance();
			if ($result === FALSE)
			{
				LoggingAddError("listOtherTeam 2: ".mysqli_error($sql));
				return ERR_DATABASE;
			}		
			$row_cnt = $result->num_rows;
			if ($row_cnt > 0)
			{
				while($row = mysqli_fetch_array($result))
				{		
					$team = $row[SQL_ID];
					LoggingAdd("(listOtherTeam) Processing team: ".$team, TRUE);
					if (listTeamBuild($sql, $table_name, $url_time, $team) == STATUS_OK)
					{
						if (listTeamRac($sql, $table_name, $team, SQL_RAC, SQL_RANK_RAC) == true)
						{
							if (listTeamRac($sql, $table_name, $team, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == true)
							{
							}
						}
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
		
		$timeStop = new DateTime("now"); 
		$interval = $timeStart->diff($timeStop);
		$interval_string = $interval->format('%h H, %i M, %s S');	
//		writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
		LoggingAdd("(listOtherTeam) Finished after: ". $interval->format($interval_string),TRUE);		
		LoggingAdd("",TRUE);			
	}
	echo "ready";
}

function listTeamBuild($sql, $table_name, $url_time, $team)
{
	$b_fetch_next = TRUE;
	$i_start = 0;

	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_ID.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 100";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listTeamBuild 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$status = listTeamInsertOrUpdate($sql, $table_name, $row, $url_time, $team);
				if ($status !== true)
				{
					LoggingAddError("(listTeamBuild) abort: ".mysqli_error($sql).$status);					
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

function listTeamInsertOrUpdate($sql, $table_name, $row, $url_time, $team)
{
	$id = $row[SQL_ID];
	$user_name = $sql->real_escape_string($row[SQL_USER_NAME]);
	$country = $row[SQL_COUNTRY];
	$credit = round($row[SQL_TOTAL_CREDIT]);
	$rac = round($row[SQL_RAC],2);
	$time_stamp = $url_time;
	if ($credit > MINIMUM_CREDIT_LIST_OTHER_TEAM_AS_USER || $rac > MINIMUM_RAC_LIST_OTHER_TEAM_AS_USER)
	{
		$sqlCommand = "REPLACE INTO ".$table_name." (".SQL_ID.",".SQL_USER_TEAM.",".SQL_LIST_TIME.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_LIST_USED.") VALUES ('$id', '$team', '$time_stamp', '$user_name', '$country', '$credit', '$rac', 2)";
		$resultInsert = $sql->query($sqlCommand);LoadBallance();
		if ($resultInsert === FALSE)
		{
			LoggingAddError("listTeamInsertOrUpdate 4: ".mysqli_error($sql));
			return ERR_DATABASE;
		}			
	}
	return true;
}

function listTeamRac($sql, $table_name, $team, $order, $item)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$i_rank = 1;
	while($b_fetch_next)
	{	
		$result = $sql->query("SELECT ".SQL_ID." FROM ".$table_name." WHERE ".SQL_USER_TEAM."='$team' ORDER BY ".$order." DESC LIMIT $i_start, 100");LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listTeamRac 1: ".mysqli_error($sql));
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
					LoggingAddError("listTeamRac 2: ".mysqli_error($sql));
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

