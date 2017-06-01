<?php

require '../common.php';

dumpSqlStatsDatabase();

function dumpSqlStatsDatabase()
{
	LoggingOpen(LOCAL_LOGGING_FOLDER."backup");	
	
	$dbfilename ="seti_stats1";
	$hostname = gethostname();
	if (strpos($hostname, WEB_SERVER) === false)
	{
		// test machine	
		$dbname = "__seti_stats1";
		$dbhost = 'localhost';
		$dbuser = 'setiatnl';
		$dbpass = '23gbowMLMvTGOkypDSWlStl8vevRn1';
		$backup_file = "backup/".$dbfilename."__".date("Y-m-d-H-i-s").'.gz';
		$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass -d $dbname > $backup_file";		
	}
	else
	{
		$dbname = "lpfdx7gz_seti_stats1";
		$dbhost = 'localhost';
		$dbuser = 'lpfdx7gz_seti';
		$dbpass = '23gbowMLMvTGOkypDSWlStl8vevRn1';
		$backup_file = "backup/".$dbfilename."__".date("Y-m-d-H-i-s").'.gz';
		$command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass -d $dbname | gzip > $backup_file";		
	}


	$status = system($command);
	if ($status === false)
	{
		LoggingAddError("(dumpSqlStatsDatabase) __seti_stats1 ");
		LoggingClose();
		echo "failed";
		SendEmail("fred@efmer.eu","backup <<<<<<<<<<<<<<<<<FAILED>>>>>>>>>>>>>>>>>>");		
		die();
	}
	LoggingAdd("(dumpSqlStatsDatabase) __seti_stats1 OK", true);	
	LoggingClose();
	SendEmail("fred@efmer.eu","backup completed");
	echo "ready";
}

?>	