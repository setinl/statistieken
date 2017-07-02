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

/*
function IsDebugServer()
{
    $hostname = gethostname();
    $status = strpos($hostname, WEB_SERVER); 
    if ($status === false)
    {
        return true;
    }
    return false;
}
 */

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


require_once "email/stats_seti_nl.php";

// All capitals are always global constants 

define("LINODE_SERVER_LOGGING_FOLDER", "/var/www/html/setistats.seti.nl/status_logs/");
define("AMAZON_SERVER_LOGGING_FOLDER", "/home/bitnami/stats/log/");
define("DEBUG_LOGGING_FOLDER", "/WebServer/UniServerZSeti/www/stats/log/");

define("LINODE_SERVER_DATA_FOLDER", "/var/www/html/setistats.seti.nl/data/");
define("AMAZON_SERVER_DATA_FOLDER", "/home/bitnami/stats/data/");
define("DEBUG_DATA_FOLDER", "/WebServer/UniServerZSeti/www/data/");


define("FILE_ERROR_LOG", "error");

// progress

define("PROGRESS_NONE", 0);
define("PROGRESS_LIST_SNL", 1);
define("PROGRESS_LIST_USERS", 2);
define("PROGRESS_LIST_ALL_TEAMS", 3);
define("PROGRESS_LIST_ALL_COUNTRIES", 4);

define("TODO_NONE", 0);
define("TODO_LIST", 1);

// zero day
define("ZERO_DAY_STATS_URL", "http://setiathome.berkeley.edu/userw.php?id=");
define("ZERO_DAY_USER_CREDITS","User TotCred:");
define("ZERO_DAY_USER_RAC","User AvgCred:");
//

define("MINIMUM_RAC_FOR_ZERO_DAY_UPDATE",2);

// add to users if not TEAM SNL
define("MINIMUM_CREDIT_FOR_USER_ADDITION",500);
define("MINIMUM_RAC_FOR_USER_ADDITION",500);

// add to graph
define("MINIMUM_RAC_FOR_SNL_TEAM_ADDITION",1);
define("MINIMUM_RAC_FOR_ADDITION",500);
define("MINIMUM_RAC_FOR_ZERO_ADDITION",10000);

define("MINUMUM_RAC_FOR_TEAM_ADDITION", 40000);
define("MINUMUM_RAC_FOR_ACTIVE_MEMBER", 1);

// For list_users
// all users list, user must have min credits or have min RAC
define("MINUMUM_CREDIT_FOR_ALL_USERS_LIST", 1000000);
define("MINUMUM_RAC_FOR_ALL_USERS_LIST", 500);
// top teams
define("MINUMUM_RAC_FOR_TOP_TEAM", 300000);	// 0.3M
define("MINUMUM_CREDIT_FOR_ALL_USERS_LIST_TOP", 100000);
define("MINUMUM_RAC_FOR_ALL_USERS_LIST_TOP", 100);

// For list_all_teams
// lowering will increase the list by a lot
define("MINIMUM_RAC_LIST_ALL_TEAM", 20000);
define("MINIMUM_CREDIT_LIST_ALL_TEAM", 20000000);

define("GZ_USER", 0);
define("GZ_TEAM", 0);

define("STATUS_OK", 0);
define("STATUS_TIME_STAMP_EQUAL", 1);

define("ERR_FILE_READ", 10);
define("ERR_FILE_WRITE", 11);

define("ERR_EMPTY", 20);
define("ERR_NOT_FOUND", 21);
define("ERR_TAG_MISMATCH", 22);

define("ERR_NOT_IN_OUR_TEAM", 40);

define("ERR_DATABASE", 50);

define("SNL_TEAM_ID","30190");

