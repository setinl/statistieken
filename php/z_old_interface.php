<?php

define("ACTION_READ_DATABASE_STATUS",0);
define("ACTION_READ_DATABASE_ZERO_DAY_STATUS",1);

define("ACTION_READ_CREDIT", 10);
define("ACTION_READ_RAC", 11);

define("ACTION_READ_TABLE_TEAM_SNL", 100);

// http://json.parser.online.fr/


require('sql/sql_read.php');
require('../generate/php/status.php');
require('../generate/php/common.php');

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

$sqlStats = connectSqlSetiStats();
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
	case ACTION_READ_TABLE_TEAM_SNL:
		echo json_encode(ReadDatabaseTable($sql));		
	break;

	case ACTION_READ_DATABASE_STATUS:
		echo json_encode(ReadDatabaseStatus($sql));	
	break;

	case ACTION_READ_DATABASE_ZERO_DAY_STATUS:
		echo json_encode(ReadDatabaseZeroDayStatus($sql));	
	break;

	case ACTION_READ_RAC:
		$data = GetRac($sql, $sqlStats, $id);
		echo $data;
	break;
	case ACTION_READ_CREDIT:
		$data = GetCredit($sql, $sqlStats, $id);
		echo $data;	
	break;
}

if ($sqlStats  !== false) mysqli_close($sqlStats);	
if ($sql  !== false) mysqli_close($sql);

LoggingAppendClose();

die ();

function ReadDatabaseTable($sqlStats)
{
	$table = "listSnlTeam_temp";
	$sqlCommand = "SELECT ".SQL_USER_NAME.",".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_RANK_RAC.",".SQL_RANK_CREDIT." FROM ".$table;
	$result = $sqlStats->query($sqlCommand);
	if ($result === FALSE)
	{
		mysqli_close($sqlStats);		
		$json_status = array('error', 'query_result');			
		return (json_encode($json_status));	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt > 0)
	{
		$json_data = array ();
	
		while($row = mysqli_fetch_array($result))
		{	
			$name = $row[SQL_USER_NAME];
			$country = $row[SQL_COUNTRY];
			$credit = $row[SQL_TOTAL_CREDIT];
			$rac = $row[SQL_RAC];
			$rank_rac = $row[SQL_RANK_RAC];
			$rank_credit =  $row[SQL_RANK_CREDIT];
			$data = array($name,$country,$credit,$rac,$rank_rac,$rank_credit);
			array_push($json_data, $data);
		}
		$result->close();
		return (json_encode($json_data));			
	}
	else
	{
		$json_status = array('error', 'query_col_0');			
		return (json_encode($json_status));		
	}
}

function GetRac($sql, $sqlStats, $id)
{
	$users = "user_"."$id";
	$sqlCommand = "SELECT time, rac FROM ".$users;
	$result = $sqlStats->query($sqlCommand);
	if ($result === FALSE)
	{
		mysqli_close($sqlStats);		
		$json_status = array('error', 'query_result');			
		return (json_encode($json_status));	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt > 0)
	{
		$json_data = array ();
		$data = array('data','ok');
		array_push($json_data,$data);	
	
		while($row = mysqli_fetch_array($result))
		{	
			$time = $row['time'];
			$packed_rac = $row['rac'];
			$rac = UnPackBase32($packed_rac);
			$data = array($time, $rac);
			array_push($json_data, $data);
		}
		$result->close();
		
		$command = "SELECT ".SQL_LIST_SNL_TIME.",".SQL_RAC." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_USER_ID."='$id' LIMIT 1";
		$result_zero = $sql->query($command);
		if ($result_zero !== FALSE)
		{
			$row_cnt = $result_zero->num_rows;
			if ($row_cnt > 0)
			{
				while($row = mysqli_fetch_array($result_zero))
				{	
					$time_zero = $row[SQL_LIST_SNL_TIME];
					if ($time_zero > $time)
					{
						$rac_zero = $row[SQL_RAC];
						$data = array($time_zero, $rac_zero);
						array_push($json_data, $data);	
					}
				}
			}
		}
		
		return (json_encode($json_data));			
	}
	else
	{
		$json_status = array('error', 'query_col_0');			
		return (json_encode($json_status));		
	}
}

