<?php

function listUsers($sql)
{
	set_time_limit(7200);	// 120 minutes

	$table_users = SQL_TABLE_LIST_ALL_USERS_TEMP;
	
	writeStatus($sql ,SQL_PROGRESS_TEXT, "List users");		
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_LIST_USERS);

	$timeStart = new DateTime("now"); 
	LoggingAdd("(listUsers) Start updating user list",TRUE);	
	
	$status = sqlCreateTableListUsers($sql,$table_users);
	if ($status == false)
	{
		LoggingAddError("(listUsers) unable create: ".mysqli_error($sql));		
		return ERR_DATABASE;		
	}
	$status= sqlEmptyTable($sql, $table_users);
	if ($status === FALSE)
	{
		LoggingAddError("listUsers 2: ".mysqli_error($sql));
		return ERR_DATABASE;
	}	
	
	if (listUserAdd($sql, $table_users) != true)
	{
		LoggingAddError("listUsers 3: ".mysqli_error($sql));
		return ERR_DATABASE;		
	}
	
	if (listUserAddTop($sql, $table_users) != true)
	{
		LoggingAddError("listUsers 4: ".mysqli_error($sql));
		return ERR_DATABASE;		
	}
	
	$teams =  listUsersRac($sql, $table_users);
	if ($teams == false)
	{
		LoggingAddError("listUsers 5:  ".mysqli_error($sql));		
		return false;		
	}
 	
	$countries =  listUsersCountryRac($sql, $table_users);
	if ($countries == false)
	{
		LoggingAddError("listUsers 6:  ".mysqli_error($sql));		
		return false;		
	}	
	
	$status =  listUsersWorldRac($sql, $table_users);
	if ($status == false)
	{
		LoggingAddError("listUsers 7:  ".mysqli_error($sql));		
		return false;		
	}	

	if (listAddTeamName($sql, $table_users) != true)
	{
		LoggingAddError("listUsers team name ".mysqli_error($sql));
		return ERR_DATABASE;			
	}	

	// now swap the tables
	
	$status = sqlCreateTableListUsers($sql,SQL_TABLE_LIST_ALL_USERS);	// create one to make sure the rename goes all right.
	if ($status == false)
	{
		LoggingAddError("(listUsers) unable create 2: ".mysqli_error($sql));		
		return ERR_DATABASE;		
	}
	
	$command = "RENAME TABLE ".SQL_TABLE_LIST_ALL_USERS." TO ".SQL_TABLE_LIST_ALL_USERS_DUMMY.",".SQL_TABLE_LIST_ALL_USERS_TEMP." TO ".SQL_TABLE_LIST_ALL_USERS;
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsers swap: ".mysqli_error($sql));
		return false;	
	}	

	$command = "DROP TABLE IF EXISTS ".SQL_TABLE_LIST_ALL_USERS_DUMMY.",".SQL_TABLE_LIST_ALL_USERS_TEMP;
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsers dummy: ".mysqli_error($sql));
		return false;	
	}
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	LoggingAdd("(listUsers) Processed: ".$teams." teams, ".$countries." countries. Finished after: ".$interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);	
	
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_NONE);
	
	return true;
}

function listUserAdd($sql, $table_users)
{
	$command_i = "INSERT INTO $table_users (".SQL_ID.",".SQL_TEAM.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.") SELECT ".SQL_ID.",".SQL_TEAM.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS;
	$command = " WHERE (".SQL_TOTAL_CREDIT.">".MINUMUM_CREDIT_FOR_ALL_USERS_LIST." OR ".SQL_RAC.">".MINUMUM_RAC_FOR_ALL_USERS_LIST.")";					
	$result = $sql->query($command_i.$command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUserAdd: ".mysqli_error($sql));
		return false;
	}
	return true;
}

function listUserAddTop($sql, $table_users)
{
	$command = "SELECT ".SQL_ID." FROM ".SQL_TABLE_TEAMS." WHERE ".SQL_RAC.">".MINUMUM_RAC_FOR_TOP_TEAM;
	$result_team = $sql->query($command);LoadBallance();
	if ($result_team === FALSE)
	{
		LoggingAddError("listUserAddTop 1: ".mysqli_error($sql));
		return false;	
	}
	$teams_count = $result_team->num_rows;
	while($row = mysqli_fetch_array($result_team))
	{
		$team = $row[SQL_ID];
		if (listUserAddTopTeam($sql,$table_users,$team) != true)
		{
			LoggingAddError("listUserAddTop 2: ".mysqli_error($sql));
			return false;			
		}
	}
	$result_team->close();
	LoggingAdd("Added Top teams: ".$teams_count,TRUE);	
	return true;
}

