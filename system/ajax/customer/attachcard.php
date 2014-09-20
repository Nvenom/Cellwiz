<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$CID = $_GET['cid'];
$CARD = $_GET['card'];
$EMAIL = $_GET['email'];

$Q = MYSQL::QUERY('UPDATE core_customers SET c_email = ?,c_card = ? WHERE c_id = ? LIMIT 1',ARRAY($EMAIL,$CARD,$CID));
ECHO $Q;
?>