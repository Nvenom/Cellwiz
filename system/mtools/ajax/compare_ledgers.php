<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$SYS_LEDGER=MYSQL::QUERY('SELECT * FROM core_stores_daily_checkouts WHERE s_id=? AND d_date=? LIMIT 1',ARRAY($user['store'],$_POST['date']));
$RESULTS = ARRAY(
    'KEY'      => $_POST['rec'] - $SYS_LEDGER['d_key'],
	'CASH'     => $_POST['cas'] - $SYS_LEDGER['d_cash'],
	'CHECK'    => $_POST['che'] - $SYS_LEDGER['d_check'],
	'AMEX'     => $_POST['ame'] - $SYS_LEDGER['d_amex'],
	'DISCOVER' => $_POST['dis'] - $SYS_LEDGER['d_discover'],
	'MASTER'   => $_POST['mas'] - $SYS_LEDGER['d_master'],
	'VISA'     => $_POST['vis'] - $SYS_LEDGER['d_visa'],
	'DEBIT'    => $_POST['deb'] - $SYS_LEDGER['d_debit'],
	'TICKETS'  => ''
);

$CHECK = 0;
FOREACH($RESULTS AS $KEY => $VALUE){
    IF($VALUE != 0 && $CHECK == 0){
		$RESULTS['TICKETS'] = ARRAY(
			'TICKETS'  => MYSQL::QUERY("SELECT qb_id,items,pm_1,pm_1_cost,pm_2,pm_2_cost FROM core_checkout_sessions WHERE s_id=? AND d_date>=? AND d_date<=?",ARRAY($user['store'],$_POST['date'].' 00:00:00',$_POST['date'].' 24:59:59'))
		);
	    $CHECK = 1;
	}
}

ECHO JSON_ENCODE($RESULTS);
?>