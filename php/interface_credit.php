<?php

function GetCreditUser($sql, $sqlStats, $id, $tm)
{
	if ($tm == 0) return GetCredit($sql, $sqlStats, $id, SQL_TABLE_USERS, SQL_TABLE_USER_DATA);
	return GetCreditTm($sql, $sqlStats, $id, SQL_TABLE_USERS, SQL_TABLE_USER_DATA, $tm);
}
function GetCreditTeam($sql, $sqlStats, $id, $tm)
{
	if ($tm == 0) return GetCredit($sql, $sqlStats, $id, SQL_TABLE_TEAMS, SQL_TABLE_TEAM_DATA);
	return GetCreditTm($sql, $sqlStats, $id, SQL_TABLE_TEAMS, SQL_TABLE_TEAM_DATA, $tm);	
}

function GetCredit($sql, $sqlStats, $id, $table, $table_data)
{
	$data_table = -1;
	
	$sqlCommand = "SELECT ".SQL_DATA_TABLE." FROM ".$table." WHERE ".SQL_ID."='$id' LIMIT 1";	// get right graph table
	$result = $sql->query($sqlCommand);
	if ($result === FALSE)	
	{
		$json_status = array('error', 'query_result 1');
		return (json_encode($json_status));	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt > 0)
	{	
		$row = mysqli_fetch_array($result);
		if ($row == false)
		{
			$json_status = array('error', 'query_result 2');
			return (json_encode($json_status));	
		}
		$data_table = $row[SQL_DATA_TABLE];
	}
	$result->close();
	
	if ($data_table < 0)
	{
		$json_status = array('error', 'query_result');
		return (json_encode($json_status));		
	}	
	
	$users = $table_data.$data_table;
	$sqlCommand = "SELECT time, total_credit FROM ".$users." WHERE ".SQL_ID."='$id'";
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
			$credit = UnPackBase36($packed_credit);
			$data = array($time, $credit);
			array_push($json_data, $data);
		}
		$result->close();
		
		$command = "SELECT ".SQL_LIST_TIME.",".SQL_TOTAL_CREDIT." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id' LIMIT 1";
		$result_zero = $sql->query($command);
		if ($result_zero !== FALSE)
		{
			$row_cnt = $result_zero->num_rows;
			if ($row_cnt > 0)
			{
				while($row = mysqli_fetch_array($result_zero))
				{					
					$time_zero = $row[SQL_LIST_TIME];
					if ($time_zero > $time)
					{
						$credit_zero = $row[SQL_TOTAL_CREDIT];
						$data = array($time_zero, $credit_zero);
						array_push($json_data, $data);
					}
				}
			}
			$result_zero->close();
		}

		return (json_encode($json_data));			
	}
	else
	{
		$json_status = array('error', 'query_col_0');			
		return (json_encode($json_status));		
	}
}

function GetCreditTm($sql, $sqlStats, $id, $table, $table_data)
{
	$sqlCommand = "SELECT ".SQL_TIME_STATS.",".SQL_TOTAL_CREDIT.",".SQL_RAC." FROM ".$table." WHERE ".SQL_ID."='$id' LIMIT 1";	// get right graph table
	$result = $sql->query($sqlCommand);
	if ($result === FALSE)	
	{
		echo mysqli_error($sql);
		$json_status = array('error', 'query_result 1');
		return (json_encode($json_status));	
	}
	$row_cnt = $result->num_rows;
	if ($row_cnt == 0)
	{
		$json_status = array('error', 'query_result 2');
		return (json_encode($json_status));			
	}
	$row = mysqli_fetch_array($result);
	if ($row == false)
	{
		$json_status = array('error', 'query_result 3');
		return (json_encode($json_status));	
	}
	$time = $row[SQL_TIME_STATS];
	$credit = $row[SQL_TOTAL_CREDIT];
	$rac = $row[SQL_RAC];
	$result->close();
	
	$json_data = array ();
	$data = array('data','ok');
	array_push($json_data,$data);		
	
	$delta_time = 168;							// 1 week
	$delta_credit = $rac * 7;

	for ($i = 0; $i< 160; $i++)					// 160 weeks
	{
		$data = array($time, round($credit));
		array_push($json_data, $data);
		$time += $delta_time;					// + 7 days or so
		$credit += $delta_credit;				
	}
	return (json_encode($json_data));
}

?>