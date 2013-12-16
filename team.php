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
	$showEdit = 0;

  	if(isset($_GET['id'])){
  		
  		//member is seeing someone else's profile
  		//$showEdit = 0;
  		$team_id = $_GET['id'];
  		//retrieve the info of this member
  		$sql = "SELECT * FROM {$p}team WHERE team_id = {$team_id}";
  		$stmt = $db->query($sql);
  		$row = $stmt->fetch(PDO::FETCH_ASSOC);

  	
  	} else {
  		//that means that the member is viewing his profile
  		//show the edit
  		$userEmail = $_SESSION['userEmail'];
  		$showEdit = 1;

  		//getting the deals that require the user action

  		$sql = "SELECT team_id FROM {$p}members WHERE member_email = '{$userEmail}'";
  		$stmt2 = $db->query($sql);
  		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
	  	$team_id = $row2['team_id'];

  		$sql = "SELECT * FROM {$p}team WHERE team_id = {$team_id}";
      $stmt = $db->query($sql);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <a href="home.php" class="navbar-brand">CoTr</a>
        <div class="nav-collapse collapse in" id="nav-collapse-01">
                  <ul class="nav">
                    <li class=""><a href="initiate.php">Initiate Deal</a></li>
                    <li class=""><a href="activeDeals.php">Active Deal</a></li>
                    <li class=""><a href="budget.php">Budget</a></li>
                    <li class=""><a href="history.php">History</a></li>
                  </ul> <!-- /nav -->
                  <ul class="nav" style="padding-left: 180px;">
                  	<li class=""><a href="explore.php">Explore</a></li>
                    <?php if($showEdit == 1) { ?>
                    <li class="active"><a href="team.php">My Team</a></li>
                    <?php } else {?>
                    <li class=""><a href="team.php">My Team</a></li>
                    <?php } ?>
                    <li class=""><a href="profile.php">My Profile</a></li>
                  </ul> <!-- /nav -->
          
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
</div>
<br>
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
  <h4 id="topname"><?php echo($row['team_name']);?><span style="font-size: 16px"></span>
    <p style="display: inline; float: right;"><?php if($showEdit ==2) {?><a href="teamEdit.php"><span id="pencil" class="glyphicon glyphicon-pencil"></span></a><?php }?></p>
    <p style="display: inline; float: right;"><?php if($showEdit ==0) {?><a href="#"><span id="request">Request for Invitation</span></a><?php }?></p></h4>
    
  <div id="colpad" class="col-md-2"><img src="http://placehold.it/167x167.jpg" style="border:5px solid #cccccc"><br><br>
  <ul class="nav nav-list">

  			<li class="nav-header">Team Since</li>
  			<span><?php echo($row['team_since']) ?></span>

        <li class="divider"></li>

        <li class="nav-header">MEMBERS</li>
        <?php 
        //getting the members based on the team id
        $sql = "SELECT member_name, member_id FROM {$p}members WHERE team_id = {$team_id}";
        $stmt4 = $db->query($sql);
       

        while ( $row4 = $stmt4->fetch(PDO::FETCH_ASSOC) ){

        ?>
        <li><a href="profile.php<?php echo("?id=".$row4['member_id'])?>"><?php echo($row4['member_name']) ?></a></li>

        <?php }?>

  			<li class="divider"></li>

            <li class="nav-header">Markets</li>
        <?php 
            	
          
            $markets = $row['markets'];

            $market = explode(';', $markets);
            //calculating the length of the array 
            $cnt = count($market);
            for($i=0; $i<$cnt-1; $i++){
        ?>
        <li><?php echo($market[$i])?></li>
        <?php 
            }
        ?>
          
            <li class="divider"></li>
            <li class="nav-header">Sectors</li>
            <span>Agriculture</span><br>
            <span>Retail</span><br>
            <span>Health</span><br>
            <span>Service</span>
           
          </ul>
    </div>
    <div class="col-md-10"> <h6>Ideology</h6>
    <?php if($row['ideology'] == null){$row['ideology'] = 'Sorry no ideology added by the team!!';}?>
      <p class="detail"><?php echo($row['ideology']); ?></h6>
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
      <h6>Deals Stats</h6>
      <p>Total Deals: <?php echo($numDeals); ?></p>
      <p>Success Rate:</p>
      <div class="detail"><div class="progress">
  <div class="progress-bar progress-bar-primary" style="width: <?php echo($success_percent).'%'; ?>;"><?php echo($success_percent)."%"; ?></div>
  </div>
</div>

<h6>Team Contract</h6>
<div class="detail">
<p>1 Year</p>
</div>

<h6>Recommendations</h6>
<?php 
//getting the recommendations from the recommendation table
$sql = "SELECT recommendation, member_name
		FROM {$p}team_recommendations 
		JOIN {$p}members ON {$p}team_recommendations.recommender_id = {$p}members.member_id
		WHERE {$p}team_recommendations.team_id = {$row['team_id']}";
$stmt7 = $db->query($sql);
while ($row7 = $stmt7->fetch(PDO::FETCH_ASSOC)){
?>

 <div class="detail"><div class="well"><p><?php echo($row7['member_name']) ?><blockquote><?php echo($row7['recommendation']) ?>
 </blockquote></p></div>

<?php
}
	
?>

<form name="recommendForm" action="teamRecommendationInsert.php" method="post">
<div>
<textarea row="8" class="form-control" id="exampleInputPassword1" placeholder="Your Recommendation" name="reco"></textarea>
<p style="margin-top: 5px;" align="right"><button class="btn btn-primary btn-wide" id="recoButton">Recommend</button></p>
<input type="hidden" value="<?php echo($row['team_id']) ?>" name="team_id">
</div>
</form

     
      

  
</div>    <!-- /.container -->


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
    // jQuery UI Datepicker JS init
var datepickerSelector = '#datepicker-01';
$(datepickerSelector).datepicker({
  showOtherMonths: true,
  selectOtherMonths: true,
  dateFormat: "d MM, yy",
  yearRange: '-1:+1'
}).prev('.btn').on('click', function (e) {
  e && e.preventDefault();
  $(datepickerSelector).focus();
});

// Now let's align datepicker with the prepend button
$(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('.btn').outerWidth()});

$(function() {
      $(".tip").tooltip();
    });

</script>
  </body>
</html>
