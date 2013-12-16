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
  $p = $CFG->dbprefix;
  $sql = "SELECT member_id, team_id FROM {$p}members WHERE member_email = '{$userEmail}'";
  $stmt = $db->query($sql);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $team_id = $row['team_id'];
  $sql = "SELECT budget, budget_current FROM {$p}team where team_id = '{$team_id}'";
  $stmt2 = $db->query($sql);
  $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
  $budget = $row2['budget'];
  $budget_current = $row2['budget_current'];
  $budget_left_percentage = ($budget_current/$budget)*100;
  $budget_invested_percentage = 100 - $budget_left_percentage;
}
  // print "budget = ".$budget;
  // print "budget current = ".$budget_current;
  // print "budget left= ".$budget_left_percentage;
  // print "budget invested= ".$budget_invested_percentage;

?> 

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Trade Collaborator</title>
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

    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.js?2.1.3"></script>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.geom.js?2.1.3"></script>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.layout.js?2.1.3"></script>
 
    <style type="text/css">
        .slice text {
            font-size: 16pt;
            font-family: Arial;
        } 
        svg {
            width: 600px;
            height: 450px;
            font: 10px sans-serif;
            margin-left: 200px;
            text-align: center;
            shape-rendering: crispEdges;
        }  
    </style>
    
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
                    <li class="active"><a href="budget.php">Budget</a></li>
                    <li class=""><a href="history.php">History</a></li>

                                      </ul> <!-- /nav -->
                  <ul class="nav" style="padding-left: 180px;">
                    <li class=""><a href="explore.php">Explore</a></li>
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
    <?php 

          echo '<p style="color:blue">'."Total Budget = $".$budget."</p>\n";
     
          echo '<p style="color:green">'."Amount Invested = $".($budget - $budget_current)."</p>\n";

          echo '<p style="color:red">'."Amount Left = $".$budget_current."</p>\n";
          
          
    ?>
    <div id="barChart" style="margin-left: 100px;">
    <script type="text/javascript">
 
    var w = 600,                        //width
    h = 400,                            //height
    r = 200,                            //radius
    color = d3.scale.category20c();     //builtin range of colors
 
    data = [{"label":"Budget Left", "value":<?php echo($budget_left_percentage);?>}, 
            {"label":"Invested", "value":<?php echo($budget_invested_percentage);?>}];
    
    var vis = d3.select("body")
        .append("svg:svg")              //create the SVG element inside the <body>
        .data([data])                   //associate our data with the document
            .attr("width", w)           //set the width and height of our visualization (these will be attributes of the <svg> tag
            .attr("height", h)
        .append("svg:g")                //make a group to hold our pie chart
            .attr("transform", "translate(" + r + "," + r + ")")    //move the center of the pie chart from 0, 0 to radius, radius
 
    var arc = d3.svg.arc()              //this will create <path> elements for us using arc data
        .outerRadius(r);
 
    var pie = d3.layout.pie()           //this will create arc data for us given a list of values
        .value(function(d) { return d.value; });    //we must tell it out to access the value of each element in our data array
 
    var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
        .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
        .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
            .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
                .attr("class", "slice");    //allow us to style things in the slices (like text)
 
        arcs.append("svg:path")
                .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
                .attr("d", arc);                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function
 
        arcs.append("svg:text")                                     //add a label to each slice
                .attr("transform", function(d) {                    //set the label's origin to the center of the arc
                //we have to make sure to set these before calling arc.centroid
                d.innerRadius = 0;
                d.outerRadius = r;
                return "translate(" + arc.centroid(d) + ")";        //this gives us a pair of coordinates like [50, 50]
            })
            .attr("text-anchor", "middle") 
            .attr("fill", "white")
            .attr("font-size","12px")                       //center the text on it's origin
            .text(function(d, i) { return data[i].label; });        //get the label from our original data array
        
    </script>
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


  </body>
</html>
