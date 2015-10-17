Due to the 12-hour rule, players are not available to manage here for the 12 hours immediately following the start time of a game in which their school is participating. For more information see <a href="./index.php/about/game-rules">Game Rules</a> under "Roster Moves."

<?php

$document = JFactory::getDocument();
#$document->addScript('fch-lib.js');
require_once ("fch-lib.js");

require_once ("fch-lib.php");
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League is $leagueID");

$season = getSeason();

if ($_POST['action'] == "doAcquire")
{
	echo displayResults(acquirePlayer($userID,$leagueID,$_POST['playerID'],$_POST['destination'],$_POST['position'],$_POST["transactionID"]));
	
}

$limit_f_a = getPositionLimit("F","a", $leagueID);
$limit_f_b = getPositionLimit("F","b", $leagueID);
$limit_d_a = getPositionLimit("D","a", $leagueID);
$limit_d_b = getPositionLimit("D","b", $leagueID);
$limit_g_a = getPositionLimit("G","a", $leagueID);
$limit_g_b = getPositionLimit("G","b", $leagueID); 

$roster_f_a=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"F\" and statusCode = \"act\"");
$roster_f_b=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"F\" and statusCode = \"ben\"");
$roster_d_a=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"D\" and statusCode = \"act\"");
$roster_d_b=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"D\" and statusCode = \"ben\"");
$roster_g_a=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"G\" and statusCode = \"act\"");
$roster_g_b=selectCount("fch_rosters"," Where season = \"$season\" and leagueID = $leagueID and userID = $userID and releaseDate is  null and position = \"G\" and statusCode = \"ben\"");

?>
<p/>
<h3>Your Roster Slots (used/allowed)</h3>
<style>
@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	.type1 td:nth-of-type(2):before { content: "Forwards"; }
	.type1 td:nth-of-type(3):before { content: "Defensemen"; }
	.type1 td:nth-of-type(4):before { content: "Goalies"; }

</style>
	<table width = 100% class = "type1">
<thead><tr>
	<th scope=\"col\">&nbsp;</th>
	<th scope=\"col\">Forwards</th>
	<th scope=\"col\">Defensemen</th>
	<th scope=\"col\">Goalies</th>
</tr></thead>
<tr>
	<td class = "rowHead">Active Roster</td>
		<td><?php echo $roster_f_a; ?> / <?php echo $limit_f_a; ?></td>
		<td><?php echo $roster_d_a; ?> / <?php echo $limit_d_a; ?></td>
		<td><?php echo $roster_g_a; ?> / <?php echo $limit_g_a; ?></td>
	</td>
</tr>
<tr>
	<td class = "rowHead">Reserve</td>
	<td><?php echo $roster_f_b; ?> / <?php echo $limit_f_b; ?></td>
	<td><?php echo $roster_d_b; ?> / <?php echo $limit_d_b; ?></td>
	<td><?php echo $roster_g_b; ?> / <?php echo $limit_g_b; ?></td>
</tr>
</table>

 
 <div class="contact-form">
<form method="get" name="nameCheckForm"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Who Would You Like to Acquire?</legend>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
	Name</label></div>
				<div class="controls"><input type="text" name="nameCheck" id="nameCheck" value="Example: Gaudreau" class="required" size="30" onclick = "javascript:clearField(this);" required aria-required="true" /></div>
			</div>
		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick="javascript:showUser(document.forms['nameCheckForm'].elements['nameCheck'].value,'../../acquire-results');">Go &gt;&gt;</button>	
		</div>
		</fieldset>
	</form>
</div>
<div id="txtHint"></div>
<!--  -->
<!--   -->