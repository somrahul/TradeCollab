<?php
require_once 'config.php';
require_once 'db.php';

session_start();

//print_r($_POST);

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	//print $_SESSION['userEmail'];
	//print_r($_POST);
	//updating the status table for the status
	$userEmail = $_SESSION['userEmail'];
	$p = $CFG->dbprefix;
	//getitng the member id of the logged in user
	$sql = "SELECT member_id FROM {$p}members WHERE member_email = '{$userEmail}'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$member_id = $row['member_id'];
	
	//saving the recommendation for the user
	$sql = "INSERT INTO {$p}recommendations (member_id, recommender_id, recommendation) VALUES ('{$_POST['member_id']}', '{$member_id}', '{$_POST['reco']}')";
	$stmt = $db->query($sql);

	$_SESSION['success'] = "Thanks for giving the recommendation!!";
	header('Location: profile.php'.'?id='.$_POST['member_id']);
}

?>