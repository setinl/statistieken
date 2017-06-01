<?php

function downloadUrlGz($sql, $url_gz, $file_local, $time_stamp_sql, &$time_stamp_url, $sql_var_name)
{
//	global $gi_user_gz_timestamp;
//	global $gi_team_gz_timestamp;

	set_time_limit(1200);	// does nothing in safe mode	20 minutes
	
//	$time_stamp_sql = userGzTimestamp($sql ,$file);	// get time stamp from database
	$time_stamp_url = GetUrlFileTime($url_gz);
	if ($time_stamp_url == ERR_FILE_READ) {return ERR_FILE_READ;}
	
	$dateString = gmdate("Y M d H:i:s", $time_stamp_url*3600);
	
	LoggingAdd("Check if update needed: ".$url_gz." timestamp: [sql] ".$time_stamp_sql." [url] ".$time_stamp_url." [GMT] ".$dateString,TRUE);	
	if ($time_stamp_sql >= $time_stamp_url)
	{
		LoggingAdd("Timestamp is equal, no update necessary.",TRUE);	
		return STATUS_TIME_STAMP_EQUAL;		
	}
	
	writeStatus($sql, $sql_var_name, "");
	
//	if ($type == GZ_USER)	
//	{
//		glb::set(GLOBAL_TEAM_GZ_TIMESTAMP, $time_stamp_url);
//		echo "ts: ".glb::get(GLOBAL_TEAM_GZ_TIMESTAMP);
//		LoggingAdd("Timestamp user download: ". $timestamp = glb::get(GLOBAL_TEAM_GZ_TIMESTAMP), TRUE);
//	}
//	else	
//	{
//		glb::set(GLOBAL_USER_GZ_TIMESTAMP, $time_stamp_url);
//		echo "ts: ".glb::get(GLOBAL_USER_GZ_TIMESTAMP);		
//		LoggingAdd("Timestamp team download: ". glb::get(GLOBAL_USER_GZ_TIMESTAMP), TRUE);		
//	}	
	
	$timeStart = new DateTime("now"); 
	
	$fp_r = @fopen($url_gz, "rb");
	if (!$fp_r)	{
		LoggingAddError("(gzRead) Unable to read file: ".$url_gz);	
		return ERR_FILE_READ;
	}	
	
	$fp_w = @fopen($file_local, 'w');	
	if (!$fp_w)	{
		LoggingAddError("(gzRead) Unable to read file: ".$file_local);
		return ERR_FILE_WRITE;
	}
	LoggingAdd("(gzRead) Start reading: ".$url_gz." Folder: ".$file_local,TRUE);
	
	set_time_limit(600);	// does nothing in safe mode	10 min	
	
	while (!feof($fp_r)) {
		$data_read = fread($fp_r, 8192);
		if (strlen($data_read) == 0) {
			break;	// 0 not necessarily an error but it is the EOF
		}
		fwrite($fp_w, $data_read);
	}
	
	fclose($fp_r);
	fclose($fp_w);
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');
	writeStatus($sql, $sql_var_name, $interval_string);		
	LoggingAdd("(gzRead) Finished reading: ".$url_gz." after: ". $interval_string ,TRUE);	
	LoggingAdd("",TRUE);	
	return STATUS_OK;
}

?>