<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$string = $_GET['string'];

$Main = MYSQL::QUERY('SELECT c_id,c_name,c_phone FROM core_customers WHERE c_card = ? LIMIT 1', ARRAY($string));
if(!$Main == ""){
        ECHO '<option value='.$Main['c_id'].'>'.$Main['c_name'].' ~ '.FORMAT::PHONE($Main['c_phone']).'</option>';
} else {
    echo '<option value="">0 Customer(s) Found...</option>';
}
?>