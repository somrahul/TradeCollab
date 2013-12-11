<?php
require_once 'config.php';
require_once 'db.php';

session_start();

if(!isset($_SESSION['loggedIn'])) {
	echo "You are not logged in. Please follow the link";
	echo("<a href='http://localhost:8888/tradeCollab/'>Log In</a>");
	return;
} else {
	$userEmail = $_SESSION['userEmail'];

	//getting the deals created by this user and that require action by others
	$p = $CFG->dbprefix;

	$sql = "SELECT member_id, team_id, member_name FROM {$p}members WHERE member_email = '{$userEmail}'";
	$stmt = $db->query($sql);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$member_id = $row['member_id'];
	$member_name = $row['member_name'];
  $team_id = $row['team_id'];
	//print $member_id;

  //select the deals that were initiated by this member id


	$sql = "SELECT {$p}deal.deal_id, COUNT( * ) 
          FROM {$p}deal
          LEFT JOIN {$p}deal_status ON {$p}deal.deal_id = {$p}deal_status.deal_id
          WHERE {$p}deal.member_id =  '{$member_id}'
          AND {$p}deal.team_id =  '{$team_id}'
          AND member_status IS NULL
          AND deal_end > NOW()
          GROUP BY {$p}deal.deal_id";
	
  $stmt = $db->query($sql);
	
	$dealIds = array();
	while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
		//print $row['deal_id'];
		$dealIds[] =  $row['deal_id'];
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
                    <li class="active"><a href="activeDeals.php">Active Deal</a></li>
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
</div>
  <br>
  <div class="container">
  <ul class="nav nav-tabs">
  <li class="active">
    <a href="#">By Me</a>
  </li>

  <li>
    <a href="activeDealsOthers.php">By Others</a>
  </li>
</ul>
</div>
<?php
//getting the data from the database corresponding to the deal ids and displaying it here
if(count($dealIds) <= 0){
	//no deals found
?>

<div style="color: green;">There are no active deals currently!!</div>
<?php
} else {
	for ($i=0;$i<count($dealIds);$i++){
		$dealId = $dealIds[$i];
		$sql = "SELECT * FROM {$p}deal where deal_id = '{$dealId}' and deal_end > NOW()";
		

		$stmt = $db->query($sql);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!empty($row)) {

		//print_r($row);
		//getting the username of the person who created the deal
		//getting the row id
		$row_id = $row['deal_id'];

		$sql = "SELECT member_name FROM {$p}members WHERE member_id = '{$row['member_id']}'";
		$stmt1 = $db->query($sql);
		$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
		$deal_creator = $row1['member_name'];
?>
    <div class="container" >
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
      <div class="row" id="tablehead">
   <div class="col-md-2" >
    <h6>Deal Info</h6>
    
   </div>
   <div class="col-md-4">
    <div class="row">
      <div class="col-md-4">
       <h6>CP</h6>
      </div>
      <div class="col-md-4" >
        <h6>Qty</h6>
      </div>
      <div class="col-md-4" >
        <h6>Amt</h6>
      </div>
    </div>
   </div> 
   <div class="col-md-2">
    <h6>Approval</h6>
   </div>
   <div class="col-md-4">
    <h6>Comments</h6>
   </div> 
 
 </div>
 <div class="row" id="tabledata">
   <div class="col-md-2">
    <div class="row"><?php echo($row['stock_name']);?>  </div>
    <div class="row"><?php echo($row['market']);?>   </div>
  <div class="row">Request for: <?php echo($row['deal_nature']);?>    </div>
  <div class="row">Ending on: <?php echo($row['deal_end']);?>   </div>
   </div>
   <div class="col-md-4">
    <div class=row>
      <div class="col-md-4">
       <?php echo("$".$row['stock_price']);?> 
      </div>
      <div class="col-md-4" >
        <?php echo($row['stock_quant']);?> 
      </div>
      <div class="col-md-4" >
        <?php $amt = $row['stock_quant']*$row['stock_price']; echo("$".$amt);?> 
      </div>
    </div>
   </div> 
   <div class="col-md-2">
   		
        <?php 
        //based on the deal id get all the users and their status on this deal
        $sql = "SELECT member_status, member_name from {$p}deal_status
				LEFT JOIN {$p}members ON {$p}members.member_id = {$p}deal_status.member_id
				WHERE deal_id = {$row_id}";
		$stmt2 = $db->query($sql);
		while ( $row2 = $stmt2->fetch(PDO::FETCH_ASSOC) ) {
			
				if($row2['member_status'] == null)
					$row2['member_status'] = "Not Decided";
        ?> 
        <span><?php echo($row2['member_name']).": ";?></span>
        <?php if($row2['member_status'] == 'YES') {?>
        <span id="yes" class="glyphicon glyphicon-ok"></span><br><br>
        <?php } else if ($row2['member_status'] == 'NO') { ?>
        <span id="no" class="glyphicon glyphicon-remove"></span><br><br>
        <?php } else { ?>
        <span id="not_decided"><?php echo($row2['member_status']);?></span><br><br>
      	<?php } } ?>
   </div>
   <div class="col-md-4">
    <span id="username"><?php echo($deal_creator).": ";?></span><span><?php echo($row['reason']);?> </span>
   <!-- <form id="message_form"> -->
    	<div style="text-align: right;" >
    		<input type="text" class="form-control input-sm" placeholder="Small" id="<?php echo("chat".$row['deal_id']);?>"/>
    		<input type="hidden" value="<?php echo($member_id);?>" name="member_id" id="<?php echo($member_id);?>">
		    <button style="margin-top: 5px; "   class="btn btn-sm" onclick="insertMessage(<?php echo($row['deal_id']);?>)">Reply</button>
		    
		    <p id="<?php echo("messages".$row['deal_id']);?>" style="font-size: 16px; overflow: scroll; height: 100px;">
		    <?php 
		    //getting all the chats corresponding to the deal id
		    $sql = "SELECT chat, member_name
			FROM {$p}comments JOIN {$p}members 
			ON {$p}comments.member_id = {$p}members.member_id 
			WHERE deal_id = {$row['deal_id']} ORDER BY {$p}comments.chat_created DESC";

			$stmt10 = $db->query($sql);

			//$messages = array(); 

			while ( $row10 = $stmt10->fetch(PDO::FETCH_ASSOC) ) { 
				
			
			echo("<b>".$row10['member_name']."</b>: ".$row10['chat']."<br>");
			
			} 
		    ?>
		    </p>
  		</div>
  	<!-- </form> -->

   </div> 
 
 </div>
</div>
</div>  
<?php
}
}
}
}

