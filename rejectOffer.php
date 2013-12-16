<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {

	//member has reh=jected the offer
    //delete the details from the table

    $p = $CFG->dbprefix;

	$firstName = $_SESSION['loggedIn'];
    $userEmail = $_SESSION['userEmail'];
    $lastName = $_SESSION['lname'];

	$sql = "DELETE FROM {$p}members WHERE member_email = :email";
            //echo $sql;
            $stmt = $db->prepare($sql);
            //$stmt->execute();
            $stmt->execute(array(
                ':email' => $userEmail));
            //take to the home
            //$_SESSION['userEmail'] = 'You Collab Bitch';

	header('Location: logout.php');
}

?>