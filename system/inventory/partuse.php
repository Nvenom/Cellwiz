<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$PARTS = MYSQL::QUERY('SELECT t_repair_items FROM core_tickets_processed WHERE t_checkout_created >= ? AND t_checkout_created <= ? AND t_store = ?',ARRAY('2013-01-01 00:00:00', '2013-04-31 24:59:59', '00003'));

$ITEM = ARRAY();
$ARRAY = ARRAY();

FOREACH($PARTS AS $P){
    IF(!EMPTY($P)){
        $S = EXPLODE("|",$P['t_repair_items']);
		FOREACH($S AS $I){
		    $IS = EXPLODE("-",$I);
			IF($IS[0] == 'it'){
				    $INFO = MYSQL::QUERY('SELECT dp.p_name, dm.m_name, ma.m_name as manu FROM device_parts dp JOIN device_models dm ON dp.p_model_id = dm.m_id JOIN device_manufacturers ma ON dm.m_manufacturer_id = ma.m_id WHERE dp.p_id = ? LIMIT 1',ARRAY($IS[1]));
				    IF(!EMPTY($INFO)){
					    $NAME = STR_REPLACE($INFO['manu']." ","",$INFO['m_name']);
						$ARRAY[$INFO['manu']]['COUNT']++;
						$ARRAY[$INFO['manu']]['DEVICES'][$NAME]['COUNT']++;
						$ARRAY[$INFO['manu']]['DEVICES'][$NAME]['PARTS'][$INFO['p_name']]['COUNT']++;
						$ARRAY[$INFO['manu']]['DEVICES'][$NAME]['PARTS'][$INFO['p_name']]['PARTN'] = $IS[1];
					}
			}
		}
	}
}

echo '<pre>';
PRINT_R($ARRAY);
?>