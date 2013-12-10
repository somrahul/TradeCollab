<?php
require_once 'config.php';
require_once 'db.php';

session_start();
header('Content-type: application/json');

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	//print $_SESSION['userEmail'];
	//print_r($_POST);
	//updating the tradeCollab_comments table 
	$p = $CFG->dbprefix;
	$sql = "INSERT INTO {$p}comments (deal_id, member_id, chat, chat_created) 
			VALUES ('{$_POST['deal_id']}', '{$_POST['member_id']}', '{$_POST['chatMessage']}', NOW())";
	$stmt = $db->query($sql);

	//retrieving the chats and sending them back
	$sql = "SELECT chat, member_name
			FROM {$p}comments JOIN {$p}members 
			ON {$p}comments.member_id = {$p}members.member_id 
			WHERE deal_id = {$_POST['deal_id']} ORDER BY {$p}comments.chat_created DESC";

	$stmt2 = $db->query($sql);

	$messages = array(); 

	while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC) ) { 
		$messages[] = $row; 

	} 

	//print_r($messages);

	echo(json_encode($messages));
}

?>