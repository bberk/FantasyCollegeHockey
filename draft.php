

<script>
        function confirmCustomPick(formName,buttonID)
        {
            if(document.forms[formName].elements["first"].value == "")
            {
                alert ("You need to enter a first name.");
                return;
            }
            if(document.forms[formName].elements["last"].value == "")
            {
                alert ("You need to enter a last name.");
                return;
            }
            var r = confirm("STOP AND READ! When the draft is over, this player will NOT be transferred to your regular roster. You must check later and add him from the official roster once he is available.");
            if (r == true)
            {
                submitForm(formName,buttonID);
            }
            
        }
           function rescindPick(pickID,leagueID)
        {
            var r = confirm("Are you sure?");
            if (r == true)
            {
                // Rescind Pick
                //alert(window.location);
                var loc = window.location;
                window.location.replace(loc + "?action=rescind&leagueID="+leagueID+"&pickID="+pickID);
                //window.location = ""
            }
            
        }
        
        function closeDraft(leagueID)
        {
            var r = confirm("STOP!\n\nProceeding will:\n1.Close this draft\n2.Delete all rosters in your league and replace them with drafted players. You can reopen the draft, but any roster information will be gone.\n\nAre you really sure?");
            if (r == true)
            {
                // Rescind Pick
                //alert(window.location);
                var loc = window.location;
                window.location.replace(loc + "?action=closeDraft&leagueID="+leagueID);
                //window.location = ""
            }
            
        }
        
        
        
    var leagueID, lastPick, currentPick;
      
      function setLastPick(pick)
      {
          lastPick = pick;
      }
      function getLastPick()
      {
          return lastPick; 
      }
      function setCurrentPick(pick)
      {
          console.log("setting current pick: " + pick);
		  currentPick = parseInt(pick);
      }
      function getCurrentPick()
      {
		  
          return currentPick;
      }      
      function getLeagueID()
      {
          return leagueID;
      }
      function setLeagueID(id)
      {
          leagueID = parseInt(id);
      }
      function pullPickFromPhp(whichURL) {
          url= '../../draft-'+whichURL+'.php';
          
          if (window.XMLHttpRequest) {              
            AJAX=new XMLHttpRequest();              
          } else {                                  
            AJAX=new ActiveXObject("Microsoft.XMLHTTP");
          }
          if (AJAX) {
            AJAX.open("POST", url, false);
            AJAX.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            AJAX.send();
            //alert(AJAX.responseText);
            return AJAX.responseText;                                         
          } else {
             return false;
          }                                             
        }

        //var fileFromServer = pullPickFromPhp();
      
      
      isAdmin = false;
        </script>
<?php
$document = JFactory::getDocument();
#$document->addScript('fch-lib.js');
require_once("fch-lib.js");

require_once("fch-lib.php");
$userID   = getUserID();
$leagueID = leagueHandler($_POST, $userID);
if (!($leagueID > 0))
    return;

$season = getSeason();

///////// POST
debug("League is $leagueID action is " . $_POST['action']);
if ($_POST['action'] == "doAcquire") {
    debug("Calling draftPlayer: User=" . $userID . " League = " . $leagueID . " Player=" . $_POST['playerID'] . " Position=" . $_POST['position'] . " TransactionID = " . $_POST["transactionID"]);
    echo displayResults(draftPlayer($userID, $leagueID, $_POST['playerID'], $_POST['position'], $_POST["transactionID"], "R"));
} else {
    debug("No doAcquire directive.");
}

if ($_POST['action'] == "openDraft") {
    executeGenericSql("UPDATE fch_leagues SET draftStatus = \"open\" WHERE id = $leagueID and season = \"$season\"");
    echo displayResults(getReturnCode(1, "The draft is officially open."));
    $_GET["action"] = ""; // So you don't trigger the close action later
}

