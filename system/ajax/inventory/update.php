<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$P = $_GET['p'];
	$V = $_GET['v'];
	$T = $_GET['t'];
	
	SWITCH($T){
	    CASE 'Q': 
		    $R = 'quantity';
		    BREAK;
		CASE 'P':
		    $R = 'price';
			BREAK;
		CASE 'M':
		    $R = 'minimum';
	        BREAK;
	}
	
	$CH = SUBSTR($V, 0, 1);
	IF(IS_NUMERIC($CH)){$CH = '=';}
	$VALUE = STR_REPLACE(ARRAY('+','-','=',' '),'',$V);
	
	IF($CH == '+' || $CH == '-' || $CH == '='){
	    IF($CH != '='){$R = $R." = ".$R;}
		IF($CH != '-'){$OD = '';} ELSE {$OD = '-';}
	    $Q = 'INSERT INTO inventory_stock (store, item, quantity, minimum, price, modified) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE '.$R.' '.$CH.' '.$VALUE;
	    MYSQL::QUERY($Q,ARRAY($user['store'],$P,$OD.$VALUE,0,0,DATE('Y-m-d H:i:s')));
		
		ECHO $CH."|".$VALUE;
	}
?>