<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$val = $_GET['value'];
	$pid = $_GET['pid'];
	
	$pluscheck = strpos($val, '+');
	$minuscheck = strpos($val, '-');
	if($pluscheck === 0){
	    $nv = explode('+', $val);
		$nv = explode('/', $nv[1]);
		MYSQL::QUERY("INSERT INTO inventory_stock (store, item, quantity, price, modified) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE quantity=quantity + ?, price=?, modified=?", ARRAY($user['store'], $pid, $nv[0], $nv[1], Date("Y-m-d H:i:s"), $nv[0], $nv[1], Date("Y-m-d H:i:s")));
		echo $nv[0]." parts added into inventory.";
	}
	if($minuscheck === 0){
		$nv = explode('-', $val);
		MYSQL::QUERY("UPDATE inventory_stock SET quantity=quantity - ?, modified=? WHERE store=? AND item=?", ARRAY($nv[1], Date("Y-m-d H:i:s"), $user['store'], $pid));
		echo $nv[1]." parts removed from inventory.";
	}
?>