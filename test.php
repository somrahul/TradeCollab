<?php
session_start();

if(isset($_SESSION['loggedIn'])) {
	$to = "somrahul@umich.edu";
	$subject = "Test mail";
	$message = "Hello! This is a simple email message.";
	$from = "somesh@xyz.com";
	$headers = "From:" . $from;
	mail($to,$subject,$message,$headers);
	echo "Mail Sent.";
	echo "the user email is ".$_SESSION['userEmail'];
} else {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
}


?>