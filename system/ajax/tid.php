<?php 
	REQUIRE("../../frame/engine.php");ENGINE::START();
    $USER = USER::VERIFY(0,TRUE);

	$C = ($_GET['setting'] == 0) ? 't_imei':'t_id';
	$M = MYSQL::QUERY('SELECT t_id FROM core_tickets_status WHERE '.$C.' = ? LIMIT 1', ARRAY($_GET['code']));
	if(!$M == ""){
		echo $M['t_id'];
	} else {
	    echo 'nan';
	}
?>