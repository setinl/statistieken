<?php

// Check connection

// SELECT -> $result->close();
// not for SET INSERT, UPDATE or DELETE

// Database

// __seti 			user setiatnl
// __seti_stats1		user setiatnl

	
function connectSqlSeti()
{
    $array = GetPassWordSqlReadWrite();
    $sql_password_rw = $array["sql_password_rw"];
    if (IsDebugServer())
	{
		// test machine
		$mysqli  = @new mysqli("localhost","setiatnl","TILpOIYCB0BSYDm2","__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error (connectSqlSeti) seti: " . $mysqli ->connect_errno);
			return false;
		}
	}
	else
	{
		// server
                
		$mysqli  = @new mysqli("localhost",$sql_password_rw,"__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error (connectSqlSeti) seti: " . $mysqli ->connect_errno);
			return false;
		}	
	}
	return $mysqli ;
}

function connectSqlSetiStatsC()
{
    $array = GetPassWordSqlReadWrite();
    $sql_password_rw = $array["sql_password_rw"]; 
    if (IsDebugServer())
	{
		// test machine
		$mysqli  = @new mysqli("localhost","setiatnl","TILpOIYCB0BSYDm2","__seti_stats");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error (connectSqlSetiStatsC) seti_stats: " . $mysqli ->connect_errno);
			return false;
		}
	}
	else
	{
		// server
		$mysqli  = @new mysqli("localhost","setiatnl",$sql_password_rw,"__seti_stats");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error seti_stats: " . $mysqli ->connect_errno);
			return false;
		}	
	}	
	return $mysqli ;
}

?>	