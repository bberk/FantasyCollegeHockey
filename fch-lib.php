
<STYLE>

	<!--[if !IE]><!-->
	<style>
* { 
	margin: 0; 
	padding: 0; 
}
body { 
	/* font: 14px/1.4 Georgia, Serif;  */
}
#page-wrap {
	margin: 50px;
}
p {
	margin: 20px 0; 
}

	/* 
	Generic Styling, for Desktops/Laptops 
	*/
	table { 
		 
		border-collapse: collapse; 
		border:1px solid gray;
		color:#fff;
		background:#D3E4E5; /* Greyish Blue */
	}
	/* Zebra striping */
	tr:nth-of-type(odd) { 
		background: #eee; 
	}
	th.rowEmphasis  {
		text-align:left;
		text-transform:none;
		background: #1a4f5f;  
	}
	th.rowEmphasis:nth-of-type(odd)  {
		background-color: #49afcd !important; 
	}
	th.rowEmphasis:nth-of-type(even)  {
		background-color: #D3E4E5 !important; 
	}
	th { 
		background:#1a4f5f; /*#49afcd;*/ /* Light blue */
		font-weight: bold; 
		font-color:#000000;
		padding:3px 10px 3px 10px;
		text-align:center;
		text-transform:uppercase;
		border: 1px solid #ccc; 
	}
	td { 
		padding: 6px; 
		border: 1px solid #ccc; 
		color:#363636;
	}
	td.rowHeadLeft {
		font-weight: bold; 
		font-size: 120%;
		text-align:left;
	}
	td.rowHead {
		font-weight: bold; 
		font-size: 120%;
	}
	tbody td a { color:#363636;
		text-decoration:none;
	}
	tbody td a:visited { color:gray;
	 text-decoration:line-through;
	}
	tbody td a:hover { text-decoration:underline;
	}

	tbody th a { color:#363636;
	 font-weight:normal;
	 text-decoration:none;
	}
	tbody th a:hover { color:#363636;
	}
	tbody td+td+td+td a { background-image:url('http://www.admixweb.com/downloads/csstablegallery/bullet_blue.png');
	 background-position:left center;
	 background-repeat:no-repeat;
	 color:#03476F;
	 padding-left:15px;
	}
	tbody td+td+td+td a:visited { background-image:url('http://www.admixweb.com/downloads/csstablegallery/bullet_white.png');
	 background-position:left center;
	 background-repeat:no-repeat;
	}
	tbody th, tbody td { text-align:center;
	 vertical-align:top;
	}
	/*
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

		tr { border: 1px solid #ccc; }

		td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
 		  text-align: center;

		}

		td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}

	}

	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body {
			padding: 0;
			margin: 0;
			width: 320px; }
		}

	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body {
			width: 495px;
		}
	}

	</style>
	<!--<![endif]-->
	<STYLE>
div.messageOk {
  background: #90EE90 url(../images/message-icon.gif) no-repeat 9px 8px; 
  border-top: 2px solid #8db1d4; 
  border-bottom: 2px solid #8db1d4;
  margin:10px 0px;
  padding: 10px 10px 10px 40px;
  color: #385c7f;
  text-align: left;
  font-weight: bolder;
}

div.messageOk a {
  text-decoration: underline;
  color: #4c88bc;
}

div.messageErr {
  background: #FF6347 url(../images/message-icon.gif) no-repeat 9px 8px; 
  border-top: 2px solid #8db1d4; 
  border-bottom: 2px solid #8db1d4;
  margin:10px 0px;
  padding: 10px 10px 10px 40px;
  color: #FFFFFF;
  text-align: left;
  font-weight: bolder;
}

div.messageErr a {
  text-decoration: underline;
  color: #FFFFFF;
}
</STYLE>
<?php
//385c7f
date_default_timezone_set("America/New_York");
require_once 'config.php';
require_once 'email.php';
//require_once 'regarding-league.php';
$pleaseJoin = "Please join a league first.";

function initializeDB()
{
	$username = "fanta66_php";
	$password = "@U81rc*5Pq0m";
	$hostname = "127.0.0.1"; 
	$db = "fanta66_joomla";
	$mysqli = new mysqli($hostname, $username, $password, $db);
	//echo "using " . $db;
	/*
	 * This is the "official" OO way to do it,
	 * BUT $conect_error was broken until PHP 5.2.9 and 5.3.0.
	 */
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') '
				. $mysqli->connect_error);
	}
	return $mysqli;
}

function closeDB($con) {
	$con->close();
}

function getUserID()
{
	$user = JFactory::getUser();
 
	if (!$user->guest) {
	  //echo 'You are logged in as:<br />';
	  //echo 'User name: ' . $user->username . '<br />';
	  //echo 'Real name: ' . $user->name . '<br />';
	  return $user->id ;
	}
	else
		return -1;
}
function selectCount($table, $where)
{
	//  "WHERE" MUST  be in the passed in whereclause
	$con = initializeDB();
	$sql = "SELECT count(*) from " . $table . " " . $where ;
	$result = mysqli_query($con, $sql);
	// BREAKS DRAFT
	//debug("SelectCount: " .$sql);
	$q = intval($result->num_rows );
	if ($q > 0) {
		// one row only, using if
		while($row = $result->fetch_assoc()) {
			return intval($row["count(*)"]);
		}
	} else {
		return intval(0);
	}
	closeDB($con);
}

function selectSum($table, $column, $where)
{
	//  "WHERE" MUST  be in the passed in whereclause
	$con = initializeDB();
	
	$sql = "SELECT SUM($column) from " . $table . " " . $where ;
	$result = mysqli_query($con, $sql);
	//debug("SelectSum: " .$sql);
	$q = intval($result->num_rows );
	if ($q > 0) {
		// one row only, using if
		while($row = $result->fetch_assoc()) {
			//debug($row["SUM($column)"]);
			//debug("$column");
			$out= intval($row["SUM($column)"]);
		}
	} else {
		$out=intval(0);
	}
	closeDB($con);
	return $out;
}

function selectSumWithGroupBy($table, $column, $where, $groupBy)
{
	//  "WHERE" MUST  be in the passed in whereclause
	
	$con = initializeDB();
	$sql = "SELECT SUM($column), $groupBy from " . $table . " " . $where . " GROUP BY $groupBy ORDER BY SUM($column) DESC";
	$result = mysqli_query($con, $sql);
	debug("selectSumWithGroupBy: " . $sql);
	$q = intval($result->num_rows );
	debug("selectSumWithGroupBy: Returned $q rows");
	if ($q > 0) {
		// one row only, using if
		while($row = $result->fetch_assoc()) {
			//debug($row["SUM($column)"]);
			debug($row[$groupBy] . " == ".$row["SUM(".$column.")"]); //$row[$userValue]
			$out[$row[$groupBy]] = intval($row["SUM(" . $column .")"]);
		}
	} else {
		return intval(0);
	}
	
	closeDB($con);
	return $out;
}

function executeGenericInsertSQL($sql)
{
	// echo's in this mess up transactionID!
	$con = initializeDB();
	//echo $sql;
	mysqli_query($con,$sql);
	$id = $con->insert_id;
	if (strlen($con->error) == 0)
		$r = $id;
	else
		$r = -1;
	closeDB($con);
	return $r;
}

function executeGenericSQL($sql)
{
	//echo $sql;
	$con = initializeDB();
	debug("ExecuteGenericSQL: " . $sql);
	$r = mysqli_query($con,$sql);
	debug("SQL erorr if any" . $con->error);
	if (strlen($con->error) == 0)
		$r = getReturnCode(1,"Transaction Successful.");
	else
		$r =  getReturnCode(0,"Error in Transaction: " . $con->error);
	closeDB($con);
	
	return $r;
	//return $stmt->execute();
}



function getReturnCode($code,$msg)
{
	debug("GetReturnCode: $code $msg");
	$c = array(
		"msg" => $msg,
		"status" => $code
	);
	
	return $c;
}

function isDebug()
{
	$c = getConfig();
	return $c["debug"];
}

function debug($msg)
{
	if (isDebug())
		echo "DEBUG: " . $msg . "<br/>\n";
}



function getFormattedSeason($type)
{
	
	// 1: 2014-2015
	// 2: 20142015
	// 
	return 0;
}

function createLeague($leagueName, $pw, $displayName, $limit_f_a, $limit_f_b,$limit_d_a, $limit_d_b, $limit_g_a, $limit_g_b, $registrationDeadline, $draftTime, $invites)
{
	$season = getSeason();
	//echo "creating league $leagueName, $pw, $displayName, $registrationDeadline, $draftTime , $limit_f_a, $limit_f_b, $limit_d_a, $limit_d_b, $limit_g_a, $limit_g_b";
	
	$r = getReturnCode(1,"");

	if (selectCount("fch_leagues"," WHERE display_name = \"$leagueName\" and season = \"$season\"") > 0)
		return getReturnCode(0,"Sorry, that league name is already taken.");
	$user = getUserID();
	if ($user === -1)
		return getReturnCode(0,"Sorry, could not ascertain your user ID.");
	

	
	if (intval($limit_f_a) > 12)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many active roster forwards. ";
	}
	if (intval($limit_f_a) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few active roster forwards. ";
	}
	if (intval($limit_f_b) > 12)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many reserve forwards. ";
	}
	if (intval($limit_f_b) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few reserve forwards. ";
	}
	if (intval($limit_d_a) > 8)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many active roster defensemen. ";
	}
	if (intval($limit_d_a) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few active roster defensemen. ";
	}
	if (intval($limit_d_b) > 8)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many reserve defensemen. ";
	}
	if (intval($limit_d_b) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few reserve defensemen. ";
	}	
	if (intval($limit_g_a) > 4)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many active roster goalies. ";
	}
	if (intval($limit_g_a) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few active roster goalies. ";
	}
	if (intval($limit_g_b) > 4)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too many reserve goalies. ";
	}
	if (intval($limit_g_b) < 1)
	{
		$r["status"] = 0;
		$r["msg"] .= " You have too few reserve goalies. ";
	}		
	
	if (($regTimeStamp = strtotime($registrationDeadline)) === false) 
    {
		$r["status"] = 0;
		$r["msg"] .= " There was an error reading the registration deadline. ";
	}
	
	if (($draftTimeStamp = strtotime($draftTime)) === false) 
    {
		$r["status"] = 0;
		$r["msg"] .= " There was an error reading the draft time. ";
	}

	if ($r["status"] === 0)
		return $r;
	
	//YYYY-MM-DD HH:MM:SS
	
	$draftTimeSqlFormat = date("Y-m-d G:i:00",$draftTimeStamp);
	$regTimeSqlFormat = date("Y-m-d G:i:00",$regTimeStamp);

	$interval = $draftTimeStamp - $regTimeStamp;
	debug("Interval $interval");
	if (intval($interval) < 86400)
	{
		$r["status"] = 0;
		$r["msg"] .= " Draft must be 24 hours after registration deadline. ";
	}

    
	$season = getSeason();
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_leagues (
		id ,
		display_name ,
		season ,
		admin_user,
		pw,
		limit_f_a,
		limit_f_b,
		limit_d_a,
		limit_d_b,
		limit_g_a,
		limit_g_b,
		registrationDeadline,
		draftTime,
		draftStatus
		)
		VALUES (
			NULL ,  "$leagueName",  "$season",  $user, "$pw", $limit_f_a, $limit_f_b, $limit_d_a, $limit_d_b, $limit_g_a,$limit_g_b, DATE_ADD("$draftTimeSqlFormat", INTERVAL -1 HOUR), "$draftTimeSqlFormat", "0"
		);
		
