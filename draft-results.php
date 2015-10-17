

<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'fch-lib.php';
require_once 'fch-lib.js';
    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/' ));  
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );

    $mainframe = JFactory::getApplication('site');
$season = getSeason();
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League $leagueID season $season");
$tryAgain = "<form name = \"tryAgain\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_tryAgain\" onclick=\"javascript:showSubpage('','../../draft-search');\">Search Again &gt;&gt;</button></form>";

$draftOpenStatus = getSingleton("fch_leagues","draftStatus"," WHERE id = $leagueID and season = \"$season\"");


debug("draftOpenStatus " . $draftOpenStatus);

	if ($draftOpenStatus != "open")
	{
		$isDraftOpen = false;
	}
	else
		$isDraftOpen = true;
	
	if ((!($isDraftOpen)) && ($draftOpenStatus != "closed"))
	{
		echo "Your administrator has not yet opened the draft. $tryagain";
		return;
	}
	if ($draftOpenStatus == "closed")
	{
		echo "The draft is now closed. $tryagain";
		return;
	}

$playerQuery = $_GET['q']; 



   $limit_f = getPositionLimit("F","a", $leagueID) + getPositionLimit("F","b", $leagueID) ;
   $limit_d = getPositionLimit("D","a", $leagueID) + getPositionLimit("D","b", $leagueID);
   $limit_g = getPositionLimit("G","a", $leagueID) + getPositionLimit("G","b", $leagueID);
   debug("Limits $limit_f $limit_d $limit_g");


$roster_f=selectCount("fch_draft"," Where   leagueID = $leagueID and userID = $userID and position = \"F\" ");
//$roster_f+=selectCount("fch_draft_customplayer"," Where   leagueID = $leagueID and userID = $userID and position = \"F\" ");

$roster_d=selectCount("fch_draft"," Where   leagueID = $leagueID and userID = $userID and position = \"D\" ");
//$roster_d+=selectCount("fch_draft_customplayer"," Where   leagueID = $leagueID and userID = $userID and position = \"D\" ");

$roster_g=selectCount("fch_draft"," Where   leagueID = $leagueID and userID = $userID and position = \"G\" ");
//$roster_g+=selectCount("fch_draft_customplayer"," Where   leagueID = $leagueID and userID = $userID and position = \"G\" ");


