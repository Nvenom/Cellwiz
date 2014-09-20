<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);

	$price  =  $_POST['price'];   unset($_POST['price']);
	$time   =  $_POST['time'];    unset($_POST['time']);
	$items  =  $_POST['items'];   unset($_POST['items']);
	$ticket =  $_POST['ticket'];  unset($_POST['ticket']);
	
	$Main = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", array($ticket));
	
	IF($Main['t_status']==1){
	    $status = array(2);
	    foreach($_POST as $k => $v){
	        $key1 = "t_".str_replace($ticket, "", $k);
		    $key2 = explode(".",$Main[$key1]);
		    $key3 = $v.".".$key2[1];
            array_push($status, $key3);
	    }
	
	    array_push($status, $ticket);
	    MYSQL::QUERY("INSERT INTO core_tickets_repair (t_id, t_customer, t_manufacturer, t_model, t_imei, t_password, t_phy, t_liq, t_sof, t_created_by, t_store, t_session, t_created) SELECT * FROM core_tickets_estimate WHERE t_id = ? LIMIT 1", array($ticket));
	    MYSQL::QUERY("UPDATE core_tickets_repair SET t_estimate_created = ?, t_estimate_price = ?, t_estimate_items = ?, t_estimate_time = ? WHERE t_id = ? LIMIT 1", array(Date("Y-m-d H:i:s"), $price, $items, $time, $ticket));
	    MYSQL::QUERY("UPDATE core_tickets_status SET t_status = ?,t_simcard = ?,t_sdcard = ?,t_case = ?,t_charger = ?,t_power = ?,t_buttons = ?,t_inaudio = ?,t_exaudio = ?,t_touch = ?,t_housing = ?,t_charging = ?,t_service = ? WHERE t_id = ? LIMIT 1", $status);
	    MYSQL::QUERY("DELETE FROM core_tickets_estimate WHERE t_id = ? LIMIT 1", array($ticket));
	    USER::NOTE($ticket,"Ticket Estimated [ $price ] [ $time ]",2);
        USER::STAT('estimates');
	    USER::MEDAL('bronze',1);
	}
?>