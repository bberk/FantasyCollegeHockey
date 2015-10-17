
<?php

$document = JFactory::getDocument();
#$document->addScript('fch-lib.js');
require_once( "fch-lib.js");
require_once("fch-lib.php");

$limit_f_a = getPositionLimit("F","a", $leagueID);
$limit_f_b = getPositionLimit("D","b", $leagueID);
$limit_d_a = getPositionLimit("G","a", $leagueID);
$limit_d_b = getPositionLimit("F","b", $leagueID);
$limit_g_a = getPositionLimit("D","a", $leagueID);
$limit_g_b = getPositionLimit("G","b", $leagueID); 

$roster_f_a=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"F\" and rosterStatus = \"act\"");
$roster_f_b=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"F\" and rosterStatus = \"ben\"");
$roster_d_a=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"D\" and rosterStatus = \"act\"");
$roster_d_b=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"D\" and rosterStatus = \"ben\"");
$roster_g_a=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"G\" and rosterStatus = \"act\"");
$roster_g_b=selectCount("v_current_rosters"," Where userID = \"$userID\" and releaseDate is not null and playerPosition = \"G\" and rosterStatus = \"ben\"");

$userID = getUserID();
$leagueID = getSingleton("fch_user_preferences","displayLeague"," WHERE userID = $userID");
?>
 
 <div class="contact-form">
<form method="get" name="nameCheckForm"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Player Query</legend>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
	Name</label></div>
				<div class="controls"><input type="text" name="nameCheck" id="nameCheck" value="Example: Gaudreau" class="required" size="30" onclick = "javascript:clearField(this);" required aria-required="true" /></div>
			</div>
			
						
		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick="javascript:showUser(document.forms['nameCheckForm'].elements['nameCheck'].value,'../../detailed-statistics-results');">Go &gt;&gt;</button>	
		</div>
		</fieldset>
	</form>
</div>
<div id="txtHint"></div>
