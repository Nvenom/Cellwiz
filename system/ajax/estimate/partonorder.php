<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$ticket = $_GET['tid'];
	MYSQL::QUERY("UPDATE core_tickets_status SET t_reserved = ? WHERE t_id = ? LIMIT 1;", array(2,$ticket));
?>