?>  
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

<script type="text/javascript">

function clickedYes(dealID, response) {
	console.log(dealID, response)
	var responseID = "response"+dealID;
	var formID = "responseForm"+dealID;
	console.log("response id = ", responseID);
	console.log("Form id = ", formID);
	//document.getElementByID(responseID).value = 'YES';
	$("#"+responseID).val('YES');
	$("#"+formID).submit();

}

function clickedNo(dealID, response) {
	console.log(dealID, response)
	var responseID = "response"+dealID;
	var formID = "responseForm"+dealID;
	console.log("response id = ", responseID);
	console.log("Form id = ", formID);
	//document.getElementByID(responseID).value = 'YES';
	$("#"+responseID).val('NO');
	$("#"+formID).submit();
}

function insertMessage(dealID){
	console.log("inside the insert message");
	var chat = $("#chat"+dealID).val();
	console.log("chat = " + chat);
	//making the request to store the chat in the database
	var ajaxCall = $.ajax({
		type: "POST",
  		dataType: "json",
		data: {chatMessage: chat, deal_id: dealID, member_id: '<?php echo($member_id)?>'},
		url: "chatMessages.php",
		success: function(resultData){
			console.log(resultData);
			$("#messages"+dealID).html("");
			console.log("length = " + resultData.length);
           for (var i = 0; i < resultData.length; i++) { 
          		entry = resultData[i];
          		$("#messages"+dealID).append("<b>"+entry.member_name+"</b>").append(":&nbsp"+entry.chat+"<br>").css({"font-size": "16px"}); 
          	}
          	$("#chat"+dealID).val('');
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest="+XMLHttpRequest.responseText+"\ntextStatus="+textStatus+"\nerrorThrown="+errorThrown);
    }
	});
	
}

//getting the chats at the start of the page



</script>

  </body>
</html>