SQL;
	debug($sql);	
	$leagueID = executeGenericInsertSQL($sql);
	//echo "Created league id = ". $leagueID;
	$r1 = addGMToLeague($leagueID,$user,$displayName);
	
	$body = "You have been invited to join a Fantasy College Hockey league!";
	$body.= $leagueName .' has just been created. To join, visit http://www.fantasycollegehockey.com, sign up or login, and click Join League in the menu.' . "\n\n";
	if (strlen($pw)>0)
			$body .= "The password to join the league is $pw.\n\n";
		else
			$body .= "There is no password to join the league. When prompted, leave the field blank.\n\n";
	$body .= "The registration deadline is $regTimeSqlFormat and the draft is at $draftTimeSqlFormat, Eastern time.";
	
	$inviteEmails = explode(",",$invites);
	debug("Invites $invites");
	foreach ($inviteEmails as $email) {
		debug("Emailing " . $email);
		sendEmail($email,"You have been invited to join a Fantasy College Hockey league",$body) ;
	}
	sendEmail("bob.hatcher@gmail.com","New League Created","New League: $leagueName, $leagueID. Invites: $invites");
	if ($r1["status"] ===1)
		return getReturnCode(1,"Your league has been created and you are its manager. Please see the league control panel for more information.");
}

function addGMToLeague($leagueID,$userID,$displayName)
{
	$con = initializeDB();
	$season = getSeason();
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_league_membership (
		id ,
		leagueID ,
		userID ,
		teamDisplayName,
		season,
		status

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$displayName", "$season", "active"
		);
		
SQL;
	//echo $sql;	
	$con->close();
	executeGenericSQL($sql);
	generateUserPreferences($userID);
	return getReturnCode(1,"You have successfully joined the league.");
 
}

function getNextTransactionID($userID, $leagueID)
{
	return executeGenericInsertSQL("INSERT INTO fch_transactions VALUES (NULL, NULL, $userID, $leagueID, 0)");
}

function setTransactionComplete($transactionID)
{
	executeGenericInsertSQL("UPDATE fch_transactions SET status = 1 WHERE id = $transactionID");
}

function okToTransact($transactionID)
{
	$x = getSingleton("fch_transactions","status"," WHERE id = $transactionID");
	
	if ($x == 0)
		return true;
	else
		return false;
}

function getOptionSet($table,$id,$userValue, $where, $formNameTag) {
	$con = initializeDB();
	$sql = "SELECT $id, $userValue FROM $table $where ORDER BY $userValue ASC";
	debug("getOptionSet: $sql");
	$result = $con->query($sql);

	$out = "<SELECT name = \"$formNameTag\">";
	if ($result->num_rows > 0) {
    // output data of each row	
    while($row = $result->fetch_assoc()) {
		$out .= "<OPTION value = \"".$row[$id] . "\">".$row[$userValue]."</option>\n";
        //$out .= "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    }
} else {
    return "";
}
$con->close();
$out .= "</select>";
return $out;
}

function getPosition($playerID)
{
	$season = getSeason();
	return getSingleton("fch_players","position"," WHERE playerID = \"$playerID\" and season = \"$season\"");
}

function getPlayerListDivFormat($userID, $leagueID, $activeOrBench, $position) {
	$con = initializeDB();
	$nextGame = getNextGameBySchool(); 

	$sql = "SELECT r.playerID as playerID, p.schoolID as schoolID, r.statusCode, CONCAT( p.firstName,  \" \", p.lastName,  \", \", p.schoolDisplayName ) AS playerDisplayName FROM fch_rosters r, fch_players p where r.position = \"$position\" and r.statusCode = \"$activeOrBench\" and r.userID = $userID and r.leagueID = $leagueID and r.releaseDate is null and r.playerID = p.playerID and schoolID not in (select schoolID from v_blacklist) ORDER BY lastName asc";
	//echo $sql;
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			debug($nextGame[$row[playerID]] . " = next game $row[schoolID]");
			$out .= "<li id = \"" . $row['playerID'] . "\">" . $row['playerDisplayName'] . "<br/>Next: ".$nextGame[$row[schoolID]]."</li>\n";
		}
	} else {
		return "";
	}
	$con->close();
	return $out;
}



function getPlayerListHiddenFormat($userID, $leagueID, $activeOrBench, $position) {
	$con = initializeDB();
	$sql = "SELECT r.playerID as playerID, r.statusCode as statusCode, CONCAT( p.firstName,  \" \", p.lastName,  \", \", p.schoolDisplayName ) AS playerDisplayName FROM fch_rosters r, fch_players p where r.position = \"$position\" and r.statusCode = \"$activeOrBench\" and r.userID = $userID and r.leagueID = $leagueID and r.releaseDate is null and r.playerID = p.playerID ORDER BY lastName asc";
	//echo $sql;
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			$out .= "<input type = \"hidden\" name = \"s_".$row['playerID']."\" id = \"h_".$row['playerID']."\" value = \"" . $row['statusCode'] . "\"></input>\n";
			$out .= "<input type = \"hidden\" name = \"b_".$row['playerID']."\" id = \"b_".$row['playerID']."\" value = \"" . $row['statusCode'] . "\"></input>\n";
			$out .= "<input type = \"hidden\" name = \"s_".$row['playerID']."\" id = \"s_".$row['playerID']."\" value = \"" . $row['statusCode'] . "\"></input>\n";

		}
	} else {
		return "";
	}
	$con->close();
	return $out;
}

function moveBetween($userID,$leagueID,$playerID,$position,$moveTo, $transactionID)
{
	if ($moveTo == "ben")
		$moveFrom = "act";
	else 
		$moveFrom = "ben";
	//$position = getPosition($playerID);
	debug("Moving to $moveTo: $userID $leagueID  $playerID $position from $moveFrom to $moveTo, transaction $transactionID");
	if (!okToTransact($transactionID))
		return getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button.");


	$check = isAvailable($playerID,$leagueID);
	
	$rosterCount =selectCount("fch_rosters"," Where userID = $userID and season = \"$season\" and releaseDate is  null and position = \"$position\" and statusCode = \"$moveTo\"");
	if ($rosterCount > 1) {
		debug("Attempting to move to where this player already is. Exiting.");
		$r = array();
		$r["status"] = -1;
		return;
	}
	$r = array();
	
	if (intval($check) == 1){   // 1= blacklist
		$r["status"] = 0;
		$r["msg"] = "Error: Player is currently playing a game.";
		return $r;
	}
	
	$rosterCount =selectCount("fch_rosters"," Where userID != $userID and season = \"$season\" and releaseDate is  null and position = \"$position\" and statusCode = \"$moveFrom\"");
	$limit = getPositionLimit($position,$moveTo, $leagueID);
	debug("Roster Slot calc: $rosterCount >= $limit");
	if ($rosterCount >= $limit) {
		debug("no slots, returning failure");
		$r["status"] = 0;
		$r["msg"] = "Your ".getDisplayRosterName($moveFrom)." is full at this position.";
		return $r;
	}
	
	$season = getSeason();
	$sql = "UPDATE fch_rosters SET releaseDate = DATE_ADD(CURRENT_TIMESTAMP,INTERVAL + 3 HOUR) where playerID = \"$playerID\" and userID = $userID and leagueID = $leagueID and  releaseDate is NULL  and  season = \"$season\" and statusCode = \"$moveFrom\"";
	debug("MoveTo $moveTo: " . $sql);
	$ok = executeGenericSQL($sql);
	if (!$ok)
	{
		$r["msg"] = "There has been a database error (moveToBench/update).";
		$r["status"] = 0;
		return $r;
	}
	debug("errcode: " .$ok);
	$season = getSeason();
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_rosters (
		id ,
		leagueID ,
		userID ,
		playerID,
		season,
		statusCode,
		position,
		acquireDate

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID", "$season", "$moveTo", "$position", date_add(current_timestamp,interval + 3 hour)
		);
		
SQL;
		debug("MoveTo $moveTo: " . $sql);

	$rosterID = executeGenericInsertSQL($sql);
	if ($rosterID == -1){	
		$r["msg"] = "There has been a database error (moveToBench/insert).";
		$r["status"] = 0;
		return $r;
	}
	
	if ($moveTo == "ben")
		$logAction = "Benched";
	else
		$logAction = "Activated";
	$r["logAction"] = $logAction;
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_roster_transaction (
		id ,
		leagueID ,
		userID ,
		playerID,
		season,
		transactionDate,
		transactionType

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID", "$season", DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR), "$logAction"
		);
		
SQL;
	$ok = executeGenericSQL($sql);
	
	$r["status"] = 1;
	$r["msg"] = "Your transaction was successful.";
	$r["id"] = $rosterID;

	return $r;
}


