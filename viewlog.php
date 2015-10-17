

<?php
error_reporting(E_ERROR | E_PARSE);
require 'fch-lib.php';
    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/' ));  
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );

    $mainframe = JFactory::getApplication('site');
$season = getSeason();
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League ID $leagueID");

$lastPick= getSingleton("fch_draft","playerID"," WHERE leagueID = $leagueID ORDER BY dateStamp DESC LIMIT 0,1");
$pickType= getSingleton("fch_draft","pickType"," WHERE leagueID = $leagueID ORDER BY dateStamp DESC LIMIT 0,1");
$pickBy= getSingleton("fch_draft","userID"," WHERE leagueID = $leagueID ORDER BY dateStamp DESC LIMIT 0,1");
$pickByDisp= getSingleton("fch_league_membership","teamDisplayName"," WHERE leagueID = $leagueID and userID = $userID ");
$overall= getSingleton("fch_draft","overallPick"," WHERE leagueID = $leagueID ORDER BY dateStamp DESC LIMIT 0,1");


if ($pickType == "m")
	echo "manual pick";
else
	echo getSingleton("fch_players","CONCAT(LEFT(firstName,1), \". \" , lastName, '<BR/>', position, \", \", schoolDisplayName)"," WHERE playerID = \"$lastPick\"");
echo "<br/>by $pickByDisp";
echo "<br/>#$overall Overall";
?>