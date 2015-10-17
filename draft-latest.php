

<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'fch-lib.php';
require_once "fch-lib.js";

    define( '_JEXEC', 1 );
    define( 'JPATH_BASE', realpath(dirname(__FILE__).'/' ));  
    require_once ( JPATH_BASE .'/includes/defines.php' );
    require_once ( JPATH_BASE .'/includes/framework.php' );

    $mainframe = JFactory::getApplication('site');
$season = getSeason();
$userID = getUserID();
$leagueID = leagueHandler($_POST, $userID);
debug("League ID $leagueID");

$justNowPick = intval(getSingleton("fch_draft","max(overallPick)"," WHERE leagueID = $leagueID"));
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
  jQuery( document ).ready(function() {
    jQuery( "#runningDraftList", window.parent).append( "<LI>NewItem</LI>" );
	//appendPick("test");

  });


//appendPick("test");
</script>
Hello World draft-latest 
<?php 
debug ("Passed in pick " . $_GET['q'] . " and just now $justNowPick");
$passedInPick = $_GET['q'];

if (intval($passedInPick) < intval($justNowPick))
{
	//alert("Pick is in");
	?>
	<script>
		//setLatestPick(<?php echo $justNowPick; ?>);
		jQuery('#runningDraftList ol').append('<li><?php echo rand() ?></li>');
		//appendPick("test");
	</script>
	<?php
	
}
else
{
}
debug (rand());

 ?>