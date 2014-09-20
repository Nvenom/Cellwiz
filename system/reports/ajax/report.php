<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

switch($user['level']){
    case 1:$rw = 's_manager';break;
	case 2:$rw = 's_gmanager';break;
	case 3:$rw = 's_owner';break;
	case 4:$rw = 's_region';break;
	case 5:$rw = 'all';break;
	case 6:$rw = 'all';break;
	case 7:$rw = 'all';break;
	case 8:$rw = 'all';break;
}

$store = $_GET['s'];
$range = $_GET['r'];
$data = $_GET['d'];
$type = $_GET['t'];

if($rw == 'all'){
    $store = MYSQL::QUERY("SELECT s_id,s_name FROM core_stores WHERE s_id = ? LIMIT 1", ARRAY($store));
} else {
    $store = MYSQL::QUERY("SELECT s_id,s_name FROM core_stores WHERE $rw = ? AND s_id = ? LIMIT 1", ARRAY($user['user_id'], $store));
}

IF(!$store == ''){
    FUNCTION Date_UTC($dr){
	    return array(date('Y', strtotime(str_replace('-', '/', $dr))), date('m', strtotime(str_replace('-', '/', $dr))), date('d', strtotime(str_replace('-', '/', $dr))), date('H', strtotime(str_replace('-', '/', $dr))),date('i', strtotime(str_replace('-', '/', $dr))));
	}

    $pos = strpos($range, ' - ');
	require("$data.php");
	REPORT::FORMAT($store,$range,$pos);
}
?>