define("XML_USERS","<users>");
define("XML_USERS_END","</users>");
define("XML_USER","<user>");
define("XML_USER_END","</user>");
define("XML_TEAM_ID","<teamid>");
define("XML_TEAM_ID_END","</teamid>");
define("XML_ID","<id>");
define("XML_ID_END","</id>");
define("XML_NAME","<name>");
define("XML_NAME_END","</name>");
define("XML_COUNTRY","<country>");
define("XML_COUNTRY_END","</country>");

define("XML_TEAMS","<teams>");
define("XML_TEAMS_END","</teams>");
define("XML_TEAM","<team>");
define("XML_TEAM_END","</team>");
define("XML_T_ID","<id>");
define("XML_T_ID_END","</id>");
define("XML_T_TYPE","<type>");
define("XML_T_TYPE_END","</type>");
define("XML_T_NAME","<name>");
define("XML_T_NAME_END","</name>");
define("XML_T_USER_ID","<userid>");
define("XML_T_USER_ID_END","</userid>");
define("XML_T_CREDIT","<total_credit>");
define("XML_T_CREDIT_END","</total_credit>");
define("XML_T_RAC","<expavg_credit>");
define("XML_T_RAC_END","</expavg_credit>");
define("XML_T_URL","<url>");
define("XML_T_URL_END","</url>");
define("XML_T_NAME_HTLM","<name_html>");
define("XML_T_NAME_HTML_END","</name_html>");
define("XML_T_DESCR","<description>");
define("XML_T_DESCR_END","</description>");
define("XML_T_COUNTRY","<country>");
define("XML_T_COUNTRY_END","</country>");

define("XML_TOTAL_CREDIT","<total_credit>");
define("XML_TOTAL_CREDIT_END","</total_credit>");
define("XML_RAC","<expavg_credit>");
define("XML_RAC_END","</expavg_credit>");

// SQL 


define("SQL_REMOVE_LOGGING_COUNT","remove_logging_count");

define("SQL_USER_TEAM_TIME","user_team_time");

define("SQL_USER_START_TIME","user_start_time");
define("SQL_USER_FILE_READ_DURATION","user_file_read_duration");
define("SQL_USER_PROCESSED_DURATION","user_processed_duration");
define("SQL_USER_ADD_DURATION","user_add_duration");
define("SQL_USER_URL_FILE_TIME","user_url_file_time");

define("SQL_TEAM_START_TIME","team_start_time");
define("SQL_TEAM_FILE_READ_DURATION","team_file_read_duration");
define("SQL_TEAM_PROCESSED_DURATION","team_processed_duration");
define("SQL_TEAM_ADD_DURATION","team_add_duration");
define("SQL_TEAM_URL_FILE_TIME","team_url_file_time");

define("SQL_TOTAL_ERROR_COUNT","u_t_total_error_count");
define("SQL_USER_ERROR_COUNT", "users_error_count");
define("SQL_TEAM_ERROR_COUNT", "team_error_count");
define("SQL_TODO", "todo");
define("SQL_PROGRESS", "progress");
define("SQL_PROGRESS_TEXT", "progress_text");
define("SQL_PROGRESS_TIME", "progress_time");
define("SQL_PROGRESS_DURATION", "progress_duration");
define("SQL_PROGRESS_ERROR", "progress_error");

define("SQL_ZERO_DAY_START_TIME","start_time");
define("SQL_ZERO_DAY_TIME_FETCH","last_fetch_time");
define("SQL_ZERO_DAY_DURATION", "duration");				
define("SQL_ZERO_DAY_STATUS", "status");
define("SQL_ZERO_USER_NAME", "user_name");
define("SQL_ZERO_PROCESSED_COUNT", "processed_count");
define("SQL_ZERO_ERROR_COUNT", "error_count");

define("SQL_ID","id");
define("SQL_TEAM","team");


