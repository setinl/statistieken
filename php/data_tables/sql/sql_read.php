<?php

define ("WEB_SERVER", "ip");

function sqlTables()
{
	$hostname = gethostname();
	if (strpos($hostname, WEB_SERVER) === false)
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
