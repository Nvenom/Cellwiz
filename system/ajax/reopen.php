<?php 
	REQUIRE("../../frame/engine.php");ENGINE::START();
    $USER = USER::VERIFY(0,TRUE);

	$TID = $_GET['tid'];
	$TICKET = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", ARRAY($TID));
	SWITCH($TICKET['t_status']){
	    CASE 97: $TBL = 'core_tickets_estimate';$ST = 1; BREAK;
		CASE 98: $TBL = 'core_tickets_repair';$ST = 2; BREAK;
		CASE 99: $TBL = 'core_tickets_checkout';$ST = 3;BREAK;
	}
	$ROWS = MYSQL::QUERY("SHOW COLUMNS FROM $TBL");
	$QUERY = "INSERT INTO $TBL SELECT ";
	$I = 0;
	FOREACH($ROWS AS $R){
	    IF($I == 0){
		    $QUERY .= $R['Field'];
		} ELSE {
		    $QUERY .= ",".$R['Field'];
		}
		$I++;
	}
	$QUERY .= " FROM core_tickets_walkout WHERE t_id = ? LIMIT 1";
	MYSQL::QUERY($QUERY, ARRAY($TID));
	MYSQL::QUERY("DELETE FROM core_tickets_walkout WHERE t_id = ? LIMIT 1", ARRAY($TID));
	MYSQL::QUERY("UPDATE core_tickets_status SET t_status = ? WHERE t_id = ? LIMIT 1", ARRAY($ST,$TID));
	USER::NOTE($TID,"Ticket Re-Opened",2);
?>