<?php

define("ACTION_READ_DATABASE_STATUS",0);
define("ACTION_READ_DATABASE_ZERO_DAY_STATUS",1);

define("ACTION_READ_CREDIT_USER", 10);
define("ACTION_READ_CREDIT_USER_TM", 11);

define("ACTION_READ_CREDIT_TEAM", 15);
define("ACTION_READ_CREDIT_TEAM_TM", 16);

define("ACTION_READ_RAC_USER", 20);
define("ACTION_READ_RAC_TEAM", 21);

define("ACTION_READ_TABLE_TEAM_SNL", 100);

// http://json.parser.online.fr/


require('passwords/pass_read.php');
require('sql/sql_read.php');
require('../generate/php/status.php');
require('../generate/php/common.php');
require('interface_status.php');
require('interface_rac.php');
require('interface_credit.php');

$injson = file_get_contents("php://input");
$jsonPos = strpos($injson,'{');
if ($jsonPos === false)
{
	$json_status = array('error', 'no_json_start');
	die (json_encode($json_status));
}
	
$json = substr($injson, $jsonPos);	
$jsonObj = Json_decode($json);
$action = $jsonObj->action;
$id = $jsonObj->id;
$time = $jsonObj->time;
$token = $jsonObj->token;	

$sqlStats = connectSqlSetiStatsC();
if ($sqlStats  === false)
{
	$json_status = array('error', 'database sqlStats');	
	echo json_encode($json_status);	
	$action = -1;
}

$sql = connectSqlSeti();
if ($sql === false)
{
	$json_status = array('error', 'database sql');	
	echo json_encode($json_status);
	$action = -1;	
}

switch ($action)
{
//	case ACTION_READ_TABLE_TEAM_SNL:
//		echo json_encode(ReadDatabaseTable($sql));		
//	break;

	case ACTION_READ_DATABASE_STATUS:
		echo json_encode(ReadDatabaseStatus($sql));	
	break;

	case ACTION_READ_DATABASE_ZERO_DAY_STATUS:
		echo json_encode(ReadDatabaseZeroDayStatus($sql));	
	break;

	case ACTION_READ_RAC_USER:
		$data = GetRacUser($sql, $sqlStats, $id);
		echo $data;
	break;
	case ACTION_READ_RAC_TEAM:
		$data = GetRacTeam($sql, $sqlStats, $id);
		echo $data;
	break;
	case ACTION_READ_CREDIT_USER:
		$data = GetCreditUser($sql, $sqlStats, $id, 0);
		echo $data;	
	break;
	case ACTION_READ_CREDIT_USER_TM:
		$data = GetCreditUser($sql, $sqlStats, $id, 1);
		echo $data;	
	break;
	case ACTION_READ_CREDIT_TEAM:
		$data = GetCreditTeam($sql, $sqlStats, $id, 0);
		echo $data;	
	break;
	case ACTION_READ_CREDIT_TEAM_TM:
		$data = GetCreditTeam($sql, $sqlStats, $id, 1);
		echo $data;	
	break;
}

if ($sqlStats  !== false) mysqli_close($sqlStats);	
if ($sql  !== false) mysqli_close($sql);

LoggingAppendClose();

die ();


function UnPackBase36($number)
{
	$convert = base_convert ($number , 36, 11 );
	return str_replace('a','.',$convert);
}

?>

