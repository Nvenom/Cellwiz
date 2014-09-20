<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$load = $_GET['nmb'];

$params = array($load);
$Main = MYSQL::QUERY('SELECT l_id,l_name FROM core_advert_locations WHERE l_method = ? ORDER BY l_name ASC', $params);
foreach ($Main as $b){
    echo "<option value='".$b['l_id']."'>".$b['l_name']."</option>";
}
?>