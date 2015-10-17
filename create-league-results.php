<!DOCTYPE html>
<?php

error_reporting(E_ERROR | E_PARSE);
require_once 'fch-lib.php';
//require_once "fch-lib.js";

    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/' ));  
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );
$season = getSeason();
?>

  <script>
  jQuery(function() {
    jQuery( "#draftDate" ).datepicker({ minDate: "+1D", maxDate: "+6M" });
  });
  jQuery(function() {
    jQuery( "#registrationDate" ).datepicker({ minDate: "+1D", maxDate: "+6M" });
  });
  


  </script>

  <?php 
  
  if (selectCount("fch_leagues"," WHERE display_name = \"" . $_GET['q'] . "\" and season = \"$season\"") > 0)
  
  {
	  echo "<h3>Sorry, the name " . $_GET['q'] . " is taken, please try again.</h3>";
	  return;
  }
  
  ?>

 	<h2>Available!</h2>
	<form method="post" name="createLeague"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>Fill Us In On the Details</legend>
			<input name = "leagueDisplayName" value = "<?php echo $_GET['q'] ?>" type = "hidden">
			<input name = "action" value = "doCreateLeague" type = "hidden">

			<!--
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_emailmsg-lbl" for="jform_contact_emailmsg" class="hasTooltip required" title="&lt;strong&gt;Subject&lt;/strong&gt;&lt;br /&gt;Enter the subject of your message here .">
						Registration Deadline
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "registrationDate" id="registrationDate" onClick="javascript:jQuery('#registrationDate').datepicker({minDate: '+1D', maxDate: '+6M'});jQuery('#registrationDate').datepicker('show');">&nbsp;
					<select name="registrationTime" id="registrationTime">
					<option value="5:00:00 AM">5:00 AM</option>
					<option value="5:15:00 AM">5:15 AM</option>
					<option value="5:30:00 AM">5:30 AM</option>
					<option value="5:45:00 AM">5:45 AM</option>
				 
					<option value="6:00:00 AM">6:00 AM</option>
					<option value="6:15:00 AM">6:15 AM</option>
					<option value="6:30:00 AM">6:30 AM</option>
					<option value="6:45:00 AM">6:45 AM</option>
				 
					<option value="7:00:00 AM">7:00 AM</option>
					<option value="7:15:00 AM">7:15 AM</option>
					<option value="7:30:00 AM">7:30 AM</option>
					<option value="7:45:00 AM">7:45 AM</option>
				 
					<option value="8:00:00 AM">8:00 AM</option>
					<option value="8:15:00 AM">8:15 AM</option>
					<option value="8:30:00 AM">8:30 AM</option>
					<option value="8:45:00 AM">8:45 AM</option>
				 
					<option value="9:00:00 AM">9:00 AM</option>
					<option value="9:15:00 AM">9:15 AM</option>
					<option value="9:30:00 AM">9:30 AM</option>
					<option value="9:45:00 AM">9:45 AM</option>
				 
					<option value="10:00:00 AM">10:00 AM</option>
					<option value="10:15:00 AM">10:15 AM</option>
					<option value="10:30:00 AM">10:30 AM</option>
					<option value="10:45:00 AM">10:45 AM</option>
				 
					<option value="11:00:00 AM">11:00 AM</option>
					<option value="11:15:00 AM">11:15 AM</option>
					<option value="11:30:00 AM">11:30 AM</option>
					<option value="11:45:00 AM">11:45 AM</option>
				 
					<option value="12:00:00 PM">12:00 PM</option>
					<option value="12:15:00 PM">12:15 PM</option>
					<option value="12:30:00 PM">12:30 PM</option>
					<option value="12:45:00 PM">12:45 PM</option>
				 
					<option value="1:00:00 PM">1:00 PM</option>
					<option value="1:15:00 PM">1:15 PM</option>
					<option value="1:30:00 PM">1:30 PM</option>
					<option value="1:45:00 PM">1:45 PM</option>
				 
					<option value="2:00:00 PM">2:00 PM</option>
					<option value="2:15:00 PM">2:15 PM</option>
					<option value="2:30:00 PM">2:30 PM</option>
					<option value="2:45:00 PM">2:45 PM</option>
				 
					<option value="3:00:00 PM">3:00 PM</option>
					<option value="3:15:00 PM">3:15 PM</option>
					<option value="3:30:00 PM">3:30 PM</option>
					<option value="3:45:00 PM">3:45 PM</option>
				 
					<option value="4:00:00 PM">4:00 PM</option>
					<option value="4:15:00 PM">4:15 PM</option>
					<option value="4:30:00 PM">4:30 PM</option>
					<option value="4:45:00 PM">4:45 PM</option>
				 
					<option value="5:00:00 PM">5:00 PM</option>
					<option value="5:15:00 PM">5:15 PM</option>
					<option value="5:30:00 PM">5:30 PM</option>
					<option value="5:45:00 PM">5:45 PM</option>
				 
					<option value="6:00:00 PM">6:00 PM</option>
					<option value="6:15:00 PM">6:15 PM</option>
					<option value="6:30:00 PM">6:30 PM</option>
					<option value="6:45:00 PM">6:45 PM</option>
				 
					<option value="7:00:00 PM">7:00 PM</option>
					<option value="7:15:00 PM">7:15 PM</option>
					<option value="7:30:00 PM">7:30 PM</option>
					<option value="7:45:00 PM">7:45 PM</option>
				 
					<option value="8:00:00 PM" SELECTED>8:00 PM</option>
					<option value="8:15:00 PM">8:15 PM</option>
					<option value="8:30:00 PM">8:30 PM</option>
					<option value="8:45:00 PM">8:45 PM</option>
				 
					<option value="9:00:00 PM">9:00 PM</option>
					<option value="9:15:00 PM">9:15 PM</option>
					<option value="9:30:00 PM">9:30 PM</option>
					<option value="9:45:00 PM">9:45 PM</option>
				 
					<option value="10:00:00 PM">10:00 PM</option>
					<option value="10:15:00 PM">10:15 PM</option>
					<option value="10:30:00 PM">10:30 PM</option>
					<option value="10:45:00 PM">10:45 PM</option>
				 
					<option value="11:00:00 PM">11:00 PM</option>
					<option value="11:15:00 PM">11:15 PM</option>
					<option value="11:30:00 PM">11:30 PM</option>
					<option value="11:45:00 PM">11:45 PM</option>
				</select>
				Eastern Time
				</div>
            </div>
			-->
			
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_emailmsg-lbl" for="jform_contact_emailmsg" class="hasTooltip required" title="&lt;strong&gt;Subject&lt;/strong&gt;&lt;br /&gt;Enter the subject of your message here .">
						Draft Date
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "draftDate" id="draftDate" onClick="javascript:jQuery('#draftDate').datepicker({minDate: '+1D', maxDate: '+6M'});jQuery('#draftDate').datepicker('show');">&nbsp;
					<select name="draftTime" id="draftTime">
					<option value="5:00:00 AM">5:00 AM</option>
					<option value="5:15:00 AM">5:15 AM</option>
					<option value="5:30:00 AM">5:30 AM</option>
					<option value="5:45:00 AM">5:45 AM</option>
				 
					<option value="6:00:00 AM">6:00 AM</option>
					<option value="6:15:00 AM">6:15 AM</option>
					<option value="6:30:00 AM">6:30 AM</option>
					<option value="6:45:00 AM">6:45 AM</option>
				 
					<option value="7:00:00 AM">7:00 AM</option>
					<option value="7:15:00 AM">7:15 AM</option>
					<option value="7:30:00 AM">7:30 AM</option>
					<option value="7:45:00 AM">7:45 AM</option>
				 
					<option value="8:00:00 AM">8:00 AM</option>
					<option value="8:15:00 AM">8:15 AM</option>
					<option value="8:30:00 AM">8:30 AM</option>
					<option value="8:45:00 AM">8:45 AM</option>
				 
					<option value="9:00:00 AM">9:00 AM</option>
					<option value="9:15:00 AM">9:15 AM</option>
					<option value="9:30:00 AM">9:30 AM</option>
					<option value="9:45:00 AM">9:45 AM</option>
				 
					<option value="10:00:00 AM">10:00 AM</option>
					<option value="10:15:00 AM">10:15 AM</option>
					<option value="10:30:00 AM">10:30 AM</option>
					<option value="10:45:00 AM">10:45 AM</option>
				 
					<option value="11:00:00 AM">11:00 AM</option>
					<option value="11:15:00 AM">11:15 AM</option>
					<option value="11:30:00 AM">11:30 AM</option>
					<option value="11:45:00 AM">11:45 AM</option>
				 
					<option value="12:00:00 PM">12:00 PM</option>
					<option value="12:15:00 PM">12:15 PM</option>
					<option value="12:30:00 PM">12:30 PM</option>
					<option value="12:45:00 PM">12:45 PM</option>
				 
					<option value="1:00:00 PM">1:00 PM</option>
					<option value="1:15:00 PM">1:15 PM</option>
					<option value="1:30:00 PM">1:30 PM</option>
					<option value="1:45:00 PM">1:45 PM</option>
				 
					<option value="2:00:00 PM">2:00 PM</option>
					<option value="2:15:00 PM">2:15 PM</option>
					<option value="2:30:00 PM">2:30 PM</option>
					<option value="2:45:00 PM">2:45 PM</option>
				 
					<option value="3:00:00 PM">3:00 PM</option>
					<option value="3:15:00 PM">3:15 PM</option>
					<option value="3:30:00 PM">3:30 PM</option>
					<option value="3:45:00 PM">3:45 PM</option>
				 
					<option value="4:00:00 PM">4:00 PM</option>
					<option value="4:15:00 PM">4:15 PM</option>
					<option value="4:30:00 PM">4:30 PM</option>
					<option value="4:45:00 PM">4:45 PM</option>
				 
					<option value="5:00:00 PM">5:00 PM</option>
					<option value="5:15:00 PM">5:15 PM</option>
					<option value="5:30:00 PM">5:30 PM</option>
					<option value="5:45:00 PM">5:45 PM</option>
				 
					<option value="6:00:00 PM">6:00 PM</option>
					<option value="6:15:00 PM">6:15 PM</option>
					<option value="6:30:00 PM">6:30 PM</option>
					<option value="6:45:00 PM">6:45 PM</option>
				 
					<option value="7:00:00 PM">7:00 PM</option>
					<option value="7:15:00 PM">7:15 PM</option>
					<option value="7:30:00 PM">7:30 PM</option>
					<option value="7:45:00 PM">7:45 PM</option>
				 
					<option value="8:00:00 PM" SELECTED>8:00 PM</option>
					<option value="8:15:00 PM">8:15 PM</option>
					<option value="8:30:00 PM">8:30 PM</option>
					<option value="8:45:00 PM">8:45 PM</option>
				 
					<option value="9:00:00 PM">9:00 PM</option>
					<option value="9:15:00 PM">9:15 PM</option>
					<option value="9:30:00 PM">9:30 PM</option>
					<option value="9:45:00 PM">9:45 PM</option>
				 
					<option value="10:00:00 PM">10:00 PM</option>
					<option value="10:15:00 PM">10:15 PM</option>
					<option value="10:30:00 PM">10:30 PM</option>
					<option value="10:45:00 PM">10:45 PM</option>
				 
					<option value="11:00:00 PM">11:00 PM</option>
					<option value="11:15:00 PM">11:15 PM</option>
					<option value="11:30:00 PM">11:30 PM</option>
					<option value="11:45:00 PM">11:45 PM</option>
				</select>
				Eastern Time
				<br>One hour prior to draft time, registration will close and the system will generate the draft order.
				</div>
			</div>
		
			
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Your Team's Name
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "teamDisplayName" id="teamDisplayName" >&nbsp;
				</div>
            </div>	
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Password to Join
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "password" id="password" >&nbsp;Leave blank for an open league
				</div>
            </div>
		</fieldset>
		<fieldset>
			<legend>Roster Sizes</legend>
			You can specify the maximum amount of players a team in your league can carry by position. GM's in your league do not have to have to use all their available slots. This can not be changed after your league is created.
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Active Roster Forwards
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_f_a" id="limit_f_a" value = "6"  onBlur = "javaScript:validateRosterLimit()";>&nbsp;Max 12, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Active Roster Defensemen
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_d_a" id="limit_d_a" value = "4"  onBlur = "javaScript:validateRosterLimit()";>&nbsp;Max 8, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Active Roster Goalies
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_g_a" id="limit_g_a" value = "2"  onBlur = "javaScript:validateRosterLimit()";>&nbsp;Max 4, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Reserve Forwards
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_f_b" id="limit_f_b" value = "6"  onBlur = "javaScript:validateRosterLimit()";>&nbsp;Max 12, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Reserve Defensemen
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_d_b" id="limit_d_b" value = "4">&nbsp; Max 8, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_teamDisplayName-lbl" for="teamDisplayName" class="hasTooltip required" title="teamDisplayName">
						Reserve Goalies
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "limit_g_b" id="limit_g_b" value = "2" onBlur = "javaScript:validateRosterLimit()";>&nbsp;Max 4, Min 1
				</div>
            </div>
			<div class="control-group">
                <div class="control-label">
					<label id="jform_contact_invitees-lbl" for="invitees" class="hasTooltip required" title="invitees">
						People to Invite
					</label>
				</div>
                <div class="controls">
					<input type="text" name = "invitees" id="invitees" value = "" >&nbsp;Email Addresses separated by commas
				</div>
            </div>
		</fieldset>
		<div class="form-actions">	
			<button id = "submitCreateLeague" class="btn btn-primary validate" type="button" onClick="javascript:validateForm('createLeague','submitCreateLeague')">Go &gt;&gt;</button>	
		</div>
		
		</fieldset>
	
	</form>