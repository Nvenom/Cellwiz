<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$Q = $_GET['quann'];
	$P = $_GET['price'];
	$M = $_GET['minim'];
	$PID = $_GET['pid'];
	
	IF(EMPTY($Q)){$Q = 0;}
	IF(EMPTY($P)){$P = 0;}
	IF(EMPTY($M)){$M = 0;}
	
	$A = ARRAY();
	
	$A['Q1'] = SUBSTR($Q, 0, 1);
	IF(IS_NUMERIC($A['Q1'])){$A['Q1'] = '=';}
	$A['Q2'] = STR_REPLACE(ARRAY('+','-','=',' '),'',$Q);
	IF($A['Q1'] != '='){$A['Q3'] = 'quantity = quantity '.$A['Q1'].' ?';} else {$A['Q3'] = 'quantity = ?';}
	
	$A['P1'] = SUBSTR($P, 0, 1);
	IF(IS_NUMERIC($A['P1'])){$A['P1'] = '=';}
	$A['P2'] = STR_REPLACE(ARRAY('+','-','=',' '),'',$P);
	IF($A['P1'] != '='){$A['P3'] = 'price = price '.$A['P1'].' ?';} else {$A['P3'] = 'price = ?';}
	
	$A['M1'] = SUBSTR($M, 0, 1);
	IF(IS_NUMERIC($A['M1'])){$A['M1'] = '=';}
	$A['M2'] = STR_REPLACE(ARRAY('+','-','=',' '),'',$M);
	IF($A['M1'] != '='){$A['M3'] = 'minimum = minimum '.$A['M1'].' ?';} else {$A['M3'] = 'minimum = ?';}
	
	$Q = 'INSERT INTO inventory_stock (store, item, quantity, minimum, price, modified) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE '.$A['Q3'].', '.$A['P3'].', '.$A['M3'];
	MYSQL::QUERY($Q,ARRAY($user['store'],$PID,$A['Q2'],$A['M2'],$A['P2'],DATE('Y-m-d H:i:s'),$A['Q2'],$A['P2'],$A['M2']));
	
	$CHECK = MYSQL::QUERY('SELECT quantity,minimum,price FROM inventory_stock WHERE store = ? AND item = ? LIMIT 1',ARRAY($user['store'],$PID));
	ECHO $CHECK['quantity'].'|'.$CHECK['price'].'|'.$CHECK['minimum'];
?>