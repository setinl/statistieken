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
    
    $from = "statsseti@efmer.eu";

    $array = GetPassWordEmail();
    
    $host = $array["email_host"];
    $port = $array["email_port"];
    $username = $array["email_username"];
    $password = $array["email_password"];
            
    $subject = "Seti statistics";
    $body = "$message";
    $context = "text/html;charset=utf-8";
    
    $headers = array ('From' => $from, 'To' => $to,'Subject' => $subject, 'Content-Type' => $context, 'date' => date('r', time()));
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
