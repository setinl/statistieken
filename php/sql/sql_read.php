<?php

// Check connection

// SELECT -> $result->close();
// not for SET INSERT, UPDATE or DELETE

// Database READ ACCESS ONLY

// __seti 		user setiatnl_r
// __seti_stats1	user setiatnl_r

// User privileges: Data: SELECT

function connectSqlSeti()
{
    $array = GetPassWordSqlRead();
    $sql_password_r = $array["sql_password_r"]; 
    if (IsDebugServer())
	{
		// test machine
		$mysqli  = @new mysqli("localhost","setiatnl_r","Mkllm4pFnV2rhbKb","__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAppendAddError("database error seti: " . $mysqli ->connect_errno);
			return false;
		}
	}
	else
	{
		// server
		$mysqli  = @new mysqli("localhost","setiatnl_r",$sql_password_r,"__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAppendAddError("database error seti: " . $mysqli ->connect_errno);
			return false;
		}	
	}
	return $mysqli ;
}


function connectSqlSetiStatsC()
{
    $array = GetPassWordSqlRead();
    $sql_password_r = $array["sql_password_r"];     
    if (IsDebugServer())
	{
		// test machine
		$mysqli  = @new mysqli("localhost","setiatnl_r","Mkllm4pFnV2rhbKb","__seti_stats");
		if ($mysqli ->connect_errno)	{
			LoggingAppendAddError("database error seti_stats1: " . $mysqli ->connect_errno);
			return false;
		}
	}
	else
	{
		// server
		$mysqli  = @new mysqli("localhost","setiatnl_r",$sql_password_r,"__seti_stats");
		if ($mysqli ->connect_errno)	{
			LoggingAppendAddError("database error seti_stats1: " . $mysqli ->connect_errno);
			return false;
		}	
	}	
	return $mysqli ;
}

?>