<?php

function ReadDatabaseStatus($sql)
{
	$json_data = array('data', 'ok');		
	
	$data_array = readStatusTableAll(SQL_TABLE_STATUS, $sql);
	if ($data_array == false)
	{
		$json_data = array('data', 'error');			
		return $json_data;		
	}

	array_push($json_data, $data_array);
		
	return $json_data;
}

function ReadDatabaseZeroDayStatus($sql)
{
	$json_data = array('data', 'ok');		
	
	$data_array = readStatusTableAll(SQL_TABLE_ZERO_DAY_STATUS, $sql);
	if ($data_array == false)
	{
		$json_data = array('data', 'error');			
		return $json_data;		
	}

	array_push($json_data, $data_array);
		
	return $json_data;
	
}

?>