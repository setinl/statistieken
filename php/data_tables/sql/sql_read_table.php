<?php

define ("DEBUG_SERVER", "localhost");

function sqlTables()
{
    $array = GetPassWordSqlRead();
    $sql_password_r = $array["sql_password_r"];   
    $hostname = gethostname();  // don't use IsDebugServer, because it's defined in the unused common.php
	if (strpos($hostname, DEBUG_SERVER) === true)
	{
		// test machine
		$sql_details = array(
			'user' => 'setiatnl_r',
			'pass' => 'Mkllm4pFnV2rhbKb',
			'db'   => '__seti',
			'host' => 'localhost'
		);
	}
	else
	{
		// server
		$sql_details = array(
			'user' => 'setiatnl_r',
			'pass' => $sql_password_r,
			'db'   => '__seti',
			'host' => 'localhost'
		);	
	}
	return $sql_details ;
}
