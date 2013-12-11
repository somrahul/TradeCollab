<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	if(isset($_POST["mkt"])&&isset($_POST["stckname"])&&isset($_POST["cprice"])&&isset($_POST["reason"])) {
		//print_r($_POST);
		//Saving the values in the dataabse
		$stckname = $_POST['stckname'];
		$cprice = $_POST['cprice'];
		$stckquant = $_POST['stckquant'];
		$nature = $_POST['nature'];
		$reason = $_POST['reason'];
		$endDate = $_POST['date'];
		$market = $_POST["mkt"];

		$p = $CFG->dbprefix;

		$userEmail = $_SESSION['userEmail'];

		//getting the teamid, memberid, memberemail
		$stmt = $db->query("SELECT * from {$p}members WHERE member_email='{$userEmail}'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
       	
       	try { 
			$db->beginTransaction();         
			

	        if ($row != null){

	        	$team_id = $row['team_id'];
	        	$member_id = $row['member_id'];
	        	$member_email = $row['member_email'];

				$sql = "INSERT INTO {$p}deal (team_id, member_id, member_email, stock_name, stock_price, stock_quant, deal_nature, reason, deal_end, deal_created, market) 
						VALUES ('{$team_id}', '{$member_id}', '{$member_email}', '{$stckname}', '{$cprice}', '{$stckquant}', 
						'{$nature}', '{$reason}', STR_TO_DATE('{$endDate}','%d %M,%Y'), NOW(), '{$market}')";

				$stmt = $db->prepare($sql);
				$stmt->execute();

				//deal inserted

				//when the deal is created also update the deal status tabel to show that the other members have to take action
				//for the person who created the deal the status should be yes

				//get the deal id of the created deal
				//$id = mysql_insert_id(); //not working
				
				$id = $db->lastInsertId(); 

				//step 1: get all the members from the team of the person who created the deal
				$sql = "SELECT member_id FROM {$p}members WHERE team_id = '{$team_id}'";
				$stmt = $db->query($sql);
				// $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
				// print_r($row);

				//saving the member ids in an array
				$memberIds = array();

				while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
					$memberIds[] = $row['member_id'];
				}

				for ($i=0;$i<count($memberIds);$i++){
					$member_id_2 = $memberIds[$i];
					//print $member_id_2;
					if($member_id_2 == $member_id){
						//print "inside if";
						$sql = "INSERT INTO {$p}deal_status (team_id, deal_id, member_id, member_status) 
								VALUES ('{$team_id}', '{$id}', '{$member_id_2}', 'YES')";
						$stmt = $db->prepare($sql);
						$stmt->execute();
					} else{
						$sql = "INSERT INTO {$p}deal_status (team_id, deal_id, member_id) 
								VALUES ('{$team_id}', '{$id}', '{$member_id_2}')";
						$stmt = $db->prepare($sql);
						$stmt->execute();
					}
				}

			}

			$db->commit(); 

		} catch(PDOExecption $e) { 
		        $dbh->rollback(); 
		        print "Error!: " . $e->getMessage() . "</br>"; 
		} 

		$_SESSION['success'] = 'The deal has been created successfully';
		header ('Location: home.php');

	} else {
		$_SESSION['error'] = 'Please fill the fields. They all are Required';
		//header ('Location: initiate.php');
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
                    <li class="active"><a href="#">Initiate Deal</a></li>
                    <li class=""><a href="activeDeals.php">Active Deal</a></li>
                    <li class=""><a href="budget.php">Budget</a></li>
                    <li class=""><a href="history.php">History</a></li>

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
  <br><br>
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
			<div class="col-md-4">SELECT MARKET</div>
				<div class="col-md-5">
			<!-- Retrieving the values from the database to decide the values here-->
			<?php 
				$p = $CFG->dbprefix;
				if (isset($_SESSION['userEmail'])) {
					$userEmail = $_SESSION['userEmail'];
				}
				

				//planing the join query 
				$sql = "SELECT markets from {$p}team 
						JOIN {$p}members on {$p}team.team_id = {$p}members.team_id
						WHERE {$p}members.member_email = '{$userEmail}'";
				$stmt = $db->query($sql);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($row != null){
					$markets = $row['markets'];

					$market = explode(';', $markets);
					//calculating the length of the array 
					$cnt = count($market);
					for($i=0; $i<$cnt-1; $i++){
					?>
						
					<label class="radio">
			  		<input type="radio" name="mkt" value="<?php echo($market[$i]);?>" data-toggle="radio">
			 		 <?php echo($market[$i])?>
					</label>

					<?php
					}
				}
				 

			?>
				</div>
			  </div>
			<div class="row row-form">
			<div class="col-md-4">STOCK NAME</div>
				<div class="col-md-5"><input type="text" name="stckname" placeholder="stck_name"></div>
			</div>
			<div class="row row-form">
			<div class="col-md-4">STOCK QUANTITY</div>
				<div class="col-md-5"><input type="text" name="stckquant" placeholder="stck_quant"></div>
			</div>
			  <div class="row row-form">
			<div class="col-md-4">CURRENT PRICE</div>
			<div class="col-md-5"><input type="mumber" name="cprice" placeholder="$"></div>
			  </div>  
			  <div class="row row-form">
			<div class="col-md-4">DEAL NATURE</div>
			<div class="col-md-5"><label class="radio">
			  <input type="radio" name="nature" value="BUY" data-toggle="radio" checked>
			  Buy
			</label>

			<label class="radio">
			  <input type="radio" name="nature" value="SELL" data-toggle="radio">
			  Sell
			</label></div>
			  </div>
			  <div class="row row-form">
			<div class="col-md-4">REASON</div>
			<div class="col-md-5"><textarea rows="4" cols="25" name="reason"> 
			</textarea></div>
			  </div>
			  <div class="row row-form">
			<div class="col-md-4">END DEAL ON</div>
			<div class="col-md-5">
			  <div class="form-group">
			  <div class="input-group">
			      <input type="text" class="form-control" name="date" value="15 December, 2013" id="datepicker-01" />
			  </div>
			</div></div>
			  </div>
			  <div class="row row-form">
			<div class="col-md-9" id="submit"><button class="btn btn-default btn-wide">Clear</button>&nbsp;<button class="btn btn-primary btn-wide">Submit</button></div>
			  </div> 
		</form>
</div>
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
