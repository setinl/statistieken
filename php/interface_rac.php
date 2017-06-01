<?php

function GetRacUser($sql, $sqlStats, $id)
{
	return GetRac($sql, $sqlStats, $id, SQL_TABLE_USERS, SQL_TABLE_USER_DATA);
}
function GetRacTeam($sql, $sqlStats, $id)
{
	return GetRac($sql, $sqlStats, $id, SQL_TABLE_TEAMS, SQL_TABLE_TEAM_DATA);
}

function GetRac($sql, $sqlStats, $id, $table, $table_data)
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
	$sqlCommand = "SELECT time, rac FROM ".$users." WHERE ".SQL_ID."='$id'";
	$result = $sqlStats->query($sqlCommand);
	if ($result === FALSE)
	{
		$json_status = array('error', 'query_result 3');			
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
			$rac = UnPackBase36($packed_rac);
			$data = array($time, $rac);
			array_push($json_data, $data);
		}
		$result->close();
		
		$command = "SELECT ".SQL_LIST_TIME.",".SQL_RAC." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id' LIMIT 1";
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

?>