function releasePlayer($userID,$leagueID,$playerID, $transactionID)
{
	$season = getSeason();
	debug("releasePlayer: ". okToTransact($transactionID) . " $transactionID");
	if (!okToTransact($transactionID))
		return getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button.");
	$sql = "UPDATE fch_rosters SET releaseDate = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR) where playerID = \"$playerID\" and userID = $userID and leagueID = $leagueID and  releaseDate is NULL and season = \"$season\" ";
	$ok = executeGenericSQL($sql);	
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_roster_transaction (
		id ,
		leagueID ,
		userID ,
		playerID,
		season,
		transactionDate,
		transactionType

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID", "$season", DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR), "Released"
		);
		
SQL;
	$ok = executeGenericSQL($sql);	

	setTransactionComplete($transactionID);
	debug($ok . " returned code");
	if ($ok)
		return getReturnCode(1,"Your transaction has been completed.");

	
}

function getPositionLimit($pos, $rosterType,$leagueID)
{
	$where = " WHERE id = $leagueID AND season = \"".getSeason()."\"";
	
	if (($pos == "F") && ($rosterType == "a"))
		return getSingleton("fch_leagues","limit_f_a",$where);
	if (($pos == "F") && ($rosterType == "b"))
		return getSingleton("fch_leagues","limit_f_b",$where);
	if (($pos == "D") && ($rosterType == "a"))
		return getSingleton("fch_leagues","limit_d_a",$where);
	if (($pos == "D") && ($rosterType == "b"))
		return getSingleton("fch_leagues","limit_d_b",$where);
	if (($pos == "G") && ($rosterType == "a"))
		return getSingleton("fch_leagues","limit_g_a",$where);
	if (($pos == "G") && ($rosterType == "b"))
		return getSingleton("fch_leagues","limit_g_b",$where);
	if (($pos == "F") && ($rosterType == "act"))
		return getSingleton("fch_leagues","limit_f_a",$where);
	if (($pos == "F") && ($rosterType == "ben"))
		return getSingleton("fch_leagues","limit_f_b",$where);
	if (($pos == "D") && ($rosterType == "act"))
		return getSingleton("fch_leagues","limit_d_a",$where);
	if (($pos == "D") && ($rosterType == "ben"))
		return getSingleton("fch_leagues","limit_d_b",$where);
	if (($pos == "G") && ($rosterType == "act"))
		return getSingleton("fch_leagues","limit_g_a",$where);
	if (($pos == "G") && ($rosterType == "ben"))
		return getSingleton("fch_leagues","limit_g_b",$where);
	return 0;
}

function getAllPositionLimits($leagueID)
{
	$season = getSeason();
	$sql = "SELECT limit_f_a, limit_f_b, limit_d_a, limit_d_b, limit_g_a, limit_g_b FROM fch_leagues WHERE season = \"$season\" and id = $leagueID";
	$where = " WHERE id = $leagueID AND season = \"".getSeason()."\"";
	$con = initializeDB();
	$result = $con->query($sql);
	
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			$r["f_a"] = $row["limit_f_a"];
			$r["f_b"] = $row["limit_f_b"];
			$r["g_a"] = $row["limit_g_a"];
			$r["g_b"] = $row["limit_g_b"];
			$r["d_a"] = $row["limit_d_a"];
			$r["d_b"] = $row["limit_d_b"];
		}
	}
	closeDB($con);
	return $r;
}

function getPositionLimitForm($pos,$rosterType,$leagueID)
{
	$out;
	$out .= "<input type = \"hidden\" name = \"limit_f_a\" value = \"" . getPositionLimit("F","a",$leagueID) . "\"></input>";
	$out .= "<input type = \"hidden\" name = \"limit_f_b\" value = \"" . getPositionLimit("F","b",$leagueID) . "\"></input>";
	$out .= "<input type = \"hidden\" name = \"limit_d_a\" value = \"" . getPositionLimit("D","a",$leagueID) . "\"></input>";
	$out .= "<input type = \"hidden\" name = \"limit_d_b\" value = \"" . getPositionLimit("D","b",$leagueID) . "\"></input>";
	$out .= "<input type = \"hidden\" name = \"limit_g_a\" value = \"" . getPositionLimit("G","a",$leagueID) . "\"></input>";
	$out .= "<input type = \"hidden\" name = \"limit_g_b\" value = \"" . getPositionLimit("G","b",$leagueID) . "\"></input>";			
	return $out;
}

function getDisplayRosterName($activeOrBench) 
{
	if ($activeOrBench == "act")
		return "active roster";
	else
		return "reserve";
}

function acquirePlayer($userID,$leagueID,$playerID,$activeOrBench, $position,$transactionID)
{
	$season = getSeason();
	//$check = selectCount("v_current_rosters", " WHERE playerID = \"$playerID\" and leagueID = $leagueID and  userID = $userID");
	//if ($check > 0)
	//	return "Error: Player is on another roster.";

	if (!okToTransact($transactionID))
		return getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button.");

	$check = isAvailable($playerID,$leagueID);
	debug("DoAcquire: Check returned $check");
	$r = array();
	
	if ($check > 0){ // 0 is available
		$r["status"] = 0;
		$r["msg"] = "Error: Player is on another roster or his team is currently playing.";
		return $r;
	}
	debug("DoAcquire : Getting Roster Counts");
	$rosterCount =selectCount("fch_rosters"," Where userID = $userID and leagueID = $leagueID and season = \"$season\" and releaseDate is  null and position = \"$position\" and statusCode = \"$activeOrBench\"");
	$limit = getPositionLimit($position,$activeOrBench, $leagueID);
	debug("Roster Slot calc: $rosterCount >= $limit");
	if ($rosterCount >= $limit) {
		debug("no slots, returning failure");
		$r["status"] = 0;
		$r["msg"] = "Your ".getDisplayRosterName($activeOrBench)." is full at this position.";
		return $r;
	}
	debug("passed validation, inserting");
	
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_rosters (
		id ,
		leagueID ,
		userID ,
		playerID,
		season,
		statusCode,
		position,
		acquireDate

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID", "$season", "$activeOrBench", "$position", DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR)
		);
		
SQL;
	//debug($sql);
	$rosterID = executeGenericInsertSQL($sql);

	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_roster_transaction (
		id ,
		leagueID ,
		userID ,
		playerID,
		season,
		transactionDate,
		transactionType

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID", "$season", DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR), "Acquired"
		);
		
SQL;
debug("acquirePlayer: " . $sql);
	$rosterOK = executeGenericInsertSQL($sql);

	$r["status"] = 1;
	$r["msg"] = "Your transaction was successful.";
	$r["id"] = $rosterID;
	setTransactionComplete($transactionID);
	return $r;
	
}

function draftPlayer($userID,$leagueID,$playerID,$position,$transactionID,$passedInStatus)
{                   
	$season = getSeason();
	debug("passed in status $passedInStatus");
	if (!okToTransact($transactionID))
		return getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button.");

	
	// If PassedPick, skip validation and just insert 'em
	if ($playerID == "PassedPick")
	{
		$status = "P";
		$overallPick = getSingleton("fch_draft","max(overallPick)"," where leagueID = $leagueID") + 1;
	}
	else
	{
		/////////// IS NOT PASSED PICK
		$status = $passedInStatus;
		
		$check = selectCount("fch_draft"," where leagueID = $leagueID and playerID = \"$playerID\"");
		
		$r = array();
		
		if ($check > 0){ // 0 is available
			$r["status"] = 0;
			$r["msg"] = "Error: Player has already been drafted.";
			return $r;
		}
		
		
		$rosterCount =selectCount("fch_draft"," Where userID = $userID and position = \"$position\" and leagueID = $leagueID");
		$limit = getPositionLimit($position,"b", $leagueID) +getPositionLimit($position,"a", $leagueID);
		debug("Roster Slot calc: $rosterCount >= $limit");
		if ($rosterCount >= $limit) {
			debug("no slots, returning failure");
			$r["status"] = 0;
			$r["msg"] = "You have already drafted a full complient at this position.";
			return $r;
		}
		debug("passed validation, inserting");
		
		
		$overallPick = getSingleton("fch_draft","max(overallPick)"," where leagueID = $leagueID") + 1;

		$passedPick = getSingleton("fch_draft","overallPick", " WHERE leagueID = $leagueID and userID = $userID and status = \"P\" ORDER BY overallPick DESC Limit 0,1");
		if ($passedPick != "")
		{
			$overallPick = $passedPick;
			debug("Replacing Passed Pick at Overall pick # $passedPick");
			$replace = "  We have replaced your previously passed pick at overall pick # $overallPick.";
			
			executeGenericSQL("DELETE FROM fch_draft WHERE leagueID = $leagueID and userID = $userID and status = \"P\" and overallPick = $overallPick");
		}
		else {
			$withdrawnPick = getSingleton("fch_draft","overallPick", " WHERE leagueID = $leagueID and userID = $userID and status = \"W\" ORDER BY overallPick DESC Limit 0,1");

			if ($withdrawnPick != "")
			{
				$overallPick = $withdrawnPick;
				debug("Replaceing Withdrawn Pick at Overall pick # $withdrawnPick");
				$replace = "  We have replaced your previously withdrawn pick at overall pick # $overallPick.";
				$delete = "DELETE FROM fch_draft WHERE leagueID = $leagueID and userID = $userID and status = \"W\" and overallPick = $overallPick";
				debug("DELETE " + $delete);

				executeGenericSQL($delete);
				
			}
		}
	}
	$sql = <<<SQL
	INSERT INTO  fanta66_joomla.fch_draft (
		id ,
		leagueID ,
		userID ,
		playerID,
		position,
		dateStamp,
		overallPick,
		status

		)
		VALUES (
			NULL ,  $leagueID,  $userID, "$playerID",  "$position", DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR), $overallPick, "$status"
		);
		
