<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	//print $_SESSION['userEmail'];
	print_r($_POST);
	//updating the status table for the status
	$p = $CFG->dbprefix;
	//$sql = "UPDATE {$p}deal_status SET member_status = '{$_POST['response']}'  WHERE member_id = '{$_POST['member_id']}' AND deal_id = '{$_POST['deal_id']}'";
	//$stmt = $db->query($sql);

	//$_SESSION['success'] = "Response Recorded";
	//header('Location: home.php');
}

?>