<?php

if (IsDebugServer())
{
    
}
else
{
    require_once "Mail.php";       
}
 
 function SendEmail($to, $message)
 { 
    if (IsDebugServer())
    {
        return true;
    } 
    $from = "stats@seti.nl";

    $array = GetPassWordEmail();
    
    $host = $array["email_host"];
    $port = $array["email_port"];
    $username = $array["email_username"];
    $password = $array["email_password"];
            
    $subject = "Seti statistics";
    $body = "$message";
    $context = "text/plain;charset=utf-8";
    
    $headers = array ('From' => $from, 'To' => $to,'Subject' => $subject, 'Content-Type' => $context);
    $smtp = Mail::factory('smtp',
    array ('host' => $host,
        'port' => $port,
        'auth' => true,
        'username' => $username,
        'password' => $password));

    $mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
        return false;
    } else {
        return true;
    }
 }

 ?>
