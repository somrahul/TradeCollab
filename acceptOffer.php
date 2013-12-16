<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	$p = $CFG->dbprefix;
	//member has accepted the offer
	$firstName = $_SESSION['loggedIn'];
    $userEmail = $_SESSION['userEmail'];
    $lastName = $_SESSION['lname'];

	$sql = "UPDATE {$p}members SET member_name = :name, member_last_name = :lname, member_since = NOW() WHERE member_email = :email";
            //echo $sql;
            $stmt = $db->prepare($sql);
            //$stmt->execute();
            $stmt->execute(array(
                ':name' => $firstName,
                ':lname' => $lastName,
                ':email' => $userEmail));
            //take to the home
            //$_SESSION['userEmail'] = 'You Collab Bitch';

	$_SESSION['success'] = "Welcome User";
	header('Location: home.php');
}

?>