<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$tid = $_GET['tid'];
	$note = $_GET['note'];
	
	$Ticket = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", ARRAY($tid));
	if($Ticket['t_status'] == 1){
		MYSQL::QUERY("INSERT INTO core_tickets_walkout (t_id, t_customer, t_manufacturer, t_model, t_imei, t_password, t_phy, t_liq, t_sof, t_created_by, t_store, t_session, t_created) SELECT * FROM core_tickets_estimate WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("UPDATE core_tickets_status SET t_status = 97 WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("DELETE FROM core_tickets_estimate WHERE t_id = ? LIMIT 1", ARRAY($tid));
	} else if($Ticket['t_status'] == 2){
	    MYSQL::QUERY("INSERT INTO core_tickets_walkout (t_id, t_customer, t_manufacturer, t_model, t_imei, t_password, t_phy, t_liq, t_sof, t_created_by, t_store, t_session, t_created, t_estimate_created, t_estimate_price, t_estimate_items, t_estimate_time) SELECT * FROM core_tickets_repair WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("UPDATE core_tickets_status SET t_status = 98 WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("DELETE FROM core_tickets_repair WHERE t_id = ? LIMIT 1", ARRAY($tid));
	} else if($Ticket['t_status'] == 3){
	    MYSQL::QUERY("INSERT INTO core_tickets_walkout (t_id, t_customer, t_manufacturer, t_model, t_imei, t_password, t_phy, t_liq, t_sof, t_created_by, t_store, t_session, t_created, t_estimate_created, t_estimate_price, t_estimate_items, t_estimate_time, t_repair_created, t_repair_price, t_repair_items, t_repair_time) SELECT * FROM core_tickets_checkout WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("UPDATE core_tickets_status SET t_status = 99 WHERE t_id = ? LIMIT 1", ARRAY($tid));
		MYSQL::QUERY("DELETE FROM core_tickets_checkout WHERE t_id = ? LIMIT 1", ARRAY($tid));
	}
	if(!$note == ''){
	    USER::NOTE($tid,"Walkedout Ticket [$note]",2);
	} else {
	    USER::NOTE($tid,"Walked this ticket out",2);
	}
	TRACKING::WALKOUTS($user);
	echo "Ticket Walked Out";
?>