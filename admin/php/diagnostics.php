<?php

require_once '../../generate/php/passwords/pass.php';
require '../../generate/php/common.php';

echo 'diagnostics<br>';

echo 'Hostname: '.gethostname().'<br>';
echo 'Logging folder: '.LoggingFolder('diagnostics').'<br>';
LoggingOpen('diagnostics');

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

$emailText = "Diagnostics: Total diskspace: ".$ds. " Free diskspace: ".$df. " ".$server;
echo $emailText.'<br>';

if (SendEmail("fred@efmer.com","diagnostics test mail","a diagnostics test" ))
{
    echo 'Success: Email send<br>';
}
else
{
    echo 'FAILED to send email<br>';               
}
   
LoggingClose();	

echo 'END of diagnostics<br>';

function ConvertSize($size){
  $base = log($size) / log(1024);
  $suffix = array("", "KB", "MB", "GB", "TB");
  $f_base = floor($base);
  return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

?>
