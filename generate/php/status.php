<?php

function readStatus($sql ,$row_item)
{
	return readStatusTable(SQL_TABLE_STATUS, $sql, $row_item);
}

function writeStatus($sql ,$row_item, $row_data)
{
	return writeStatusTable(SQL_TABLE_STATUS, $sql, $row_item, $row_data);
}

function readStatusZeroDay($sql ,$row_item)
{
	return readStatusTable(SQL_TABLE_ZERO_DAY_STATUS, $sql, $row_item);
}

function writeStatusZeroDay($sql ,$row_item, $row_data)
{
	return writeStatusTable(SQL_TABLE_ZERO_DAY_STATUS, $sql, $row_item, $row_data);
}

function readStatusTable($table, $sql ,$row_item)
{
	$row_item_read  = '';

	$sqlCommand = "SELECT * FROM ".$table." LIMIT 1";
	$result = $sql->query($sqlCommand);
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			if ($row = mysqli_fetch_array($result))
			{
				$row_item_read = $row[$row_item];
				$result->close();
				return $row_item_read;
			}
		}
	}
	else
	{
		LoggingAddError("(readStatusTable): ".$table." : ". mysqli_error($sql));
		return FALSE;
	}
	
	return "";
}

function readStatusTableAll($table, $sql)
{
	$sqlCommand = "SELECT * FROM ".$table." LIMIT 1";
	$result = $sql->query($sqlCommand);
	if ($result)
	{
		$row_cnt = $result->num_rows;
		if ($row_cnt > 0)
		{
			if ($row = mysqli_fetch_array($result))
			{
				return $row;
			}	
		}
	}
	return false;
}


function writeStatusTable($table, $sql ,$row_item, $row_data)
{
	$sqlCommand = "UPDATE ".$table." SET ".$row_item."='".$row_data."' LIMIT 1";
	$query = $sql->query($sqlCommand);	// only use the first record
	if ($query === FALSE)	
	{
		LoggingAddError("(writeStatusTable)".$table." : " . mysqli_error($sql));
		return FALSE;
	}
	return TRUE;
}

?>