if ($_POST["action"] == "doCustomPick") {
    $school = preg_replace('/[^a-zA-Z]+/i', '', getSingleton("fch_schools", "school_name", " WHERE school_id = \"" . $_POST["schoolID"] . "\""));
    debug("DoCustomPick. School is " . $school);
    $playerID = preg_replace('/[^a-z\d ]/i', '', $_POST["first"] . $_POST["last"] . $school);
    $first    = $_POST["first"];
    $last     = $_POST["last"];
    $position = $_POST["position"];
    $schoolID = $_POST["schoolID"];
    debug("DoCustomPick. $first $last $position $schoolID");
	if (intval(selectCount("fch_draft_customplayer"," WHERE playerID = \"$playerID\"")) > 0)
	{
		echo displayResults(getReturnCode(0,"Error: Someone else has already made this custom pick."));
	}
	else {
		$result = executeGenericInsertSQL("INSERT INTO fanta66_joomla.fch_draft_customplayer (id, userID, leagueID, first, last, playerID,position ,schoolID ) VALUES (NULL, $userID, $leagueID, \"$first\", \"$last\",\"$playerID\", \"$position\", \"$schoolID\")");
		if ($result == -1) {
			$r["msg"]    = "There has been a database error.";
			$r["status"] = 0;
			echo displayResults($r);
			return;
		}
		
		echo displayResults(draftPlayer($userID, $leagueID, $playerID, $position, $_POST["transactionID"], "C"));
	}
}
if ($_POST["action"] == "skipPick")
{
    debug("Calling skipPick: User=" . $_POST["userID"] . " leagueID = " . $_POST["leagueID"]);
    echo displayResults(draftPlayer($_POST["userID"], $_POST["leagueID"], "PassedPick", "X", $transactionID, "P"));
}	 

// If we did a POST, purge GET.
if (strlen($_POST["action"]) > 0)
	$_GET = null;


////////// GET
if ($_GET["action"] == "rescind") {
    
    $r = executeGenericSQL("UPDATE fch_draft SET playerID = \"WithdrawnPick\" , position = \"X\" , status = \"W\" WHERE leagueID = " . $_GET[leagueID] . " AND overallPick = " . intval($_GET[pickID]));
    if ($r["status"] == 1)
        echo displayResults(getReturnCode(1, "Your pick was successfully withdrawn."));
    else
        echo displayResults(getReturnCode(0, "There has been an error and your pick was not withdrawn."));
}
if ($_GET["action"] == "closeDraft") {
    executeGenericSql("UPDATE fch_leagues SET draftStatus = \"closed\" WHERE id = $leagueID and season = \"$season\"");
	closeDraft($leagueID);
    echo displayResults(getReturnCode(1, "The draft is officially closed."));
}


if (selectCount("fch_players", " WHERE playerID = \"WithdrawnPick\"") == 0) {
    executeGenericSQL("INSERT INTO fch_players SET playerID = \"WithdrawnPick\", schoolID = \"pc\" , season = \"$season\"");
}
if (selectCount("fch_players", " WHERE playerID = \"PassedPick\"") == 0) {
    executeGenericSQL("INSERT INTO fch_players SET playerID = \"PassedPick\", schoolID = \"pc\" , season = \"$season\"");
}
$leagueAdmin = getSingleton("fch_leagues", "admin_user", " WHERE id = $leagueID and season = \"$season\"");

// Is user the admin?
if (intval($leagueAdmin) == intval($userID))
    $userIsAdmin = true;
else
    $userIsAdmin = false;

if (getSingleton("fch_leagues", "draftStatus", " WHERE id = $leagueID and season = \"$season\"") != "open")
    $isDraftOpen = false;
else
    $isDraftOpen = true;

$nextToDraft = onTheClock($leagueID);


if ($userIsAdmin) {
?>
       <h3>Administrator Draft Controls</h3><table border width = 100%><tr><td>
        <script>
        isAdmin = true;
        </script>
        <?php
    if ($isDraftOpen) {
        $closeDraftButton = '<button class="btn btn-primary validate" type="button" id = "close_draft_button" onclick="javascript:closeDraft(' . $leagueID . ');">Close Draft &gt;&gt;</button>';
        echo $closeDraftButton . '<p/>';
        debug("Next To Draft (at load time):  " . $nextToDraft["gmName"]);
        $skipTurn .= '<form method = "post" name = "skip_pick" enctype="multipart/form-data" >';
        $skipTurn .= '<input type = "hidden" name = "action"  value = "skipPick" >';
        $skipTurn .= '<input type = "hidden" name = "skipPickID" id = "skipPickID"  value = "'.$nextToDraft["pickSeq"].'" >';
        $skipTurn .= '<input type = "hidden" name = "leagueID" value = "' . $leagueID . '">';
        $skipTurn .= '<input type = "hidden" name = "userID" id = "pickingUser" value = "'.$nextToDraft["userID"].'">';		
        $skipTurn .= '<button class="btn btn-primary validate" type="button" id = "skip_pick_button" onclick="javascript:submitForm(\'skip_pick\',\'skip_pick_button\');">Skip Pick For ' . $nextToDraft["gmName"] . ' &gt;&gt;</button> </form>';
        echo $skipTurn;
    } else {
        $openDraftButton .= '<form method = "post" name = "open_draft_form" enctype="multipart/form-data" >';
        $openDraftButton .= '<input type = "hidden" name = "action"  value = "openDraft" >';
        $openDraftButton .= '<input type = "hidden" name = "leagueID" value = "' . $leagueID . '">';
        $openDraftButton .= '<button class="btn btn-primary validate" type="button" id = "open_draft_button" onclick="javascript:submitForm(\'open_draft_form\',\'open_draft_button\');">Open Draft &gt;&gt;</button> </form>';
        echo $openDraftButton;
    }
?>
       </td></tr></table>
        <?php
}


