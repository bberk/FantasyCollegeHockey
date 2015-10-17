<?php

require_once 'fch-lib.php';
require_once 'email.php';

generateDraftOrder();


function generateDraftOrder()
{
	$sql = 
	"
	SELECT l.id AS leagueID
		FROM  `fch_leagues` l
		WHERE l.registrationDeadline < DATE_ADD(NOW( ), INTERVAL + 3 HOUR) 
		AND l.draftGenerated = FALSE 
		LIMIT 0 , 30
	";
	$con = initializeDB();
	debug("generateDraftOrder: $sql");
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row	
    while($row = $result->fetch_assoc()) {
		debug("Invoking for league " . $row[leagueID]);
		generateDraftOrderSpecificLeague($row[leagueID]);
		notifyDraftOrder($row[leagueID]);
    }
} else {
    return "";
}
$con->close();
return $out;
}

function generateDraftOrderSpecificLeague($leagueID)
{
	$season = getSeason();
	$sql = "
	select userID from fch_league_membership
	where leagueID = $leagueID
	and season = \"$season\"
	order by rand();
	";
	$con = initializeDB();
	debug("generateDraftOrderSpecificLeague: $sql");
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row	
	$order = 1;
    while($row = $result->fetch_assoc()) {
		$r = executeGenericSQL("UPDATE fch_league_membership SET draftOrder = $order WHERE userID = ".$row[userID]." and leagueID = $leagueID and season = \"$season\"");
		if ($r["code"] != 1)
			echo "Error generateDraftOrderSpecificLeague " . $r["msg"];
		$order++;
	}
    
	} else {
		debug("generateDraftOrderSpecificLeague - Aborting");
		return "";
	}
	//notifyDraftOrder($leagueID);
	debug("generateDraftOrderSpecificLeague :: UPDATE fch_leagues SET draftGenerated = TRUE where id = $leagueID and season = \"$season\"");
	$r = executeGenericSQL("UPDATE fch_leagues SET draftGenerated = TRUE where id = $leagueID and season = \"$season\"");
	$con->close();
	if ($r["code"] != 1)
		echo "Error generateDraftOrderSpecificLeague " . $r["msg"];
}


function notifyDraftOrder($leagueID)
{
	debug("notifydraftorder.. hello");
	$order = getDraftOrder($leagueID);
	$season = getSeason();
	$sql = "
	SELECT u.email
		FROM fch_users u, fch_league_membership m
		WHERE u.id = m.userID
		AND season =  \"$season\"
		AND m.leagueID = $leagueID
	";
	debug("notifyDraftOrder: $sql");
	$draftTime = getSingleton("fch_leagues", "draftTime", " WHERE id = $leagueID and season = \"$season\"");
	$leagueName = getSingleton("fch_leagues", "display_name", " WHERE id = $leagueID and season = \"$season\"");

	
	$body = <<<BODY
	Hello,\n\n
	This is an automated notification from Fantasy College Hockey.\n
	You've joined a league - $leagueName. The registration deadline has passed, and we've generated a draft order. Here it is:\n
	$order\n
	
	Note: The draft will occur in order for the first round, then in reverse order for the 2nd round, and vice versa. For example if there are 3 people in your draft, it'll go pick 1-2-3-3-2-1-1-2-3 and so on.
	
	Your draft is scheduled for $draftTime EST\n\n
	
	Have a great season and feel free to contact us at fantasycollegehockey@gmail.com.
BODY;
	
	
	$con = initializeDB();
	debug("notifyDraftOrder: $sql");
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row	
	$order = 1;
    while($row = $result->fetch_assoc()) {
		sendEmail($row[email],"Your FCH Draft is Coming! Here's Your Draft Order",$body) ;
		debug("EMAIL: " . $row[email] . " Body: ".$body);

    }
	} else {
		return "";
	}
}

	


?>
