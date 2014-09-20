<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$ticket = $_GET['tid'];
	$Main = MYSQL::QUERY("SELECT * FROM core_tickets_repair WHERE t_id = ? LIMIT 1", array($ticket));
	$items = $Main['t_estimate_items'];
	$items1 = explode("|",$items);
	$ns = "";
	foreach($items1 as $i){
		if(!empty($i)){
			$i = explode("-",$i);
		    if($i[0] == "it"){
			    MYSQL::QUERY("UPDATE inventory_stock SET quantity = quantity - 1 WHERE item=? AND store=? LIMIT 1", array($i[1],$user['store']));
		        $Quan = MYSQL::QUERY("SELECT * FROM inventory_stock WHERE item=? AND store=? LIMIT 1", array($i[1],$user['store']));
				print_r($Quan);
				if($Quan > 0){
				    $ns .= $i[1]."|";
				}
			}
	    }
	}
	IF(!$ns==""){MYSQL::QUERY("UPDATE core_tickets_status SET t_order = ? WHERE t_id = ? LIMIT 1;", array($ns, $ticket));}
	MYSQL::QUERY("UPDATE core_tickets_status SET t_reserved = 1 WHERE t_id = ? LIMIT 1", array($ticket));
	TRACKING::ACCEPTS($user);
?>