$positionLimits = getAllPositionLimits($leagueID);

$limit_f = $positionLimits["f_a"] + $positionLimits["f_b"];
$limit_d = $positionLimits["d_a"] + $positionLimits["d_b"];
$limit_g = $positionLimits["g_a"] + $positionLimits["g_b"];


$roster_f = selectCount("fch_draft", " Where   leagueID = $leagueID and userID = $userID and position = \"F\" ");
$roster_d = selectCount("fch_draft", " Where   leagueID = $leagueID and userID = $userID and position = \"D\" ");
$roster_g = selectCount("fch_draft", " Where   leagueID = $leagueID and userID = $userID and position = \"G\" ");

$allowF = 0;
$allowD = 0;
$allowG = 0;

if ($limit_f > $roster_f)
	$allowF = 1;
if ($limit_d > $roster_d)
	$allowD = 1;
if ($limit_g > $roster_g)
	$allowG = 1;


debug("USER: " . $userID);
?>
  
   <?php
$lastPick = -1;



debug("last pick number (" . getLastPickNumber($leagueID) . ")");
?>
<p/>

   <style type="text/css" media="screen, print, projection">
      #fch_wrap {
      width:750px;
      margin:0 auto;
      background:#99c;
      }
      #fch_header {
      padding:5px 10px;
      background:#ddd;
      }
      h1 {
      margin:0;
      }
      #fch_nav {
      padding:5px 10px;
      }
      #fch_nav ul {
      margin:0;
      padding:0;
      list-style:none;
      }
      #fch_nav li {
      display:inline;
      margin:0;
      padding:0;
      }
      #fch_main {
      float:left;
      width:480px;
      padding:3px;
	  border-style: solid;
      }
      h2 {
      margin:0 0 1em;
      }
      #fch_sidebar {
      float:right;
      width:230px;
      padding:10px;
      background:#ddd;
      }
      #fch_footer {
      clear:both;
      padding:5px 10px;
      background:#cc9;
      }
      #fch_footer p {
      margin:0;
      }
      * html #fch_footer {
      height:1px;
      }
   </style>
<div id = "fch_wrap">
<div id = "fch_sidebar">
<h4>
On the Clock
</h4><div id = "onTheClock">
<?php
echo $nextToDraft["gmName"];
?>
</div>
   <h4>Your Draft By Position</h4>
   <table width = 100% >
      <thead>
         <tr>
            <th scope=\"col\">F</th>
            <th scope=\"col\">D</th>
            <th scope=\"col\">G</th>
         </tr>
      </thead>
      <tr>
         <td><?php
echo $roster_f;
?> / <?php
echo $limit_f;
?></td>
         <td><?php
echo $roster_d;
?> / <?php
echo $limit_d;
?></td>
         <td><?php
echo $roster_g;
?> / <?php
echo $limit_g;
?></td>
         </td>
      </tr>
   </table>
   <h3>Draft Log</h3>
   <a href="#" onclick="manualRefresh(); return false;">Refresh</a>
   <div style="width:230px;height:400px;line-height:3em;overflow:auto;padding:5px;" id = "runningList">
      <?php
echo getFullDraftList($leagueID, $userID, "all");
?>
  </div>

    <div id = "debugSlot"></div>
    <div id = "notes">Note: withdrawn or skipped re-picks won't appear until this page is refreshed (or you make a pick).</div>
</div>


<div id = "fch_main" >

 <div id="tlkio" data-theme="http://fantasycollegehockey.com/joomla/templates/tx_zenith/css/responsive.css" data-channel="FantasyCollegeHockey-Draft-<?php
echo $leagueID;
?>" style="width:100%;height:350px;"></div><script async src="http://tlk.io/embed.js" type="text/javascript"></script>
<!-- Main Search Panel --><font size = "small">Chat history is retained for 10 minutes.</font></div><div id = "fch_main" >

   <div id="txtHint" style="height:350px;line-height:3em;overflow:auto;padding:5px;>
      <?php
