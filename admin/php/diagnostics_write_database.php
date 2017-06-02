<?php

// write database test

require '../../generate/php/common.php';
require('../../generate/php/passwords/pass.php');
require '../../generate/php/sql/sql.php';

echo 'diagnostics<br>';

echo 'Hostname: '.gethostname().'<br>';
echo 'Logging folder: '.LoggingFolder('diagnostics').'<br>';
LoggingOpen('diagnostics');

$localhost = "Localhost: ".gethostname();
$currentPhp = "Current PHP version: ".phpversion();

LoggingAdd("-------------------------------------------------", TRUE);


LoggingAdd($localhost, TRUE);
LoggingAdd($currentPhp, TRUE);

if (IsDebugServer())
{
    $server = "Server: Debug";
}
else
{
    $server = "Server: Internet";
}
echo $server.'<br>';
        
$ds = disk_total_space("/");
$df = disk_free_space("/");
$ds = ConvertSize($ds);
$df = ConvertSize($df);

// test database write

$sql = connectSqlSeti();
if ($sql === false)
{
    $sqlStatusTest = "Failed: connectSqlSeti";
    LoggingAddError($sqlStatusTest, true);
    echo "ERROR: ".$sqlStatusTest.'<br>';
}
 else
{
    $table = SQL_TABLE_USERS;
    $id = '8906489';
    $command = "SELECT ".SQL_USER_NAME.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_RANK_CREDIT.",".SQL_RANK_RAC." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id' LIMIT 1";
    $result = $sql->query($command);
    if ($result === FALSE)
    {
        $sqlStatusTest = "Failed: SELECT";
        LoggingAddError($sqlStatusTest, true);
        echo "ERROR: ".$sqlStatusTest.'<br>';
    }
    else
    {
        $sqlStatusTest = "Database access write: OK";
        LoggingAdd($sqlStatusTest, true);
        echo $sqlStatusTest.'<br>';
    }
}
mysqli_close($sql);

//$array = GetPassWordSqlRead();
//$sql_password_r = $array["sql_password_r"];
//echo $sql_password_r;

$emailText = "Diagnostics: Total diskspace: ".$ds. " Free diskspace: ".$df. " ".$server;
echo $emailText.'<br>';
   
if (SendEmail("fred@efmer.com","diagnostics test mail","a diagnostics test" ))
{
    $emailStatusText = 'Success: Email send';
}
else
{
    $emailStatusText = 'FAILED to send email';               
}

LoggingAdd($emailStatusText, TRUE);
echo $emailStatusText.'<br>';

LoggingClose();	

echo 'END of diagnostics<br>';

function ConvertSize($size){
  $base = log($size) / log(1024);
  $suffix = array("", "KB", "MB", "GB", "TB");
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function connectSqlSetiD()
{
    $array = GetPassWordSqlReadWrite();
    $sql_password_rw = $array["sql_password_rw"];
    echo $sql_password_rw.'<br>';
    if (IsDebugServer())
	{
		// test machine
		$mysqli  = @new mysqli("localhost","setiatnl","TILpOIYCB0BSYDm2","__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error (connectSqlSeti) seti: " . $mysqli ->connect_errno);
			return false;
		}
	}
	else
	{
		// server
                
		$mysqli  = @new mysqli("localhost",$sql_password_rw,"__seti");
		if ($mysqli ->connect_errno)	{
			LoggingAddError("database error (connectSqlSeti read/write) seti: " . $mysqli ->connect_errno);
			return false;
		}	
	}
	return $mysqli ;
}



?>
