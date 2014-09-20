<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);

	$price  =  $_POST['price'];   unset($_POST['price']);
	$time   =  $_POST['time'];    unset($_POST['time']);
	$items  =  $_POST['items'];   unset($_POST['items']);
	$ticket =  $_POST['ticket'];  unset($_POST['ticket']);
	
	$Main  = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", array($ticket));
	IF($Main['t_status'] != 2){die();}
	$Items = MYSQL::QUERY("SELECT t_estimate_items FROM core_tickets_repair WHERE t_id = ? LIMIT 1", array($ticket));$itemsold = explode("|", $Items['t_estimate_items']);
	
	$itemsnew = explode("|", $items);
	$removed = array_diff($itemsold,$itemsnew);
	$added   = array_diff($itemsnew,$itemsold);
	
	foreach($removed as $k => $v){
	    $v = explode("-",$v);
		if($v[0] == "it"){
	        MYSQL::QUERY("UPDATE inventory_stock SET quantity = quantity + 1 WHERE store = ? AND item = ? LIMIT 1", array($user['store'],$v[1]));
		}
	}
	
	foreach($added as $k => $v){
	    $v = explode("-",$v);
		if($v[0] == "it"){
	        MYSQL::QUERY("UPDATE inventory_stock SET quantity = quantity - 1 WHERE store = ? AND item = ? LIMIT 1", array($user['store'],$v[1]));
		}
	}
	
	$status = array(3);
	foreach($_POST as $k => $v){
	    $key1 = "t_".str_replace($ticket, "", $k);
		$key2 = explode(".",$Main[$key1]);
		$key3 = $key2[0].".".$v;
		array_push($status, $key3);
	}
	
	$reward = round($price / 50);
	
	array_push($status, $ticket);
	MYSQL::QUERY("INSERT INTO core_tickets_checkout (t_id, t_customer, t_manufacturer, t_model, t_imei, t_password, t_phy, t_liq, t_sof, t_created_by, t_store, t_session, t_created, t_estimate_created, t_estimate_price, t_estimate_items, t_estimate_time) SELECT * FROM core_tickets_repair WHERE t_id = ? LIMIT 1", array($ticket));
	MYSQL::QUERY("UPDATE core_tickets_checkout SET t_repair_created = ?, t_repair_price = ?, t_repair_items = ?, t_repair_time = ? WHERE t_id = ?", array(Date("Y-m-d H:i:s"), $price, $items, $time, $ticket));
	MYSQL::QUERY("UPDATE core_tickets_status SET t_status = ?,t_order = 0,t_simcard = ?,t_sdcard = ?,t_case = ?,t_charger = ?,t_power = ?,t_buttons = ?,t_inaudio = ?,t_exaudio = ?,t_touch = ?,t_housing = ?,t_charging = ?,t_service = ? WHERE t_id = ? LIMIT 1", $status);
	MYSQL::QUERY("DELETE FROM core_tickets_repair WHERE t_id = ? LIMIT 1", array($ticket));
	MYSQL::QUERY('INSERT INTO core_tickets_note (t_id, t_note, t_note_by, t_date) VALUES (?, ?, ?, ?)', ARRAY($ticket, "Device Repaired [$time]", $user['user_id'], Date("Y-m-d H:i:s")));
	TRACKING::REPAIRS($itemsnew, $user);
	USER::NOTE($ticket,"Device Repaired [ $price ] [ $time ]",2);
	USER::STAT('repairs');
	USER::MEDAL('bronze',$reward);
?> 