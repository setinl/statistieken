<?php

// If there is an error processing the seti_stats table, the "server_status" table will prevent a new run.
// It is safe to remove (drop) the "server_status" table and let it run again. 
// The URL timestamp (SQL_TIME_STATS) will prevent double entries in the tables for data with the same URL timestamp (Create time of the Data file from Berkeley)

// SQL_TABLE_USER_DATA and SQL_TABLE_TEAM_DATA are data tables limited by TABLE_DATA_MAX. When the max is reached a new table is created.
// SQL_DATA_TABLE keeps track of the table in use for a user or team ID.

// Every SQL command must be followed by "LoadBallance()" to limit the server load. It adds a small delay ever xx times for xx milli seconds.

// The time zone is UTC (GMT).

//SET @num:=0;
//UPDATE list_all_users SET rank_rac = (@num:=@num+1) ORDER BY rac DESC

function updateStats()
{
	$users_add = false;
	$team_add = false;
	
	global $gi_error_count;		

	date_default_timezone_set("UTC"); 	
	
	$users_add = updateStatsUser();
	$team_add = updateStatsTeam();
	
	$gi_error_count = 0;
	
	if ($users_add || $team_add)
	{
		return;
	}
	
	UpdateLists();
        
}

function updateStatsUser()
{
	global $gi_error_count;	

	$url_user_gz = "http://setiathome.berkeley.edu/stats/user.gz";
                
	$file_local =  DataFolder('user.gz');

	$stats_added = false;	

	LoggingOpen("user");

	$sql = connectSqlSeti();

	$sql_statsC = connectSqlSetiStatsC();
	if ($sql_statsC !== false)
	{
		mysqli_close($sql_statsC);	// if we get this far it is.
	}	
//	$sql_stats = NULL;

	if ($gi_error_count == 0)		// no use starting on an error, it may be a failed database.
	{
		$status = sqlCreateTableStatus($sql);
		if ($status === TRUE)
		{
			$status = sqlCreateTableUsers($sql);
		}
		
		$date = new DateTime("now");
		$time_stamp = $date->getTimestamp();
		writeStatus($sql, SQL_USER_TEAM_TIME, $time_stamp);
			
		if ($status === TRUE)
		{
			if (!userGzReadAndProcess($sql, $url_user_gz, $file_local, $stats_added))
			{
				mysqli_close($sql);	
				LoggingAreThereErrors();
				LoggingClose();
				return false;			// blocking one is blocking all
			}
		}
		if ($stats_added)
		{
			writeStatus($sql ,SQL_TODO, TODO_LIST);	// next time update list
		}
	}

	// log errors

	if ($sql !== false)
	{
		LoggingRemoveOld($sql);	
		
		$error_count = readStatus($sql ,SQL_TOTAL_ERROR_COUNT);
		if ($error_count !== FALSE)
		{	
			$error_count += $gi_error_count;
			$status = writeStatus($sql, SQL_TOTAL_ERROR_COUNT, $error_count);
			if ($status === FALSE)
			{
				LoggingAddError("Writing error_count");	
			}
		}
		writeStatus($sql, SQL_USER_ERROR_COUNT, $gi_error_count);
		mysqli_close($sql);			
	}
//	if ($sql_stats != NULL) {mysqli_close($sql_stats);}

	// now we handle 

	LoggingAreThereErrors();
	LoggingClose();
	
	return $stats_added;
}

function updateStatsTeam()
{
	global $gi_error_count;	
	$processed = false;

	if ($gi_error_count != 0) {return $processed;}		// no use starting on an error, it may be a failed database.

	$url_team_gz = "http://setiathome.berkeley.edu/stats/team.gz";
	$file_local =  DataFolder('team.gz');        
	$stats_added = false;
//	$error_detected = false;	

	LoggingOpen("team");

	$sql = connectSqlSeti();

	// just check if the database is still there.
/*	
	$sql_stats = connectSqlSetiStats();
	if ($sql_stats !== false)
	{
		mysqli_close($sql_stats);	// if we get this far it is.
	}
	$sql_stats = NULL;

 */	
	$sql_statsC = connectSqlSetiStatsC();
	if ($sql_statsC !== false)
	{
		mysqli_close($sql_statsC);	// if we get this far it is.
	}	
	

	if ($gi_error_count == 0)		// no use starting on an error, it may be a failed database.
	{
		// sqlCreateTableStatus should be there.
		$status = sqlCreateTableTeams($sql);
		if ($status === TRUE)
		{
			if (!teamGzReadAndProcess($sql, $url_team_gz, $file_local, $stats_added))
			{
				mysqli_close($sql);	
				LoggingAreThereErrors();
				LoggingClose();
				return false;			// blocking one is blocking all
			}
		}
		if ($stats_added)
		{		
			writeStatus($sql ,SQL_TODO, TODO_LIST);	// next time update list
		}
	}

	// log errors

	if ($sql !== false)
	{
		$error_count = readStatus($sql ,SQL_TOTAL_ERROR_COUNT);
		if ($error_count !== FALSE)
		{	
			$error_count += $gi_error_count;
			$status = writeStatus($sql, SQL_TOTAL_ERROR_COUNT, $error_count);
			if ($status === FALSE)
			{
				LoggingAddError("Writing error_count");	
			}
		}
		writeStatus($sql, SQL_TEAM_ERROR_COUNT, $gi_error_count);
		mysqli_close($sql);			
	}

	LoggingAreThereErrors();
	LoggingClose();
	
	return $stats_added;
}

function updateLists()
{
	global $gi_error_count;	
	$gi_error_count = 0;
	
	LoggingOpen("lists");

	$sql = connectSqlSeti();	
	
	if ($gi_error_count == 0)		// no use starting on an error, it may be a failed database.
	{
		$todo = readStatus($sql,SQL_TODO);
		if ($todo == TODO_LIST)
		{	
			writeStatus($sql ,SQL_TODO, TODO_NONE);
			
			$timeStart = new DateTime("now"); 
			$time_stamp = $timeStart->getTimestamp();
			writeStatus($sql, SQL_PROGRESS_TIME, $time_stamp);
			writeStatus($sql, SQL_PROGRESS_DURATION, "");
			
			listSnlTeam($sql);
			listUsers($sql);
			listAllTeams($sql);			
			listCountries($sql);
			
			$timeStop = new DateTime("now"); 
			$interval = $timeStart->diff($timeStop);
			$interval_string = $interval->format('%h H, %i M, %s S');
			writeStatus($sql, SQL_PROGRESS_DURATION, $interval_string);			
			writeStatus($sql, SQL_PROGRESS_ERROR, $gi_error_count);
                        
                        BackupSeti();
		}
		
		mysqli_close($sql);	
	}
	
	LoggingAreThereErrors();
	LoggingClose();	
	
}

?>