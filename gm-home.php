<?php



require_once "fch-lib.php";
require_once "fch-lib.js";
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);



debug("User is $userID League is $leagueID");
$season = getSeason();


$app = JFactory::getApplication();
$teamDisplayName = getSingleton("fch_league_membership","teamDisplayName"," WHERE userID = $userID and leagueID = $leagueID and season = \"$season\"");
debug("Team display Name: $teamDisplayName");
$menu = &JSite::getMenu();
$active = $menu->getActive();
$menuname = $active->params->get('page_heading');
echo $menuname;

$start = intval($_POST[paginationStart]);
debug("Pagination start $start");
if (!($start >= 0))
	$start = 0;

$place = getPlace($userID,$leagueID);
getSuffix($place);
?>
<script>
jQuery(document).ready(function ()
{
jQuery('html').find('h1').html("	<h1 class=\"page-title\"><?php echo $teamDisplayName ?></h1>");
});
</script>
<?php
//echo displayResults(getReturnCode(0,"Notice: If you had a player on your roster who is no longer with his team, he has been removed."));
$totalPoints =  getTotalPointsForUser($userID,$leagueID);
?>

<h3>You are in <?php echo getSuffix($place); ?> place with <?php echo $totalPoints ?> points.</h3>
<h4>Current Roster</h4>

<?php
$totalPointsEarned = getTotalPointsForUserByPlayer($userID,$leagueID,0);
$pointsEarned30d = getTotalPointsForUserByPlayer($userID,$leagueID,30);
$lastGameHash = getLastGameHash();
$playerDisplayNames = getPlayerDisplayNames();
$gamesPlayed = getGamesPlayedByPlayer();
$positions = getPositionHash();
$currentRoster = getCurrentRoster($userID, $leagueID);
$nextGame = getNextGame(); 
$totalPoints = selectSumWithGroupBy("fch_consolidated_results", "totalPoints", " WHERE season = \"$season\"", "playerID");

if ($currentRoster == -1)
{
	echo "<h3>You do not have any players on your roster yet.</h3>";
	return;
}
?>
<STYLE>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.summaryTableF td:nth-of-type(1):before { content: "Player"; }
	.summaryTableF td:nth-of-type(2):before { content: "A/R*"; }
	.summaryTableF td:nth-of-type(3):before { content: "Pos"; }
	.summaryTableF td:nth-of-type(4):before { content: "Last Game"; }
	.summaryTableF td:nth-of-type(5):before { content: "Next Game"; }
	.summaryTableF td:nth-of-type(6):before { content: "Earned For You"; }
	.summaryTableF td:nth-of-type(7):before { content: "Earned For You, Last 30d"; }
	.summaryTableF td:nth-of-type(8):before { content: "Total Points"; }
	.summaryTableF td:nth-of-type(9):before { content: "Points Per Game"; }
		.summaryTableG td:nth-of-type(1):before { content: "Player"; }
	.summaryTableG td:nth-of-type(2):before { content: "A/R*"; }
	.summaryTableG td:nth-of-type(3):before { content: "Pos"; }
	.summaryTableG td:nth-of-type(4):before { content: "Last Game"; }
	.summaryTableG td:nth-of-type(5):before { content: "Next Game"; }
	.summaryTableG td:nth-of-type(6):before { content: "Earned For You"; }
	.summaryTableG td:nth-of-type(7):before { content: "Earned For You, Last 30d"; }
	.summaryTableG td:nth-of-type(8):before { content: "Total Points"; }
	.summaryTableG td:nth-of-type(9):before { content: "Points Per Game"; }
		.summaryTableD td:nth-of-type(1):before { content: "Player"; }
	.summaryTableD td:nth-of-type(2):before { content: "A/R*"; }
	.summaryTableD td:nth-of-type(3):before { content: "Pos"; }
	.summaryTableD td:nth-of-type(4):before { content: "Last Game"; }
	.summaryTableD td:nth-of-type(5):before { content: "Next Game"; }
	.summaryTableD td:nth-of-type(6):before { content: "Earned For You"; }
	.summaryTableD td:nth-of-type(7):before { content: "Earned For You, Last 30d"; }
	.summaryTableD td:nth-of-type(8):before { content: "Total Points"; }
	.summaryTableD td:nth-of-type(9):before { content: "Points Per Game"; }
	}
