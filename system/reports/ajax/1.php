<?php
CLASS REPORT{
    PUBLIC STATIC FUNCTION FORMAT($S, $R, $P){
	    $I = ARRAY();
		if($P === false){
	        $I[] = STR_REPLACE("/", "-", $R)." 00:00:00";
			$I[] = STR_REPLACE("/", "-", $R)." 24:59:59";
			$SET = 'HOUR';
        } else {
            $R = explode(" - ", $R);
	        $I[] = STR_REPLACE("/", "-", $R[0]);
	        $I[] = STR_REPLACE("/", "-", $R[1]);
			$SET = 'DAY';
        }
		$I[] = $S['s_id'];
		
		$Q = "SELECT MIN(t_checkout_created) AS `time`, AVG(t_checkout_price) AS `cost` FROM core_tickets_processed WHERE t_checkout_created >= ? AND t_checkout_created <= ? AND t_store = ? GROUP BY";
		$CA = MYSQL::QUERY($Q." $SET(t_checkout_created) ORDER BY t_checkout_created ASC",$I);
		$CO = MYSQL::QUERY($Q." t_checkout_created ORDER BY t_checkout_created ASC",$I);
		$STRING = ARRAY();
		$STRING[] = array('name' => 'Average Charge', 'data' => '');
		$STRING[] = array('name' => 'Checkout Charges', 'data' => '');
		foreach($CA as $C){
		    $T = Date_UTC($C['time']);
		    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$T[3].",".$C['cost'];
		}
		foreach($CO as $C){
		    $T = Date_UTC($C['time']);
		    $STRING[1]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$T[3].",".$T[4].",".$C['cost'];
		}
		ECHO JSON_ENCODE($STRING);
	}
}
?>