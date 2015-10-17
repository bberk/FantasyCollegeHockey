<?php 

require_once "./pear/Mail.php";  

function sendEmail($recipient,$subject,$body) {
	$from = "Fantasy College Hockey <notifications@fantasycollegehockey.com>"; 
	$to = $recipient; //"Bob Hatcher <bob.hatcher@gmail.com>"; 
	//$subject = "Hi!"; 
	//$body = "Hi,\n\nHow are you?";  
	$host = "ssl://kensington.lunarbreeze.com"; 
	$port = "465"; 
	$username = "notifications@fantasycollegehockey.com"; 
	$password = "Q#g1femXL+UN";  
	$headers = array ('From' => $from,   'To' => $to,   'Subject' => $subject, 'Bcc' => 'fantasycollegehockey@gmail.com'); 
	$smtp = Mail::factory('smtp',   array ('host' => $host,     'port' => $port,     'auth' => true,     'username' => $username,     'password' => $password));  
	!PEAR::isError($smtp) or die($smtp->getMessage()); 

	$mail = $smtp->send($to, $headers, $body);  
	if (PEAR::isError($mail))
	{   
		echo("<p>There has been an error sending e-mail: " . $mail->getMessage() . "</p>");  
	} 
	else 
	{   
		return("<p>Message successfully sent!</p>");  
	}
} ?>