</STYLE>
<?php
$out .= "\"Last Game\" refers to the most recent game a player's team has started. If it was recent, statistics might not be available yet and may read \"No Points.\".";
$th .= "<th scope=\"col\">Player</th>";
$th .= "<th scope=\"col\">A/R*</th>";
$th .= "<th scope=\"col\">Pos</th>";
$th .= "<th scope=\"col\">Last Game</th>";
$th .= "<th scope=\"col\">Next Game</th>";
$th .= "<th scope=\"col\">Earned For You</th>";
$th .= "<th scope=\"col\">Earned For You, Last 30d</th>";
$th .= "<th scope=\"col\">Total Points</th>";
$th .= "<th>Points Per Game</th>";
$th .= "</tr></thead>";
$fout .= "<h4>Forwards</h4><table class = \"summaryTableF\" width = 100%><thead>$th<tr>";
$dout .= "<h4>Defensemen</h4><table class = \"summaryTableD\" width = 100%><thead>$th<tr>";
$gout .= "<h4>Goaltenders</h4><table class = \"summaryTableG\" width = 100%><thead>$th<tr>";


foreach ($currentRoster as $key => $value)
{
	debug("Iterating on currentroster $key => $value GP: " .$gamesPlayed[$key] );
	if ($gamesPlayed[$key] > 0)
		$gp = round($totalPoints[$key] / $gamesPlayed[$key], 2);
	else
		$gp = 0;
	if ($totalPoints[$key] > 0)
		$totalPointsDisp = $totalPoints[$key];
	else
		$totalPointsDisp = 0;
	
	$ab = $currentRoster[$key];
	if (strlen($nextGame[$key]) > 0)
		$nextGameDisp = $nextGame[$key];
	else
		$nextGameDisp = "Not scheduled or exhibition";
	if ($totalPointsEarned[$key] > 0)
		$tpEarned = $totalPointsEarned[$key];
	else
		$tpEarned = 0;
	if ($pointsEarned30d[$key]>0)
		$earned30d = $pointsEarned30d[$key];
	else
		$earned30d =0;
	if ($ab == "act")
		$ab = "A";
	else
		$ab = "R";
	debug($positions[$key]);
	$line =  "<TR><TD><B>$playerDisplayNames[$key]</B></td><td>$ab</td><td>$positions[$key]</td><td>$lastGameHash[$key]</td><td>$nextGameDisp</td><td>$tpEarned</td><td>$earned30d </td><td>$totalPointsDisp</td><td>$gp</td></tr>";
	if ($positions["$key"] == "G")
		$gout .= $line;
	if ($positions[$key] == "D")
		$dout .= $line;
	if ($positions[$key] == "F")
		$fout .= $line;
}


$dout .= "</table>";
$gout .= "</table>";
$fout .= "</table>";
$out .= $fout . $dout . $gout;

echo $out;
?>
* A = Active Roster, R = Reserve
<p/>
<h3>Game by Game Results</h3>
Note: If you have released a player they may appear here but not on your current roster above.
<STYLE>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.gameByGame td:nth-of-type(1):before { content: "Player"; }
	.gameByGame td:nth-of-type(2):before { content: "FCH Points"; }
	.gameByGame td:nth-of-type(3):before { content: "Details"; }
	.gameByGame td:nth-of-type(4):before { content: "Date"; }
	.gameByGame td:nth-of-type(5):before { content: "Opponent"; }
</STYLE>

<?php
echo getGameByGameResults($userID,$leagueID,0);
?>