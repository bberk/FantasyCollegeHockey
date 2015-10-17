
<?php
require_once("fch-lib.js");
require_once("fch-lib.php");

if ($_POST['action'] == "doCreateLeague")
{
	debug("creating league");
	echo displayResults(createLeague($_POST['leagueDisplayName'], $_POST['password'], $_POST['teamDisplayName'],  $_POST["limit_f_a"], $_POST["limit_f_b"], $_POST["limit_d_a"], $_POST["limit_d_b"], $_POST["limit_g_a"], $_POST["limit_g_b"], $_POST["registrationDate"] . " " . $_POST["registrationTime"], $_POST["draftDate"] . " " . $_POST["draftTime"], $_POST["invitees"]));
}
?>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<script>
	/*
	function validateDates() 
	{
		var regDate = new Date(document.forms["createLeague"].elements["registrationDate"].value + " " + document.forms["createLeague"].elements["registrationTime"].value);
		var draftDate = new Date(document.forms["createLeague"].elements["draftDate"].value + " " + document.forms["createLeague"].elements["draftTime"].value);
		if ((draftDate - regDate) < 86400000)
		{
			alert("Your draft must be 24 hours or more after your registration date.");
			return false;
		}
		return true;
	}
	*/
	function intValue(field)
	{
		return parseInt(document.forms["createLeague"].elements[field].value);
	}
	function validateRosterLimit()
	{
		
		if (intValue("limit_f_a") > 12)
		{
			alert ("You have too many active roster forwards.");
			return false;
		}
		if (intValue("limit_f_a") < 1)
		{
			alert ("You have too few active roster forwards.");
			return false;
		}
		if (intValue("limit_f_b") > 12)
		{
			alert ("You have too many reserve forwards.");
			return false;
		}
		if (intValue("limit_f_b") < 1)
		{
			alert ("You have too few reserve forwards.");
			return false;
		}
		///
		if (intValue("limit_d_a") > 8)
		{
			alert ("You have too many active roster defensemen.");
			return false;
		}
		if (intValue("limit_d_a") < 1)
		{
			alert ("You have too few active roster defensemen.");
			return false;
		}
		if (intValue("limit_d_b") > 8)
		{
			alert ("You have too many reserve defensemen.");
			return false;
		}
		if (intValue("limit_d_b") < 1)
		{
			alert ("You have too few reserve defensemen.");
			return false;
		}
		////
		if (intValue("limit_g_a") > 4)
		{
			alert ("You have too many active roster goalies.");
			return false;
		}
		if (intValue("limit_g_a") < 1)
		{
			alert ("You have too few active roster goalies.");
			return false;
		}
		if (intValue("limit_g_b") > 4)
		{
			alert ("You have too many active roster goalies.");
			return false;
		}
		if (intValue("limit_g_b") < 1)
		{
			alert ("You have too few reserve goalies.");
			return false;
		}
		return true;
	}
	function validateForm(formName, buttonID)
	{
		//if (!validateDates())
		//{
		//	return false;
		//}
		if (document.forms["createLeague"].elements["teamDisplayName"].value == "")
		{
			alert("You must enter a team name.");
			return false;
		}
		
		submitForm(formName,buttonID);

		
	}
	</script>
	
<form method="get" name="nameCheckForm"  class="form-validate form-horizontal">  
		<fieldset>
			<legend>What's Your League Name?</legend>
			<div class="control-group">
				<div class="control-label"><label id="jform_contact_name-lbl" for="jform_contact_name" class="hasTooltip required" title="&lt;strong&gt;Name&lt;/strong&gt;&lt;br /&gt;Your name.">
	Name</label></div>
				<div class="controls"><input type="text" name="nameCheck" id="nameCheck" value="" class="required" size="30" onclick = "javascript:clearField(this);" required aria-required="true" />
				</div>
			</div>
		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick="javascript:showUser(document.forms['nameCheckForm'].elements['nameCheck'].value,'../../create-league-results');">Go &gt;&gt;</button>	
		</div>
		</fieldset>
	</form>
<div id = "txtHint"></div>