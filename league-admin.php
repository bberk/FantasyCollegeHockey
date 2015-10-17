<script>

function confirmSubmit(formName, buttonName)
{
	var r = confirm("ARE YOU SURE?\nThis is the nuclear option. GM will be completely removed.");
	if (r == true) {
		submitForm(formName,buttonName);
	} 
}

</script>

<?php 
   $document = JFactory::getDocument();
   #$document->addScript('fch-lib.js');
   require_once ("fch-lib.js");

   require_once ("fch-lib.php");
   $userID = getUserID();
   $leagueID = leagueHandler($_POST, $userID);
      $season = getSeason();
   debug("League is $leagueID action is " . $_POST['action']);
   
   $adminID = getSingleton("fch_leagues","admin_user"," WHERE id = $leagueID and season = \"$season\"");
   debug("$userID != $adminID");
   if ($userID != $adminID)
   {
	   echo "Sorry, you are not the league administrator.";
	   return;
   }
   
     if ($_POST["action"] == "allowRegAfterDeadline")
	 {
		if (!okToTransact($_POST["transactionID"]))
	   		echo displayResults(getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button."));
		else {
			debug("allowRegAfterDeadline " . $_POST["allowRegAfterDeadlineCheckbox"]);
			if ($_POST["allowRegAfterDeadlineCheckbox"] == "on")
			{
				$r = executeGenericSQL("UPDATE fch_leagues SET allowRegAfterDeadline = TRUE WHERE $leagueID = id and season = \"$season\"");
			}
			else
			{
				$r = executeGenericSQL("UPDATE fch_leagues SET allowRegAfterDeadline = FALSE WHERE $leagueID = id and season = \"$season\"");
			}
			setTransactionComplete($_POST["transactionID"]);
			debug("Return Code $r");
			if ($r != -1)
				echo displayResults(getReturnCode(1,"Successfully updated league configuration."));
			else
				echo displayResults(getReturnCode(0,"There has been an error. Please try again or contact us."));
		}
		
	 }

   
   if ($_POST["action"] == "removeGM")
   {
	   if (!okToTransact($_POST["transactionID"]))
	   		echo displayResults(getReturnCode(0,"This transaction has already been completed. Please do not use the refresh button."));
		else {
			$gmToDelete = intval($_POST["gmToDelete"]);
			debug("Removing $gmToDelete from league $leagueID");
			$r = executeGenericSQL("UPDATE fch_league_membership SET status = \"removed\" WHERE userID = $gmToDelete and $leagueID = leagueID and season = \"$season\"");
			if ($r != -1)
			{	
				$r = executeGenericSQL("UPDATE fch_rosters SET releaseDate = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL +3 HOUR) where  userID = $gmToDelete and leagueID = $leagueID and  releaseDate is NULL and season = \"$season\" ");
				if ($r != -1)
					echo displayResults(getReturnCode(1,"Successfully removed this GM."));

			}
			else
		   		echo displayResults(getReturnCode(0,"There has been an error releasing this GM's players."));

		}
		setTransactionComplete($_POST["transactionID"]);
   }
   ?>
   
   <h3>More Admin Tools Coming Soon.</h3>
   
   <h4>Registration Deadline</h4>
   <?php echo getSingleton("fch_leagues","registrationDeadline"," WHERE id = $leagueID and season = \"$season\""); ?>
   
   <h4>Draft Time</h4>
   <?php echo getSingleton("fch_leagues","draftTime"," WHERE id = $leagueID and season = \"$season\""); 
   
   $regIsAllowed = getSingleton("fch_leagues","allowRegAfterDeadline"," WHERE id = $leagueID and season = \"$season\"");
   debug("Reg Is Allowed: $regIsAllowed");
   if ($regIsAllowed)
   {
	   $regIsAllowedCheck = "CHECKED";
   }
   
   ?>
   <h4>All Registered GMs</h4>
   <?php  echo getRawGMList($leagueID); 
   $transactionID = getNextTransactionID($userID, $leagueID);
   ?>
   <form method="post" name="allowRegAfterDeadline"  class="form-validate form-horizontal">  
  			<input name = "transactionID" value = "<?php echo $transactionID; ?>" type = "hidden">

		<fieldset>
			<legend>Allow Registration After Deadline</legend>
			<input name = "action" value = "allowRegAfterDeadline" type = "hidden">
			<div class="control-group">
						<div class="control-label">
							<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
								Allow Registration After Deadline
							</label>
						</div>
						<div class="controls">
							<input type="checkbox" name = "allowRegAfterDeadlineCheckbox" id="allowRegAfterDeadlineCheckbox" <?php echo $regIsAllowedCheck ?>>&nbsp;
							<br/>Warning: This will allow registration but if the draft order has already been generated, the user will not be added to the draft.
						</div>
			</div>
		</fieldset>
		<div class="form-actions">	
			<button id = "allowRegAfterDeadline" class="btn btn-primary validate" type="button" onClick="javascript:submitForm('allowRegAfterDeadline','allowRegAfterDeadline')">Update &gt;&gt;</button>	
		</div>
	</form>
	
	<form method="post" name="removeGM"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Remove a GM From League</legend>
			<input name = "action" value = "removeGM" type = "hidden">
			<input name = "transactionID" value = "<?php echo $transactionID; ?>" type = "hidden">

			<div class="control-group">
						<div class="control-label">
							<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
								GM
							</label>
						</div>
						<div class="controls">
							<?php echo getOptionSet("fch_league_membership","userID","teamDisplayName", " WHERE leagueID  = $leagueID and userID != $userID and season = \"$season\" and status = \"active\"", "gmToDelete"); ?>
							Warning: Irreverisible!
							
						</div>
			</div>
		</fieldset>
		<div class="form-actions">	
			<button id = "removeGM" class="btn btn-primary validate" type="button" onClick="javascript:confirmSubmit('removeGM','removeGM')">Remove GM &gt;&gt;</button>	
		</div>
	</form>	
	