define("SQL_TIME_DATA","time");
define("SQL_USER_TEAM","team");
define("SQL_USER_NAME","name");
define("SQL_COUNTRY","country");
define("SQL_TOTAL_CREDIT","total_credit");
define("SQL_RAC","rac");
define("SQL_RANK_RAC","rank_rac");
define("SQL_RANK_CREDIT","rank_credit");
define("SQL_RANK_COUNTRY_RAC","rankc_rac");
define("SQL_RANK_COUNTRY_CREDIT","rankc_credit");
define("SQL_RANK_WORLD_RAC","rankw_rac");
define("SQL_RANK_WORLD_CREDIT","rankw_credit");

define("SQL_DATA_TABLE","data_table");
define("SQL_ACTIVE","active");

define("SQL_TEAM_ID","id");
define("SQL_TEAM_NAME","name");
define("SQL_TEAM_SHORT_NAME","team_s");

define("SQL_TIME_STATS", "time_stats");

define("SQL_LIST_TIME","time");
define("SQL_LIST_USED","used");
define("SQL_LIST_ACTIVE","active");

define("SQL_TABLE_STATUS","server_status");
define("SQL_TABLE_ZERO_DAY_STATUS","server_status_zero");
define("SQL_TABLE_USERS", "users");
define("SQL_TABLE_TEAMS", "teams");


define("TABLE_DATA_MAX", 2000);

// NO CAPITALS IN TABLE OR LIST NAMES !!!!!!!!!!!!!!!!!!!!!!!!!!!

define("SQL_TABLE_USER_DATA", "ud_");
define("SQL_TABLE_TEAM_DATA", "td_");
define("SQL_TABLE_LIST_SNL_TEAM", "list_snl_team");
//define("SQL_TABLE_LIST_OTHER_TEAM", "list_other_team");

define("SQL_TABLE_LIST_ALL_TEAMS", "list_all_teams");
//define("SQL_TABLE_LIST_SNL_TEAM_NEW", "listSnlTeam_new");
define("SQL_TABLE_LIST_SNL_TEAM_TEMP", "list_snl_team_temp");

define("SQL_TABLE_LIST_ALL_USERS", "list_all_users");
define("SQL_TABLE_LIST_ALL_USERS_TEMP", "list_all_users_temp");
define("SQL_TABLE_LIST_ALL_USERS_DUMMY", "list_all_users_dummy");

define("SQL_TABLE_LIST_ALL_COUNTRIES", "list_all_countries");
define("SQL_TABLE_LIST_ALL_COUNTRIES_TEMP", "list_all_countries_temp");
define("SQL_TABLE_LIST_ALL_COUNTRIES_DUMMY", "list_all_countries_dummy");

///


$gi_error_count = 0;
$gfp_logging = NULL;
$gfp_logging_append = NULL;

$gi_load_ballance = 0;
$glogging_fullname  = "";



// make sure the server load stays low.
// without 		(userGzProcess) Finished processing: 0 Hour, 4 Minutes, 20 Seconds
// > 40 10000
// > 20 10000	(userGzProcess) Finished processing: 0 Hour, 16 Minutes, 20 Seconds	// server 1 Hour, 37 Minutes, 9 Seconds
// > 10 10000 	(userGzProcess) Finished processing: 0 Hour, 27 Minutes, 24 Seconds
function LoadBallance()
{
	global $gi_load_ballance;
	$gi_load_ballance++;
	if ($gi_load_ballance > 100)	// more = more CPU usage....
	{
		usleep(250);	// 0.25 msec sleep
		$gi_load_ballance  = 0;
	}
}

function GetTime()
{
	$date = new DateTime("now");
	return $date->format('Y-d-F H:i:s ');
}

// time is -1 on error
// otherwise a timestamp is returned

