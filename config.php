<?php // Configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

// No trailing slash
$CFG->wwwroot = 'http://localhost:8888/TradeCollab';
$CFG->staticroot = $CFG->wwwroot;
$CFG->bootstrap = $CFG->staticroot . "/static/bootstrap";
$CFG->dirroot = realpath(dirname(__FILE__));
$CFG->timezone = 'America/New_York';


$CFG->database  = 'tradeCollab';
$CFG->pdo       = 'mysql:host=localhost;dbname=tradeCollab';
$CFG->dbuser    = 'dbuser';
$CFG->dbpass    = 'pass123';
$CFG->dbprefix  = 'tradeCollab_';



// No trailing tag to avoid white space
