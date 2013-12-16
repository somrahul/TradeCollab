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
	//getting all the member info

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
        <a href="home.php" class="navbar-brand">CoTr</a>
        <div class="nav-collapse collapse in" id="nav-collapse-01">
                  <ul class="nav">
                    <li class=""><a href="initiate.php">Initiate Deal</a></li>
                    <li class=""><a href="activeDeals.php">Active Deal</a></li>
                    <li class=""><a href="budget.php">Budget</a></li>
                    <li class=""><a href="history.php">History</a></li>
                  </ul> <!-- /nav -->
                    <ul class="nav" style="padding-left: 180px;">
                    <li class="active"><a href="explore.php">Explore</a></li>
                    <li class=""><a href="team.php">My Team</a></li>
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
  

    <div class="container">
     <div class="row">
      <div class="col-md-6">
        <div id="broker" class="well">
          <H6>MEMBERS</H6><div class="input-group">                               
                  <input type="text" class="form-control" placeholder="Search" id="search-query-2">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><span class="fui-search"></span></button>
                  </span>
                </div>
         <?php 
         //getting all the member info
         $p = $CFG->dbprefix;
         $sql = "SELECT * FROM {$p}members";
         $stmt = $db->query($sql);
		 while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){

		 	$teamID = $row['team_id'];

		 	if($row['member_name'] != null) {

         ?>
        <div class="detail"><p class="lead"> <a href="profile.php?id=<?php echo($row['member_id']);?>"><?php echo($row['member_name']." ".$row['member_last_name']." ")?></a></p>
          <ul class="nav nav-list">

          	<li class="nav-header">SKILLS</li>
          	<?php 
            	if($row['member_skills'] == null){

            		echo("Sorry no skills added by the user");

            	} else {?>
          	<li><h6 style="margin-bottom: 20px;"> 
          	<?php 
            		$skills = $row['member_skills'];

					$skill = explode(',', $skills);
					//calculating the length of the array 
					$cnt = count($skill);
					for($i=0; $i<$cnt-1; $i++){
            ?>
            <span class="label label-success"><?php echo($skill[$i]); ?></span>&nbsp;&nbsp;
            <?php 
        		}}
            ?>
            </li>
            
            
            <li class="nav-header">Badges Recieved</li>
            <div class="row">
            	<div class="col-md-2"><img id="badges" src="images/icons/medal.svg" height="5%" width="40%"></div>
	            <div class="col-md-2"><img id="badges" src="images/icons/rocket.svg" height="5%" width="40%"></div>
	           	<div class="col-md-2"><img id="badges" src="images/icons/bulb.svg" height="5%" width="40%"></div>
	            <div class="col-md-2"><img id="badges" src="images/icons/money.svg" height="5%" width="40%"></div>
	        </div>
	        
	        <button id="" class="btn btn-primary btn-wide">Invite Member</button>
          </ul>
    	</div><br>
    	<?php } }?>
        </div>
      </div>

      <!-- The teams start from here-->
      <div class="col-md-6">
        <div id="team" class="well">
          <H6>TEAMS</H6><div class="input-group">                               
                  <input type="text" class="form-control" placeholder="Search" id="search-query-2">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-default"><span class="fui-search"></span></button>
                  </span>
               </div>
          <?php 
         //getting all the member info
         $p = $CFG->dbprefix;
         $sql = "SELECT * FROM {$p}team";
         $stmt = $db->query($sql);
		 while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){

         ?>
          <div class="detail"><p class="lead"><?php if($row['team_id'] != $teamID) {?><a href="team.php?id=<?php echo($row['team_id']);?>"><?php } else {?><a href="team.php"><?php } echo($row['team_name']);?></a></p>
          <div class="row"><div class="col-md-6">
          <ul class="nav nav-list">
            <li class="nav-header">Team Since</li>
            <li><?php echo($row['team_since']) ?></li>
            <li class="nav-header">Ideology</li>
            <?php if($row['ideology'] == null){$row['ideology'] = 'Sorry no ideology added by the team!!';}?>
            <li><?php echo($row['ideology']); ?></li>
          </ul>
        </div>
        <div class="col-md-6"><ul class="nav nav-list">
            <li class="nav-header">Markets</li>
            <li><ul>
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

            </ul></li>
            <li class="nav-header">Members</li>
            <li><ul>
            <?php 
	        //getting the members based on the team id
	        $sql = "SELECT member_name, member_id FROM {$p}members WHERE team_id = {$row['team_id']}";
	        $stmt4 = $db->query($sql);
	       

	        while ( $row4 = $stmt4->fetch(PDO::FETCH_ASSOC) ){

	        	if($row4['member_name'] != null) {

	        ?>
           <li><a href="profile.php<?php echo("?id=".$row4['member_id'])?>"><?php echo($row4['member_name']) ?></a></li>
            <?php } }?>
        	</ul></li>
          </ul></div></div>
    </div>
          <?php }?>
      </div>
  
      
     </div></div>
     
     </div>

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
$(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('.btn').outerWidth()});</script>
  </body>
</html>
