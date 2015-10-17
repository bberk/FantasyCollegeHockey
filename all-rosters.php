
<style>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.rosterAll td:nth-of-type(1):before { content: "Active Roster"; }
	.rosterAll td:nth-of-type(2):before { content: "Reserve"; }

</style>
<?php
require_once "fch-lib.php";
require_once "fch-lib.js";

$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);

echo "<!-- leagueID $leagueID -->";
debug("League is $leagueID");
$season = getSeason();


$displayNames = getPlayerDisplayNames();
$con = initializeDB();
$sql = <<<SQL
select teamDisplayName, userID from fch_league_membership where leagueID = $leagueID and status = "active" and season = "$season" order by teamDisplayName ASC
SQL;
debug($sql);
$result = $con->query($sql);
if ($result->num_rows > 0) 
{
	// output data of each row	
	while($row = $result->fetch_assoc()) 
	{	
		?>
		<h3><?php echo $row[teamDisplayName] ?></h3>
		<?php
		$thisUser = $row["userID"];
		echo renderCurrentRoster($thisUser, $leagueID);
		
		
		debug("Key pair. key = $key value = " .$out[$key]  );
	}
}
else {
	debug("Warning, got zero rows back");
}




closeDB($con);
?>