function listUserAddTopTeam($sql, $table_users, $team)
{
	$command_i = "REPLACE INTO $table_users (".SQL_ID.",".SQL_TEAM.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.") SELECT ".SQL_ID.",".SQL_TEAM.",".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS;
	$command = " WHERE ".SQL_TEAM."='$team' AND ("   .SQL_TOTAL_CREDIT.">".MINUMUM_CREDIT_FOR_ALL_USERS_LIST_TOP." OR ".SQL_RAC.">".MINUMUM_RAC_FOR_ALL_USERS_LIST_TOP.")";
	$result = $sql->query($command_i.$command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUserAddTopTeam: ".mysqli_error($sql));
		return false;
	}
	return true;
}

function listUsersTeamArray($sql, &$team_id_array, &$team_name_array)
{
	$command = "SELECT ".SQL_TEAM_NAME.",".SQL_TEAM_ID." FROM ".SQL_TABLE_TEAMS;
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsersTeamArray: ".mysqli_error($sql));
		return false;	
	}
	$team_name = "";
	while($row_tn = mysqli_fetch_array($result))
	{
		$full = $row_tn[SQL_TEAM_NAME];		
		$team_id = $row_tn[SQL_TEAM_ID];		
		if (strlen($full) > 23) {$full = substr($full,0,20).'...';}
		$team_name = $sql->real_escape_string($full);
		
		array_push($team_id_array, $team_id);
		array_push($team_name_array, $team_name);		
	}
	$result->close();	
	return true;
}

function listAddTeamName($sql, $table_users)
{
	$write_counter = 0;

	$team_id_array = array();
	$team_name_array = array();	
	$status = listUsersTeamArray($sql, $team_id_array, $team_name_array);
	if ($status === false)
	{
		LoggingAddError("listAddTeamName array: ".mysqli_error($sql));
		return ERR_DATABASE;
	}	
	
	$command ="SELECT DISTINCT ".SQL_TEAM." FROM ".$table_users." ORDER BY ".SQL_USER_TEAM." DESC";
	$result_team = $sql->query($command);LoadBallance();
	if ($result_team === FALSE)
	{
		LoggingAddError("listAddTeamName 1: ".mysqli_error($sql));
		return ERR_DATABASE;	
	}
	
	while($row = mysqli_fetch_array($result_team))
	{
		$team_id = $row[SQL_TEAM];
		
		$key = array_search($team_id, $team_id_array);		
		if ($key === false) {$team_name = "";}
		else{$team_name = $team_name_array[$key];}

		if ($write_counter-- <= 0)
		{
			writeStatus($sql ,SQL_PROGRESS_TEXT, "Team: (2) ".$team_id);
			$write_counter = 100;
		}
		
		$command = "UPDATE ".$table_users." SET ".SQL_TEAM_SHORT_NAME."='$team_name' WHERE ".SQL_TEAM."='$team_id'";
		$result= $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("listAddTeamName 3: ".mysqli_error($sql));	
			return ERR_DATABASE;	
		}
	}
	$result_team->close();
	return true;
}

//////////////////////// team

