<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require_once 'config.php';
require_once 'db.php';
require_once $CFG->dirroot.'/lightopenid/openid.php';

try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID($CFG->wwwroot);
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://www.google.com/accounts/o8/id';
            $openid->required = array('contact/email', 'namePerson/first', 'namePerson/last');
            $openid->optional = array('namePerson/friendly');
            header('Location: ' . $openid->authUrl());
        }
?>
<!-- <form action="?login" method="post">
    <button>Login with Google</button>
</form> -->
<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else if ( ! $openid->validate() ) {
        echo 'You were not logged in by Google.  It may be due to a technical problem.';
    } else {
        session_start();
        $identity = $openid->identity;
        $userAttributes = $openid->getAttributes();
  //       echo("\n<pre>\nAttributes:\n");
		// print_r($userAttributes);
		// echo("\n</pre>\n");
        $firstName = isset($userAttributes['namePerson/first']) ? $userAttributes['namePerson/first'] : false;
        $lastName = isset($userAttributes['namePerson/last']) ? $userAttributes['namePerson/last'] : false;
        $userEmail = isset($userAttributes['contact/email']) ? $userAttributes['contact/email'] : false;
		//next what to do should be here
        //saving it into the database here
        $p = $CFG->dbprefix;

        //check if the user is already there in the database
        $stmt = $db->query("SELECT * from {$p}members WHERE member_email='{$userEmail}'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //that means that the user is new
        if ($row == null){
            //echo "inside if";
            //writing the query to enter the user email and password
            $sql = "INSERT into {$p}members (member_email, member_name) VALUES (:email, :name)";
            //echo $sql;
            $stmt = $db->prepare($sql);
            $stmt->execute(array(
                ':email' => $userEmail,
                ':name' => $firstName));

            //header based on the new user
            $_SESSION['loggedIn'] = $firstName;
            $_SESSION['userEmail'] = $userEmail;
            
            header('Location: onboarding.php');
            return; 

        } else if ($row['member_name'] == null){
            //the user is a collaborator
            //insert the name and direct to the home page
            $sql = "UPDATE {$p}members SET member_name = :name WHERE member_email = :email";
            //echo $sql;
            $stmt = $db->prepare($sql);
            //$stmt->execute();
            $stmt->execute(array(
                ':name' => $firstName,
                ':email' => $userEmail));
            //take to the home
            //$_SESSION['userEmail'] = 'You Collab Bitch';
            $_SESSION['loggedIn'] = $firstName;
            $_SESSION['userEmail'] = $userEmail;

            header('Location: home.php');
            return; 

        } else{
        	//the user is returning / initiator 
            //load the page based on this
            //header based on the exisitng user
            //$_SESSION['userEmail'] = 'You Initiator Bitch';
            //print "HI Biatch!!"
            $_SESSION['loggedIn'] = $firstName;
            $_SESSION['userEmail'] = $userEmail;

            header('Location: home.php');
            return; 
        }

        

        //$_SESSION['loggedIn'] = $firstName;

        //header('Location: test.php');
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Flat UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <!-- <link href="<?php //echo($CFG->bootstrap)?>/css/bootstrap.css" rel="stylesheet"> -->

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
        <a href="index.php" class="navbar-brand">CoTr</a>
        <div class="nav-collapse collapse in" id="nav-collapse-01">
                <ul class="nav">
                    <li class="active"><a href="happening.php">Sneak Peak Inside</a></li>
                </ul>
        </div>
          <form class="navbar-search pull-right" action="?login" method="post">
              <div class="input-group input-group-sm">
                  <button class="btn btn-embossed btn-primary">
                    Login using Google
                  </button>
             
              </div>
          </form>
      </div>
    </div>
  </div>
 <div class="container">
     <div class="row">
      <div class="col-md-3">
        <div id="broker" class="well">
          <div class="headtitle">TOP BROKERS</div>
          <div class="data">#1 Sachin Tendulkar<br>
          #2 Madhuri Dixit<br>
          #3 Karan Arjun<br>
          #4 Salman Khan<br>
          #5 Aamir Khan</div>
        </div>
      </div>
      <div class="col-md-3">
        <div id="team" class="well">
          <div class="headtitle">TOP TEAMS</div>
          <div class="data">#1 Mumbai Indians<br>
          #2 Rajasthan Royals<br>
          #3 Kings 11 Punjab<br>
          #4 Chennai Super Kings<br>
          #5 Bangalore Royal Challengers</div>
        </div>
      </div>
  
      <div class="col-md-6">
        <div id="message" class="well">
          <div class="headtitle">SEEKING TEAM MEMBERS</div>
          <div class="data">#1 Team KKR needs one member, specializing in IT<br>
          #2 Team MI needs two members, specializing in Rail Roads<br>
          #3 Team CSK needs three members, specializing in Textile<br>
          #4 Team RR needs one member, specializing in IT<br>
          #5 Team RCB needs one member, specializing in Power</div>
        </div>
      </div>
     </div></div>
     <div class="container">
     <div class="row">
      <div class="col-md-8">
      <div id="news" class="well">
          <div class="headtitle">NEWS</div>
          <div class="data">#1 The Disastrous Rollout Of Obamacare Just Saved The Economy<br>
          #2 Fox News Reporter Scores A Victory For Every Journalist In America<br>
          #3 Here's Paul Krugman's Plan To Grow The Economy Without Bubbles<br>
          #4 Here Are Stanford's Billion-Dollar Fraternities And Dorms, Where Famous Tech Founders Started Out<br>
          #5 A US Spy Agency Came Up With The Worst Possible Logo — And Jon Stewart Ripped It To Shreds</div>
        </div>
      </div>
      <div class="col-md-4">
      <div id="company" class="well">
          <div class="headtitle">HOT COMPANIES</div>
          <div class="data">#1 Google<br>
          #2 Reliance<br>
          #3 Apple<br>
          #4 PepsiCo<br>
          #5 United Breweries</div>
        </div>
      </div>
    </div>
</div>
     </div>
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
  </body>
</html>

