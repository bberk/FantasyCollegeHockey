<?php

$config = null;

function getConfig()
{
	if ($config == null) 
	{
		$c = array(
			"debug" => false
		);
	$config = $c;
	return $c;
	}
	else
	{
		return $config;
	}

}

function enableDebug($trueFalse)
{
	$config["debug"] = $trueFalse;
}

function getSeason()
{
	$m = date('n');
	
	//debug($m);
	$y = date('Y');
	$lastyear = intval ($y) - 1;
	$nextyear = intval 	 ($y) + 1;
	
	if ($m > 7)
		return strval($y) . "-" . strval($nextyear);
	else
		return strval($lastyear) . "-" . strval($y);
	//return 0;
}

function getLastSeason()
{
	$m = date('n');
	
	//debug($m);
	$y = date('Y') -1;
	$lastyear = intval ($y) - 1;
	$nextyear = intval 	 ($y) + 1;
	
	if ($m > 7)
		return strval($y) . "-" . strval($nextyear);
	else
		return strval($lastyear) . "-" . strval($y);
	//return 0;
}
?>