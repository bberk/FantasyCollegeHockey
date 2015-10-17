<?php

require_once 'fch-lib.php';
require_once 'email.php';

sqlRefresh();

function sqlRefresh()
{
	$season = getSeason();
	/// /////////////////////////////////////////////////
	/// TRUNCATE SCHEDULE
	$sql = <<<SQL
		truncate table fanta66_joomla.fch_schedule;
SQL;
	$scheduleTruncateResults = executeGenericSQL($sql);

	/// /////////////////////////////////////////////////
	/// REBUILD SCHEDULE
	$sql = <<<SQL
		insert into fanta66_joomla.fch_schedule
		(dateStamp,visitor,home)
		select date_stamp,visitor,home from fanta66_zudnicfchmaster.schedule_master;
SQL;
	$scheduleResults = executeGenericSQL($sql);
	
	
	/// /////////////////////////////////////////////////
	/// TRUNCATE SCHEDULE
	$sql = <<<SQL
		truncate table fanta66_joomla.fch_schedule_lastseason;
SQL;
	$scheduleTruncateResultsLY = executeGenericSQL($sql);

	/// /////////////////////////////////////////////////
	/// REBUILD SCHEDULE
	$sql = <<<SQL
		insert into fanta66_joomla.fch_schedule_lastseason
		(dateStamp,visitor,home)
		select date_stamp,visitor,home from fanta66_zudnicfchmaster.schedule_lastseason;
SQL;
	$scheduleResultsLY = executeGenericSQL($sql);

	/// /////////////////////////////////////////////////
	/// TRUNCATE RESULTS	
		$sql = <<<SQL
		DELETE FROM fanta66_joomla.fch_consolidated_results where season = "$season";
SQL;
	$out .= "<HR><B>Truncate Results SQL: </B><br/>$sql<p/>\n";

	$truncateResultsResults = executeGenericSQL($sql);

		/// /////////////////////////////////////////////////
	/// REBUILD RESULTS - SKATERS
		$sql = <<<SQL
insert into fanta66_joomla.fch_consolidated_results
(dateStamp
,totalPoints
,season
,playerID
,schoolID
,opponent
,opponentDisplayName
,playerDisplayName
,position
,schoolDisplayName
,statLine
,dateLine
,atVs)

SELECT DISTINCT 
	`fanta66_zudnicfchmaster`.`results_skaters`.`date_stamp` AS `date_stamp`

	,`fanta66_zudnicfchmaster`.`results_skaters`.`total_points` AS `totalPoints`
	,convert(CONCAT (
			substr(`fanta66_zudnicfchmaster`.`results_skaters`.`season`, 1, 4)
			,"-"
			,substr(`fanta66_zudnicfchmaster`.`results_skaters`.`season`, 5, 4)
			) using utf8) AS `season`
	,replace(replace(replace(replace(replace(`fanta66_zudnicfchmaster`.`results_skaters`.`player`, "'", ""), "-", ""), ".", ""), " ", ""), "~", "") AS `playerID`
	,`fanta66_zudnicfchmaster`.`player_master`.`school_id` AS `schoolID`
	,

IF (
		(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_skaters`.`visitor`)
		,`fanta66_zudnicfchmaster`.`results_skaters`.`home`
		,`fanta66_zudnicfchmaster`.`results_skaters`.`visitor`
		) AS `opponent`
	,`fanta66_zudnicfchmaster`.`school_master`.`school_shortname` AS `opponentDisplayName`
	,CONCAT (
		`fanta66_zudnicfchmaster`.`player_master`.`first_name`
		," "
		,`fanta66_zudnicfchmaster`.`player_master`.`last_name`
		,", "
		,`fanta66_zudnicfchmaster`.`player_master`.`school_name`
		) AS `playerDisplayName`
	,`fanta66_zudnicfchmaster`.`player_master`.`position` as position
	,`fanta66_zudnicfchmaster`.`player_master`.`school_name` as schoolDisplayName

	,CONCAT (
		convert(IF (
				(1 > `fanta66_zudnicfchmaster`.`results_skaters`.`goals`)
				,"0"
				,`fanta66_zudnicfchmaster`.`results_skaters`.`goals`
				) using latin1 )
			," G, "
			,convert(IF (
					(1 > `fanta66_zudnicfchmaster`.`results_skaters`.`assists`)
					,"0"
					,`fanta66_zudnicfchmaster`.`results_skaters`.`assists`
					) using latin1 )
				," A "
				,`fanta66_zudnicfchmaster`.`results_skaters`.`info` ) AS `statLine`
				,CONCAT (convert(date_format(`fanta66_zudnicfchmaster`.`results_skaters`.`date_stamp`, "%c/%e") using latin1)) AS `dateLine`
				,convert(IF (
						(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_skaters`.`visitor`)
						," at "
						," vs "
						) using latin1 ) AS `atVs` FROM (
					(
						`fanta66_zudnicfchmaster`.`results_skaters` JOIN `fanta66_zudnicfchmaster`.`player_master`
						) JOIN `fanta66_zudnicfchmaster`.`school_master`
					) WHERE (
						(`fanta66_zudnicfchmaster`.`player_master`.`text_id` = `fanta66_zudnicfchmaster`.`results_skaters`.`player`)
						AND (
							`fanta66_zudnicfchmaster`.`school_master`.`school_id` = IF (
								(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_skaters`.`visitor`)
								,`fanta66_zudnicfchmaster`.`results_skaters`.`home`
								,`fanta66_zudnicfchmaster`.`results_skaters`.`visitor`
								) ) ) ORDER BY `fanta66_zudnicfchmaster`.`results_skaters`.`date_stamp` DESC;
SQL;
	$skaterInsertResults=executeGenericSQL($sql);	
	$out .= "<HR><B>Skater Results SQL: </B><br/>$sql<p/>\n"; 
	/////////////////////////////////////////
	// GOALIE RESULTS
	$sql = <<<SQL
	insert into fanta66_joomla.fch_consolidated_results
(dateStamp
,totalPoints
,season
,playerID
,schoolID
,opponent
,opponentDisplayName
,playerDisplayName
,position
,schoolDisplayName
,statLine
,dateLine
,atVs)
	SELECT DISTINCT `fanta66_zudnicfchmaster`.`results_goalies`.`date_stamp` AS `date_stamp`
	,`fanta66_zudnicfchmaster`.`results_goalies`.`points` AS `total_points`
	,convert(CONCAT (
			substr(`fanta66_zudnicfchmaster`.`results_goalies`.`season`, 1, 4)
			,"-"
			,substr(`fanta66_zudnicfchmaster`.`results_goalies`.`season`, 5, 4)
			) using utf8) AS `season`
	,replace(replace(replace(replace(replace(`fanta66_zudnicfchmaster`.`results_goalies`.`player`, "'", ""), "-", ""), ".", ""), " ", ""), "~", "") AS `playerID`
	,`fanta66_zudnicfchmaster`.`player_master`.`school_id` AS `schoolID`
	

,IF (
		(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_goalies`.`visitor`)
		,`fanta66_zudnicfchmaster`.`results_goalies`.`home`
		,`fanta66_zudnicfchmaster`.`results_goalies`.`visitor`
		) AS `opponent`
	,`fanta66_zudnicfchmaster`.`school_master`.`school_shortname` AS `opponentDisplayName`
	,CONCAT (
		`fanta66_zudnicfchmaster`.`player_master`.`first_name`
		," "
		,`fanta66_zudnicfchmaster`.`player_master`.`last_name`
		, ", "
		,`fanta66_zudnicfchmaster`.`player_master`.`school_name`

		) AS `playerDisplayName`
	,`fanta66_zudnicfchmaster`.`player_master`.`position` AS position
	,`fanta66_zudnicfchmaster`.`player_master`.`school_name` as schoolDisplayName
	,CONCAT (
		`fanta66_zudnicfchmaster`.`results_goalies`.`goals_allowed`
		," GA, "
		,`fanta66_zudnicfchmaster`.`results_goalies`.`saves`
		," saves, "
		,round(`fanta66_zudnicfchmaster`.`results_goalies`.`gaa`, 3)
		," GAA, "
		,`fanta66_zudnicfchmaster`.`results_goalies`.`decision`
		) AS `statLine`
	,CONCAT (convert(date_format(`fanta66_zudnicfchmaster`.`results_goalies`.`date_stamp`, "%c/%e") using latin1)) AS `dateLine`
	,convert(IF (
			(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_goalies`.`visitor`)
			," at "
			," vs "
			) using latin1 ) AS `atVs` FROM (
		(
			`fanta66_zudnicfchmaster`.`results_goalies` JOIN `fanta66_zudnicfchmaster`.`player_master`
			) JOIN `fanta66_zudnicfchmaster`.`school_master`
		) WHERE (
			(`fanta66_zudnicfchmaster`.`player_master`.`text_id` = `fanta66_zudnicfchmaster`.`results_goalies`.`player`)
			AND (
				`fanta66_zudnicfchmaster`.`school_master`.`school_id` = IF (
					(`fanta66_zudnicfchmaster`.`player_master`.`school_id` = `fanta66_zudnicfchmaster`.`results_goalies`.`visitor`)
					,`fanta66_zudnicfchmaster`.`results_goalies`.`home`
					,`fanta66_zudnicfchmaster`.`results_goalies`.`visitor`
					) ) ) ORDER BY `fanta66_zudnicfchmaster`.`results_goalies`.`date_stamp` DESC;
SQL;
	$goalieInsertResults = executeGenericSQL($sql);	
	$out .= "<HR><B>Goalie Results SQL: </B><br/>$sql<p/>\n";

	$out .= "Skater Results Insert: " . $skaterInsertResults["msg"] . "\n<br/>";
	$out.= "Goalie Results Insert: " . $goalieInsertResults["msg"] . "\n<br/>";
	
	$playerTruncateResults = executeGenericSQL("truncate table fanta66_joomla.fch_players");
	$out.= "Player Truncate: " . $playerTruncateResults["msg"] . "\n";
	$sql = <<<SQL
	insert into fanta66_joomla.fch_players
(playerID, lastName, firstName, schoolDisplayName, schoolID, playerYear, position, season)
select distinct
	replace(replace(replace(replace(replace(`fanta66_zudnicfchmaster`.`player_master`.`text_id`, "'", ""), "-", ""), ".", ""), " ", ""), "~", "") AS `playerID`
	,`fanta66_zudnicfchmaster`.`player_master`.`last_name` as lastName
	,`fanta66_zudnicfchmaster`.`player_master`.`first_name` as firstName
	,`fanta66_zudnicfchmaster`.`player_master`.`school_name` as schoolDisplayName
	, `fanta66_zudnicfchmaster`.`player_master`.`school_id` as schoolID
	, `fanta66_zudnicfchmaster`.`player_master`.`year_in_school` as playerYear
	, `fanta66_zudnicfchmaster`.`player_master`.`position` as position
	, convert(CONCAT (
			substr(`fanta66_zudnicfchmaster`.`player_master`.`season`, 1, 4)
			,"-"
			,substr(`fanta66_zudnicfchmaster`.`player_master`.`season`, 5, 4)
			) using utf8) AS `season`
	from `fanta66_zudnicfchmaster`.`player_master`
	where `fanta66_zudnicfchmaster`.`player_master`.`test` = "prod"
SQL;
	$sql .= " and season = \"".preg_replace("/[^A-Za-z0-9 ]/", '', getSeason())."\";";
	$playerInsertResults = executeGenericSQL($sql);
	$out.= "Player refresh insert: " . $playerInsertResults["msg"] . "\n";
	echo "Full Insert playerInsertResults: $sql<p>\n\n";

	$lastSeason = preg_replace("/[^A-Za-z0-9 ]/", '', getLastSeason());
	$season = getSeason();
	$sql = <<<SQL
	insert into fanta66_joomla.fch_players
(playerID, lastName, firstName, schoolDisplayName, schoolID, playerYear, position, season)
select distinct
	replace(replace(replace(replace(replace(`fanta66_zudnicfchmaster`.`player_master`.`text_id`, "'", ""), "-", ""), ".", ""), " ", ""), "~", "") AS `playerID`
	,`fanta66_zudnicfchmaster`.`player_master`.`last_name` as lastName
	,`fanta66_zudnicfchmaster`.`player_master`.`first_name` as firstName
	,`fanta66_zudnicfchmaster`.`player_master`.`school_name` as schoolDisplayName
	, `fanta66_zudnicfchmaster`.`player_master`.`school_id` as schoolID
	, "?" as playerYear
	, `fanta66_zudnicfchmaster`.`player_master`.`position` as position
	, $season AS `season`
	from `fanta66_zudnicfchmaster`.`player_master`
	where `fanta66_zudnicfchmaster`.`player_master`.`year_in_school` != "Sr" 
SQL;
$sql .= " and `fanta66_zudnicfchmaster`.`player_master`.`season` = \"$lastSeason\" " ;
$sql .= " and `fanta66_zudnicfchmaster`.`player_master`.`school_id` not in (select distinct schoolID from fanta66_joomla.fch_players where season = \"".getSeason()."\");";
SQL;
	echo "Delta Insert: $sql<BR>\n\n";
	$deltaInsertResults = executeGenericSQL($sql);
	$out.= "Player delta insert: " . $deltaInsertResults["msg"] . "\n";

	$deltaUpdateResults = executeGenericSQL("UPDATE fch_players SET season = \"".getSeason()."\", playerYear = NULL where playerYear = \"?\"");
	$out.= "Player delta update: " . $deltaInsertResults["msg"] . "\n";

	// Draft times are in eastern time. No offset.
	$sql = "SELECT u.email as email, DATE_FORMAT(l.draftTime ,'%W, %b %d %h:%i %p, Eastern time') as draftTime, l.id as id
	FROM fch_users u, fch_leagues l, fch_league_membership lm
	WHERE l.draftTime > DATE_ADD( NOW( ) , INTERVAL +24 HOUR ) 
	AND l.draftTime < DATE_ADD( NOW( ) , INTERVAL +48 HOUR ) 
	AND lm.leagueID = l.id
	AND l.draftReminderSent != TRUE
	AND lm.userID = u.id";
	/*
	$sql = "
	SELECT u.email AS email, DATE_FORMAT( l.draftTime,  '%W, %b %d %h:%i %p, Eastern time' ), l.id AS draftTime
	FROM fch_users u, fch_leagues l, fch_league_membership lm
	WHERE lm.leagueID = l.id
	AND lm.userID = u.id
	AND l.id =35";
	*/
	$con = initializeDB();
	$result = $con->query($sql);
	
	if ($result->num_rows > 0) {
		 // output data of each row	
		while($row = $result->fetch_assoc()) 
		{
			$body = "Hi,\n";
			$body .= "This is a reminder that your Fantasy College Hockey draft is tomorrow, " . $row[draftTime] . ".\n\n";
			$body .= "The draft is live on fantasycollegehockey.com. The draft is available in the League menu and includes a live chat.\n\n";
			$body .= "One hour prior to draft time, the system will close new registrations to the league and generate the draft order. The order will be e-mailed to you.\n\n";
			$body .= "Your league administrator will need to open the draft. When the draft is over, the administrator will close the draft and your drafted players will be moved onto your roster.\n\n";
			$body .= "** DO NOT REPLY ** to this email. Please contact us at fantasycollegehockey@gmail.com if you have questions.\n\n";
			$body .= "Have fun!\n"; 

			$updateDraftReminder = "UPDATE fch_leagues SET draftReminderSent = TRUE WHERE id = " . $row[id];
			$out .= "<HR>Draft Reminder, SQL: $updateDraftReminder";
			executeGenericSQL($updateDraftReminder);
			
			sendEmail($row[email],"Reminder: Your Draft Is Tomorrow",$body);
			$emailSentTo .= $row[email] . ", ";
		}
		
	}
	$truncateByTeamResult = executeGenericSql("DELETE FROM fanta66_joomla.fch_schedule_byteam");
	$out .= "<hr/>Delete all from fch_scheduleByTeam: " . $truncateByTeamResult["msg"];
	
	$sql = "
		insert into fch_schedule_byteam (id,dateStamp,schoolID,atVs,opponent) 
		(select NULL, dateStamp, home, \"vs\" , visitor  from fch_schedule)
		union all
		(select NULL, dateStamp, visitor as schoolID, \"at\" as atVs, home as opponent from fch_schedule)";
	$rebuildByTeamResult=executeGenericSql($sql);
	$out .= "<hr>Rebuild fch_scheduleByTeam: <p/>\n$sql<p/>\nResult:" . $rebuildByTeamResult["msg"];	

	closeDB($con);
	$out .= "<p/>\n	Email sent to: $emailSentTo";
	sendEmail("bob.hatcher@gmail.com","FCH Cron Results",$out) ;

	echo $out;
	
	}
	echo $out;
	
	
	?>
	Done
