<?php
define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/' ));  

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );


$app = JFactory::getApplication('site');    
$user = JFactory::getUser();
require_once ("fch-lib.php");
require_once ("config.php");

echo getFullDraftList(leagueHandlerNoUI(null, getUserID()), getUserID(), "all");
//echo json_encode(onTheClock(leagueHandlerNoUI(null, getUserID())));
?>