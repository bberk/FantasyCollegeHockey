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



$thisSeason = getSeason();
$lastSeason = getLastSeason();
$thisSeasonNoDash = preg_replace("/[^A-Za-z0-9 ]/", '', $thisSeason);
$lastSeasonNoDash = preg_replace("/[^A-Za-z0-9 ]/", '', $lastSeason);

?>
<form name = "get_stats" action = "/get_stats.cgi" method = "get"  class="form-validate form-horizontal">
	<fieldset>
		<div class="control-group">
			<legend>What Would You Like to Download?</legend>
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Player Type</label>
				</div>

				<div class="controls">
					<select name = "type">
						<option value = "skaters">Skaters</option>
						<option value  = "goalies">Goaltenders</option>
					</select>
				</div>
		</div>
		<div class="control-group">

				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Season</label>
				</div>
				<div class="controls">
					<select name = "season">
						<option value = "<?echo $thisSeasonNoDash;?>"><?echo $thisSeason;?> </option>
						<option value  = "<?echo $lastSeasonNoDash;?>"><?echo $lastSeason; ?> </option>
					</select>
				</div>
		</div>
		<div class="control-group">
				
				
				<div class="control-label">
					<label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
					Format</label>
				</div>
				<div class="controls">
					<select name = "dl_as">
					<option value = "csv">CSV (Excel)</option>
					<option value  = "html">Raw HTML</option>
					</select>
				</div>
				<input type= "hidden" name = "dl_type" value = "current_stats" />
		</div> <!-- control group -->
		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick= "javaScript:submitForm('get_stats','submitQuery');">Go &gt;&gt;</button>	
		</div>
	</fieldset>

</form>

<p/>

<a href="/get_stats.cgi?type=all_players">Click here to download a CSV file of all players currently known in the system for this season.</a>