function listUsersRac($sql, $table_name)
{
	$write_counter = 0;
	
	$command ="SELECT DISTINCT ".SQL_TEAM." FROM ".$table_name." ORDER BY ".SQL_TEAM." DESC";
	$result_team = $sql->query($command);LoadBallance();
	if ($result_team === FALSE)
	{
		LoggingAddError("listUsersRac 1: ".mysqli_error($sql));
		return ERR_DATABASE;	
	}
	
	$teams = $row_cnt = $result_team->num_rows;
	while($row = mysqli_fetch_array($result_team))
	{
		$team = $row[SQL_TEAM];
		
		if ($write_counter-- <= 0)
		{
			writeStatus($sql ,SQL_PROGRESS_TEXT, "Team: ".$team);
			$write_counter = 200;
		}
		
		if (listUsersSortRac($sql, $table_name, $team, SQL_RAC, SQL_RANK_RAC) == false)
		{						
			LoggingAddError("listUsersRac SortRac Team 1: ".mysqli_error($sql));
			return false;	
		}
		if (listUsersSortRac($sql, $table_name, $team, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == false)
		{						
			LoggingAddError("listUsersRac SortRac Team 2: ".mysqli_error($sql));
			return false;	
		}		
	}
	$result_team->close();
	return $teams;
}

function listUsersSortRac($sql, $table_name, $team, $order, $item)
{
	$result = $sql->query("SET @num:= 0");
	if ($result === FALSE)
	{
		LoggingAddError("listUsersSortRac 1: ".mysqli_error($sql));
		return false;	
	}	
	
	$command = "UPDATE ".$table_name." SET $item = (@num:=@num+1) WHERE ".SQL_USER_TEAM."='$team' ORDER BY ".$order." DESC";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsersSortRac 2: ".mysqli_error($sql));
		return false;	
	}
	return true;	
}

//////////////////////// country

function listUsersCountryRac($sql, $table_name)
{
	$write_counter = 0;
	$command ="SELECT DISTINCT ".SQL_COUNTRY." FROM ".$table_name." ORDER BY ".SQL_COUNTRY." DESC";
	$result_team = $sql->query($command);LoadBallance();
	if ($result_team === FALSE)
	{
		LoggingAddError("listUsersCountryRac 1: ".mysqli_error($sql));
		return ERR_DATABASE;	
	}
	
	$teams = $row_cnt = $result_team->num_rows;
	while($row = mysqli_fetch_array($result_team))
	{
		$country = $row[SQL_COUNTRY];
		
		if ($write_counter-- <= 0)
		{
			writeStatus($sql ,SQL_PROGRESS_TEXT, $country);	
			$write_counter = 10;
		}		
//		LoggingAdd("(listUsersRac) Processing team: ".$team, TRUE);
		if (listUsersCountrySortRac($sql, $table_name, $country, SQL_RAC, SQL_RANK_COUNTRY_RAC) == false)
		{						
			LoggingAddError("listUsersCountryRac SortRac Country 1: ".mysqli_error($sql));
			return false;	
		}
		if (listUsersCountrySortRac($sql, $table_name, $country, SQL_TOTAL_CREDIT, SQL_RANK_COUNTRY_CREDIT) == false)
		{						
			LoggingAddError("listUsersCountryRac SortRac Country 2: ".mysqli_error($sql));
			return false;	
		}		
	}
	$result_team->close();
	return $teams;
}

function listUsersCountrySortRac($sql, $table_name, $country, $order, $item)
{
	$result = $sql->query("SET @num:= 0");
	if ($result === FALSE)
	{
		LoggingAddError("listUsersCountrySortRac 1: ".mysqli_error($sql));
		return false;	
	}	
	
	$command = "UPDATE ".$table_name." SET $item = (@num:=@num+1) WHERE ".SQL_COUNTRY."='$country' ORDER BY ".$order." DESC";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsersCountrySortRac 2: ".mysqli_error($sql));
		return false;	
	}
	return true;	
}

//////////////////////// world

function listUsersWorldRac($sql, $table_name)
{
	if (listUsersWorldSortRac($sql, $table_name, SQL_RAC, SQL_RANK_WORLD_RAC) == false)
	{						
		LoggingAddError("listUsersWorldRac SortRac Country 1: ".mysqli_error($sql));
		return false;	
	}
	if (listUsersWorldSortRac($sql, $table_name, SQL_TOTAL_CREDIT, SQL_RANK_WORLD_CREDIT) == false)
	{						
		LoggingAddError("listUsersWorldRac SortRac Country 2: ".mysqli_error($sql));
		return false;	
	}
	return true;
}

function listUsersWorldSortRac($sql, $table_name, $order, $item)
{
	$result = $sql->query("SET @num:= 0");
	if ($result === FALSE)
	{
		LoggingAddError("listUsersWorldSortRac 1: ".mysqli_error($sql));
		return false;	
	}	
	
	$command = "UPDATE ".$table_name." SET $item = (@num:=@num+1) ORDER BY ".$order." DESC";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listUsersWorldSortRac 2: ".mysqli_error($sql));
		return false;	
	}
	return true;	
}

?>