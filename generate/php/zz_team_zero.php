<?php

// all members that are NOT in a team

function teamZero($sql)
{
	set_time_limit(600);	// 10 minutes	
	
	$timeStart = new DateTime("now"); 
	LoggingAdd("(teamZero) Start updating TeamZero list",TRUE);	
	
	$team = 0;// team zero;
	
	$team_credit = 0;
	$team_rac = 0;
	
	$b_fetch_next = TRUE;
	$i_start = 0;

	while($b_fetch_next)
	{		
		$command = "SELECT ".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".SQL_TABLE_USERS." WHERE ".SQL_USER_TEAM."='$team' LIMIT $i_start, 100";
		$result = $sql->query($command);LoadBallance();
		if ($result === FALSE)
		{
			LoggingAddError("teamZero 1: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$team_rac += UnPackBase32($row[SQL_RAC]);
				$team_credit += UnPackBase32($row[SQL_TOTAL_CREDIT]);
			}
		}
		else
		{
			// empty
			$b_fetch_next = FALSE;
		}		
		$result->close();		
		$i_start = $i_start + 100;
	}
	
	$timestamp_url = teamGzTimestamp($sql);
	$data_id = 0; // zero
	$data_type = 0;
	$packed_data_user_id = 0;
	$data_name = "Team Zero";
	$data_name_html = "Team Zero";
	$data_url = "";
	$data_descr = "No team users";
	$country = "QY";
	$packed_data_credit = PackBase32($team_credit);
	$packed_data_rac = PackBase32($team_rac);
	
	$result = $sql->query("SELECT ".SQL_ID." FROM ".SQL_TABLE_TEAMS." WHERE ".SQL_ID."='$data_id' LIMIT 1");LoadBallance();
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			$result->close();
			
			$sqlCommand = "UPDATE ".SQL_TABLE_TEAMS." SET time_last='$timestamp_url', type='$data_type', user_id='$packed_data_user_id', name='$data_name', name_html='$data_name_html', url='$data_url', descr='$data_descr', ".SQL_COUNTRY."='$country', ".SQL_TOTAL_CREDIT."='$packed_data_credit', rac='$packed_data_rac' WHERE ".SQL_ID."='$data_id' LIMIT 1";	
			$result = $sql->query($sqlCommand);	LoadBallance();
			if (!$result)
			{
				LoggingAddError("teamZero 2: " . mysqli_error($sql));
				return ERR_DATABASE;
			}
			$action = "Update, ";			
		}
		else
		{
			$result = $sql->query("INSERT INTO ".SQL_TABLE_TEAMS."(".SQL_ID.", time_last, type, user_id, name, name_html, url, descr,".SQL_COUNTRY.", ".SQL_TOTAL_CREDIT.", rac) VALUES ('$data_id', '$timestamp_url', '$data_type', '$packed_data_user_id', '$data_name', '$data_name_html', '$data_url', '$data_descr', '$country', '$packed_data_credit', '$packed_data_rac')");LoadBallance();
			if ($result === FALSE)
			{
				LoggingAddError("teamZero 3: ".$team." ".$data_id.mysqli_error($sql));
				return ERR_DATABASE;
			}
			$action = "Insert, ";			
		}
	}
	if (!$result)
	{
		LoggingAddError("teamZero 4: " . mysqli_error($sql));
		return ERR_DATABASE;		
	}		
		
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	LoggingAdd("(teamZero) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);	

	return STATUS_OK;
}
