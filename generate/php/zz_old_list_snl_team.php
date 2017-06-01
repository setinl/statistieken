<?php

function listSnlTeam($sql)
{
	set_time_limit(3600);	// 1 hour

	// make sure the 2 tables are always there
	$status = sqlCreateTableListSnlTeam($sql,SQL_TABLE_LIST_SNL_TEAM);	
	if ($status === TRUE)
	{
		$table_name = SQL_TABLE_LIST_SNL_TEAM_NEW;
		$status = sqlCreateTableListSnlTeam($sql,$table_name);
		if ($status === TRUE)
		{
			$timeStart = new DateTime("now"); 
			LoggingAdd("(listSnlTeam) Start building SnlTeam list",TRUE);

			if (sqlEmptyTable($sql, $table_name))
			{
				if (listSnlTeamBuild($sql, $table_name) == STATUS_OK)
				{
						if (listSnlTeamRac($sql, $table_name, SQL_RAC, SQL_RANK_RAC) == true)
						{
							if (listSnlTeamRac($sql, $table_name, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == true)
							{
							$command = "RENAME TABLE ".SQL_TABLE_LIST_SNL_TEAM." TO ".SQL_TABLE_LIST_SNL_TEAM_TEMP.",".SQL_TABLE_LIST_SNL_TEAM_NEW." TO ".SQL_TABLE_LIST_SNL_TEAM.",".SQL_TABLE_LIST_SNL_TEAM_TEMP." TO ".SQL_TABLE_LIST_SNL_TEAM_NEW;
							$result = $sql->query($command);LoadBallance();
							if ($result === FALSE)
							{
								LoggingAddError("listSnlTeam swap: ".mysqli_error($sql));
								return ERR_DATABASE;								
							}
						}
						
					}
				}

			}
		}
		$table_name = SQL_TABLE_LIST_SNL_TEAM_NEW;		
		if (!sqlEmptyTable($sql, $table_name))
		{
			LoggingAddError("listSnlTeam empty: ".mysqli_error($sql));
			return ERR_DATABASE;			
		}
		
		$timeStop = new DateTime("now"); 
		$interval = $timeStart->diff($timeStop);
		$interval_string = $interval->format('%h H, %i M, %s S');	
//		writeStatus($sql, SQL_TEAM_ADD_DURATION, $interval_string);		
		LoggingAdd("(listSnlTeam) Finished after: ". $interval->format($interval_string),TRUE);		
		LoggingAdd("",TRUE);			
	}
}

function listSnlTeamBuild($sql, $table_name)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$team = SNL_TEAM_ID;
	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_USER_ID.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 10";	// only our own team
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
				$id = $row[SQL_USER_ID];
				$user_name = $sql->real_escape_string($row[SQL_USER_NAME]);
				$country = $row[SQL_COUNTRY];
				$credit = round(UnPackBase32($row[SQL_TOTAL_CREDIT]));
				$rac = UnPackBase32($row[SQL_RAC]);
				$rac = round($rac,2);
							
				if ($credit > 100 || $rac > 100)
				{
					$resultInsert = $sql->query("INSERT INTO ".$table_name. "(".SQL_USER_ID.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.") VALUES ('$id', '$user_name', '$country', '$credit', '$rac')");LoadBallance();
					if ($resultInsert === FALSE)
					{
						LoggingAddError("listSnlTeamBuild 2: ".mysqli_error($sql));
						return ERR_DATABASE;
					}
				}
			}
			$result->close();
		}
		else
		{
			// empty
			$b_fetch_next = FALSE;
		}		
		$i_start = $i_start + 10;
	}
		
	return STATUS_OK;
}

function listSnlTeamRac($sql, $table_name, $order, $item)
{
	$b_fetch_next = TRUE;
	$i_start = 0;
	$i_rank = 1;
	while($b_fetch_next)
	{	
		$result = $sql->query("SELECT ".SQL_USER_ID." FROM ".$table_name." ORDER BY ".$order." DESC LIMIT $i_start, 10");LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listSnlTeamBuild 1: ".mysqli_error($sql));
			return false;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$id = $row[SQL_USER_ID];			
				$result_update = $sql->query( "UPDATE ".$table_name." SET ".$item."='$i_rank' WHERE ".SQL_USER_ID."='$id' LIMIT 1");LoadBallance();
				if ($result_update === FALSE)
				{
					LoggingAddError("listSnlTeamBuild 2: ".mysqli_error($sql));
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
		$i_start = $i_start + 10;
	
	}
	return true;
}



?>