<?php

define("ACTION_RESET_ERRRORS", 10);
define("ACTION_RELOAD_LIST", 11);
define("ACTION_FORCE_RELOAD", 15);
define("ACTION_UPDATE_MANUALLY", 20);

define("STATUS_BUSY", -1);

// http://json.parser.online.fr/

require_once '../../generate/php/passwords/pass.php';
require_once '../../generate/php/common.php';
require_once '../../generate/php/sql/sql.php';
require_once '../../generate/php/compress.php';	
require_once '../../generate/php/xml_parser.php';
require_once '../../generate/php/country.php';
require_once '../../generate/php/user_gz.php';
require_once '../../generate/php/team_gz.php';
require_once '../../generate/php/list_snl_team.php';
require_once '../../generate/php/list_all_teams.php';
require_once '../../generate/php/list_all_countries.php';
require_once '../../generate/php/list_users.php';
require_once '../../generate/php/stats_add.php';
require_once '../../generate/php/status.php';
require_once '../../generate/php/create_table.php';
require_once '../../generate/php/download_gz.php';
require_once '../../generate/php/generate_stats.php';
	
//echo 'test'	;
		
$json_status = array('status');
	
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
//	$email = $jsonObj->email;
//	$password = $jsonObj->password;
$token = $jsonObj->token;	

loggingOpen('admin');

$sql = connectSqlSeti();
if ($sql  === false)
{
	$json_status = array('error', 'database');
        LoggingClose();	
	die (json_encode($json_status));		
}

LoggingClose();	
	
switch ($action)
{	
	case ACTION_RESET_ERRRORS:
		$error_count = readStatus($sql, SQL_TOTAL_ERROR_COUNT);
		if ($error_count === FALSE)
		{
			$json_status = array('error', 'unable_to_read');	
			die (json_encode($json_status));	
		}
		if ($error_count == 0)
		{
			$json_status = array('status', 'reset_error_no_need');
			die (json_encode($json_status));				
		}
		$error_count_users = readStatus($sql, SQL_USER_ERROR_COUNT);
		if ($error_count_users === FALSE)
		{
			$json_status = array('error', 'unable_to_read');	
			die (json_encode($json_status));	
		}
		if ($error_count_users == STATUS_BUSY)
		{
			$json_status = array('error', 'busy');	
			die (json_encode($json_status));					
		}
		sleep(2);	// check again after xx seconds, to make sure -1 (busy) wasn't missed.
		$error_count_users = readStatus($sql, SQL_USER_ERROR_COUNT);	
		if ($error_count_users === FALSE)
		{
			$json_status = array('error', 'unable_to_read');	
			die (json_encode($json_status));			
		}
		if ($error_count_users == STATUS_BUSY)
		{
			$json_status = array('error', 'busy');	
			die (json_encode($json_status));					
		}
		// never ever reset users_gz_error_count, generate_stats is the only one allowed to do that.
		$status = writeStatus($sql, SQL_TOTAL_ERROR_COUNT, 0);	// reset total error count.
		if ($status === FALSE)
		{
			$json_status = array('error', 'unable_to_write');	
			die (json_encode($json_status));							
		}
		$json_status = array('status', 'reset_error_ok');
		die (json_encode($json_status));			
	break;
        
        case ACTION_RELOAD_LIST:
                $status = writeStatus($sql, SQL_TODO, TODO_LIST);   // set todo build list
		if ($status === FALSE)
		{
			$json_status = array('error', 'unable_to_write');
			die (json_encode($json_status));							
		}
		$json_status = array('status', 'list_build_set_ok');
		die (json_encode($json_status));          
        break;
       	
	case ACTION_FORCE_RELOAD:
		$status = writeStatus($sql, SQL_USER_URL_FILE_TIME, -1);
		if ($status === FALSE)
		{
			$json_status = array('error', 'user_file_time');	
			die (json_encode($json_status));							
		}
		$status = writeStatus($sql, SQL_TEAM_URL_FILE_TIME, -1);
		if ($status === FALSE)
		{
			$json_status = array('error', 'team_file_time');	
			die (json_encode($json_status));							
		}		
		
		$json_status = array('status', 'force_reload_ok');
		die (json_encode($json_status));		
	break;
	
	case ACTION_UPDATE_MANUALLY:
		$json_status = array('error', 'no_longer_active');  // DO NOT USE
		die (json_encode($json_status));
		$reply = updateStats();
		if ($reply == "ready")
		{
			$json_status = array('status', 'update_end');
			die (json_encode($json_status));
		}
		// dump some stuff but only on the test machine
		
		$hostname = gethostname();
		if (strpos($hostname, WEB_SERVER) === false)
		{
			die($reply);
		}
		$json_status = array('error', 'update_end');
		die (json_encode($json_status));
	break;		
}
$json_status = array('error', 'no_action');	
die (json_encode($json_status));
		
?>	