require_once "draft-search.php";
?>
  </div>
  
   
   <!--<iframe src="http://web.myirc.net/?randomnick=&channels=#FCH-Draft-<?php
echo $leagueID;
?>" width="480" height="400"></iframe>-->
   <!-- <iframe src="http://kiwiirc.com/client/irc.myirc.net/#FantasyCollegeHockey.com-Draft-<?php
echo $leagueID;
?>" width="480" height="400"></iframe> -->
</div> <!-- main -->

<script type="text/javascript">//<![CDATA[
// <!--

      
      http = getHTTPObject();
      
      function getHTTPObject(){
        var xmlhttp;
      
        if(!xmlhttp && typeof XMLHttpRequest != 'undefined'){
          try {
            xmlhttp = new XMLHttpRequest();
          }catch(e){
            xmlhttp = false;
          }
        }
        return xmlhttp;
      }
      
// do this onload
    var initialPick = JSON.parse(pullPickFromPhp("ontheclock"));
    setCurrentPick(parseInt(initialPick["overallPick"]));  
	
      
	function liveController(refreshAll)
	{
		
		var pickInfo = JSON.parse(pullPickFromPhp("ontheclock"));
		
		if (refreshAll)
		{
			jQuery("#runningDraftList").html(pullPickFromPhp("getall"));
		}
		
		console.log("liveController " + new Date() + " is admin="+pickInfo["isAdmin"] +" Pick Seq from PHP Ajax Call: " + pickInfo['overallPick'] + " JS Current Pick Value = " + getCurrentPick());
		//jQuery("#debugSlot").html(Math.random());
		var draftStatus = pickInfo["draftStatus"];
		var onTheClockAppend = "";
		var isYou ="";
		if (draftStatus == 0)
		{
			onTheClockAppend = "<p/><em>The draft is not yet open.</em>";
		}
		if (draftStatus == "closed")
		{
			onTheClockAppend = "<p/><em>The draft is closed.</em>";
		}
		if (parseInt(pickInfo["userID"]) == parseInt(pickInfo["thisUserID"]))
		{
			isYou = " <font color = \"red\"><B>YOU</B></font>";
		}
		//"# "+pickInfo["overallPick"] + " Overall: " + 
		jQuery("#onTheClock").html(pickInfo['gmName']+isYou+onTheClockAppend);
		
		if (parseInt(pickInfo['overallPick']) > parseInt(getCurrentPick()))
		{
			console.log(parseInt(pickInfo['overallPick']) + " determined greater than " + parseInt(getCurrentPick()))
			var latestPickListItem = String(pickInfo['lastPickDisplay']);
			latestPickListItem.replace(/\\"/g, '"');
			//jQuery("#runningDraftList").append(latestPickListItem);
			jQuery("#runningDraftList").html(pullPickFromPhp("getall"));
			jQuery("#skipPickID").val(pickInfo["overallPick"]);
			setCurrentPick(parseInt(pickInfo["overallPick"]));
			if (pickInfo["isAdmin"] == true) {
				console.log("Updating button for admin..." + jQuery.fn.jquery);
				jQuery("#skip_pick_button").html('Skip Pick For ' + pickInfo['gmName'] + "&gt;&gt;");
				jQuery('#pickingUser').val(pickInfo['userID']);
			}
		}
		


	}
        
	  var latestInterval;
	  latestInterval = setInterval ( "liveController(false)", 2000 );
	  console.log("interval id at load " + latestInterval);
	  
	  function onBlur() {
		  //alert("If you click away, information will not refresh. It will once you return. Interval ID " + latestInterval);
		  clearInterval(latestInterval);
	  }
	  function onFocus(){
		  latestInterval = setInterval ( "liveController(false)", 2000 );
		  console.log("Refreshing whole list");
		  liveController(true);
		  console.log ("new interval id on focus " + latestInterval);    
	  }

	  if (/*@cc_on!@*/false) { // check for Internet Explorer
		  document.onfocusin = onFocus;
		  document.onfocusout = onBlur;
	  } else {
		  window.onfocus = onFocus;
		  window.onblur = onBlur;
	  }
	  
	  function manualRefresh()
	  {
		  //jQuery("#runningDraftList").html(pullPickFromPhp("getall"));
		liveController(true);
	  }
// -->      
//]]></script>
<script>    
    if(typeof window.history.pushState == 'function') {
        window.history.pushState({}, "Hide", "http://fantasycollegehockey.com/index.php/league/draft");
    }
</script><p/> 