SQL;
	debug($sql);
	$rosterID = executeGenericInsertSQL($sql);
	
	$r["status"] = 1;
	$r["msg"] = "Your transaction was successful. $replace";
	$r["id"] = $rosterID;
	setTransactionComplete($transactionID);
	return $r;
	
}

function isAvailable($playerID, $leagueID)
{
	$season = getSeason();
		$sql = <<<SQL
SELECT playerID,  IF( schoolID
IN (
SELECT schoolid
FROM v_blacklist
),  true,  false ) as blacklist,
if (playerID  in (select playerID from fch_rosters where leagueID = $leagueID and  releaseDate is  NULL and season = "$season" ),true,false) as taken
FROM  `fch_players` 
where 
playerID = "$playerID"
and season = "$season"
SQL;
	debug("isAvailable" . $sql);
	$con = initializeDB();
	$result = $con->query($sql);
	
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			if ($row['blacklist'] == 1)
				return 1;
			if ($row['taken'] == 1)
				return 2;
			return 0; // avail
		}
	}
	//return 3;
	closeDB($con);
	return $r;
}

function getSingleton($table, $field, $where)
{
	
	// DEBUGGING IN THIS METHOD BREAKS THE DRAFT FUNCTION.
	// Unexpected Token Illegal..
	
	$season = getSeason();
		$sql = <<<SQL
SELECT $field FROM  $table
 $where 
SQL;
	//debug("getSingleton: ". $sql);
	$con = initializeDB();
	$result = $con->query($sql);
	//echo $sql;
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {
			$r = $row[$field];
		}
	} else {
		return "";
	}

	closeDB($con);
	//debug("getSingleton returning $r");
	return $r;
}

function displayResults($r)
{

	if ($r["status"] == -1)
		return;
	if (intval($r["status"]) == 1)
		return "<div class = \"messageOk\">".$r["msg"]."</div>";
	else
		return "<div class = \"messageErr\">".$r["msg"]."</div>";

	//echo "<div class = \"messageErr\">".$r["msg"]."</div>";
}

function leagueHandler($postArray,$userID)
{
	$leagueID = insideLeagueHandler($postArray,$userID);
	debug("leagueID in leaguehandler" . $leagueID);
	if (!($leagueID >0))
		if ($postArray["action"] != "leagueEnroll")
			echo displayResults(getReturnCode(0,"Welcome! You need to join a league."));
	echo "\n\n<!-- ## FCH User ID: $userID -->";
	echo "\n\n<!-- ## FCH League ID: $leagueID -->";

	return $leagueID;
}

function leagueHandlerNoUI($postArray,$userID)
{
	return insideLeagueHandler($postArray,$userID);
	
}

function insideLeagueHandler($postArray, $userID)
{
	// If it is currently being reset

	$count = selectCount("fch_league_membership"," WHERE userID = $userID and status = \"active\"");
	debug("User is in this many leagues: $count");
	if ($count == 0)
		return -1;
	
	if ($postArray['action'] == "leagueChange")
	{	
		//debug("Changing League to " . $_POST['league']);
		$league = $postArray['league'];
		// Not gonna work, not in the header.
		debug("insideleaguehandler, postarray action is leaguechange");
		executeGenericSQL("UPDATE fch_user_preferences SET displayLeague = \"$league\" WHERE userID = $userID");
		
	}
	else
	{
		//$sql = 
		$league = getSingleton("fch_user_preferences","displayLeague"," WHERE userID = $userID");
		//echo "League from DB " . $league;
	}
	// BREAKS DRAFT
	//debug("League handler returning " .$league);
	return $league;
}


function getPlace($userID, $leagueID)
{
	debug("getPlace..."); 
	return  getStandings($userID,$leagueID,"place");
	
}


function getStandings($userID,$leagueID,$type)
{
	// If $type == "place" pass in a userID and get back a sorted associative array
	// if $type = "all" pass in null userID and get back an integer
	$season = getSeason();
	$sql = "select teamDisplayName, userID from fch_league_membership where leagueID = $leagueID and season = \"$season\" and status = \"active\"";
	$con = initializeDB();
	debug("getStandings: $sql");
	$result = $con->query($sql);
	$out = array();
	if ($result->num_rows > 0) {
		 // output data of each row	
		while($row = $result->fetch_assoc()) 
		{
			$totalPoints = getTotalPointsForUser($row['userID'], $leagueID);
			debug("TotalPoints returned is $totalPoints. Team is ".$row["teamDisplayName"]);
			$team = $row["teamDisplayName"];
			$thisUser = intval($row['userID']);
			debug("Setting resultsById User is $thisUser, points = $totalPoints"); 
			$resultsById[$thisUser] = $totalPoints;
			$out["$team"] = intval($totalPoints) ;
			debug("getstandings: : ". $out[$team] ." = $totalPoints");
		}
	}
	else
	{
		debug("Warning: getStandings returned no rows.");
	}
	closeDB($con);
	debug("Type is $type");
	if ($type == "all")
	{
		arsort($out,1);
		return ($out);
	}
	if ($type == "place")
	{
		debug("Returning resultsByID");
		$place = 1;
		arsort($resultsById,1);
		foreach ($resultsById as $key => $value)
		{
			debug("place = $place userID = $key value = $value");
			if ($key == $userID)
				return $place;
			else
				$place ++;
		}
		//print_r($resultsById);
		//return $resultsById[$userID];
	}
	//print_r($out);
	// can't just return arsort($out)
	
	//print_r($out);
	
	
}

function getStandingsAsTable($leagueID)
{
	$standings = getStandings(null,$leagueID,"all");
	//print_r($standings);
	$out = "<table class = \"standings\"><thead><tr><th scope=\"col\">Place</th><th>Points</th><th scope=\"col\">Team</th></tr></thead>";
	$x = 1;
	foreach ($standings as $key => $value) 
	{
		$out .= "<tr><td class = \"rowHead\">".$x."</td><TD>$value</TD><td>$key</td></tr>"; 

		$x ++;
	}
	$out .= "</table>";
	return $out;
	
}

function displayStandings($leagueID)
{
	echo getStandingsAsTable($leagueID);
}

function getStandingsOld($leagueID)
{
	$season = getSeason();
	$sql = <<<SQL
select   rosters.userID, 
(select teamDisplayName from fch_league_membership where season = "$season" and leagueID = $leagueID and userID = rosters.userID and status = \"active\") as teamDisplayName,
 sum(results.totalPoints) as t from fch_rosters rosters, fch_consolidated_results results
where rosters.acquireDate < results.dateStamp and (rosters.releaseDate > results.dateStamp or rosters.releaseDate is null)
and results.playerID = rosters.playerID 
and rosters.season = "$season" 
and results.season = "$season" 
and rosters.leagueID = $leagueID
group by rosters.userID
order by t desc
SQL;
	$x = 1;
	debug("getStandings: ". $sql);
	$con = initializeDB();
	$result = $con->query($sql);
	//echo $sql;
	$out = "<table><thead><tr><th scope=\"col\">Place</th><th>Points</th><th scope=\"col\">Team</th></tr></thead>";
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {	
			debug ($row["userID"] . "-" . $row["t"] . " " . $userID);
			$out .= "<tr><th scope=\"row\">".$x."</th><TD>".getTotalPointsForUser($userID,$leagueID)."</TD><td>".$row['teamDisplayName']."</td></tr>";
			$x ++;
		}
	} else {
		debug("No rows, returning null");
		return "No Standings to Display.";
	}
	$out .= "</table>";
	closeDB($con);
	debug("getStandings returning $out");
	return $out;

}

function getSuffix($number)
{
	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13)
   $abbreviation = $number. 'th';
else
   $abbreviation = $number. $ends[$number % 10];
return $abbreviation;

}

$whereForResults = null;

function getWhereForResults($gm,$league)
{
	if ($whereForResults == null)
		return buildWhereForResults($gm, $league);
	else
		return $whereForResults;
}