function GetUrlFileTime($file)
{
	if(!UrlExists($file))
	{
		LoggingAdd("Server down? Could not find: ".$file, true);	
		return -1;		
	}

	$fp_r = fopen($file, "rb");
	if (!$fp_r)	{
		LoggingAdd("(GetUrlFileTime) open: ".$file,TRUE);		// not an error, just unable to read file.
		return -1;
	}
	$data= fread($fp_r, 10);	// we need to read data first, otherwise $meta data is empty. curl or PHP bug
	$meta = stream_get_meta_data($fp_r);
	if ($meta === false)
	{
		fclose($fp_r);
		LoggingAdd("GetUrlFileTime, meta 1",TRUE);				// not an error, just unable to read file.
		return -1;  // Problem reading data from url
	}
	// The header is in Array ( [wrapper_data] => Array ( [headers] =>
	// or Array ( [wrapper_data] =>

	if (isset($meta['wrapper_data'][0]))
	{
		$header = $meta;
		$array_id = "wrapper_data";
	}	
	else
	{
		if (isset($meta['wrapper_data']['headers'][0]))
		{
			$header = $meta['wrapper_data'];
			$array_id = "headers";			
		}
		else
		{
			LoggingAdd("GetUrlFileTime, meta 2: ".print_r($meta,true),TRUE);	// not an error, just unable to read file.
			return -1;
		}
	}

	$modtime = "";
	
	for ($j = 0; isset($header[$array_id][$j]); $j++)
	{
		if (strstr(strtolower($header[$array_id][$j]), 'last-modified'))
		{
			$modtime = substr($header[$array_id][$j], 15);
			break;
		}
	}
	fclose($fp_r);
	$date = DateTime::createFromFormat("*, d M Y H:i:s *", $modtime);
	if ($date == false)
	{
		$text = print_r(DateTime::getLastErrors(),true);
		LoggingAdd("GetUrlFileTime, time: ".$modtime." error dump: ".$text,TRUE);	// not an error, just unable to read file.
		return -1;
	}
	return round($date->getTimestamp()/3600);// one hour timestamp is accurate enough, rounded down to the hour
} 

function UrlExists($url)
 { 
    $hdrs = get_headers($url);
	if (is_array($hdrs))
	{
		$pos=  strpos($hdrs[0],"HTTP");
		if ($pos >= 0) {return true;}
	}
	return false;
}

function FilePermission($file)
{
	echo 'Folder: ' . getcwd().'<br>';
	echo "Permission: ".$file.' ';
	$file = 'loggingtest.php';
	echo substr(decoct(fileperms($file)), -4);
	echo '<br>end<br>';
}

function DataFolder($file)
{
    $whatServer = IsWhatServer();
    if ($whatServer == SERVER_ID_LINODE)
    {
        return LINODE_SERVER_DATA_FOLDER.$file;
    }
     if ($whatServer == SERVER_ID_AMAZON)
    {
        return AMAZON_SERVER_DATA_FOLDER.$file;
    }    
    return DEBUG_DATA_FOLDER.$file;        
}

function LoggingFolder($file)
{
    $whatServer = IsWhatServer();
    if ($whatServer == SERVER_ID_LINODE)
    {
        return LINODE_SERVER_LOGGING_FOLDER.$file;
    }
     if ($whatServer == SERVER_ID_AMAZON)
    {
        return AMAZON_SERVER_LOGGING_FOLDER.$file;
    }    
    return DEBUG_LOGGING_FOLDER.$file;
}

function LoggingOpen($file_in)
{
	global $gfp_logging;
	global $glogging_fullname;
	
	$file = LoggingFolder($file_in);
	
	$time = new DateTime("now"); 	
	$file_time = $file.$time->format("_Y-m-d").".log";
	$gfp_logging = fopen($file_time, "ab");
	$glogging_fullname = $file_time;
 
//        echo "<br> test test test <br>";
//	echo "<br>".$glogging_fullname."<br>";
//        var_dump($gfp_logging);
	
}

function LoggingClose()
{
	global $gfp_logging;
	if ($gfp_logging != NULL)
	{
		fclose($gfp_logging);
	}
}

function LoggingAdd($txt, $crlf)
{
	global $gfp_logging;
	if ($gfp_logging != NULL)
	{
		if ($crlf) {fwrite($gfp_logging, "\r\n");}
		if ($crlf) {fwrite($gfp_logging, GetTime());}
		fwrite($gfp_logging, $txt);
	}
}

