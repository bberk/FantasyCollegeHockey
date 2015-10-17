<?php
require_once "fch-lib.php";
require_once "fch-lib.js";
//	require_once "regarding-league.php";
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League is $leagueID");
?>
<?php
	$x = 0;
	if ($_POST['action'] == "doRelease")
	{
		debug( "processing post");
		foreach($_POST as $key => $value)
		{	
			debug($key . " - " . $value);
			// Expect b_ to be baseline, s_ to be status as updated by user
			if (substr($key, 0,2 ) == "s_")
			{
				$playerID = substr($key,2);
				debug( "Player is " . $playerID . " trigger is " . $_POST["s_" . $playerID]);
				if ($_POST["s_" . $playerID] == "dirty")
				{
					debug("RELEASING " . $playerID);
					$r = releasePlayer($userID,$leagueID,$playerID,$_POST["transactionID"]);
					$x ++;
				}
			}
		}
		
		if ($x == 0)
		{
			$r["status"] = 0;
			$r["msg"] = "You did not select any players to drop.";
		}
		echo displayResults($r);
			
	}
?>


<script>

jQuery(function($) {

jQuery(document).ready(function (){
    jQuery('#l2').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l1').append(t);
	  //alert(jQuery("#s_" + this.id).val());
	  jQuery("#s_" + this.id).val("ok");
	  //alert(jQuery("#s_" + this.id).val());

    });
    jQuery('#l1').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l2').append(p);
	  	  //alert(jQuery("#s_" + this.id).val());

	  jQuery("#s_" + this.id).val("dirty");
	  	  //alert(jQuery("#s_" + this.id).val());

    });
    jQuery('#l4').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l3').append(t);
	  	  //alert(jQuery("#s_" + this.id).val());

	  jQuery("#s_" + this.id).val("ok " + "#s_" + this.id);
	  	  //alert(jQuery("#s_" + this.id).val());

    });
    jQuery('#l3').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l4').append(p);
	  	  //alert(jQuery("#s_" + this.id).val());

	  jQuery("#s_" + this.id).val("dirty");
	  	  //alert(jQuery("#s_" + this.id).val());

	  });    
    jQuery('#l6').on('click','li',function(){
      var t=jQuery(this);
      jQuery('#l5').append(t);
	  	  //alert(jQuery("#s_" + this.id).val());

	  jQuery("#s_" + this.id).val("ok");
	  	  //alert(jQuery("#s_" + this.id).val());

    });
    jQuery('#l5').on('click','li',function(){
      var p=jQuery(this);
      jQuery('#l6').append(p);
	  	  //alert(jQuery("#s_" + this.id).val());

	  jQuery("#s_" + this.id).val("dirty");
	  	  //alert(jQuery("#s_" + this.id).val());

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

Move players to the right column to release them. It doesn't matter if they are currently on your active roster or reserve.

<form name = "processRoster" method = "POST">

<div  class = "fch">
	<h2>Roster</h2>
    <h3>Forwards</h3>
	<ul class = "left" id="l1">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "act","F");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","F") ;
				echo getPlayerListDivFormat($userID, $leagueID, "ben","F");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","F") ;
	?>
    </ul>
</div>
<div  class = "fch">
	<h2>Release</h2>
	<h3>&nbsp;</h3>
    <ul class="right" id="l2">
	<?php
		
		
	?>
    </ul>
</div>
<div id="newline"><br/>&nbsp;</div>

<div  class = "fch">
    <h3>Defensemen</h3>
	<ul class = "left" id="l3">
	<?php
		echo getPlayerListDivFormat($userID, $leagueID, "act","D");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","D") ;
		echo getPlayerListDivFormat($userID, $leagueID, "ben","D");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","D") ;
	
	?>
    </ul>
</div>
<div  class = "fch">
	<h3>&nbsp;</h3>
			<ul class = "right" id="l4">

	<?php

		
	?>
	</ul>
</div>
<div id="newline"><br/>&nbsp;</div>
<div id="d_count"><script></script></div>
<div  class = "fch">
	

    <h3>Goaltenders</h3>
	<ul class = "left" id="l5">
	<?php
				echo getPlayerListDivFormat($userID, $leagueID, "act","G");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "act","G") ;
				echo getPlayerListDivFormat($userID, $leagueID, "ben","G");
		$hiddenFields .= getPlayerListHiddenFormat($userID, $leagueID, "ben","G") ;

	?>
	</ul>
</div>
<div  class = "fch">
	<h3>&nbsp;</h3>
		<ul class = "right" id="l6">

	<?php
		
	?>
	</ul>
</div>
<div id="newline"><br/>&nbsp;</div>
	<fieldset>
		<input name = "action" value = "doRelease" type = "hidden">
		<input name = "transactionID" value = "<?php echo getNextTransactionID($userID, $leagueID);?>" type = "hidden">

		<div class="form-actions">	
			<button id = "submitQuery" class="btn btn-primary validate" type="button" onClick= "javaScript:submitForm('processRoster','submitQuery');">Go &gt;&gt;</button>	
		</div>
		</fieldset>
		<?php echo $hiddenFields; ?>
</form>

	<p/>