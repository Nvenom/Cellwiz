<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$date = $_GET['date'];
$store = $_GET['store'];

$NOTES = MYSQL::QUERY("SELECT ctn.t_note FROM core_tickets_status cts JOIN core_tickets_note ctn ON cts.t_id = ctn.t_id AND ctn.t_note LIKE ? WHERE t_store = ?",ARRAY('%Walkedout Ticket%',$STORE,$SD,$ED))
?>