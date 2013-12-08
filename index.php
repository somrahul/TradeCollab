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
            $_SESSION['userEmail'] = 'You NEW Bitch';
            //header based on the new user
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
            $_SESSION['userEmail'] = 'You Collab Bitch';
        } else{
        	//the user is returning / initiator 
            //load the page based on this
            //header based on the exisitng user
            $_SESSION['userEmail'] = 'You Initiator Bitch';
        }

        

        

        header('Location: test.php');
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
        <a href="#" class="navbar-brand">CoTr</a>
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
      <div class="col-md-3">
      </div>
      <div class="col-md-6">
    <div class="abtcotr">
      <div class="cotrimg">
      <img src="http://placehold.it/200x200">
      </div>
      <div id="cotrdesc">
        Well, the way they make shows is, they make one show. That show's called a pilot. Then they show that show to the people who make shows, and on the strength of that one show they decide if they're going to make more shows. Some pilots get picked and become television programs. Some don't, become nothing. She starred in one of the ones that became nothing.
      </div>
    </div>
    
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
  </body>
</html>

