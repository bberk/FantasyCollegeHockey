<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
<?php
require_once "fch-lib.php";
require_once "fch-lib.js";

$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);

echo "<!-- leagueID $leagueID -->";
debug("League is $leagueID");
$season = getSeason();
?>











<script src="/jquery.blockUI.js"></script>
<B>Missing players?</b> Due to the 12-hour rule, players are not available to manage here for the 12 hours immediately following the start time of a game in which their school is participating. For more information see <a href="./index.php/about/game-rules">Game Rules</a> under "Roster Moves."
<script>

jQuery(function($) {

jQuery(document).ready(function (){
    jQuery('#l2').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l1').append(t);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("act");
 	  //alert("after: " + jQuery("#s_" + this.id).val());
  	  jQuery('#qty_f_a').text(parseInt(jQuery('#qty_f_a').text()) + 1);
  	  jQuery('#qty_f_b').text(parseInt(jQuery('#qty_f_b').text()) - 1);

    });
    jQuery('#l1').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l2').append(p);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("ben");
 	  //alert("after: " + jQuery("#s_" + this.id).val());
  	  jQuery('#qty_f_a').text(parseInt(jQuery('#qty_f_a').text()) - 1);
  	  jQuery('#qty_f_b').text(parseInt(jQuery('#qty_f_b').text()) + 1);

    });
    jQuery('#l4').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l3').append(t);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("act");
 	  //alert("after: " + jQuery("#s_" + this.id).val());	  
	  jQuery('#qty_d_a').text(parseInt(jQuery('#qty_d_a').text()) + 1);
  	  jQuery('#qty_d_b').text(parseInt(jQuery('#qty_d_b').text()) - 1);
    });
    jQuery('#l3').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l4').append(p);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("ben");
 	  //alert("after: " + jQuery("#s_" + this.id).val());
	  jQuery('#d_count').html(jQuery("#l3 > div").length);
  	  jQuery('#qty_d_a').text(parseInt(jQuery('#qty_d_a').text()) - 1);
  	  jQuery('#qty_d_b').text(parseInt(jQuery('#qty_d_b').text()) + 1);
	  });    
    jQuery('#l6').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l5').append(t);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("act");
 	  //alert("after: " + jQuery("#s_" + this.id).val());	
  	  jQuery('#qty_g_a').text(parseInt(jQuery('#qty_g_a').text()) + 1);
  	  jQuery('#qty_g_b').text(parseInt(jQuery('#qty_g_b').text()) - 1);	  
    });
    jQuery('#l5').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l6').append(p);
	  //alert("before: " + jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("ben");
 	  //alert("after: " + jQuery("#s_" + this.id).val());	  
	  jQuery('#qty_g_a').text(parseInt(jQuery('#qty_g_a').text()) - 1);
  	  jQuery('#qty_g_b').text(parseInt(jQuery('#qty_g_b').text()) + 1);
    });
	jQuery("#submitQuery").click(function() {
      //alert("clicked! " + jQuery('#qty_g_a').text() + " " + jQuery('#limit_g_a').text());
		if (parseInt(jQuery('#qty_f_a').text()) > parseInt(jQuery('#limit_f_a').text())){
			alert("Too many forwards in the active column.");
			return;
		}
		if (parseInt(jQuery('#qty_f_b').text()) > parseInt(jQuery('#limit_f_b').text())){
			alert("Too many forwards in the Reserve column.");
			return;
		}
		if (parseInt(jQuery('#qty_d_a').text()) > parseInt(jQuery('#limit_d_a').text())){
			alert("Too many defensemen in the active column.");
			return;
		}
		if (parseInt(jQuery('#qty_d_b').text()) > parseInt(jQuery('#limit_d_b').text())){
			alert("Too many defensemen in the Reserve column.");
			return;
		}
		if (parseInt(jQuery('#qty_g_b').text()) > parseInt(jQuery('#limit_g_b').text())){
			alert("Too many goalies in the Reserve column.");
			return;
		}
		if (parseInt(jQuery('#qty_g_a').text()) > parseInt(jQuery('#limit_g_a').text())){
			alert("Too many goalies in the active column.");
			return;
		}
		jQuery("#submitQuery").prop("disabled",true);
		jQuery("#submitQuery").html("Sit Tight...");
		jQuery("#processRoster").submit();
    });
});
  
})
</script>
<style>
*{margin:0;padding:0}
div.fch {width:40%;float:left;margin:0 2.5%;min-height:1px;}
ul.left li {height:30px;line-height:30px;background:palegreen;color:#000;margin:3px 0;padding:0 10px;cursor:pointer;height:100%;}
ul.right li {height:30px;line-height:30px;background:lavender;color:#000;margin:3px 0;padding:0 10px;cursor:pointer;height:100%;}
ul.left li:hover {background:tomato;color:#fff;height:100%;}
ul.right li:hover {background:tomato;color:#fff;height:100%;}

#newline {
	clear:both;
}
</style>
<?php
	$successFlag = 1;
	$skipCount = 0;
	$totalCount = 0;
	if ($_POST['action'] == "processRoster")
	{
		foreach($_POST as $key => $value)
		{	
			// Expect b_ to be baseline, s_ to be status as updated by user
			if (substr($key, 0,2 ) == "b_")
			{
				$playerID = substr($key,2);
				//echo "Player is " . $player;
				if ($_POST["b_" . $playerID] != $_POST["s_" . $playerID])
				{
					debug( "$playerID  was chagnged - " . $_POST["s_" . $playerID], " transactionID = " . $_POST["transactionID"]);
					$position = getSingleton("fch_players","position"," WHERE playerID= \"$playerID\" and season = \"$season\"");
					if ($_POST["s_" . $playerID] == "act")
						$r = moveBetween($userID,$leagueID,$playerID,$position,"act",$_POST["transactionID"]);
					else
						$r = moveBetween($userID,$leagueID,$playerID,$position,"ben",$_POST["transactionID"]);
					$totalCount ++;
					if ($r["status"] == 0)
					{
						$successFlag = intval(0);
						$msg .= " There was a problem with " . getSingleton("fch_players","CONCAT(firstName, \" \", lastName)", " WHERE playerID = \"$playerID\" ") . ". " ;
						$msg .= "  " . $r["msg"];
						
					}
					if ($r["status"] == 1)
					{
						$msg .= " Successfully " . $r["logAction"] . " " . getSingleton("fch_players","CONCAT(firstName, \" \", lastName)", " WHERE playerID = \"$playerID\" "). ". ";
					}
					if ($r["status"] == -1)
						$skipCount++;
				}
			}
		}
		$r = array();
		debug("Processed $totalCount and skipped $skipCount");
		if ($totalCount == $skipCount){
			$r["status"] = 0;
			$r["msg"] = "Your selections did not result in any changes.";
		}
		else {
		$r["msg"] = $msg;
		$r["status"] = $successFlag;
		}
		setTransactionComplete($transactionID);

		echo displayResults($r);
	}
	$areGamesUnderway = intval(selectCount("v_blacklist",""));
	debug("areGamesUnderway = $areGamesUnderway");
	if ($areGamesUnderway > 0)
	{
		//$gamesUnderway= "<B>There are currently games underway.</B> Due to the 12-hour rule, <em>players are not available to manage here for the 12 hours immediately following the start time of a game</em> in which their school is participating. For more information see <a href=\"./index.php/about/game-rules\">Game Rules</a> under \"Roster Moves.\"";
		?>
		
		<script>
			
			jQuery(document).ready(function() { 
			//$('#demo9').click(function() { 
				jQuery.blockUI({ message: '<h1>There are Games Underway.</h1><p/>For 12 hours after their game starts, players can\'t be moved, so they don\'t appear here. You can still move players who are not in-game.<p/>Please Click the Grey Area to Proceed.' }); 
				jQuery('.blockOverlay').attr('title','There are currently games underway.<br/>Some players are not available to manage. See Game Rules for more info.<br/>Click to Continue').click(jQuery.unblockUI); 
			//}); 
		}); 
		</script>
		<?php
		}
	?>
<?php

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

<form id = "processRoster" method = "POST">

<div  class = "fch">

	<h2>Active Roster</h2>
	
    <h3>Forwards</h3>
	<?php echo $gamesUnderway; ?>

	<ul class = "left" id="l1">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "act","F");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","F") ;
	?>
    </ul>
	Using <span id = "qty_f_a"><?php echo $roster_f_a ?></span> of <span id = "limit_f_a"><?php echo $limit_f_a ?></span> slots.
</div>
<div  class = "fch">
	<h2>Reserve</h2>
	<h3>&nbsp;</h3>
    <ul class="right" id="l2">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "ben","F");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","F") ;
		
	?>
    </ul>
	Using <span id = "qty_f_b"><?php echo $roster_f_b ?></span> of <span id = "limit_f_b"><?php echo $limit_f_b ?></span> slots.

</div>
<div id="newline"><br/>&nbsp;</div>

<div  class = "fch">
    <h3>Defensemen</h3>
		<?php echo $gamesUnderway; ?>

	<ul class = "left" id="l3">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "act","D");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","D") ;
		
	?>
    </ul>
		Using <span id = "qty_d_a"><?php echo $roster_d_a ?></span> of <span id = "limit_d_a"><?php echo $limit_d_a ?></span> slots.

