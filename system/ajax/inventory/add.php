<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$PID = $_GET['addpart'];
    $PCO = $_GET['partcolor'];
    $PDC = $_GET['partdesc'];
	$MOD = $_GET['model'];
	
	$Q = MYSQL::QUERY('SELECT * FROM device_parts_list WHERE id = ? LIMIT 1',ARRAY($PID));
	$PID = $Q['name'];
	
	IF($PCO != 'None'){
	    IF(!EMPTY($PDC)){
		    $PNAME = "$PID - $PCO ($PDC)";
		} ELSE {
		    $PNAME = "$PID - $PCO";
		}
	} ELSE {
	    IF(!EMPTY($PDC)){
		    $PNAME = "$PID ($PDC)";
		} ELSE {
		    $PNAME = "$PID";
		}
	}
	
	MYSQL::QUERY('INSERT INTO device_parts (p_model_id, p_name, p_entry) VALUES (?,?,?)', ARRAY($MOD,$PNAME,1));
	echo $PNAME." Added.";
?>