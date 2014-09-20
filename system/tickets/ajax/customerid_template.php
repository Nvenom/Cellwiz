<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$string = $_GET['string'];
$string = str_replace('-', '', $string);
$string = '%'.$string.'%';

$params = array(':phone' => $string, ':name' => $string);
$Main = MYSQL::QUERY('SELECT c_id,c_name,c_phone FROM core_customers WHERE c_phone LIKE :phone OR c_name LIKE :name', $params);
if(!$Main == ""){
	$options = "";
	$i = 0;
    foreach($Main as $a){
        $options .= '<option value='.$a['c_id'].'>'.$a['c_name'].' ~ '.FORMAT::PHONE($a['c_phone']).'</option>';
		$i++;
    }
	echo '<option value="">'.$i.' Customer(s) Found...</option>'.$options;
} else {
    echo '<option value="">0 Customer(s) Found...</option>';
}
?>