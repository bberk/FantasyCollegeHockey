<?php



require_once "fch-lib.php";
require_once "fch-lib.js";
//require_once "regarding-league.php";
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("User is $userID League is $leagueID");
$season = getSeason();

$start = intval($_POST[paginationStart]);
debug("Pagination start $start");
if (!($start >= 0))
	$start = 0;

?>
<STYLE>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.gameByGame td:nth-of-type(1):before { content: "GM"; }
	.gameByGame td:nth-of-type(2):before { content: "Player"; }
	.gameByGame td:nth-of-type(3):before { content: "FCH Points"; }
	.gameByGame td:nth-of-type(4):before { content: "Details"; }
	.gameByGame td:nth-of-type(5):before { content: "Date"; }
	.gameByGame td:nth-of-type(6):before { content: "Opponent"; }
	.standings td:nth-of-type(1):before { content: "Place"; }
	.standings td:nth-of-type(2):before { content: "Points"; }
	.standings td:nth-of-type(3):before { content: "Team"; }

</STYLE>
<h3>Standings</h3>
<?php
echo displayStandings($leagueID);
?>
<h3>Recent Results</h3>
<?php
echo getGameByGameResultsForLeague($leagueID,$start);
?>