<?php
CLASS REPORT{
    PUBLIC STATIC FUNCTION FORMAT($S, $R, $P){
	    $I = array($S['s_id']);
	    if($P === false){
            $TI = "AND d_date = ? LIMIT 1";
	        $I[] = str_replace("/", "-", $R);
        } else {
            $R = explode(" - ", $R);
	        $TI = "AND d_date >= ? AND d_date <= ? ORDER BY d_date ASC";
	        $I[] = str_replace("/", "-", $R[0]);
	        $I[] = str_replace("/", "-", $R[1]);
        }
	    $CT = MYSQL::QUERY("SELECT * FROM core_stores_daily_tickets WHERE s_id = ? $TI",$I);
	    $AE = MYSQL::QUERY("SELECT * FROM core_stores_daily_accepts WHERE s_id = ? $TI",$I);
		$WT = MYSQL::QUERY("SELECT * FROM core_stores_daily_walkouts WHERE s_id = ? $TI",$I);
		$CD = MYSQL::QUERY("SELECT * FROM core_stores_daily_checkouts WHERE s_id = ? $TI",$I);
		$RD = MYSQL::QUERY("SELECT * FROM core_stores_daily_repairs WHERE s_id = ? $TI",$I);
		$CC = MYSQL::QUERY("SELECT * FROM core_stores_daily_customers WHERE s_id = ? $TI",$I);
		$STRING = ARRAY(
		    ARRAY('name' => 'Created Tickets',    'data' => ''),
	        ARRAY('name' => 'Accepted Estimates', 'data' => ''),
		    ARRAY('name' => 'Walked Out Tickets', 'data' => ''),
		    ARRAY('name' => 'Checked Out Tickets', 'data' => ''),
		    ARRAY('name' => 'Repaired Devices', 'data' => ''),
		    ARRAY('name' => 'Customer Accounts Created', 'data' => '')
		);
		if(!$P === false){
	        foreach($CT as $CL){
			    $T = Date_UTC($CL['d_date']);
			    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_key'];
		    }
			foreach($AE as $A){
			    $T = Date_UTC($A['d_date']);
			    $STRING[1]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$A['d_key'];
		    }
			foreach($WT as $W){
			    $T = Date_UTC($W['d_date']);
			    $STRING[2]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$W['d_key'];
		    }
			foreach($CD as $C){
			    $T = Date_UTC($C['d_date']);
			    $STRING[3]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$C['d_key'];
		    }
			foreach($RD as $R){
			    $T = Date_UTC($R['d_date']);
			    $STRING[4]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$R['d_key'];
		    }
			foreach($CC as $C){
			    $T = Date_UTC($C['d_date']);
			    $STRING[5]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$C['d_key'];
		    }
		} else {
		    $T = Date_UTC($CT['d_date']);
		    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_key'];
			$STRING[1]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$AE['d_key'];
			$STRING[2]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$WT['d_key'];
			$STRING[3]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CD['d_key'];
			$STRING[4]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$RD['d_key'];
			$STRING[5]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CC['d_key'];
		}
		
		if($P === false){
		    $STRING[0]['type'] = 'column';
			$STRING[1]['type'] = 'column';
			$STRING[2]['type'] = 'column';
			$STRING[3]['type'] = 'column';
			$STRING[4]['type'] = 'column';
			$STRING[5]['type'] = 'column';
		}
		
		ECHO JSON_ENCODE($STRING);
	}
}
?>