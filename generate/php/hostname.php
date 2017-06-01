<?php

function SendEmail($email, $message)
{
	$subject = 'Seti statistics';
	$headers = 'From: seti@efmer.eu' . "\r\n" . 'Reply-To: seti@efmer.eu' . "\r\n" .   'X-Mailer: PHP/' . phpversion();
 
	if(mail($email, $subject, $message, $headers))
	{
		return true;
	}

	return false;
}

echo gethostname()."<br>";

date_default_timezone_set("UTC"); 

$time = new DateTime("now"); 	
echo $time->format("_Y-m-d-H-i");

SendEmail("fred@efmer.com","database error");

echo "ready";


?>