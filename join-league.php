<?php
require_once "fch-lib.php";
require_once "fch-lib.js";

$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League is $leagueID");
$season = getSeason();
?>

To join a league, choose the league you're looking to join, the password provided by its administrator, and the name of your team. You can only join a league once. If a league's registration deadline has passed, it does not appear on the list.<p/>
<?php
debug($_POST["action"]);
if ($_POST["action"] == "leagueEnroll")
{
	$pass = getSingleton("fch_leagues","pw", " where id = ".$_POST['leagueID']." and season = \"".getSeason()."\"");
	debug("Criteria -- displName=". $_POST["teamDisplayName"] . " pw=" . $_POST['password'] . " pass=" . $pass);
	
	if (($_POST['password'] == $pass) && strlen ($_POST["teamDisplayName"]) > 0)
		echo displayResults(addGMToLeague($_POST["leagueID"],$userID,$_POST["teamDisplayName"]));
	else
		echo displayResults(getReturnCode(0,"Sorry, your password was incorrect or you didn't enter a team name."));
}

?>
 <div class="contact-form">
<form  name="leagueEnroll" method="post" class="form-validate form-horizontal" >
			<input name = "action" value = "leagueEnroll" type = "hidden">
  
		<fieldset>
			<legend>What League Would You Like to Join?</legend>
			<div class="control-group">
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
						League</label>
				</div>
				
				<div class="controls">
					<?php echo getOptionSet("fch_leagues","id","display_name"," WHERE (id not in (select leagueID from fch_league_membership where userID = $userID)) AND season = \"$season\" AND (registrationDeadline > date_add(now(),interval - 3 hour) OR allowRegAfterDeadline = TRUE)","leagueID"); ?><br/>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Password</label>
				</div>
				<div class="controls">
					<input type="text" name="password" id="password"  class="required" size="30" required aria-required="true" />
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Your Team Name</label>
				</div>
				<div class="controls">
					<input type="text" name="teamDisplayName" id="teamDisplayName"  class="required" size="30" required aria-required="true" />
				</div>
			</div>
			<div class="form-actions">	
				<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick= "javascript:document.forms['leagueEnroll'].submit();">Get Rolling &gt;&gt;</button>	
			</div>
		</fieldset>
	</form>
</div>

