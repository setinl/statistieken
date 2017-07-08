<?php

// 2 databases:
// __seti
// __seti_stats

// users
// id 			bigint
// time_first	int		user seen first
// time_last	int		user seen last, may have left team
// time_stats	int		time the last data was added
// name			tinytext utf8
// country		tinytext utf8
// total_credit	tinytext ascii packed base 32
// rac			tinytext ascii packed base 32
// id			primairy key
// >> times are 1 second times / 3600 = 1 hour timestamp


function sqlCreateTableUsers($sql)
{
	$sql_command = "SELECT 1 FROM ".SQL_TABLE_USERS;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".SQL_TABLE_USERS."
		(
			".SQL_ID." bigint,
			".SQL_USER_TEAM." int default -1,
			time_first int default -1,
			time_last int default -1,
			time_stats int default -1,
			name tinytext,
			country  tinytext,
			".SQL_TOTAL_CREDIT." DOUBLE,
			".SQL_RAC." DOUBLE,
			".SQL_DATA_TABLE." smallint default -1,
			".SQL_ACTIVE." tinyint default -1,
			PRIMARY KEY (".SQL_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			echo $sql_command;
			LoggingAddError("sqlCreateTableUsers: ".mysqli_error($sql));
			return FALSE;
		}

		LoggingAdd("Table __seti.users created",TRUE);
	}

	return TRUE;
}

function sqlCreateTableTeams($sql)
{
	$sql_command = "SELECT 1 FROM .".SQL_TABLE_TEAMS;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS teams
		(
			".SQL_TEAM_ID." bigint,
			time_last int default -1,
			time_stats int default -1,
			type tinytext,
			name tinytext,
			name_html tinytext,
			url tinytext,
			user_id	tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_TOTAL_CREDIT." double,
			".SQL_RAC." double,
			descr text,
			country  tinytext,
			".SQL_DATA_TABLE." smallint default -1,
			PRIMARY KEY (id)
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError($sql_command." - ");		
			LoggingAddError("sqlCreateTableTeams: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Table __seti.teams created",TRUE);
	}

	return TRUE;
}

// server_status
// users_gz_time_stamp 		bigint
// users_gz_error_count 	int			// -1 = running, 0 = created
// teams_gz_time_stamp 		bigint
// teams_gz_error_count		int			// -1 = running, 0 = created
// error_count 				int			// total errors so far.


function sqlCreateTableStatus($sql)
{
	$sql_command = "SELECT 1 FROM ".SQL_TABLE_STATUS;
	$result = $sql->query($sql_command);
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".SQL_TABLE_STATUS."
		(
			".SQL_REMOVE_LOGGING_COUNT." int default 20,		
			".SQL_USER_TEAM_TIME." bigint default -1,
			".SQL_USER_START_TIME." bigint default -1, 
			".SQL_USER_FILE_READ_DURATION." tinytext,
			".SQL_USER_PROCESSED_DURATION." tinytext,
			".SQL_USER_ADD_DURATION." tinytext,
			".SQL_USER_URL_FILE_TIME." bigint default -1, 
			".SQL_USER_ERROR_COUNT. " int default 0,
			".SQL_TEAM_START_TIME." bigint default -1, 					
			".SQL_TEAM_FILE_READ_DURATION. " tinytext,
			".SQL_TEAM_PROCESSED_DURATION. " tinytext,
			".SQL_TEAM_ADD_DURATION. " tinytext,
			".SQL_TEAM_URL_FILE_TIME." bigint default -1, 
			".SQL_TEAM_ERROR_COUNT." int default 0,
			".SQL_TOTAL_ERROR_COUNT." int default 0,
			".SQL_TODO." int default 0,
			".SQL_PROGRESS." int default 0,
			".SQL_PROGRESS_TEXT." tinytext,
			".SQL_PROGRESS_TIME." bigint default -1,
			".SQL_PROGRESS_DURATION. " tinytext,
			".SQL_PROGRESS_ERROR." int default 0
		)DEFAULT CHARSET=utf8";
		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError($sql_command." - ");			
			LoggingAddError("sqlCreateTableStats: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Table __seti.".SQL_TABLE_STATUS." created",TRUE);
		// create first record.
		$sqlCommand = "INSERT INTO ".SQL_TABLE_STATUS."() VALUES ()";
		$query = $sql ->query($sqlCommand);
		if ($query === FALSE)
		{
			return FALSE;
		}		
	}
	return TRUE;
}

function sqlCreateTableZeroDayStatus($sql)
{
	$sql_command = "SELECT 1 FROM ".SQL_TABLE_ZERO_DAY_STATUS;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".SQL_TABLE_ZERO_DAY_STATUS."
		(
			".SQL_ZERO_DAY_START_TIME." bigint default -1,
			".SQL_ZERO_DAY_TIME_FETCH." bigint default -1,				
			".SQL_ZERO_DAY_DURATION." tinytext,				
			".SQL_ZERO_DAY_STATUS." int default -1,
			".SQL_ZERO_USER_NAME."	tinytext,
			".SQL_ZERO_PROCESSED_COUNT." int default -1,
			".SQL_ZERO_ERROR_COUNT." int default 0
		)DEFAULT CHARSET=utf8";
		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableZeroDayStatus: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Table __seti.".SQL_TABLE_ZERO_DAY_STATUS." created",TRUE);
		// create first record.
		$sqlCommand = "INSERT INTO ".SQL_TABLE_ZERO_DAY_STATUS."() VALUES ()";
		$query = $sql ->query($sqlCommand);
		if ($query === FALSE)
		{
			return FALSE;
		}		
	}


	return TRUE;
}