function LoggingAddError($txt)
{
	$txt = "===================> ERROR: ".$txt;
	LoggingAdd($txt, TRUE);
	global $gi_error_count;
	$gi_error_count = $gi_error_count + 1;
}

function LoggingAreThereErrors()
{
	global $gi_error_count;
	global $glogging_fullname;
	
	if ($gi_error_count > 0)
	{
		$error = "===================> ERROR detected: ".$gi_error_count;
		LoggingAdd("SETI@Netherlands statistics database ".$error, TRUE);
		SendEmail("fred@efmer.com","database error, see log: ".$glogging_fullname );		
	}
	else
	{
		LoggingAdd("===================> SUCCESS, no errors detected.===========================", TRUE);	
		LoggingAdd("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>", TRUE);			
	}
}

function LoggingRemoveOld($sql)
{
	$remove = false;
	
	$logging_count = readStatus($sql ,SQL_REMOVE_LOGGING_COUNT);
	if ($logging_count === false)
	{
		return;
	}
	$logging_count--;
	if ($logging_count <= 0)
	{
		$logging_count = 120;
		$remove = true;
	}
	writeStatus($sql, SQL_REMOVE_LOGGING_COUNT, $logging_count);
	
	if ($remove == false)
	{
		LoggingAdd("Check to remove old logging: ".$logging_count,TRUE);
		return;
	}
	
	LoggingAdd("Removing old logging files",TRUE);	
	
        $dir = LoggingFolder('');
//	$dir = LOCAL_LOGGING_FOLDER;
	if (is_dir($dir))
	{
		$dh = opendir($dir);
		if ($dh)
		{
			while (($file = readdir($dh)) !== false)
			{
				$file_type = filetype($dir.$file);
				if ($file_type == "file")
				{
					if (strstr($file,".log"))
					{
						$time = filemtime($dir.$file);
						$time14 = $time + (60*60*24*14); 	// remove > 14 days
						if (time() > $time14)
						{
							unlink(LoggingFolder('').$file);
							LoggingAdd("Deleting file: ".LoggingFolder('').$file,TRUE);								
						}
					}
				}
			}
			closedir($dh);
		}
	}
}

function LoggingAppendOpen($file_in)
{
	global $gfp_logging_append;

        $file = LoggingFolder($file_in);        
        
	$time = new DateTime("now"); 	
	$file_time = $file.$time->format("_Y-m-d").".log";
	$gfp_logging_append = fopen($file_time, "ab");
}

function LoggingAppendClose()
{
	global $gfp_logging_append;
	if ($gfp_logging_append != NULL)
	{
		fclose($gfp_logging_append);
	}
}

function LoggingAppendAdd($txt, $crlf)
{
	global $gfp_logging_append;
	
	if ($gfp_logging_append == NULL)
	{
		LoggingAppendOpen(FILE_ERROR_LOG);
	}
	
	if ($gfp_logging_append != NULL)
	{
	if ($crlf) { fwrite($gfp_logging_append, "\r\n");}
		if ($crlf) { fwrite($gfp_logging_append, GetTime());}
		fwrite($gfp_logging_append, $txt);
	}
}

function LoggingAppendAddError($txt)
{
	$txt = "===================> ERROR: ".$txt;
	LoggingAppendAdd($txt, TRUE);
}

function userCurrentlyRunning($sql)
{
	$status = readStatus($sql ,SQL_USER_ERROR_COUNT);
	if ($status === FALSE) return true;
	if ($status == "") return true;
	if ($status == -1) return true;				// running
	return false;
}

function teamCurrentlyRunning($sql)
{
	$status = readStatus($sql ,SQL_TEAM_ERROR_COUNT);
	if ($status === FALSE) return true;
	if ($status == "") return true;
	if ($status == -1) return true;				// running
	return false;
}

?>