</div>
<div  class = "fch">
	<h3>&nbsp;</h3>
			<ul class = "right" id="l4">

	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "ben","D");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","D") ;
		
	?>
	</ul>
	Using <span id = "qty_d_b"><?php echo $roster_d_b ?></span> of <span id = "limit_d_b"><?php echo $limit_d_b ?></span> slots.
</div>
<div id="newline"><br/>&nbsp;</div>
<div  class = "fch">
	

    <h3>Goaltenders</h3>
		<?php echo $gamesUnderway; ?>

	<ul class = "left" id="l5">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "act","G");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","G") ;

	?>
	</ul>
		Using <span id = "qty_g_a"><?php echo $roster_g_a ?></span> of <span id = "limit_g_a"><?php echo $limit_g_a ?></span> slots.

</div>
<div  class = "fch">
	<h3>&nbsp;</h3>
		<ul class = "right" id="l6">

	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "ben","G");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","G") ;
	?>
	</ul>
		Using <span id = "qty_g_b"><?php echo $roster_g_b ?></span> of <span id = "limit_g_b"><?php echo $limit_g_b ?></span> slots.

</div>
<div id="newline"><br/>&nbsp;</div>
<input name = "transactionID" value = "<?php echo getNextTransactionID($userID, $leagueID);?>" type = "hidden">

<input name = "action" value = "processRoster" type = "hidden">
<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button">Go &gt;&gt;</button>	Please be patient and only click once.
		</div>
<?php echo $hiddenFields; ?>
</form>

	<p/>