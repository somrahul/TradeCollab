<?php
session_start();
$to = "somrahul@umich.edu";
$subject = "Test mail";
$message = "Hello! This is a simple email message.";
$from = "somesh@xyz.com";
$headers = "From:" . $from;
mail($to,$subject,$message,$headers);
echo "Mail Sent.";
echo "the user email is ".$_SESSION['userEmail'];
?>