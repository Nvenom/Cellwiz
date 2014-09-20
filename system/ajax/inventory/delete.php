<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$pid = $_GET['pid'];
	MYSQL::QUERY("DELETE FROM device_parts WHERE p_id = ? LIMIT 1", ARRAY($pid));
	echo "1 Part Removed from Database.";
?>