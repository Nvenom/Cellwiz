<?php
require("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);

IF(EMPTY($_GET['sSearch'])){
    $S = $_GET['string'];
} ELSE {
    $S = $_GET['sSearch'];
}

$iDS = $_GET['iDisplayStart'];
$iDL = $_GET['iDisplayLength'];
$iSC = $_GET['iSortCol_0'];
$iSD = $_GET['sSortDir_0'];

SWITCH($iSC){
    CASE 0:$SS = 'ORDER BY c_name '.$iSD;BREAK;
	CASE 1:$SS = 'ORDER BY c_phone '.$iSD;BREAK;
	CASE 2:$SS = '';BREAK;
}

$iTotal = MYSQL::QUERY('SELECT COUNT(c_id) AS `COUNT` FROM core_customers WHERE c_name LIKE ? OR c_phone LIKE ?', ARRAY('%'.$S.'%','%'.$S.'%'));
$iQuery = MYSQL::QUERY("SELECT * FROM core_customers WHERE c_name LIKE ? OR c_phone LIKE ? $SS LIMIT $iDS,$iDL", ARRAY('%'.$S.'%','%'.$S.'%'),FALSE,TRUE);

$OUTPUT = ARRAY('sEcho' => $_GET['sEcho'], 'iTotalRecords' => $iTotal[0]['COUNT'], 'iTotalDisplayRecords' => $iTotal[0]['COUNT'], 'aaData');
FOREACH($iQuery AS $C){
    $OUTPUT['aaData'][] = ARRAY($C['c_name'], FORMAT::PHONE($C['c_phone']), '<button onClick="LoadCustomer('."'".$C['c_id']."'".')">LOAD</button>');
}

ECHO JSON_ENCODE($OUTPUT);
?>