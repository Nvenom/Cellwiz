<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);

$RID = $_GET['r'];
MYSQL::QUERY('DELETE FROM core_refurb_devices WHERE d_id = ? LIMIT 1',ARRAY($RID));
?>