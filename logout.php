<?php
    session_start();
    session_destroy();
    //document.location.href = "https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://www.mysite.com";
    header("Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://localhost:8888/tradeCollab");