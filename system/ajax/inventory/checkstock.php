<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$tid = $_GET['ticket'];
	$mid = $_GET['mid'];
	$part = explode("-",$_GET['part']);
	$release = explode(" ",$_GET['release']);
	$type = $_GET['type'];
	$Model = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id = ? LIMIT 1", array($mid));
	if($part[0] == "it"){
		ENGINE::ITEM($tid, $user, $Model, $part[1], $type, $release);
	} else {
		ENGINE::SERVICE($tid, $user, $Model, $part[1], $type, $release);
	}
?>