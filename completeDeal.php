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
	//print_r($_POST);
	//check the nature of the deal. //if buy then check the funds //
	$p = $CFG->dbprefix;

	//checking the deal nature
	if($_POST['deal_nature'] == 'BUY'){
		//check the budget
		//if ok do the transaction
		//print "Inside BUY";
		//getting the budget from the table
		$sql = "SELECT budget_current, {$p}team.team_id
				FROM {$p}team
				LEFT JOIN {$p}deal ON {$p}deal.team_id = {$p}team.team_id
				WHERE deal_id =  '{$_POST['deal_id']}'";
		$stmt = $db->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$budget = $row['budget_current'];
		//$budget = 23000.95;
		if($budget > $_POST['deal_amt']){
			//print "you are good to go";
			//substract the funds from the budget
			try { 
				$db->beginTransaction(); 
				$budget -= $_POST['deal_amt'];
				$sql = "UPDATE {$p}team SET budget_current = {$budget} WHERE team_id = '{$row['team_id']}'";
				$stmt2 = $db->query($sql);
				//shift the row to deals_completed
				$sql = "INSERT INTO {$p}deals_completed
						SELECT * FROM {$p}deal WHERE deal_id = '{$_POST['deal_id']}'";
				$stmt3 = $db->query($sql);
				//delete the row from the deal table	
				$sql = "DELETE from {$p}deal WHERE deal_id = '{$_POST['deal_id']}'";
				$stmt4 = $db->query($sql);
				$db->commit(); 

			} catch(PDOExecption $e) { 
			        $dbh->rollback(); 
			        print "Error!: " . $e->getMessage() . "</br>"; 
			}

			$_SESSION['success'] = "Authorization Complete!!";
			header('Location: waitingAuthorization.php');

		} else {
			//print "Sorry Insufficient Funds!!";
			$_SESSION['error'] = "Sorry Insufficient Funds!! (ab lad maro saalo!!)";
			header('Location: waitingAuthorization.php');
		}
		

	} else {
		//it means SELL
		//print "Inside SELL";
		$sql = "SELECT budget_current, {$p}team.team_id
				FROM {$p}team
				LEFT JOIN {$p}deal ON {$p}deal.team_id = {$p}team.team_id
				WHERE deal_id =  '{$_POST['deal_id']}'";
		$stmt = $db->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$budget = $row['budget_current'];
		//$budget = 23000.95;
	
		//print "you are good to go";
		//substract the funds from the budget
		try { 
			$db->beginTransaction(); 
			$budget += $_POST['deal_amt'];
			$sql = "UPDATE {$p}team SET budget_current = {$budget} WHERE team_id = '{$row['team_id']}'";
			$stmt2 = $db->query($sql);
			//shift the row to deals_completed
			$sql = "INSERT INTO {$p}deals_completed
					SELECT * FROM {$p}deal WHERE deal_id = '{$_POST['deal_id']}'";
			$stmt3 = $db->query($sql);
			//delete the row from the deal table	
			$sql = "DELETE from {$p}deal WHERE deal_id = '{$_POST['deal_id']}'";
			$stmt4 = $db->query($sql);
			$db->commit(); 

		} catch(PDOExecption $e) { 
		        $dbh->rollback(); 
		        print "Error!: " . $e->getMessage() . "</br>"; 
		}

		$_SESSION['success'] = "Authorization Complete!!";
		header('Location: waitingAuthorization.php');
	}




	try { 
		$db->beginTransaction(); 


	//$sql = "UPDATE {$p}deal_status SET member_status = '{$_POST['response']}'  WHERE member_id = '{$_POST['member_id']}' AND deal_id = '{$_POST['deal_id']}'";
	//$stmt = $db->query($sql);
	$db->commit(); 

	} catch(PDOExecption $e) { 
	        $dbh->rollback(); 
	        print "Error!: " . $e->getMessage() . "</br>"; 
	} 
	//$_SESSION['success'] = "Response Recorded";
	//header('Location: home.php');

}

?>