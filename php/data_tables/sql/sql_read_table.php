<?php

define ("SERVER_ID_DEBUG", 0);
define ("SERVER_ID_AMAZON", 1);
define ("SERVER_ID_LINODE", 2);

define ("WEB_SERVER_AMAZON", "ip");
define ("WEB_SERVER_LINODE", "efmer");


function IsDebugServer()
{
    $status = IsWhatServer();
    if ($status == SERVER_ID_DEBUG)
    {
        return true;
    }
    return false;
}

function IsWhatServer()
{
    $hostname = gethostname();
    $status = strpos($hostname, WEB_SERVER_AMAZON); 
    if ($status === false)
    {
        $status = strpos($hostname, WEB_SERVER_LINODE); 
        if ($status === false)
        {
            return SERVER_ID_DEBUG;
        }
        return SERVER_ID_LINODE;
    }
    return SERVER_ID_AMAZON;   
}

function sqlTables()
{
    $array = GetPassWordSqlRead();
    $sql_password_r = $array["sql_password_r"];   
    if (IsDebugServer())
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
