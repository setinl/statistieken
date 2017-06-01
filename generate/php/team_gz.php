<?php

function teamGzReadAndProcess($sql,$url_gz, $file_local, &$stats_added)
{
	if (!teamGzAreWeCurrentlyRunning($sql))							// check the -1 start marker
	{
		$status = writeStatus($sql, SQL_TEAM_ERROR_COUNT, -1);	// mark start
		if ($status)
		{
			$time_stamp_sql = teamGzTimestamp($sql);	// get time stamp from database	
			$status = downloadUrlGz($sql,$url_gz, $file_local, $time_stamp_sql, $timestamp_url, SQL_TEAM_FILE_READ_DURATION);	
			if ($status == STATUS_OK)
			{
				$date = new DateTime("now"); 
				$time_stamp = $date->getTimestamp();
				writeStatus($sql, SQL_TEAM_START_TIME, $time_stamp);				
				
				writeStatus($sql, SQL_TEAM_PROCESSED_DURATION, "");
				writeStatus($sql, SQL_TEAM_ADD_DURATION, "");				
			
				$status = teamGzProcess($sql,$file_local, $timestamp_url);
				if ($status == STATUS_OK)
				{
				//	$sql_stats = connectSqlSetiStats();			
				//	teamStatsAdd($sql, $sql_stats, $timestamp_url);
				//	mysqli_close($sql_stats);						
					
					$sql_stats	= connectSqlSetiStatsC();
					if ($sql_stats === false)
					{
						return false;
					}
					teamStatsAdd($sql, $sql_stats, $timestamp_url);
					mysqli_close($sql_stats);					
					
					$stats_added = true;
				}
			}
		}
		else
		{
			LoggingAddError("Unable to write: ".SQL_TEAM_ERROR_COUNT);				
		}			
	}
	else
	{
		LoggingAdd("(teamGzReadAndProcess) another instance is already running, aborting", true);
		SendEmail("fred@efmer.com","(teamGzReadAndProcess )database: another instance is already running, or an error needs to be cleared");
		return false;
	}
	return true;	
}

function teamGzProcess($sql,$file_local, $timestamp_url)
{
	$s_data_block = "";
	$s_data_read = "";
	$status = STATUS_OK;
	$teams_end = FALSE;	
	$i_block_read = 0;
	$i_record_read = 0;
	$i_record_team_read = 0;
	
	$timeStart = new DateTime("now"); 	
	
	$fp_r = gzopen($file_local, "r");
	if (!$fp_r)	{
		LoggingAddError("Unable to read file: ".$file_local);	
		return ERR_FILE_READ;
	}
	LoggingAdd("(teamGzProcess) Processing team data: ".$file_local,TRUE);

	set_time_limit(72000);	// 2 hour
	
	while (1)
	{
		// get more data
		while (!feof($fp_r) && strlen($s_data_block) < 65536)	// some fools make long long texts
		{
			$s_data_read = gzread($fp_r, 4096);
			if (strlen($s_data_read) > 0)
			{
				$i_block_read = $i_block_read + 1;					
			}
			$s_data_block.= $s_data_read;			
		}

		if ($i_block_read == 1) 
		{
			if (xmlFindTag($s_data_block, XML_TEAMS, FALSE) != STATUS_OK)
			{
				LoggingAdd("Missing <teams> tag <<<>> ".$s_data_read,TRUE);		
				return ERR_TAG_MISMATCH;			
			}
		}
		$block = xmlExtract($s_data_block, XML_TEAM, XML_TEAM_END, TRUE);
		$status = teamGzExtract($sql, $block, $timestamp_url);
		if ($status == ERR_EMPTY)
		{
			LoggingAdd("empty: ".$block."block len: ".strlen($s_data_block)."<<<<<>>>>".$s_data_block,TRUE);		
			break;		// this is the way out not more valid team items
		}
		
		if ($status == STATUS_OK) {
			$i_record_team_read = $i_record_team_read + 1;
		}
		
		$i_record_read = $i_record_read + 1;
	}
	
	if (xmlFindTag($s_data_block, XML_TEAMS_END, FALSE) == STATUS_OK) {$teams_end = TRUE;}
		
	LoggingAdd("Record team read: ".$i_record_team_read.", Blocks read: ".$i_block_read,TRUE);
	gzclose($fp_r);	
	
	if ($teams_end != TRUE) {
		LoggingAdd("Missing </teams> end tag",TRUE);	
		return ERR_TAG_MISMATCH;
	}
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	
	$interval_string = $interval->format('%h H, %i M, %s S');	
	writeStatus($sql, SQL_TEAM_PROCESSED_DURATION, $interval_string);	
	LoggingAdd("(teamsGzProcess) Finished processing teams: ". $interval_string ,TRUE);		
	LoggingAdd("",TRUE);		
	teamGzSetNewTimestamp($sql, $timestamp_url);
	
	return STATUS_OK;	
}

