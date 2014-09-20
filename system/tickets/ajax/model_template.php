<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$load = $_GET['load'];

$Main = MYSQL::QUERY('SELECT m_name FROM device_manufacturers WHERE m_id = ? LIMIT 1', array($load));
$manu = $Main['m_name'];
$Main = MYSQL::QUERY('SELECT m_id, m_name FROM device_models WHERE m_manufacturer_id = ? ORDER BY m_name ASC', array($load));
$i = 0;
$options = "";
foreach ($Main as $b){
    $model = str_replace($manu.' ', '', $b['m_name']);
    $options .= "<option value='".$b['m_id']."'>".$model."</option>";
	$i++;
}
echo '<option value="">'.$i.' Model(s) Found...</option>'.$options;
?>