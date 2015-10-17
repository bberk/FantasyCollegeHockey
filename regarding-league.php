<?php
require_once("fch-lib.php");
$userID = getUserID();
$os = "";
debug("regarding-league");
//debug(selectCount("fch_user_preferences"," WHERE userID = $userID "));
if (selectCount("fch_user_preferences"," WHERE userID = $userID ") == 0)
{
	//debug("Creating preference row , ");
	$key = rand (0, 2147483647);
	$sql = "INSERT INTO  `fanta66_joomla`.`fch_user_preferences` (`id` ,`userID` ,`displayLeague`, `secretKey`) VALUES (NULL ,  $userID, NULL, $key);";
	//debug($sql);
	executeGenericInsertSQL($sql);
	

}

//if (getSingleton("fch_user_preferences","displayLeague", " WHERE userID = $userID") == NULL)

//generateUserPreferences($userID);
$leagueCount = intval(selectCount("fch_league_membership"," where userID =  $userID  "));
$season = getSeason();
$dbLeague = getSingleton("fch_user_preferences","displayLeague", " WHERE userID = $userID");
if (strlen($dbLeague) == 0)
{
	debug("Setting default league.");
	generateUserPreferences($userID);
}
/*
if ($leagueCount == 1)
{
	debug("In only one league");
	if (intval(selectCount("fch_league_membership", " WHERE userID = $userID and season = \"$season\"")) == 1)
	{	
		debug("In only one league. Setting default membership.");
		$thisLeague = getSingleton("fch_league_membership","leagueID"," WHERE season = \"$season\" and userID = $userID");
		debug("Setting default league for user $userID to $thisLeague");
		$sql = "
		UPDATE fch_user_preferences SET displayLeague = $thisLeague
		WHERE userID = $userID";
		executeGenericSql($sql);
	}
}
*/
debug("dbLeague " . $dbLeague . " - userID: " . $userID);
if ( $leagueCount > 1)
{	
	$con = initializeDB();
	$sql ="SELECT m.leagueID AS leagueID, m.teamDisplayName AS teamDisplayName, l.display_name AS leagueDisplayName FROM fch_league_membership m, fch_leagues l WHERE l.id = m.leagueID and m.userID = $userID and m.season = \"$season\""; 
	debug($sql);
	$result = $con->query($sql);
	//echo "debug ". isDebug();
	$leagueSelector .= "<form name = \"leagueSelector\" method = \"POST\"><SELECT name = \"league\">";
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			$selectLeague = "";
			debug("<B>Checking $dbLeague</B> = ".$row["leagueID"]);
			if ($row["leagueID"] == $dbLeague){
				$usingAs = "";//Now viewing your team in " . $row['leagueDisplayName'];
				$selectedLeagueID = $row["leagueID"];
				$selectLeague = "SELECTED";
			}
			else
			{
				$selectLeague = "";
			}
			$leagueSelector .= "<OPTION value = \"".$row['leagueID']."\" $selectLeague>".$row['leagueDisplayName']."</OPTION>";
		}
	}
	
	$leagueSelector .= "<input name = \"action\" value = \"leagueChange\" type = \"hidden\">";
	$leagueSelector .= "<input name = \"go\" value = \"Change League &gt;&gt;\" class=\"btn btn-primary validate\" type = \"button\" onClick = \"javaScript:document.forms['leagueSelector'].submit()\">";
	$leagueSelector .= "</form>";
	echo $usingAs . $leagueSelector;
	closeDB($con);
	executeGenericSQL("UPDATE fch_user_preferences SET displayLeague = $selectedLeagueID WHERE userID = $userID");

}




?>


