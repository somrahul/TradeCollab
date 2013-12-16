<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	if(isset($_POST["teamName"])&&isset($_POST["memberCount"])&&isset($_POST["budget"])&&isset($_POST["contract"])&&isset($_POST["ideology"])&&isset($_POST["duration"])) {
		//var_dump($_POST);
		$teamName = $_POST["teamName"];
		//print $teamName;
		$userEmail = $_SESSION['userEmail'];
		
		$memCount = $_POST["memberCount"];
		for($i=1; $i<=$memCount; $i++){
			$index = 'p_new_'.$i;
			//print $index;
			${'collabEmail'.$i} = $_POST[$index];
			//print ${'collabEmail'.$i};
		}

		$budget = $_POST["budget"];
		//print $budget;

		$ideology = $_POST["ideology"];

		$contract = $_POST["contract"];

		$duration = $_POST["duration"];

		$market = "";

		if(isset($_POST['check1'])) {
			$market = $market."BSE;";
		}
		if(isset($_POST['check2'])) {
			$market = $market."NASDAQ;";
		}
		if(isset($_POST['check3'])) {
			$market = $market."Shanghai Stock Exchange;";
		}
		//print $market;

		$p = $CFG->dbprefix;

		//saving the team table into the database
		$sql = "INSERT INTO {$p}team (team_name, budget, markets, budget_current, team_since, ideology, contract, contract_duration) VALUES (:TN, :B, :M, :BC, NOW(), :ID, :CON, :DUR)";
		$stmt = $db->prepare($sql);
		$stmt->execute(array(
			':TN' => $teamName,
			':B' => $budget,
			':M' => $market,
			':BC' => $budget,
			':ID' => $ideology,
			':CON' => $contract,
			':DUR' => $duration
			));
		// print "Team created";

		//retrieve the team id and save it corresponding to the initiators and the collaborators
		$sql = "SELECT team_id from {$p}team where team_name = '{$teamName}'";
		$stmt = $db->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!empty($row)){
			$team_id = $row['team_id'];
			if(isset($_SESSION['userEmail'])) {
				$userEmail = $_SESSION['userEmail'];
				$sql = "UPDATE {$p}members SET team_id = {$team_id} WHERE member_email = '{$userEmail}'";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
			//set the team ids and create the member rows for the collaborators
			for($i=1; $i<=$memCount; $i++){
				$sql = "INSERT into {$p}members (member_email, team_id) VALUES (:email, :id)";
				$stmt = $db->prepare($sql);
            	$stmt->execute(array(
                	':email' => ${'collabEmail'.$i},
                	':id' => $team_id));
			}	
		} 

		//send the invide to the team members
		for($i=1; $i<=$memCount; $i++){
			$emailAddress = ${'collabEmail'.$i};
			//print $emailAddress;
			//print $userEmail;
			$link = "www.cotr.someshrahul.com";
			$to = $emailAddress;
			$subject = "You've been chosen";
			$message = "Welcome to Collaborative trading platform. Please follow the link: ".$link;
			$from = $userEmail;
			$headers = "From:" . $from;
			mail($to,$subject,$message,$headers);
			
		}


		$_SESSION['success'] = 'Your team members have been sent an invitation email';
		header ('Location: home.php');

	} else {
		//all the entries in the table were not filled
		$_SESSION['error'] = 'Please fill the fields. They all are Required';
	}
	

}



?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Flat UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="css/flat-ui.css" rel="stylesheet">
    <link href="css/top.css" rel="stylesheet">

    <link rel="shortcut icon" href="images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
