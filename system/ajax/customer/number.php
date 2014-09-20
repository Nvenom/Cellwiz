<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$R = MYSQL::QUERY("SELECT c_name, c_phone FROM core_customers WHERE c_phone = ?",ARRAY($_GET['val']));
IF(EMPTY($R)){
    ECHO '1|No Customer Found with this Number';
} else {
    ECHO '0|<b>Customer(s) Found Already using this Number:</b><br/>';
	FOREACH($R AS $C){
	    ECHO "<br/>".$C['c_name']." - ". FORMAT::PHONE($C['c_phone']);
	}
}
?>