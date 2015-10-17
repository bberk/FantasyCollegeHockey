<STYLE>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.acquireResults td:nth-of-type(1):before { content: "Player"; }
	.acquireResults td:nth-of-type(2):before { content: "Position"; }
	.acquireResults td:nth-of-type(3):before { content: "Availability"; }
	.acquireResults td:nth-of-type(4):before { content: "Action"; }

</STYLE>
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


$playerQuery = $_GET['q'];



$limit_f_a = getPositionLimit("F","a", $leagueID);
$limit_f_b = getPositionLimit("F","b", $leagueID);
$limit_d_a = getPositionLimit("D","a", $leagueID);
$limit_d_b = getPositionLimit("D","b", $leagueID);
$limit_g_a = getPositionLimit("G","a", $leagueID);
$limit_g_b = getPositionLimit("G","b", $leagueID); 

$roster_f_a=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"F\" and statusCode = \"act\" and season = \"$season\"");
$roster_f_b=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"F\" and statusCode = \"ben\" and season = \"$season\"");
$roster_d_a=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"D\" and statusCode = \"act\" and season = \"$season\"");
$roster_d_b=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"D\" and statusCode = \"ben\" and season = \"$season\"");
$roster_g_a=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"G\" and statusCode = \"act\" and season = \"$season\"");
$roster_g_b=selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and releaseDate is  null and position = \"G\" and statusCode = \"ben\" and season = \"$season\"");



//echo "Looking for $playerQuery";
$sql = <<<SQL
SELECT CONCAT( firstName,  " ", lastName,  ", ", schoolDisplayName ) as playerDisplayName, playerID as playerID, position, IF( schoolID
IN (
SELECT schoolid
FROM v_blacklist
),  true,  false ) as blacklist,
if (playerID  in (select playerID from fch_rosters where leagueID = $leagueID and  releaseDate is  NULL and season = "$season" ),true,false) as taken
FROM  `fch_players` 
where 
CONCAT( firstName,  " ", lastName ) like "%$playerQuery%"
SQL;
	debug($sql);
	$con = initializeDB();
	//echo $sql;
	$result = $con->query($sql);

	if ($result->num_rows > 10) {
		echo "<h3>Too Many Results</h3>Please try again.";
		return;
	}
	
	if ($result->num_rows > 0) {
    // output data of each row	
		$out = "<h3>Results</h3><table width = 100% class = \"acquireResults\">";
		$out .= "<thead><tr><th scope=\"col\">Player</th><th scope=\"col\">Position</th><th scope=\"col\" >Availability</th><th scope=\"col\">Action</th></tr></thead>";
		while($row = $result->fetch_assoc()) {
			$status = "";
			$acquire = "";
			$allow = 1;
			$userHasButton = false;
			if ($row['taken'] == 1) {
				$status = "Taken" ;
				$acquire = "--Taken--";
				$userHasButton = true;
				$allow = 0;
			}
			if ($row['blacklist'] == 1) {
				$status = "Currently Playing" ;
				$acquire = "--Currently Playing--";
				$userHasButton = true;
				$allow = 0;
			}
			if ($allow)
				$status = "Available";
			
			debug("<HR>".$row['playerID']);
			debug("$roster_f_a = roster_f_a");
			debug("$roster_f_b = roster_f_b");
			debug("$roster_d_a = roster_d_a");
			debug("$roster_d_b = roster_d_b");
			debug("$roster_g_a = roster_g_a");
			debug("$roster_g_b = roster_g_b");
			debug("$limit_f_a = limit_f_a");
			debug("$limit_f_b = limit_f_b");
			debug("$limit_d_a = limit_d_a");
			debug("$limit_d_b = limit_d_b");
			debug("$limit_g_a = limit_g_a");
			debug("$limit_g_b = limit_g_b");
			
			if ((($roster_f_a < $limit_f_a) && $allow == 1) && $row['position'] == "F") {
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Acquire to Active &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;
			}
			if ((($roster_f_b < $limit_f_b) && $allow == 1) && $row['position'] == "F"){
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_b\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"ben\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_b\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_b','button_submit_".$row['playerID']."_b');\">Acquire to Reserve &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;

				}
			if ((($roster_d_a < $limit_d_a) && $allow == 1) && $row['position'] == "D"){
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Acquire to Active &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;

				}
			if ((($roster_d_b < $limit_d_b) && $allow == 1) && $row['position'] == "D"){
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_b\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"ben\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_b\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_b','button_submit_".$row['playerID']."_b');\">Acquire to Reserve &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;

				}
			if ((($roster_g_a < $limit_g_a) && $allow == 1) && $row['position'] == "G"){
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Acquire to Active &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;

				}
			if ((($roster_g_b < $limit_g_b) && $allow == 1) && $row['position'] == "G"){
				$acquire .= "<form method = \"post\" name = \"submit_".$row['playerID']."_b\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".getNextTransactionID($userID, $leagueID)."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"ben\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_b\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_b','button_submit_".$row['playerID']."_b');\">Acquire to Reserve &gt;&gt;</button> </form>&nbsp;\n";
				$userHasButton = true;

				}
			if (!$userHasButton)
				$acquire .= "--No Available Slots--";
			
			$out .= "<tr><td class = \"rowHead\">".$row['playerDisplayName']."</td><td>".$row['position']."</td><td>".$status."</td><td>$acquire</td></tr>\n";
		}
		$out .= "</table>";
	}
	else
		echo "<h3>No Results Found</h3>Please try again.";
	
	echo $out;
?>

