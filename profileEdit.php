<?php
require_once 'config.php';
require_once 'db.php';

session_start();

//print_r($_POST);

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
}  else if(isset($_POST['skills']) && isset($_POST['bio']) ) {

  //updating the db to update the values
  $p = $CFG->dbprefix;

  $sql = "UPDATE {$p}members SET member_bio = '{$_POST['bio']}', member_skills = '{$_POST['skills']}' WHERE member_id = {$_POST['member_id']} ";
  $stmt = $db->query($sql);

  header('Location: profile.php');

} else {
	//print_r($_GET)
	$p = $CFG->dbprefix;

  $userEmail = $_SESSION['userEmail'];
  $showEdit = 1;

  //getting the deals that require the user action

  $sql = "SELECT * FROM {$p}members WHERE member_email = '{$userEmail}'";
  $stmt = $db->query($sql);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $member_id = $row['member_id'];
  $member_name = $row['member_name'];
  $team_id = $row['team_id'];

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
                    <li class=""><a href="team.php">My Team</a></li>
                    <li class="active"><a href="profile.php<?php echo('?id='.$member_id)?>">My Profile</a></li>
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

  <h4 id="topname"><?php echo($row['member_name']." ".$row['member_last_name']." ")?><span style="font-size: 16px"><a href="email:<?php echo("email: ".$row['member_email'])?>"><?php echo("email: ".$row['member_email'])?></a></span><p style="display: inline; float: right;"><?php if($showEdit ==1) {?><span id="save" class="glyphicon glyphicon-ok"></span><?php }?></p></h4>
  <form id="updateProfile" method="POST">  
  <div id="colpad" class="col-md-2"><img src="http://placehold.it/167x167.jpg" style="border:5px solid #cccccc"><br><br>
  <ul class="nav nav-list">

  			<li class="nav-header">Member Since</li>
  				<span><?php echo($row['member_since']) ?></span>

  			<li class="divider"></li>

            <li class="nav-header">SKILLS</li>
            <input type="text" class="form-control" id="" placeholder="Skill 1" name="skills" value="<?php echo($row['member_skills'])?>">
            <span>Please input the skills in comma separated form</span>
            <li class="divider"></li>
            <li class="nav-header">Badges Recieved</li>
            <div class="row"><div class="col-md-4"><figure class="tip" data-toggle="tooltip" data-tooltip-style="light" data-placement="right" title="Badge for 1st Deal"><img id="badges" src="images/icons/medal.svg" height="60%" width="60%"><figcaption id="caption">Badge 1</figcaption>
</figure>
</div>
              <div class="col-md-4"><figure class="tip" data-toggle="tooltip" data-tooltip-style="light" data-placement="right" title="Badge for 1st Recommendation"><img id="badges" src="images/icons/rocket.svg" height="60%" width="60%"><figcaption id="caption">Badge 1</figcaption>
</figure></div>
              <div class="col-md-4"><figure class="tip" data-toggle="tooltip" data-tooltip-style="light" data-placement="right" title="Badge for 100 Deals"><img id="badges" src="images/icons/bulb.svg" height="60%" width="60%"><figcaption id="caption">Badge 1</figcaption>
</figure></div>
            </div>
            <div class="row">
            <div class="col-md-4"><figure class="tip" data-toggle="tooltip" data-tooltip-style="light" data-placement="right" title="Badge for 1st Successful Deal"><img id="badges" src="images/icons/money.svg" height="60%" width="60%"><figcaption id="caption">Badge 1</figcaption>
</figure></div>
              <div class="col-md-4"><figure class="tip" data-toggle="tooltip" data-tooltip-style="light" data-placement="right" title="Badge for 10 Successful Deals"><img id="badges" src="images/icons/search.svg" height="60%" width="60%"><figcaption id="caption">Badge 1</figcaption>
</figure></div>
              <div class="col-md-4"></div></div>
          </ul>
    </div>
    <div class="col-md-10"> <h6>Bio</h6>
     <p class="detail"><textarea rows="8" class="form-control" id="bio" placeholder="My Bio" name="bio"><?php echo($row['member_bio'])?></textarea></p>
      	<?php 
      		//to get the deal count querying the deal table
      		$sql = "SELECT COUNT( * ) AS cnt
					FROM {$p}deal
					WHERE member_id = {$row['member_id']}";
			$stmt2 = $db->query($sql);
			$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			$numDeals = "";
			if(empty($row2)){
				$numDeals = "No deals by this user";
			} else {
				$numDeals = "User initiated ".$row2['cnt']." Deals";
			}

			//calculating the successful deals
			$sql = "SELECT COUNT( * ) AS cnt
					FROM {$p}deals_completed
					WHERE member_id = {$row['member_id']}";
			$stmt3 = $db->query($sql);
			$row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

			$success_percent = ($row3['cnt']/$row2['cnt']) * 100;

      	?>
      <h6>Deals Stats</h6>
      <p>Initiated Deals: <?php echo($numDeals); ?></p>
      <p>Success Rate:</p>
      <div class="detail"><div class="progress">
  <div class="progress-bar progress-bar-primary" style="width: <?php echo($success_percent).'%'; ?>;"><?php echo($success_percent)."%"; ?></div>
  </div>
</div>

<h6>Recommendations</h6>
<?php 
//getting the recommendations from the recommendation table
$sql = "SELECT recommendation, member_name
		FROM {$p}recommendations 
		JOIN {$p}members ON {$p}recommendations.recommender_id = {$p}members.member_id
		WHERE {$p}recommendations.member_id = {$row['member_id']}";
$stmt4 = $db->query($sql);
while ($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)){
?>

 <div class="detail"><div class="well"><p><?php echo($row4['member_name']) ?><blockquote><?php echo($row4['recommendation']) ?>
 </blockquote></p></div>
 <p style="margin-top: 5px;" align="right"><button class="btn btn-primary btn-wide">Delete Recommendation</button></p>
<?php
}
	
?>

<?php if($showEdit == 0) {?>
<div>
<input type="text" class="form-control" id="exampleInputPassword1" placeholder="Your Recommendation">
<p style="margin-top: 5px;" align="right"><button class="btn btn-primary btn-wide">Recommend</button></p>
</div>
<?php }?>
     
<input type="hidden" value="<?php echo($row['member_id']);?>" name="member_id">    

</form>  
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

function getEditPage(){
	alert(1);
}

$("#save").click(function(){
  $("#updateProfile").submit();
});

</script>
  </body>
</html>