function buildWhereForResults($gm, $league)
{
	$season = getSeason();
	$sql = "SELECT playerID,releaseDate,acquireDate FROM fch_rosters WHERE season = \"$season\" and userID = $gm and leagueID = $league and statusCode = \"act\" ";
	$where = "";
	//debug("getWhereForResults: ". $sql);
	$con = initializeDB();
	$result = $con->query($sql);
	if ($result->num_rows > 0) {
    // output data of each row	
		while($row = $result->fetch_assoc()) {	
			//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
			if ($row["releaseDate"] == null)
				$row[releaseDate] = "2099-12-31 23:59:59";
			$where .= " OR (playerID = \"$row[playerID]\" AND dateStamp > '$row[acquireDate]' AND   dateStamp < '$row[releaseDate]')"; 
		}
	} else {
		return "";
	}
	

	closeDB($con);
	//debug("getWhereForResults returning $where");
	return ltrim($where," OR");
}

	function getTotalPointsForUser($userID,$leagueID)
	{
		$whereClause = getWhereForResults($userID,$leagueID);
		$season = getSeason();
		$sum = selectSum("fch_consolidated_results","totalPoints", " WHERE  season = \"$season\" and ($whereClause)");
		debug("SUM: " . $sum);
		return $sum;
	}
	
	$g_totalPointsForUserByPlayer = null;
	$g_totalPointsForUserByPlayerTimeWindow = null;
	$g_totalPointsForUserByPlayerUserID = null;
	
	function getTotalPointsForUserByPlayer($userID,$leagueID,$timeWindow)
	{
		global $g_totalPointsForUserByPlayer;
		global $g_totalPointsForUserByPlayerUserID;
		global $g_totalPointsForUserByPlayerTimeWindow;
		// Returns how many points returned by a player 
		if ($g_totalPointsForUserByPlayerUserID ==- $userID && (( $g_totalPointsForUserByPlayer != null) && ( $g_totalPointsForUserByPlayerTimeWindow == $timeWindow))){
			debug("getTotalPointsForUserByPlayer returning values stored from last time. Window = " . $timeWindow);
			return  $g_totalPointsForUserByPlayer;
		}
		$whereClause = getWhereForResults($userID,$leagueID);
		$season = getSeason();

		if (intval($timeWindow) == 0)
			$timeWindow = 7500;
		$sum = selectSumWithGroupBy("fch_consolidated_results ","totalPoints", " WHERE  dateStamp > DATE_ADD(NOW(), INTERVAL -$timeWindow DAY) $currentFilter AND  season = \"$season\" and ($whereClause)","playerID");
		//debug("SUM: " . $sum);
		$g_totalPointsForUserByPlayer = $sum;
		$g_totalPointsForUserByPlayerUserID = $userID;
		$g_totalPointsForUserByPlayerTimeWindow = $timeWindow;
		return $sum;
	}
	
	function getGameByGameResultsForLeague($leagueID,$start)
	{
		$season = getSeason();
		$sql = "select userID from fch_league_membership where season = \"$season\" and leagueID = $leagueID and status = \"active\" and userID in (SELECT DISTINCT userID FROM fch_rosters WHERE leagueID =$leagueID) ";
		$con = initializeDB();
		$result = $con->query($sql);
		$t = 1;
		
		/*
		* In this function since we don't have userID in results 
		* we have to get the whereclause and the userID as a column
		* then union it all together
		*/
		debug("<hr>getGameByGameResultsForLeague. Got $result->num_rows rows. sql: $sql");
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				$consolidatedWhere .= " SELECT $row[userID] as userID, dateStamp, DATE_FORMAT(dateStamp, '%d-%M-%Y') as dateOnly, playerID, totalPoints, dateLine,atVs, opponent, statLine, opponentDisplayName from fch_consolidated_results r WHERE (" . getWhereForResults($row[userID],$leagueID) . ")";
				$consolidatedWhere .= "     UNION ALL \n\n";
				$t ++;
			}
		}
		else {
			debug("Warning: Got zero rows.");
			return "";
		}
		// Take out the last 12 characters (" UNION ALL \n\n")

		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		// Take out the last five characters
		/*
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		$consolidatedWhere = substr_replace($consolidatedWhere, "", -1);
		*/
		$consolidatedWhere = str_replace("playerID", "r.playerID", $consolidatedWhere);
		$consolidatedWhere = str_replace("totalPoints", "r.totalPoints", $consolidatedWhere);
		$consolidatedWhere = str_replace("dateLine", "r.dateLine", $consolidatedWhere);
		$consolidatedWhere = str_replace("atVs", "r.atVs", $consolidatedWhere);
		$consolidatedWhere = str_replace("opponent", "r.opponent", $consolidatedWhere);
		//$consolidatedWhere = str_replace("opponentDisplayName", "r.opponentDisplayName", $consolidatedWhere);
		$consolidatedWhere = str_replace("statLine", "r.statLine", $consolidatedWhere);
		$consolidatedWhere = str_replace("dateStamp", "r.dateStamp", $consolidatedWhere);
		
		//debug($consolidatedWhere);
		
		
		$sql = "SELECT  re.userID, re.playerID, re.dateStamp, re.dateOnly, re.totalPoints, re.dateLine, re.atVs, re.opponent, re.statLine, re.opponentDisplayName from ($consolidatedWhere) re";
		
		//$where = getWhereForResults($userID,$leagueID);
		//$sql .= $consolidatedWhere;
		$sql .= " ORDER BY re.dateOnly desc , CAST(re.totalPoints AS SIGNED)  desc LIMIT $start," . intval(intval($start)+50);
		$players = getPlayerDisplayNames();
		$schools = getSchoolDisplayNames();
		$teamDisplayNames = getTeamDisplayNames($leagueID);
		
		$con = initializeDB();
		$result = $con->query($sql);
		debug("<hr>getGameByGameResultsForLeague. Got $result->num_rows rows. sql: $sql");
		$out = "<TABLE class = \"gameByGame\" width = 100%><THEAD><TH>GM</th><TH>Player</th><th>FCH Points</th><th>Details</th><th>Date</th><th>Opponent</th></thead>\n";
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				debug("Processing row $row[playerID], $row[userID]");
				$out .= "<TR><td class = \"rowHead\">" . $teamDisplayNames[intval($row[userID])] . "</TD>";
				$out .= "<TD>" . $players[$row[playerID]] . "</td>";
				$out .= "<TD>" . $row[totalPoints] . "</td>";
				$out .= "<TD>" . $row[statLine] . "</td>";
				$out .= "<TD>" . $row[dateLine] . "</td>";				
				$out .= "<TD>" . $row[atVs] . " " . $row[opponentDisplayName] . "</td>";
				$out .= "</tr>";
				//$out .= "<TR><TD>" . $row[playerID] . "</td>";

			}
		}
		else {
			return "";
		}
		$totalRecords = selectCount("($consolidatedWhere) re", ""); 
		$out .= "</table>$totalRecords results. Showing results ". intval($start+1) ." through ";
		if ($start > 0)
		{
			$bStart = $start - 50;
			$form .= "<form name = \"submit_pagination_b\" method = \"POST\"><input name = \"paginationStart\" type  = \"hidden\" value = \"$bStart\"><input name = \"type\" type  = \"hidden\" value = \"backward\">";
			$form .= "<button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_pagination_b\" onclick=\"javascript:submitForm('submit_pagination_b','button_submit_pagination_b');\">&lt; &lt; Last 50</button>";
			$form .= "</form>";
		}
		if ($start + 50 < $totalRecords)
		{
			$out .= intval($start + 50);
			$start += 50;
			$form .= "<form name = \"submit_pagination_a\" method = \"POST\"><input name = \"paginationStart\" type  = \"hidden\" value = \"$start\"><input name = \"type\" type  = \"hidden\" value = \"forward\">";
			$form .= "<button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_pagination_a\" onclick=\"javascript:submitForm('submit_pagination_a','button_submit_pagination_a');\">Next 50 &gt;&gt;</button>";
			$form .= "</form>";
		}
		else
		{
			$out .= intval($totalRecords);
		}
		$out .= ".  " . $form;

		closeDB($con);
		return $out;
	}
	
	function getGameByGameResults($userID,$leagueID,$start)
	{
		$season = getSeason();
		$sql = "SELECT playerID, totalPoints, dateLine, atVs, opponent, statLine, opponentDisplayName, DATE_FORMAT(dateStamp, '%d-%M-%Y') as dateOnly from fch_consolidated_results WHERE ";
		$where = getWhereForResults($userID,$leagueID);
		$sql .= $where;
		$sql .= " ORDER BY dateOnly desc LIMIT $start," . intval(intval($start)+50);
		$players = getPlayerDisplayNames();
		$schools = getSchoolDisplayNames();

		$con = initializeDB();
		$result = $con->query($sql);
		debug("getGameByGameResults $sql");
		$out = "<TABLE class = \"gameByGame\" width = 100%><THEAD><TH>Player</th><th>FCH Points</th><th>Details</th><th>Date</th><th>Opponent</th></thead>\n";
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				debug($row[playerID], $row[totalPoints]);
				$out .= "<TR><TD><B>" . $players[$row[playerID]] . "</B></td>";
				$out .= "<TD>" . $row[totalPoints] . "</td>";
				$out .= "<TD>" . $row[statLine] . "</td>";
				$out .= "<TD>" . $row[dateLine] . "</td>";				
				$out .= "<TD>" . $row[atVs] . " " . $row[opponentDisplayName] . "</td>";
				$out .= "</tr>";
				//$out .= "<TR><TD>" . $row[playerID] . "</td>";

			}
		}
		else {
			return "";
		}
		$totalRecords = selectCount("fch_consolidated_results", " WHERE " . $where); 
		$out .= "</table>$totalRecords results. Showing results ". intval($start+1) ." through ";
		if ($start > 0)
		{
			$bStart = $start - 50;
			$form .= "<form name = \"submit_pagination_b\" method = \"POST\"><input name = \"paginationStart\" type  = \"hidden\" value = \"$bStart\"><input name = \"type\" type  = \"hidden\" value = \"backward\">";
			$form .= "<button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_pagination_b\" onclick=\"javascript:submitForm('submit_pagination_b','button_submit_pagination_b');\">&lt; &lt; Last 50</button>";
			$form .= "</form>";
		}
		if ($start + 50 < $totalRecords)
		{
			$out .= intval($start + 50);
			$start += 50;
			$form .= "<form name = \"submit_pagination_a\" method = \"POST\"><input type  = \"hidden\"  name = \"paginationStart\" value = \"$start\">";
			$form .= "<button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_pagination_a\" onclick=\"javascript:submitForm('submit_pagination_a','button_submit_pagination_a');\">Next 50 &gt;&gt;</button>";
			$form .= "</form>";
		}
		else
		{
			$out .= intval($totalRecords);
		}
		$out .= ".  " . $form;

		closeDB($con);
		return $out;
	}
	
	$g_totalPointsOverall = null;
	$g_totalPointsOverallUserID = null;
	$g_totalPointsOverallTimeWindow = null;
	
	function getTotalPointsOverall($userID,$leagueID,$timeWindow)
	{
		// Returns total sum of points earned this season by a player
		global $g_totalPointsOverall;
		global $g_totalPointsOverallTimeWindow;
		global $g_totalPointsOverallUserID;
		if ($timeWindow = $g_totalPointsOverallTimeWindow && ( $g_totalPointsOverall != null && $g_totalPointsOverallUserID == $userID))
			return $g_totalPointsOverall;
		
		$season = getSeason();
		if (intVal($timeWindow) == 0)
			$timeWindow = 99999;
		$sum = selectSumWithGroupBy("fch_consolidated_results r","r.totalPoints", " WHERE  r.dateStamp > DATE_ADD(NOW(), INTERVAL -$timeWindow DAY) AND  r.season = \"$season\" ","r.playerID");
		//debug("SUM: " . $sum);
		$g_totalPointsOverall = $sum;
		$g_totalPointsOverallTimeWindow = $timeWindow;
		$g_totalPointsOverallUserID = $userID;
		return $sum;
	}
	
	$g_lastGameHash = null;
	
	function getLastGameHash()
	{
		global $g_lastGameHash;
		if (  $g_lastGameHash!= null)
			return  $g_lastGameHash;
		
		$season = getSeason();
		$schoolNames = getSchoolDisplayNames();
		
		$sql = "SELECT r.totalPoints as totalPoints, r.opponentDisplayName as opponentDisplayName, r.playerDisplayName as playerDisplayName, r.statLine as statLine, r.dateLine as dateLine, r.atVs as atVs, r.dateStamp as dateStamp, r.playerID as playerID
		FROM (

		SELECT MAX( dateStamp ) AS dateStamp, playerID
		FROM fch_consolidated_results re
		WHERE season =  \"$season\"
		GROUP BY playerID
		)re
		INNER JOIN fch_consolidated_results r
		WHERE re.dateStamp = r.dateStamp
		AND re.playerID = r.playerID";
		$con = initializeDB();
		$result = $con->query($sql);
		debug("getLastGameHash, returned $result->num_rows rows: $sql");

		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//print_r($row);
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[playerID];
				/*
				if ($row[atVs] == "at")
					$atVs = "at";
				else
					$atVs = "vs";
				*/
				if ($row[totalPoints] == 1)
					$s = "";
				else
					$s = "s";
				$mostRecentResults[$key] = $row[statLine] . ", " . $row[totalPoints] . " FCH pt" . $s . " - " . $row[dateLine] . " " . $row[atVs] . " " . $row[opponentDisplayName];
				$mostRecentResultsNoPoints[$key] =  $row[dateLine] . " " . $row[atVs] . " " . $row[opponentDisplayName];
				$mostRecentDate[$key] = $row[dateStamp];
				//print_r ($mostRecentDate);
				debug("LAST SCORE Key ->" . $key . "<-" . $row[statLine] . " - " . $row[dateLine] . " " . $atVs . " " . $row[opponentDisplayName]);
			}
		}
		else {
			return "";
		}
		closeDB($con);

		
		// repurpose from getLastGame .
		// need to find the last one in an aggregate function, then join the rest of the data back in
		$sql = "
		SELECT p.playerID, td.maxDate as maxDate, td.maxDateFormatted as maxDateFormatted, td.dateStamp as dateStam, td.schoolID as schoolID, td.atVs as atVs, td.opponentID as opponentID
			FROM (

			SELECT s.dateStamp, s.schoolID, s.atVs, s.opponent AS opponentID, DATE_FORMAT( (
			s.dateStamp
			),  \"%a %m/%d\" ) AS maxDateFormatted, gb.dateStamp AS maxDate
			FROM (

			SELECT MAX( dateStamp ) AS dateStamp, schoolID AS schoolID
			FROM fch_schedule_byteam
			WHERE dateStamp < DATE_ADD( NOW( ) , INTERVAL +3 HOUR ) 
			GROUP BY schoolID
			)gb
			INNER JOIN fch_schedule_byteam s ON gb.dateStamp = s.dateStamp
			AND gb.schoolID = s.schoolID
			ORDER BY  `s`.`dateStamp` ASC
			)td
			LEFT JOIN fch_players p ON p.schoolID = td.schoolID
			";
		$con = initializeDB();
		$result = $con->query($sql);
		debug("getLastGameHash, 2nd side, $result->num_rows rows returned: $sql");

		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[playerID];
				
				debug("MaxDate = " . $row["maxDate"] ." MostRecentDate[key] =". $mostRecentDate[$key] . " key = $key");
				if ($row["maxDate"] == $mostRecentDate[$key])
					$lastResult[$row["playerID"]] = $mostRecentResults[$key];
				else
					$lastResult[$row["playerID"]] = "No points, " .$row["maxDateFormatted"] . ", ". $row[atVs] . " " .$schoolNames[$row["opponentID"]];
				
				
				//debug("FINAL Key ->" . $key . "<-" . $row[statLine] . " School ID = " . $row["schoolID"] . " - " . $row[dateLine] . " " . $atVs . " " . $row[opponentDisplayName] . " Wrote output line " . $lastResult[$row["playerID"]] . " by comparing " . $row["lastDate"] . " vs " . $mostRecentDate[$key]);
			}
		}
		else {
			return "";
		}
		closeDB($con);
		
		return $lastResult;
	}

