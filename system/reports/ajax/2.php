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
		    ARRAY('name' => 'Sales', 'data' => '')
		);
		
		if(!$P === false){
	        foreach($CT as $CL){
			    $T = Date_UTC($CL['d_date']);
				$Total = $CL['d_gross_nontaxable'] + $CL['d_gross_taxable'];
			    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$Total;
		    }
		} else {
		    $T = Date_UTC($CT['d_date']);
			$Total = $CT['d_gross_nontaxable'] + $CT['d_gross_taxable'];
		    $STRING[0]['data'][] .= $T[0].",".$T[1].",".$T[2].",".$Total;
		}
		if($P === false){
		    $STRING[0]['type'] = 'column';
		}
		ECHO JSON_ENCODE($STRING);
	}
}
?>