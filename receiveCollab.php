<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	//print_r($_GET)
	$p = $CFG->dbprefix;
	
	//that means that the member is viewing his profile
	
	$userEmail = $_SESSION['userEmail'];
	$name = $_SESSION['loggedIn'];

	//getting the deals that require the user action

	$sql = "SELECT team_id FROM {$p}members WHERE member_email = '{$userEmail}'";
	$stmt2 = $db->query($sql);
	$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	$team_id = $row2['team_id'];

	$sql = "SELECT * FROM {$p}team WHERE team_id = {$team_id}";
  $stmt = $db->query($sql);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  	
}
?>
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
              <a href="logout.php"><div class="input-group input-group-sm">
                  <button class="btn btn-embossed btn-primary">
                    Logout
                  </button></a>
             
              </div>
          </div>
      </div>
    </div>
  </div><div class="container">
  <h3 id="heading">Welcome <?php echo($name); ?></h3><hr>
  <h5 class="detail"><?php echo($row['team_name']);?></h5>
  <div class="detail"><h6>Team Ideology</h6><p> <?php if($row['ideology'] == null){$row['ideology'] = 'Sorry no ideology added by the team!!';}?>
  <?php echo($row['ideology']); ?></p></div>
  
  <div class="detail"><h6>Team Budget</h6><p><?php echo("$".$row['budget']); ?></p></div>

  <h6>Deal Acceptance Ratio</h6>
  <?php 
          //to get the deal count querying the deal table
          $sql = "SELECT COUNT( * ) AS cnt
          FROM {$p}deal
          WHERE team_id = {$row['team_id']}";
      $stmt5 = $db->query($sql);
      $row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
      $numDeals = "";
      if(empty($row5)){
        $numDeals = "No deals";
      } else {
        $numDeals = "".$row5['cnt']." Deals";
      }

      //calculating the successful deals
      $sql = "SELECT COUNT( * ) AS cnt
          FROM {$p}deals_completed
          WHERE team_id = {$row['team_id']}";
      $stmt6 = $db->query($sql);
      $row6 = $stmt6->fetch(PDO::FETCH_ASSOC);

      $success_percent = ($row6['cnt']/$row5['cnt']) * 100;

        ?>
      <p>Total Deals: <?php echo($numDeals); ?></p>
      <div class="detail"><div class="progress">
  <div class="progress-bar progress-bar-primary" style="width: <?php echo($success_percent).'%'; ?>;"><?php echo($success_percent)."%"; ?></div>
  </div>
</div>

<div class="detail"><h6>Contract</h6><div class="well"><p class="lead">Term: <?php echo($row['contract_duration']." years"); ?></p><p><?php echo($row['contract']);?></p></div></div>


<a href="acceptOffer.php"><button id="rightbutton" class="btn btn-primary btn-wide">Accept</button></a>
<a href="rejectOffer.php"><button id="rightbutton1" class="btn btn-default btn-wide">Decline</button></a>

</div></div>
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
});

</script>
  </body>
</html>
