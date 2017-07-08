<?php

require '../../generate/php/common.php';
require('../../php/passwords/pass_read.php');
require '../../generate/php/passwords/pass.php';
require '../../php/sql/sql_read.php';

$CRLF = '<br>';

$statusText = 'S@NL diagnostics'.$CRLF;
$statusText.= 'Hostname: '.gethostname().$CRLF;
$statusText.= 'Logging folder: '.LoggingFolder('diagnostics').$CRLF;
LoggingOpen('diagnostics');

$localhost = "Localhost: ".gethostname();
$currentPhp = "Current PHP version: ".phpversion();

$statusText.= "-------------------------------------------------".$CRLF;


if (IsDebugServer())
{
    $server = "Server: Debug";
}
else
{
    $server = "Server: Internet";
}
$statusText.= $server.$CRLF;

$whatServer = IsWhatServer();
if ($whatServer == SERVER_ID_DEBUG)
{
    $server = "WhatServer: Debug";  
}
if ($whatServer == SERVER_ID_LINODE)
{
    $server = "WhatServer: Linode";  
}
if ($whatServer == SERVER_ID_AMAZON)
{
    $server = "WhatServer: Amazon Lightsail";  
}
$statusText.= $server.$CRLF;

$folder = DataFolder("");
$statusText.= $folder.$CRLF;
$folder = LoggingFolder("");
$statusText.= $folder.$CRLF;
        
$ds = disk_total_space("/");
$df = disk_free_space("/");
$ds = ConvertSize($ds);
$df = ConvertSize($df);

// test database read

$sql = connectSqlSeti();
if ($sql === false)
{
    $sqlStatusTest = "Failed: connectSqlSeti";
    LoggingAddError($sqlStatusTest, true);
    $statusText .= "ERROR: ".$sqlStatusTest.$CRLF;
}
 else
{
    $charset  = "Initial character set: ".$sql->character_set_name();
    LoggingAdd($charset, true); 
    $statusText .= $charset.$CRLF;
    
    $table = SQL_TABLE_USERS;
    $id = '8906489';
    $command = "SELECT ".SQL_USER_NAME.",".SQL_TOTAL_CREDIT.",".SQL_RAC.",".SQL_RANK_CREDIT.",".SQL_RANK_RAC." FROM ".SQL_TABLE_LIST_SNL_TEAM." WHERE ".SQL_ID."='$id' LIMIT 1";
    $result = $sql->query($command);
    if ($result === FALSE)
    {
        $sqlStatusTest = "Failed: SELECT";
        LoggingAddError($sqlStatusTest, true);
        $statusText .= "ERROR: ".$sqlStatusTest.$CRLF;
    }
    else
    {
        $sqlStatusTest = "Database access read: OK";
        LoggingAdd($sqlStatusTest, true);
        $statusText .= $sqlStatusTest.$CRLF;
    }
}
mysqli_close($sql);

//$array = GetPassWordSqlRead();
//$sql_password_r = $array["sql_password_r"];
//echo $sql_password_r;

$statusText .= "Diagnostics: Total diskspace: ".$ds. " Free diskspace: ".$df. " ".$server.$CRLF;

if (SendEmail("fred@efmer.com", $statusText))
{
    $emailStatusText = 'Success: Email send';
}
else
{
    $emailStatusText = 'FAILED to send email';               
}


LoggingAdd($emailStatusText, TRUE);
$statusText .= $emailStatusText.$CRLF;

LoggingClose();	

$statusText .= 'END of diagnostics'.$CRLF;

//$echoText = CrLfToBr($statusText);

echo $statusText;

function ConvertSize($size){
  $base = log($size) / log(1024);
  $suffix = array("", "KB", "MB", "GB", "TB");
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

function CrLfToBr($string) { 
    $string = str_replace('\r\n', "<br>", $string); 
    return $string; 
} 


?>