<div class="container">
  <div class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse-01"></button>
        <a href="#" class="navbar-brand">CoTr</a>
          <div class="navbar-search pull-right">
              <div class="input-group input-group-sm">
                  <a href="logout.php"><button class="btn btn-embossed btn-primary">
                    Logout
                  </button></a>
             
              </div>
          </div>
      </div>
    </div>
  </div>
  <h3 id="heading">GET STARTED</h3><hr>
    <div class="container">
      <div class="col-md-3">
      </div>
      <div class="col-md-9">
      	<?php
			if ( isset($_SESSION['error']) ) {
			    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
			    unset($_SESSION['error']);
			}
			if ( isset($_SESSION['success']) ) {
			    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
			    unset($_SESSION['success']);
			}
		?>
        <form name="getstarted" id="getstarted" method="POST">
			    <div class="row row-form">
				<div class="col-md-4">TEAM NAME</div>
				<div class="col-md-5"><input type="text" name="teamName" value="" placeholder="Team RGV"  ></div>
				</div>
				<div class="row row-form">
			  
			<div class="col-md-4">MEMBERS</div>
			<div class="col-md-5"><div id="addinput">
			<span id="addmbr">
			<input type="email" id="p_new" name="p_new_1" value="" placeholder="xyz@abc.com" />&nbsp;<a href="#" id="addNew">Add</a>
			</span>
			</div></div>
			  </div>
			  <div class="row row-form">
			<div class="col-md-4">BUDGET</div>
			<div class="col-md-5"><input type="mumber" name="budget" placeholder="$"></div>
			  </div>  
			  <div class="row row-form">
			<div class="col-md-4">MARKET</div>
			<div class="col-md-5"><label class="checkbox" for="checkbox1">
			  <input type="checkbox" value="BSE" name="check1" id="checkbox1" data-toggle="checkbox">
			  BSE 
			</label>
			<label class="checkbox" for="checkbox2">
			  <input type="checkbox" value="NASDAQ" name="check2" id="checkbox2" data-toggle="checkbox">
			  NASDAQ
			</label>
			<label class="checkbox" for="checkbox2">
			  <input type="checkbox" value="Shanghai Stock Exchange" name="check3" id="checkbox2" data-toggle="checkbox">
			  Shanghai Stock Exchange
			</label></div>
			</div>
			<div class="row row-form">
			<div class="col-md-4" style="margin-bottom: 20px;">TEAM IDEOLOGY</div>
			<div class="col-md-5">
				<textarea row="8" cols="50" class="form" id="ideology" placeholder="Please enter your team's ideology" name="ideology"></textarea>
			</div>
			</div>
			<div class="row row-form">
			<div class="col-md-4">TEAM CONTRACT</div>
			<div class="col-md-5">
				<textarea row="8" cols="50" class="form" id="contract" placeholder="Please enter your team's contract" name="contract"></textarea>
			</div>
			</div>

			<div class="row row-form">
				<div class="col-md-4">CONTRACT DURATION</div>
				<div class="col-md-5"><input type="text" name="duration" value="" placeholder="number of years"  ></div>
			</div>

			  <div class="row row-form">
			<div class="col-md-9" id="submit"><button class="btn btn-default btn-wide">Clear</button>&nbsp;<button class="btn btn-primary btn-wide">Submit</button></div>
			  </div> 
			  <input type="hidden" name="memberCount" id="mcount" value="">


		</form>

</div>
</div>
    <!-- /.container -->


    <!-- Load JS here for greater good =============================-->
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/flatui-checkbox.js"></script>
    <script src="js/flatui-radio.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
    <script type="text/javascript">
$(function() {
var addDiv = $('#addinput');
var i = $('#addinput span').size() + 1;

$('#addNew').live('click', function() {
$('<span id="addmbr"><input type="email" id="p_new" name="p_new_' + i +'" value="" placeholder="xyz@abc.com" />&nbsp;<a href="#" id="remNew">Remove</a> </span>').appendTo(addDiv);
i++;
return false;
});

$('#remNew').live('click', function() {
if( i > 2 ) {
$(this).parents('span').remove();
i--;
}
return false;
});

$('#submit').click(function(){
	$('#mcount').val(i-1);
	$('#getstarted').submit();
});

});

</script>
  </body>
</html>