$g_teamDisplayNames= null;
function getTeamDisplayNames($leagueID)
{

	global $g_teamDisplayNames;
	if ($g_teamDisplayNames != null)
		return  $g_teamDisplayNames;
	$season = getSeason();
	$sql = "
	SELECT teamDisplayName,userID
	FROM fch_league_membership
	WHERE season = \"$season\" and leagueID = $leagueID and status = \"active\"";
	$con = initializeDB();
	$result = $con->query($sql);
	debug("getTeamDisplayNames, got $result->num_rows rows and sql=$sql");
	if ($result->num_rows > 0) 
	{
		// output data of each row	
		while($row = $result->fetch_assoc()) 
		{	
			$out[$row[userID]] = $row[teamDisplayName];
		}
	}
	else {
		return "";
	}
	closeDB($con);
	$g_teamDisplayNames = $out;
	return $out;
	
}	
	$g_schoolDisplayNames = null;
	function getSchoolDisplayNames()
	{
		global $g_schoolDisplayNames;
		if ($g_schoolDisplayNames != null)
			return $g_schoolDisplayNames;
		
		$season = getSeason();
		$sql = "
		SELECT school_id,school_shortname
		FROM fch_schools";
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[school_id];
				$out["$key"] = $row[school_shortname] ;
				//debug( $row[firstName] . " " . $row[lastName] . ", " . $row[schoolDisplayName]);
			}
		}
		else {
			return "";
		}
		closeDB($con);
		 $g_schoolDisplayNames = $out;
		return $out;
	}
		
	$g_playerDisplayNames = null;	
	
	function getPlayerDisplayNames()
	{
		global $g_playerDisplayNames;
		if ($g_playerDisplayNames != null)
			return $g_playerDisplayNames;
		
		$season = getSeason();
		$sql = "
		SELECT playerID, firstName, lastName, schoolDisplayName
		FROM fch_players
		WHERE season =  \"$season\"";
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[playerID];
				$out["$key"] = $row[firstName] . " " . $row[lastName] . ", " . $row[schoolDisplayName];
				//debug( $row[firstName] . " " . $row[lastName] . ", " . $row[schoolDisplayName]);
			}
		}
		else {
			return "";
		}
		closeDB($con);
		$g_playerDisplayNames = $out;
		return $out;
	}
		$g_gamesPlayedByPlayer = null;
	function getGamesPlayedByPlayer()
	{ 
	global $g_gamesPlayedByPlayer;
		if ($g_gamesPlayedByPlayer != null)
			return  $g_gamesPlayedByPlayer;
		$season = getSeason();
		$sql = "select count(*) as count, s.atVs as atVs, p.playerID as playerID, p.lastName as lastName, p.firstName as firstName, s.dateStamp as dateStamp, date_format(s.dateStamp, \"%a %b %d\") as dateString, s.schoolID as schoolID, s.opponent as opponent
			from 
			(select dateStamp as dateStamp, visitor as schoolID, home as opponent, \"at\" as atVs from fch_schedule
			union
			select dateStamp as dateStamp, home as schoolID, visitor as opponent, \"vs\" as atVs from fch_schedule) s,
			fch_players p
			where p.schoolID = s.schoolID
			and dateStamp < date_add(NOW(), interval 3 hour)
			AND s.schoolID !=  \"unkn\"
			AND s.opponent !=  \"unkn\"
			GROUP BY playerID
			
			";
		debug("getNextGame $sql");
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				
				
				$out[$row[playerID]] = intval($row[count]);
				//debug("Key pair. ".$row[playerID]." = $row[dateString]"  );
			}
		}
		else {
			debug("Warning, got zero rows back");
			return -1;
		}
		$g_gamesPlayedByPlayer = $out;
		closeDB($con);
		return $out;
	}
	
	$g_gamesPlayedBySchool = null;

	function getGamesPlayedBySchool()
	{
		global $g_gamesPlayedBySchool;
		if ( $g_gamesPlayedBySchool != null)
			return  $g_gamesPlayedBySchool;
	
		$sql = "
		SELECT COUNT(*) , visitor
		FROM fch_schedule
		WHERE dateStamp < date_add(NOW( ), interval + 3 hour) 
		GROUP BY visitor";
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[visitor];
				$out["$key"] = intval($row["COUNT(*)"] );
			}
		}
		else {
			return "";
		}
		$sql = "
		SELECT COUNT(*) , home
		FROM fch_schedule
		WHERE dateStamp < date_add(NOW( ), interval + 3 hour) 
		GROUP BY home";
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[home];
				$out["$key"] += intval($row["COUNT(*)"]) ;
			}
		}
		else {
			return "";
		}
		
		closeDB($con);
		 $g_gamesPlayedBySchool = $out;
		return $out;
	}
	
	$g_positionHash = null;

	function getPositionHash()
	{
		global $g_positionHash;
		if ($g_positionHash != null)
			return  $g_positionHash;
		$season = getSeason();
		$sql = "
		SELECT playerID, position
		FROM fch_players
		WHERE season = \"$season\"";
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ($row[acquireDate] . "-" . $row["t"] . " " . $userID);
				$key = $row[playerID];
				$out["$key"] = $row[position];
				//debug($out["$key"] .$row[position]);
			}
		}
		else {
			return "";
		}
		//debug(var_dump($out));
		closeDB($con);
		$g_positionHash = $out;
		return $out;
	}
	
	$g_currentRosters = null;
	$g_currentRostersUserID = null;
	function getCurrentRoster($userID, $leagueID)
	{
		global $g_currentRostersUserID ;
		global $g_currentRosters ;
		if ($g_currentRosters != null && $g_currentRostersUserID == $userID)
			return  $g_currentRosters;
		
		$season = getSeason();
		$sql = "
		SELECT p.playerID, r.statusCode, p.position
		FROM fch_rosters r, fch_players p
		WHERE r.releaseDate IS NULL 
		AND p.playerID = r.playerID
		AND r.season =  \"$season\"
		AND r.userID =$userID
		AND r.leagueID =$leagueID
		ORDER BY p.lastName";
		debug("getCurrentRoster $sql");
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				//debug ("Acquire Date: " . $row[acquireDate] . "-" . $row["playerID"] . " " . $userID);
				$key = $row[playerID];
				$out[$key] = $row[statusCode];
				//debug("Key pair. key = $key value = " .$out[$key]  );
			}
		}
		else {
			debug("Warning, got zero rows back");
			return -1;
		}
		
		closeDB($con);
		 $g_currentRosters = $out;
		 $g_currentRostersUserID = $userID;
		return $out;
	}
	
	function renderCurrentRoster($userID, $leagueID)
	{
		$roster = getCurrentRoster($userID, $leagueID);
		if ($roster == -1)
			return "No players on roster.";
		$positions = getPositionHash();
		$season = getSeason();
		$displayNames = getPlayerDisplayNames();
		$out .= "<table class = \"rosterAll\" width = 100%>";
		$out .= "<THEAD><TH>Active Roster</TH><TH>Reserve</TH></THEAD>\n";
		foreach ($roster as $key => $value) 
		{
			debug("Processing $key $value");
			if ($value == "act")
			{
				$act .= $positions[$key] . " " . $displayNames[$key]."<br/>";
			}
			else if ($value == "ben")
			{
				$ben .= $positions[$key] . " " . $displayNames[$key]."<br/>";
			}
		}
		$out .= "<TR><TD>$act</td><td>$ben</td></tr></table>";
		return $out;
	}
	
	function getCurrentRosterByActiveReserve($userID, $leagueID, $side)
	{
		
		$season = getSeason();
		$sql = "
		SELECT playerID, statusCode
		FROM fch_rosters
		WHERE releaseDate IS NULL 
		AND statusCode = \"$side\"
		AND season =  \"$season\"
		AND userID =$userID
		AND leagueID =$leagueID
		ORDER BY statusCode";
		debug($sql);
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				debug ($row[acquireDate] . "-" . $row["playerID"] . " " . $userID);
				$key = $row[playerID];
				$out[$key] = $row[statusCode];
				debug("Key pair. key = $key value = " .$out[$key]  );
			}
		}
		else {
			debug("Warning, got zero rows back");
			return -1;
		}
		
		closeDB($con);
		$g_currentRosters = $out;
		return $out;
	}
	
	$g_nextGameBySchool = null;
	function getNextGameBySchool()
	{
		global $g_nextGameBySchool;
		if ($g_nextGameBySchool != null)
			return $g_nextGameBySchool;
		$schoolNames = getSchoolDisplayNames();
		$season = getSeason();
		$sql = "
		SELECT s.dateStamp, s.schoolID, s.atVs, s.opponent,date_format((s.dateStamp),\"%a %m/%d\") as dateString
			FROM (

			SELECT MIN( dateStamp ) AS dateStamp, schoolID AS schoolID, atVs as atVs, opponent as opponent 
			FROM fch_schedule_byteam
			WHERE dateStamp > date_add(NOW(), interval + 3 hour) 
			GROUP BY schoolID
			)gb 
			INNER JOIN fch_schedule_byteam s ON gb.dateStamp = s.dateStamp
			AND gb.schoolID = s.schoolID
			ORDER BY  `s`.`dateStamp` ASC 
			";
		debug("getNextGameBySchool $sql");
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
		// 
				//debug("getNextGameBySchool " . $row[schoolID] . " " . $row[dateString]);
				$out[$row[schoolID]] = $row[dateString]. ", " . $row[atVs] . " " . $schoolNames[$row[opponent]];
			}
		}
		else {
			debug("Warning, got zero rows back");
			return -1;
		}
		
		closeDB($con);
		$g_nextGameBySchool = $out;
		return $out;
	}
	
	$g_nextGame = null;
	function getNextGame()	
	{ 
		global $g_nextGame;
		if ($g_nextGame != null)
			return $g_nextGame;
		$schoolNames = getSchoolDisplayNames(); 
		$nextGameBySchool = getNextGameBySchool();
		$season = getSeason();
		$sql = "select  playerID, schoolID from fch_players where season = \"$season\"";
		debug("getNextGame $sql");
		$con = initializeDB();
		$result = $con->query($sql);
		if ($result->num_rows > 0) 
		{
			// output data of each row	
			while($row = $result->fetch_assoc()) 
			{	
				
				
				$out[$row[playerID]] = $nextGameBySchool[$row[schoolID]];
				//debug("Key pair. ".$row[playerID]." = $row[dateString]"  );
			}
		}
		else {
			debug("Warning, got zero rows back");
			return -1;
		}
		
		closeDB($con);
		$g_nextGame = null;
		return $out;
	}
	
	////////////////////////////////////////////////
	////////////////
	// DRAFT
	
	function getDraftOrder($leagueID) {
	$season = getSeason();
	$sql = "SELECT m.teamDisplayName, m.draftOrder, l.display_name
	FROM  `fch_league_membership` m, fch_leagues l
	WHERE leagueID = $leagueID
	AND m.leagueID = l.id
	AND m.status = \"active\"
	AND l.season =  \"$season\"
	ORDER BY m.draftOrder";
	$con = initializeDB();
	$result = $con->query($sql);
	while($row = $result->fetch_assoc()) {
		$draftOrder .= $row[draftOrder] . ". " . $row[teamDisplayName] . "\n";
	}
	
	debug( "Draft Order " . $draftOrder);
	
	$con->close();
	return $draftOrder;
		
	}
	

	function retrieveLastPick($leagueID)
      {
      	$sql = <<<SQL
      SELECT d.playerID, concat(left(p.firstName,1), ". " , p.lastName, ", ", p.position) as displayName, d.overallPick as overallPick, s.school_shortname as school, lm.teamDisplayName as team
      from fch_draft d, fch_players p, fch_schools s, fch_league_membership lm
      where d.playerID = p.playerID
      and lm.userID = d.userID
	  and lm.status = "active"
      and lm.leagueID = d.leagueID
      and p.schoolID = s.school_id
      and d.leagueID = $leagueID
      ORDER BY overallPick desc
      limit 0,1
SQL;
      	$x = 1;
      	$con = initializeDB();
      	$result = $con->query($sql);
      	if ($result->num_rows > 0) {
          // output data of each row	
      		while($row = $result->fetch_assoc()) {
      			
      			$r = "<li id= \"".$row['overallPick']."\"> ".$row['team']."<br/>&raquo; ".$row['displayName'].", " . $row['school'] . "</li>"; 
      			$x ++;
      		}
      	closeDB($con);
      	}
      	return $r;
      }	
      
	  function getFullDraftList($leagueID, $userID, $type) {
		$season = getSeason();

		if ($type == "all")
			$limit = " asc ";
		else
			$limit = " desc LIMIT 0,1";
	
		// REMOVED and p.schoolID = s.school_id 
		$sql = <<<SQL
         SELECT d.status, d.userID, d.playerID, if (d.status = "W","Withdrawn Pick",if(d.status = "P","Passed",concat(left(p.firstName,1), ". " , p.lastName, ", ", p.position, ", " , s.school_shortname))) as displayName, d.overallPick as overallPick, s.school_shortname as school, lm.teamDisplayName as team
         from fch_draft d, fch_players p, fch_schools s, fch_league_membership lm
         where (d.playerID = p.playerID
         and lm.userID = d.userID
         and lm.leagueID = d.leagueID
		 and lm.status = "active"
         and p.schoolID = s.school_id
         and d.leagueID = $leagueID)
         ORDER BY overallPick 
		 $limit
SQL;

$sql = <<<SQL
SELECT d.status, d.userID, d.playerID, IF( d.status =  "W",  "Withdrawn Pick", IF( d.status =  "P",  "Passed", CONCAT( LEFT( p.firstName, 1 ) ,  ". ", p.lastName,  ", ", p.position,  ", ", s.school_shortname ) ) ) AS displayName, d.overallPick AS overallPick, s.school_shortname AS school, lm.teamDisplayName AS team
FROM fch_draft d, fch_schools s, fch_league_membership lm, (

SELECT playerID, lastName, firstName, schoolID, position
FROM fch_players
UNION 
SELECT playerID, last, 
FIRST , schoolID, position
FROM fch_draft_customplayer
)p
WHERE (
d.playerID = p.playerID
AND lm.userID = d.userID
AND lm.leagueID = d.leagueID
AND lm.status = "active"
AND p.schoolID = s.school_id
AND d.leagueID =$leagueID
)
ORDER BY  `d`.`overallPick` asc 
SQL;

		// Warning may break stuff:
		//debug("getFullDraftList: " . $sql);
         	$x = 1;
         	$con = initializeDB();
         	$result = $con->query($sql);
         	$out .= "<ol  id = \"runningDraftList\">";   // reversed flag works in this ol but IE no bueno
         	if ($result->num_rows > 0) {
             // output data of each row	
         		while($row = $result->fetch_assoc()) {
         			
				if (($row["userID"] == $userID) && $row["status"] != "W")
					{
						$withdrawButton = "<button type = \"button\" onClick=\"javascript:rescindPick(".$row['overallPick'].",$leagueID)\">Withdraw</button>";
						$emStart = "<B><Font Color = \"red\">";
						$emEnd = "</font></b>";
					}
					else
					{
						$emStart = "";
						$emEnd = "";
						$withdrawButton = "";
					}
       			$out .= "<li id= \"".$row['overallPick']."\"> $emStart".$row['team']."<br/>&raquo; ".$row['displayName']."$emEnd</li>$withdrawButton"; 

					$x ++;
         		}
         	
         	}
			$out .= "</ol>";
         	closeDB($con);
		return $out;
	  }
	  function getLastPickNumber($leagueID)
	  {
		return intval(getSingleton("fch_draft","max(overallPick)"," WHERE leagueID = $leagueID"));
	  }
	  
	  
	  function nextDraftOrder($leagueID)
	  {
		$picksSoFar = intval(selectCount("fch_draft"," WHERE leagueID = $leagueID"));
		$totalPlayers = intval(selectCount("fch_league_membership", " WHERE leagueID = $leagueID and status = \"active\""));
		$max = getLastPickNumber($leagueID) + 1;// because we want the NEXT player
		$season = getSeason();
		debug($picksSoFar . " " . $totalPlayers . " " . $max );
		if (!($totalPlayers > 0))
		{
			displayResults(getReturnCode(0,"Error: No players in this league."));
		}
		$quotient = intval(($max -1) / $totalPlayers);
		debug("quotient $quotient");
		$mod = (($max -1) % $totalPlayers) +1;
		debug("mod $mod");
		if ($quotient % 2 == 0) // if it is even
		{
			$r = intval($mod);
			debug("$r = r");
		}
		else
		{
			$r = intval($totalPlayers - $mod + 1);
			debug("$r = r");
		}
		return $r;
	  }
		
	function onTheClock($leagueID) {
		$r["draftOrder"] = nextDraftOrder($leagueID);
		debug("draft order = ".$r["draftOrder"]);
		$season = getSeason();
		$next = getSingleton("fch_league_membership","teamDisplayName"," WHERE leagueID = $leagueID and status = \"active\" and season = \"$season\" and draftOrder = " . $r["draftOrder"]);
		$r["gmName"] = $next;
		debug("draft order = ".$next);

		$next = getSingleton("fch_league_membership","userID"," WHERE leagueID = $leagueID and status = \"active\" and season = \"$season\" and draftOrder = " . $r["draftOrder"]);
		$r["userID"] = $next;
		$next = getSingleton("fch_leagues","draftStatus"," WHERE id = $leagueID and season = \"$season\"");
		$r["draftStatus"] = $next;
		// BREAKS DRAFT
		//debug("On the clock ID: " . $r["userID"] . " Team = " . $r["gmName"]);
		
		$r["adminID"] = getSingleton("fch_leagues","admin_user"," WHERE id = $leagueID and season = \"$season\"");
		if (intval($r["adminID"]) == intval(getUserID()))
			$r["isAdmin"] = true;
		else
			$r["isAdmin"] = false;
		$r["lastPickDisplay"] =getFullDraftList($leagueID, $userID, "last");
		$r["thisUserID"] = getUserID();
		$r["overallPick"] = getSingleton("fch_draft","max(overallPick)"," where leagueID = $leagueID");
		if ($r["overallPick"] == "")
			$r["overallPick"] = 1;
		return $r;
	  }
	  
	  function userPickingNext($leagueID)
	  {
		$next =   nextDraftOrder($leagueID);
		return intval(getSingleton("fch_league_membership","userID"," WHERE leagueID = $leagueID and status = \"active\" and season = \"$season\" and draftOrder = " . $next));
	  }
	  
	  function getBestAvailable($leagueID, $position, $limit)
	  {
		 debug("GET BEST AVAILABLE");
		$userID = getUserID();
		$myPick = false;
		$transactionID = getNextTransactionID($userID, $leagueID);
		if (intval($userID) == intval(userPickingNext($leagueID)))
		{
			$myPick = true;
		}
		else
		{
			$passedPick = getSingleton("fch_draft","overallPick", " WHERE leagueID = $leagueID and userID = $userID and status = \"P\" ORDER BY overallPick DESC Limit 0,1");
			if ($passedPick != "")
			{
				$myPick = true;
			}
			else {
				$withdrawnPick = getSingleton("fch_draft","overallPick", " WHERE leagueID = $leagueID and userID = $userID and status = \"W\" ORDER BY overallPick DESC Limit 0,1");
				if ($withdrawnPick != "")
					{
						$myPick = true;
					}
			}
		}
		$pickButton = "";
		
		$out .= "<table><thead><th>Player</th><th>Pts Last Year</th><th>Points Per Game Last Year</th><th>Action</th></thead>"; 
		$lastSeason = getLastSeason();

		$sql = "
		select p.position as position, gamesPlayed, sum(r.totalPoints) as totalPoints, sum(r.totalPoints)/gamesPlayed as ppg, r.playerID as playerID, concat(left(p.firstName,1),\". \",  p.lastName, \", \", s.school_shortname) as displayName from fch_consolidated_results r, fch_players p, fch_schools s, (select schoolID, count(schoolID) as gamesPlayed from (
		select dateStamp, home as schoolID from fch_schedule_lastseason
		union
		select dateStamp, visitor as schoolID from fch_schedule_lastseason) as sched
		where sched.dateStamp < date_add(now(),interval + 3 hour)
		group by schoolID) as gp

		where r.playerID = p.playerID
		and p.schoolID  = s.school_id
		and r.season = \"$lastSeason\"
		and gp.schoolID = p.schoolID
		and r.playerID not in (select playerID from fch_draft where leagueID = $leagueID)
		and r.playerID not in (select playerID from fch_draft_customplayer where leagueID = $leagueID)
		and r.playerID not in (select playerID from fch_players_leftearly)

		and p.position = \"$position\"
		group by r.playerID
		order by sum(r.totalPoints) desc
		limit 0,$limit
		";
		
		$con = initializeDB();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
             // output data of each row	
         	while($row = $result->fetch_assoc()) 
			{
				if ($myPick)
					$pickButton .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".$transactionID."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Pick &gt;&gt;</button> </form>&nbsp;\n";
				else
					$pickButton = "";
				$out .= "<tr><td>" . $row["displayName"] . "</td><TD>" . $row["totalPoints"] . "</td><td>" . $row["ppg"] . "</td></tr>\n";
			}
		}
		$out .= "</tr></table>";
		closeDB($con);
		return $out;
		
	  }
	  
	  function closeDraft($leagueID)
	  {
		debug("Entering CloseDraft.");
		$season = getSeason();
		executeGenericSQL("DELETE FROM fch_rosters WHERE leagueID = $leagueID and season = \"$season\"");
		$positionLimits = getAllPositionLimits($leagueID);
		$sql = "SELECT userID, playerID, position FROM fch_draft WHERE leagueID = $leagueID and status <> \"W\" and status <> \"C\" and status <> \"P\"  ORDER BY overallPick DESC ";
		debug("CloseDraft: $sql");
		// acquirePlayer($userID,$leagueID,$playerID,$activeOrBench, $position,$transactionID)
		$con = initializeDB();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
             // output data of each row	
         	while($row = $result->fetch_assoc()) 
			{
				$userID = $row["userID"];
				$x = selectCount("fch_rosters"," WHERE userID = $userID and leagueID = $leagueID and season = \"$season\" and position  = \"" . $row["position"] . "\"");
				debug("<B>x=" . $x . " Limit " . $positionLimits["f_a"]."</b>");
				if ($row["position"] == "F")
				{
					if ($x < $positionLimits["f_a"])
						$dest = "act";
					else
						$dest = "ben";
					debug($row["playerID"] . " F, Dest = $dest ");
				}
				if ($row["position"] == "D")
				{
					if ($x < $positionLimits["d_a"])
						$dest = "act";
					else
						$dest = "ben";
					debug($row["playerID"] . " D, Dest = $dest ");

				}
				if ($row["position"] == "G")
				{
					if ($x < $positionLimits["g_a"])
						$dest = "act";
					else
						$dest = "ben";
					debug($row["playerID"] . " G, Dest = $dest ");

				}
				debug("CloseDraft: acquire: " . $row["userID"] . $leagueID." ".$row["playerID"]." " .$dest. " ".$row["position"]);
				acquirePlayer($row["userID"],$leagueID,$row["playerID"],$dest, $row["position"],getNextTransactionID($userID, $leagueID));
			}
		}
		closeDB($con);
	  }

	
	function generateUserPreferences($userID){
	debug("<B>GenerateUserPreferences</b>");
	$season = getSeason();
	$prefCount = intval(selectCount("fch_user_preferences"," WHERE userID = $userID and season = \"$season\""));
	// 1. Is there a preference row at all?
	if ($prefCount == 0)
	{
		debug("Creating preference row , ");
		$key = rand (0, 2147483647);
		$sql = "INSERT INTO  `fanta66_joomla`.`fch_user_preferences` (`id` ,`userID` ,`displayLeague`, `secretKey`) VALUES (NULL ,  $userID, NULL, $key);";
		debug($sql);
		executeGenericInsertSQL($sql);
	}
	// 2. Is there a league in the preference row?
	$displayLeague = getSingleton("fch_user_preferences","displayLeague"," WHERE userID = $userID");
	debug("Display League is ($displayLeague)");
	if ( $displayLeague == "")
	{
		debug("No display league set.");
		// If not, see if he has a league at all.
		$l = getSingleton("fch_league_membership","leagueID"," WHERE userID = $userID and status = \"active\" and season = \"$season\" ORDER BY id desc LIMIT 0,1");
		// If so, set it.
		if ($l != "") {
			debug("Setting display League $l");
			executeGenericSQL ("UPDATE fch_user_preferences SET displayLeague = $l WHERE userID = $userID ");
		}
		else
		{
			debug("Display League was already set.");
		}
	}
	$leagueCount = intval(selectCount("fch_league_membership"," WHERE userID = $userID and status = \"active\" and season = \"$season\""));
	$l = getSingleton("fch_league_membership","leagueID"," WHERE userID = $userID and season = \"$season\" and status = \"active\" LIMIT 0,1");
	executeGenericSQL ("UPDATE fch_user_preferences SET displayLeague = $l WHERE userID = $userID ");
	
	/*
	if ($leagueCount < 2)
	{	
		debug("generateUserPreferencesDeprecated, leaguecount < 2");
		$sql = "
		UPDATE fch_user_preferences SET displayLeague = ( SELECT leagueID
		FROM fch_league_membership
		WHERE leagueID = 35
		AND userID = $userID 
		and season = \"$season\") 
		WHERE userID = $userID
		";
		executeGenericSql($sql);
		
		}
*/
	}
	
	
	
	function getRawGMList($leagueID)
	{
		$season = getSeason();
		$sql = "select teamDisplayName from fch_league_membership where leagueID = $leagueID and status = \"active\" and season = \"$season\" ORDER BY teamDisplayName ASC";
		$con = initializeDB();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
             // output data of each row	
         	while($row = $result->fetch_assoc()) 
			{
				$out .= $row["teamDisplayName"] . "<br/>";
			}
		}
		closeDB($con);
		return $out;
		
	}
	
	?>