function teamGzExtract($sql,$block, $timestamp_url)
{
	if (strlen($block) < 4)
	{
		return ERR_EMPTY;
	}
	
	$data_id = xmlExtract($block, XML_T_ID, XML_T_ID_END, FALSE);
	$data_type = xmlExtract($block, XML_T_TYPE, XML_T_TYPE_END, FALSE);
	$data_name = xmlExtract($block, XML_T_NAME, XML_T_NAME_END, FALSE);	
	$data_name = $sql->real_escape_string($data_name);
	
	$data_user_id = xmlExtract($block, XML_T_USER_ID, XML_T_USER_ID_END, FALSE);
	
	$data_credit = round(xmlExtract($block, XML_T_CREDIT, XML_T_CREDIT_END, FALSE),2);
	$data_rac = round(xmlExtract($block, XML_T_RAC, XML_T_RAC_END, FALSE),3);
	$data_url = xmlExtract($block, XML_T_URL, XML_T_URL_END, FALSE);
	$data_url = $sql->real_escape_string($data_url);
	$data_name_html = xmlExtract($block, XML_T_NAME_HTLM, XML_T_NAME_HTML_END, FALSE);
	$data_name_html = $sql->real_escape_string($data_name_html);
	$data_descr = xmlExtract($block, XML_T_DESCR, XML_T_DESCR_END, FALSE);
	$data_descr = $sql->real_escape_string($data_descr);
	$data_country = xmlExtract($block, XML_T_COUNTRY, XML_T_COUNTRY_END, FALSE);
	$country = CountryShortString($data_country);
	
	$result = $sql->query("SELECT ".SQL_ID." FROM ".SQL_TABLE_TEAMS." WHERE ".SQL_ID."='$data_id' LIMIT 1");LoadBallance();
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			$result->close();
			
			$sqlCommand = "UPDATE ".SQL_TABLE_TEAMS." SET time_last='$timestamp_url', type='$data_type', user_id='$data_user_id', name='$data_name', name_html='$data_name_html', url='$data_url', descr='$data_descr', ".SQL_COUNTRY."='$country', ".SQL_TOTAL_CREDIT."='$data_credit', rac='$data_rac' WHERE ".SQL_ID."='$data_id' LIMIT 1";	
			$result = $sql->query($sqlCommand);	LoadBallance();
			if (!$result)
			{
				LoggingAddError("teamGzExtract 2: " . mysqli_error($sql));
				return ERR_DATABASE;
			}
			$action = "Update, ";			
		}
		else
		{
			$result = $sql->query("INSERT INTO ".SQL_TABLE_TEAMS."(".SQL_ID.", time_last, type, user_id, name, name_html, url, descr,".SQL_COUNTRY.", ".SQL_TOTAL_CREDIT.", rac) VALUES ('$data_id', '$timestamp_url', '$data_type', '$data_user_id', '$data_name', '$data_name_html', '$data_url', '$data_descr', '$country', '$data_credit', '$data_rac')");LoadBallance();
			if ($result === FALSE)
			{
				LoggingAddError("teamsGzExtract 3: ".$team." ".$data_id.mysqli_error($sql));
				return ERR_DATABASE;
			}
			$action = "Insert, ";			
		}
	}
	if (!$result)
	{
		LoggingAddError("teamGzExtract 3: " . mysqli_error($sql));
		return ERR_DATABASE;		
	}	
	
//	LoggingAdd($action."Name: ".$data_name. " Country: ".$country,TRUE);
	LoadBallance();
	return STATUS_OK;
}


function teamGzTimestamp($sql)
{
	$status = readStatus($sql ,SQL_TEAM_URL_FILE_TIME);
	if ($status === FALSE)
	{
		$status = -1;
	}
	if ($status == "")
	{
		$status = -1;
	}
	return $status;
}

function teamGzSetNewTimestamp($sql, $timestamp_url)
{
	$status = writeStatus($sql, SQL_TEAM_URL_FILE_TIME, $timestamp_url);
	return $status;
}

function teamGzAreWeCurrentlyRunning($sql)
{
	$status = readStatus($sql ,SQL_TEAM_ERROR_COUNT);
	if ($status === FALSE) return true;
	if ($status == "") return true;
	if ($status == -1) return true;				// running
	return false;
}

?>