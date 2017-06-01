<?php

// uses list_other_team

function listCountries($sql)
{
	set_time_limit(1240);	
	
	writeStatus($sql ,SQL_PROGRESS, PROGRESS_LIST_ALL_COUNTRIES);		
	
	$table_countries = SQL_TABLE_LIST_ALL_COUNTRIES_TEMP;	
	$status = sqlCreateTableListCountry($sql,$table_countries);
	if ($status == false)
	{
		LoggingAddError("(listCountries) unable create: ".mysqli_error($sql));		
		return ERR_DATABASE;		
	}
	$status= sqlEmptyTable($sql, $table_countries);
	if ($status === FALSE)
	{
		LoggingAddError("listUsers 2: ".mysqli_error($sql));
		return ERR_DATABASE;
	}	
	
	$timeStart = new DateTime("now"); 
	LoggingAdd("(listCountries) Start countries list",TRUE);	
	
	$command ="SELECT DISTINCT ".SQL_COUNTRY." FROM ".SQL_TABLE_USERS;
	$result_countries = $sql->query($command);LoadBallance();
	if ($result_countries === FALSE)
	{
		LoggingAddError("listCountries 1: ".mysqli_error($sql));
		return ERR_DATABASE;	
	}	
	while($row = mysqli_fetch_array($result_countries))
	{
		$country = $row[SQL_COUNTRY];
		writeStatus($sql ,SQL_PROGRESS_TEXT, "$country");	
		if (listCountriesAdd($sql, $table_countries ,$country) != true)
		{
			LoggingAddError("listCountries abort: ".mysqli_error($sql));
			return ERR_DATABASE;	
		}	
	}
	if (listCountriesSortRac($sql, $table_countries, SQL_RAC, SQL_RANK_RAC) == true)
	{						
		if (listCountriesSortRac($sql, $table_countries, SQL_TOTAL_CREDIT, SQL_RANK_CREDIT) == true)
		{								
		
		}
	}	
	
	// now swap the tables
	
	$status = sqlCreateTableListUsers($sql,SQL_TABLE_LIST_ALL_COUNTRIES);	// create one to make sure the rename goes all right.
	if ($status == false)
	{
		LoggingAddError("(listUsers) unable create 2: ".mysqli_error($sql));		
		return ERR_DATABASE;		
	}
	
	$command = "RENAME TABLE ".SQL_TABLE_LIST_ALL_COUNTRIES." TO ".SQL_TABLE_LIST_ALL_COUNTRIES_DUMMY.",".SQL_TABLE_LIST_ALL_COUNTRIES_TEMP." TO ".SQL_TABLE_LIST_ALL_COUNTRIES;
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listCountries swap: ".mysqli_error($sql));
		return false;	
	}	

	$command = "DROP TABLE IF EXISTS ".SQL_TABLE_LIST_ALL_COUNTRIES_DUMMY.",".SQL_TABLE_LIST_ALL_COUNTRIES_TEMP;
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listCountries dummy: ".mysqli_error($sql));
		return false;	
	}	
	
	
	
	$timeStop = new DateTime("now"); 
	$interval = $timeStart->diff($timeStop);
	$interval_string = $interval->format('%h H, %i M, %s S');	
	LoggingAdd("(listCountries) Finished after: ". $interval->format($interval_string),TRUE);		
	LoggingAdd("",TRUE);

	writeStatus($sql ,SQL_PROGRESS, PROGRESS_NONE);	
	
	return true;
}

function listCountriesAdd($sql, $table_countries, $country)
{
	$sum_rac = "SUM(".SQL_RAC.")";
	$sum_credit = "SUM(".SQL_TOTAL_CREDIT.")";

	$active  = 0;
	$command = "SELECT COUNT(".SQL_COUNTRY.") as total FROM ".SQL_TABLE_USERS." WHERE ".SQL_COUNTRY."='$country'";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listCountriesAdd count: ".mysqli_error($sql));
		return ERR_DATABASE;
	}
	while($row = mysqli_fetch_array ($result))
	{
		$active = $row['total'];
    }	
	$result->close();	
	
	$command = "SELECT $sum_rac,$sum_credit FROM ".SQL_TABLE_USERS." WHERE ".SQL_COUNTRY."='$country'";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listCountriesAdd RAC: ".mysqli_error($sql));
		return false;	
	}	
	if ($row = mysqli_fetch_array($result))
	{
		$rac = $row[$sum_rac];
//		echo "country: ".$country." RAC: ".$rac."<br>";
		$credit = $row[$sum_credit];
//		echo "country: ".$country." CREDIT: ".$credit."<br>";
		
		$command = "INSERT INTO ".$table_countries." (".SQL_COUNTRY.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_ACTIVE.") VALUES ('$country', '$credit', '$rac', '$active')";
		$resultInsert = $sql->query($command);LoadBallance();
		if ($resultInsert === FALSE)
		{
			LoggingAddError("listCountriesAdd insert: ".mysqli_error($sql));
			return false;
		}		
	}
	
	$result->close();
	return true;
}

function listCountriesSortRac($sql, $table_name, $order, $item)
{
	$result = $sql->query("SET @num:= 0");
	if ($result === FALSE)
	{
		LoggingAddError("listCountriesSortRac 1: ".mysqli_error($sql));
		return false;	
	}	
	
	$command = "UPDATE ".$table_name." SET $item = (@num:=@num+1) ORDER BY ".$order." DESC";
	$result = $sql->query($command);LoadBallance();
	if ($result === FALSE)
	{
		LoggingAddError("listCountriesSortRac 2: ".mysqli_error($sql));
		echo $command;
		return false;	
	}
	return true;	
}

?>