function GetCredit($sql, $sqlStats, $id)
{
	$users = "user_"."$id";
	$sqlCommand = "SELECT time, total_credit FROM ".$users;
	
	$result = $sqlStats->query($sqlCommand);
	if ($result === FALSE)
	{
		mysqli_close($sqlStats);		
		$json_status = array('error', 'query_result');			
		return (json_encode($json_status));	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt > 0)
	{
		$json_data = array ();
		$data = array('data','ok');
		array_push($json_data,$data);	
	
		while($row = mysqli_fetch_array($result))
		{	
			$time = $row['time'];
			$packed_credit = $row[SQL_TOTAL_CREDIT];
			$credit = UnPackBase32($packed_credit);
			$data = array($time, $credit);
			array_push($json_data, $data);
		}
		$result->close();
		
		$command = "SELECT ".SQL_LIST_SNL_TIME.",".SQL_TOTAL_CREDIT." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_USER_ID."='$id' LIMIT 1";
		$result_zero = $sql->query($command);
		if ($result_zero !== FALSE)
		{
			$row_cnt = $result_zero->num_rows;
			if ($row_cnt > 0)
			{
				while($row = mysqli_fetch_array($result_zero))
				{					
					$time_zero = $row[SQL_LIST_SNL_TIME];
					if ($time_zero > $time)
					{
						$credit_zero = $row[SQL_TOTAL_CREDIT];
						$data = array($time_zero, $credit_zero);
						array_push($json_data, $data);
					}
				}
			}
		}
		return (json_encode($json_data));			
	}
	else
	{
		$json_status = array('error', 'query_col_0');			
		return (json_encode($json_status));		
	}
}


function UnPackBase32($number)
{
	$convert = base_convert ($number , 36, 11 );
	return str_replace('a','.',$convert);
}

function ReadDatabaseStatus($sql)
{
	$status = true;
	$error_line = 0;
	$json_data = array('database', 'ok');		
	
	$data = readStatus($sql ,SQL_USER_TEAM_TIME);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_START_TIME);
	if ($data === false) {$status = false; $error_line = 2;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_FILE_READ_DURATION);
	if ($data === false) {$status = false; $error_line = 3;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_PROCESSED_DURATION);
	if ($data === false) {$status = false; $error_line = 4;}
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_ADD_DURATION);
	if ($data === false) {$status = false; $error_line = 5;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_URL_FILE_TIME);
	if ($data === false) {$status = false; $error_line = 6;}
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_START_TIME);
	if ($data === false) {$status = false; $error_line = 7;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_FILE_READ_DURATION);
	if ($data === false) {$status = false; $error_line = 8;}	
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_PROCESSED_DURATION);
	if ($data === false) {$status = false; $error_line = 9;}
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_ADD_DURATION);
	if ($data === false) {$status = false; $error_line = 10;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_URL_FILE_TIME);
	if ($data === false) {$status = false; $error_line = 11;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TOTAL_ERROR_COUNT);
	if ($data === false) {$status = false; $error_line = 12;}		
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_USER_ERROR_COUNT);
	if ($data === false) {$status = false; $error_line = 13;}
	else {array_push($json_data, $data);}	
		
	$data = readStatus($sql ,SQL_TEAM_ERROR_COUNT);
	if ($data === false) {$status = false; $error_line = 14;}
	else {array_push($json_data, $data);}	

	if ($status == false)
	{
		$json_data = array('database', 'error');			
	}
		
	return $json_data;
}

function ReadDatabaseZeroDayStatus($sql)
{
	$status = true;
	$error_line = 0;
	$json_data = array('database', 'ok');			
	
	$data = readStatusZeroDay($sql ,SQL_ZERO_DAY_START_TIME);
	if ($data === false) {$status = false; $error_line = 1;}
	else array_push($json_data, $data);	

	$data = readStatusZeroDay($sql ,SQL_ZERO_DAY_TIME_FETCH);
	if ($data === false) {$status = false; $error_line = 1;}
	else array_push($json_data, $data);		
	
	$data = readStatusZeroDay($sql ,SQL_ZERO_DAY_DURATION);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}	
	
	$data = readStatusZeroDay($sql ,SQL_ZERO_DAY_STATUS);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}		

	$data = readStatusZeroDay($sql ,SQL_ZERO_USER_NAME);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}	
	
	$data = readStatusZeroDay($sql ,SQL_ZERO_PROCESSED_COUNT);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}	
	
	$data = readStatusZeroDay($sql ,SQL_ZERO_ERROR_COUNT);
	if ($data === false) {$status = false; $error_line = 1;}
	else {array_push($json_data, $data);}	
		
	if ($status == false)
	{
		$json_data = array('database', 'error');			
	}

		
	return $json_data;	
	
}

?>