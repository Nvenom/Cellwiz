<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$CHECKOUT = MYSQL::QUERY("SELECT * FROM core_checkout_sessions WHERE qb_id = ? LIMIT 1",ARRAY($_GET['chid']));
$ITEMS = EXPLODE('|',$CHECKOUT['items']);
$LIST ='';
$IT = 1;
$oldnontaxable = 0;
$nontaxable = 0;
$oldtaxable = 0;
$taxable = 0;
$oldtotaltax = 0;
$totaltax = 0;
FOREACH($ITEMS AS $I){
    IF($I != ''){
	    $I = EXPLODE('/',$I);
		$LIST .= '|'.$I[0].'/'.$_GET['item'.$IT];
		$EX = EXPLODE('-',$I[0]);
		IF($EX[0]=='ti'){
		    MYSQL::QUERY('UPDATE core_tickets_processed SET t_checkout_created=?,t_checkout_price=? WHERE t_id=? LIMIT 1',ARRAY($_GET['date'],$_GET['item'.$IT],$EX[1]));
		    $nontaxable = $nontaxable + ($_GET['item'.$IT]-0);
			$oldnontaxable = $oldnontaxable + $I[1];
		} ELSE {
		    $taxable = $taxable + ($_GET['item'.$IT]-0);
			$totaltax = $totaltax + (($_GET['item'.$IT]-0) / 100) * $user['store_info']['s_taxrate'];
			$oldtaxable = $oldtaxable + $I[1];
			$oldtotaltax = ($I[1] / 100) * $user['store_info']['s_taxrate'];
		}
	}
	$IT++;
}
MYSQL::QUERY('UPDATE core_checkout_sessions SET items=?,pm_1=?,pm_1_cost=?,pm_2=?,pm_2_cost=?,d_date=? WHERE qb_id=? LIMIT 1',ARRAY($LIST,$_GET['pm_1'],$_GET['pm_1_charge'],$_GET['pm_2'],$_GET['pm_2_charge'],$_GET['date'],$_GET['chid']));
TRACKING::CHECKOUTS($oldnontaxable, $oldtaxable, $oldtotaltax, $user, $CHECKOUT['pm_1'], $CHECKOUT['pm_1_cost'], $CHECKOUT['pm_2'], $CHECKOUT['pm_2_cost'], '-', $_GET['date']);
TRACKING::CHECKOUTS($nontaxable, $taxable, $totaltax, $user, $_GET['pm_1'], $_GET['pm_1_charge'], $_GET['pm_2'], $_GET['pm_2_charge'], '+', $_GET['date']);
?>