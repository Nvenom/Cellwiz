<?php
require("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(1);

$STRING = $USER['store'].'/'.$_POST['date'];
MYSQL::QUERY("INSERT INTO quickbooks_queue (quickbooks_ticket_id,qb_username,qb_action,ident,priority,qb_status,enqueue_datetime) VALUES (?,?,?,?,?,?,?)",ARRAY(52,'quickbooks','SalesReceiptAdd',$STRING,0,'q',DATE("Y-m-d H:i:s")));

ECHO "Done And Queued for quickbooks.. DONT PRINT THIS FORM<br/>Ledger Form comming soon...";
?>