function sqlCreateTableListSnlTeam($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_ID." bigint,
			".SQL_LIST_TIME." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_USER_NAME." tinytext,
			".SQL_COUNTRY." tinytext,
			".SQL_TOTAL_CREDIT." bigint,
			".SQL_OVERTAKE." tinytext,
			".SQL_RAC." DOUBLE,
			".SQL_RANK_RAC. " int default 0,
			".SQL_RANK_CREDIT. " int default 0,
			".SQL_LIST_ACTIVE. " int default 0,
			".SQL_LIST_USED. " tinyint default -1,				
			PRIMARY KEY (".SQL_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableListSnlTeam: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}

function sqlCreateTableListUsers($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_ID." bigint,
			".SQL_TEAM." bigint,
			".SQL_TEAM_SHORT_NAME." tinytext,				
			".SQL_USER_NAME." tinytext,
			".SQL_COUNTRY." tinytext,
			".SQL_TOTAL_CREDIT." DOUBLE default 0,
			".SQL_RAC." DOUBLE default 0,
			".SQL_RANK_RAC. " mediumint default 0,
			".SQL_RANK_CREDIT. " mediumint default 0,
			".SQL_RANK_COUNTRY_RAC. " mediumint default 0,
			".SQL_RANK_COUNTRY_CREDIT. " mediumint default 0,
			".SQL_RANK_WORLD_RAC. " int default 0,
			".SQL_RANK_WORLD_CREDIT. " int default 0,
			INDEX (".SQL_TEAM."),
			PRIMARY KEY (".SQL_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableListUsers: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}

function sqlCreateTableListTeam($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_TEAM_ID." bigint,
			".SQL_TEAM_NAME." tinytext,
			".SQL_COUNTRY." tinytext,
			".SQL_TOTAL_CREDIT." bigint,
			".SQL_RAC." DOUBLE,
			".SQL_RANK_RAC. " int default 0,
			".SQL_RANK_CREDIT. " int default 0,
			".SQL_LIST_ACTIVE. " int default 0,				
			".SQL_LIST_USED. " tinyint default -1,					
			PRIMARY KEY (".SQL_TEAM_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableTeams: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}

function sqlCreateTableListCountry($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_COUNTRY." tinytext,
			".SQL_TOTAL_CREDIT." bigint,
			".SQL_RAC." DOUBLE,
			".SQL_RANK_RAC. " int default 0,
			".SQL_RANK_CREDIT. " int default 0,
			".SQL_LIST_ACTIVE. " int default 0,
			PRIMARY KEY (".SQL_COUNTRY."(3))
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableListCountry: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}

function sqlCreateTableListTeamTemp($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_USER_TEAM." bigint,
			".SQL_ID." bigint,						
			PRIMARY KEY (".SQL_TEAM_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableListTeamTemp: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}

/*
function sqlCreateTableListCountry($sql, $name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	}
	else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name."
		(
			".SQL_ID." BIGINT,
			".SQL_COUNTRY." TINYTEXT,
				
			PRIMARY KEY (".SQL_ID.")
		)DEFAULT CHARSET=utf8";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableListTeamTemp: ".mysqli_error($sql));
			return FALSE;
		}
	}

	return TRUE;	
}
 */

// The next tables should be as small as possible!!!!!!!!!!!!!!!!!!

// total_credit/ rac tinytext ascii packed base 36

function sqlCreateTableData($sql,$name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	} else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name." (
			".SQL_ID." bigint,
			".SQL_TIME_DATA." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_TOTAL_CREDIT." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_RAC." tinytext CHARACTER SET ascii COLLATE ascii_bin
		)";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableData: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Database ".$name." created",TRUE);
	}
	return TRUE;
}


 // the next two table can be removed eventually 
function sqlCreateTableUserId($sql,$name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	} else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name." (
			".SQL_ID." bigint,
			".SQL_TIME_DATA." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_TOTAL_CREDIT." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_RAC." tinytext CHARACTER SET ascii COLLATE ascii_bin
		)";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableUserId: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Database ".$name." created",TRUE);
	}
	return TRUE;
}

function sqlCreateTableTeamId($sql,$name)
{
	$sql_command = "SELECT 1 FROM ".$name;
	$result = $sql->query($sql_command);
	if ($result !== FALSE)
	{
		$result->close();
	} else
	{
		$sql_command = "CREATE TABLE IF NOT EXISTS ".$name." (
			".SQL_ID." bigint,
			".SQL_TIME_DATA." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_TOTAL_CREDIT." tinytext CHARACTER SET ascii COLLATE ascii_bin,
			".SQL_RAC." tinytext CHARACTER SET ascii COLLATE ascii_bin		
		)";

		$result = $sql->query($sql_command);
		if ($result === FALSE)
		{
			LoggingAddError("sqlCreateTableTeamId: ".mysqli_error($sql));
			return FALSE;
		}
		LoggingAdd("Database ".$name." created",TRUE);
	}
	return TRUE;
}

function sqlEmptyTable($sql, $name)
{
	$sql_command = "TRUNCATE TABLE ".$name;
	$result = $sql->query($sql_command);
	if ($result === FALSE)
	{
		LoggingAddError("tableEmpty: ".mysqli_error($sql));
		return FALSE;
	}
	return TRUE;
}

?>