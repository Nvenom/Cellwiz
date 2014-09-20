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
		$CT = MYSQL::QUERY("SELECT * FROM core_stores_daily_checkouts WHERE s_id = ? $TI",$I);
		$STRING = ARRAY(
		    ARRAY('name' => 'Cash', 'data' => ''),
			ARRAY('name' => 'Check', 'data' => ''),
			ARRAY('name' => 'American Express', 'data' => ''),
			ARRAY('name' => 'Discover', 'data' => ''),
			ARRAY('name' => 'Master Card', 'data' => ''),
			ARRAY('name' => 'Visa', 'data' => ''),
			ARRAY('name' => 'Debit', 'data' => '')
		);
		if(!$P === false){
	        foreach($CT as $CL){
			    $T = Date_UTC($CL['d_date']);
			    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_cash'];
				$STRING[1]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_check'];
				$STRING[2]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_amex'];
				$STRING[3]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_discover'];
				$STRING[4]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_master'];
				$STRING[5]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_visa'];
				$STRING[6]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CL['d_debit'];
		    }
		} else {
		    $T = Date_UTC($CT['d_date']);
			$STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_cash'];
			$STRING[1]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_check'];
		    $STRING[2]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_amex'];
			$STRING[3]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_discover'];
			$STRING[4]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_master'];
			$STRING[5]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_visa'];
			$STRING[6]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$CT['d_debit'];
		}
		if($P === false){
		    $STRING[0]['type'] = 'column';
			$STRING[1]['type'] = 'column';
			$STRING[2]['type'] = 'column';
			$STRING[3]['type'] = 'column';
			$STRING[4]['type'] = 'column';
			$STRING[5]['type'] = 'column';
			$STRING[6]['type'] = 'column';
		}
		ECHO JSON_ENCODE($STRING);
	}
}
?>