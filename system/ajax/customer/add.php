<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$Fname = FORMAT::TEXT($_GET['Fname']);
$Lname = FORMAT::TEXT($_GET['Lname']);
$Phone = trim($_GET['phone']);
$Sec = trim($_GET['secondarymethod']);
$SecInfo = trim($_GET['secinfo']);
$Zip = trim($_GET['zip']);
$Market = trim($_GET['market']);
$Market_Location = trim($_GET['market_location']);
$Corporate_Account = trim($_GET['corpacc']);

$params = array("$Fname $Lname", $Zip, $Corporate_Account, $Phone, $Sec, $SecInfo,Date("Y-m-d H:i:s"));
$Main = MYSQL::QUERY('INSERT INTO core_customers (c_name,c_zip,c_acc,c_phone,c_contact_method,c_contact_info,c_join_date) VALUES (?,?,?,?,?,?,?)', $params);
USER::LOG("Customer Added [$Fname $Lname][$Phone]");
TRACKING::ADVERT($Market_Location, $user);
TRACKING::CUSTOMERS($user);
echo '<option value="'.str_pad($Main, 10, "0", STR_PAD_LEFT).'">'.$Fname.' '.$Lname.'</option>';
?>