//echo "Looking for $playerQuery";
$sql = <<<SQL
SELECT CONCAT( firstName,  " ", lastName,  ", ", schoolDisplayName ) as playerDisplayName, playerID as playerID, position, 
if (playerID  in (select playerID as dPlayerID from fch_draft where leagueID = $leagueID ),true,false) as taken
FROM  `fch_players` 
where 
CONCAT( firstName,  " ", lastName ) like "%$playerQuery%"
AND playerID not in (select playerID from fch_players_leftearly)
SQL;
	debug($sql);
	$con = initializeDB(); 
	//echo $sql;
	$result = $con->query($sql);

	if ($result->num_rows > 10) {
		echo "<h3>Too Many Results</h3>$tryAgain";
		return;
	}
	$transactionID = getNextTransactionID($userID, $leagueID);
	$passButton .= '<form method = "post" name = "submit_PassedPick_a" enctype="multipart/form-data" >';
	$passButton .= '<input name = "transactionID" value = "'.$transactionID.'" type = "hidden">';
	$passButton .= '<input type = "hidden" name = "action"  value = "doAcquire" >';
	$passButton .= '<input type = "hidden" name = "playerID" value = "PassedPick">';
	$passButton .= '<button class="btn btn-primary validate" type="button" id = "button_submit_PassedPick_a" onclick="javascript:submitForm(\'submit_PassedPick_a\',\'button_submit_PassedPick_a\');">Skip Your Turn (Pass) &gt;&gt;</button> </form>';

	//$passButton = "<form method = \"post\" name = \"submit_PassedPick_a\" name = \"tryAgain\"><input name = \"transactionID\" value = \"$transactionID\" type = \"hidden\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_PassedPick_a\" onclick=\"javascript:submitForm('submit_PassedPick_a','button_submit_PassedPick_a');\">Pass &gt;&gt;</button></form>";

	

		$whoseTurn = onTheClock($leagueID);
		$usingWithdrawn = 0;
		$allowPick = 1;
		debug("User : " . $userID);
		if (!(intval($whoseTurn["userID"]) == intval($userID))) 
		{
			if (intval(selectCount("fch_draft"," WHERE userID = $userID and leagueID = $leagueID and playerID = \"WithdrawnPick\"")) > 0) 
			{
				$alert = "<h3>You are not on the clock. This pick will replace your prior withdrawn pick.</h3>";
				$usingWithdrawn = 1;
				$allowPick = 1;
			}
			else {
				if (intval(selectCount("fch_draft"," WHERE userID = $userID and leagueID = $leagueID and playerID = \"PassedPick\"")) > 0) 
				{
					$alert = "<h3>You are not on the clock. This pick will replace your prior withdrawn pick.</h3>";
					$usingWithdrawn = 1;
					$allowPick = 1;
				}
				else{
					$alert = "<h3>You are not on the clock.</h3>";
					$allowPick = 0;
					
				}
			}
		}
		if ($allowPick)
		{
			$out .= $passButton;
		}
	
	if ($result->num_rows > 0) {
    // output data of each row	
		$out = "<h3>Results</h3>$tryAgain<p/>";
		$out .= "<table width = 100%><thead><tr><th scope=\"col\">Player</th><th scope=\"col\">Position</th><th scope=\"col\">Action</th></tr></thead>";

		while($row = $result->fetch_assoc()) {
			$status = "";
			$playerAvailableToDraft = 1;
			$userAction = "";
			$userActionDetermined = false;
			if ($row['taken'] == 1) {
				$status = "Taken" ;
				$userAction = "--Taken--";
				$userActionDetermined = true;
				$playerAvailableToDraft = 0;
			}

			
			if ($playerAvailableToDraft)
				$status = "Available";
			
			

			if ($playerAvailableToDraft == 1 && $row['position'] == "F") 
			{
				if ($roster_f < $limit_f) 
				{
					$userAction .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".$transactionID."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Make Pick &gt;&gt;</button> </form>&nbsp;\n";
					$userActionDetermined = true;
				}
				else
				{
					$userAction .= "--No Available Slots--";
				}
				
			}
			if ($playerAvailableToDraft == 1 && $row['position'] == "D")
			{
				if ($roster_d< $limit_d)
				{
					$userAction .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".$transactionID."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Make Pick &gt;&gt;</button> </form>&nbsp;\n";
					$userActionDetermined = true;

				}
				else {
					$userAction .= "--No Available Slots--";
				}
			}

			if ($playerAvailableToDraft == 1 && $row['position'] == "G")
			{
				if ($roster_g < $limit_g)
				{
					$userActionDetermined = true;
					$userAction .= "<form method = \"post\" name = \"submit_".$row['playerID']."_a\" enctype=\"multipart/form-data\"><input name = \"transactionID\" value = \"".$transactionID."\" type = \"hidden\"><input value = \"".$row['position']."\" type = \"hidden\" name = \"position\"><input value = \"doAcquire\" type = \"hidden\" name = \"action\"><input type = \"hidden\" name =\"destination\" value = \"act\"><input type = \"hidden\" name = \"playerID\" value = \"".$row['playerID']."\"><button class=\"btn btn-primary validate\" type=\"button\" id = \"button_submit_".$row['playerID']."_a\" onclick=\"javascript:submitForm('submit_".$row['playerID']."_a','button_submit_".$row['playerID']."_a');\">Make Pick &gt;&gt;</button> </form>&nbsp;\n";
				}
				else {
					$userAction .= "--No Available Slots--";
				}
			}			
			
			// If the user is not on the clock override the prior button
			if (!$allowPick)
			{
				$userAction = $status;
			}

			$out .= "<tr><th scope=\"row\">".$row['playerDisplayName']."</th><td>".$row['position']."</td><td>$userAction</td></tr>\n";
		}
		$out .= "</table>";
	}
	else {
		echo "<h3>No Results Found</h3>$tryAgain";
	}

	$sql = "SELECT CONCAT( p.first,  \" \", p.last,  \", \", p.position,  \", \", s.school_shortname ) as playerID FROM fch_draft_customplayer p, fch_schools s WHERE leagueID = $leagueID AND s.school_id = p.schoolID AND p.playerID like \"%$playerQuery%\"";
	debug($sql);
	$result = $con->query($sql);

	if ($result->num_rows > 0) {
		$out .= "<h3>Nonstandard Players</h3>The following players are not in our database but have been claimed.<p/>";
		while($row = $result->fetch_assoc()) {
			$out .= $row[playerID] . "<br/>";
		}
	}
	
	echo $alert . $out;
	
	closeDB($con);
	
	$schoolOptionSet = getOptionSet("fch_schools","school_id","school_name", "", "schoolID") ;
	
	if ($allowPick) {
	
	?>

<form method="post" name="submit_customplayer_a"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Player Not Listed?</legend>
			Sometimes roster data isn't complete, particularly early in the season. <B>If you're sure your player hasn't left early for the pros,</b> you can write in your pick here. <p/>
			<input type = "hidden" name = "action" value = "doCustomPick" />
			<input name = "transactionID" value = "<?php echo $transactionID; ?>" type = "hidden" />
			<div class="control-group">
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					First Name</label>
				</div>
				<div class="controls">
					<input type="text" name="first" id="first" value="" class="required" size="30" required aria-required="true" />
				</div>
				
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Last Name</label>
				</div>
				<div class="controls">
					<input type="text" name="last" id="last" value="" class="required" size="30" required aria-required="true" />
				</div>
				
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					School</label>
				</div>
				<div class="controls">
					<?php echo $schoolOptionSet; ?>
				</div>
				
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Position</label>
				</div>
				<div class="controls">
					<select name = "position">
						<Option value = "F">Forward</option>
						<option value = "D">Defenseman</option>
						<option value = "G">Goaltender</option>
					</select>
				</div>				
			</div>
		<div class="form-actions">	
			<button class="btn btn-primary validate" type="button" id = "button_submit_customplayer_a" onclick="javascript:confirmCustomPick('submit_customplayer_a','button_submit_customplayer_a');">Pick Nonlisted Player &gt;&gt;</button> 
		</div>
		</fieldset>
	</form>
<?php
	}
	 ?>
	