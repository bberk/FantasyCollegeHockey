<STYLE>
	@media 
		only screen and (max-width: 760px),
		(min-device-width: 768px) and (max-device-width: 1024px)  {
		.detailedStatisticsSummary td:nth-of-type(1):before { content: "Player"; }
		.detailedStatisticsSummary td:nth-of-type(2):before { content: "Points to Date"; }
		.detailedStatisticsSummary td:nth-of-type(3):before { content: "Games Played"; }
		.detailedStatisticsSummary td:nth-of-type(4):before { content: "Points per Game"; }
		.detailedStatisticsDetails td:nth-of-type(1):before { content: "Player"; }
		.detailedStatisticsDetails td:nth-of-type(2):before { content: "Date"; }
		.detailedStatisticsDetails td:nth-of-type(3):before { content: "Opponent"; }
		.detailedStatisticsDetails td:nth-of-type(4):before { content: "Stat Line"; }
		.detailedStatisticsDetails td:nth-of-type(5):before { content: "FCH Points"; }

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
//$leagueID = leagueHandler($_POST, $userID);

$query = $_GET['q'];

if (strlen($query) < 3){
	echo "<h3>Query Too Short</h3>Please try again.";
		return;
		}

$sql = <<<SQL
select r.playerDisplayName, sum(r.totalPoints) as totalPoints, schoolID as schoolID
from fch_consolidated_results r
where r.playerID like "%$query%" 
and r.season = "$season" 
group by playerDisplayName
SQL;
debug($sql);
$con = initializeDB();
$result = $con->query($sql);


	if ($result->num_rows > 0) {
    // output data of each row	
		$out .= "<h3>Summary</h3><table class = \"detailedStatisticsSummary\" width = 100%>";
		$out .= "<thead><tr><th scope=\"col\">Player</th><th scope=\"col\">Points To Date</th><th>Games Played</th><th>Points Per Game</th></tr></thead>";
		while($row = $result->fetch_assoc()) {
			$gamesPlayed = selectCount("fch_schedule"," WHERE (home = \"" . $row["schoolID"] . "\" or visitor=\"".$row["schoolID"]."\") and dateStamp < date_add(current_timestamp,interval + 3 hour)");
			if ($gamesPlayed == 0 || $row['totalPoints'] == 0)
				$ppg = 0;
			else
				$ppg = round($row['totalPoints']/$gamesPlayed, 3);
			$out .= "<tr><td class=\"rowHead\">".$row['playerDisplayName']."</td><td>".$row['totalPoints']."</td><td>$gamesPlayed</td><td>$ppg</td></tr>";
		}
		$out .= "</table>";
	}
	 
$sql = <<<SQL
	SELECT playerDisplayName, statLine, atVS, totalPoints, opponentDisplayName, dateLine, schoolID
	from fch_consolidated_results
	where playerID like "%$query%"
	and season = "$season"
	order by dateStamp desc
SQL;
debug($sql);




$con = initializeDB();
	//echo $sql;
	$result = $con->query($sql);

	if ($result->num_rows ==0) {
		echo "<h3>No Results</h3>Please try again. Note: Players with no statistics this year will not appear in search results.";
		return; 
	}
	
	
	
	if ($result->num_rows > 0) {
    // output data of each row	
		$out .= "<h3>Game By Game Detail</h3><table class=\"detailedStatisticsDetails\" width = 100%>";
		$out .= "<thead><tr><th scope=\"col\">Player</th><th scope=\"col\">Date</th><th scope=\"col\" >Opponent</th><th scope=\"col\">Stat Line</th><th scope=\"col\">FCH Points</th></tr></thead>";
		while($row = $result->fetch_assoc()) {
			$out .= "<tr><td class=\"rowHead\">".$row['playerDisplayName']."</td><td>".$row['dateLine']."</td><td>". $row['atVS'] . " " . $row['opponentDisplayName'] ."</td><td>".$row['statLine']."</td><td>".$row['totalPoints']."</td></tr>";
		}
		$out .= "</table>";
	}
	closeDB($con);
	echo "Note: Players with no statistics this year will not appear in search results." . $out;
?>
<p/>
... That's all I got.