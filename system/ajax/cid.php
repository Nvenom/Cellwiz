<?php 
	require("../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);

	$MAIN = MYSQL::QUERY('SELECT * FROM core_customers WHERE c_name LIKE ? OR c_phone LIKE ? ORDER BY c_name ASC LIMIT 0,10', ARRAY('%'.$_GET['string'].'%','%'.$_GET['string'].'%'));
	IF(!EMPTY($MAIN)){
	    IF(!EMPTY($MAIN[1])){
		    ECHO 'MUL|<div><table id="custTable" class="stylized"><thead><tr><th>Name</th><th>Phone</th><th>Select</th></tr></thead><tbody>';
			FOREACH($MAIN AS $C){
			    ECHO '<tr><td>'.$C['c_name'].'</td><td>'.FORMAT::PHONE($C['c_phone']).'</td><td><button onClick="LoadCustomer('."'".$C['c_id']."'".')">LOAD</button></tr>';
			}
			$R = MYSQL::QUERY('SELECT COUNT(c_id) FROM core_customers WHERE c_name LIKE ? OR c_phone LIKE ?', ARRAY('%'.$_GET['string'].'%','%'.$_GET['string'].'%'));
			ECHO '</tbody><tfoot><tr><th>Name</th><th>Phone</th><th>Select</th></tr></tfoot></table></div>|'.$R[0]['COUNT(c_id)'];
		} ELSE {
		    ECHO 'SIN|'.$MAIN[0]['c_id'];
		}
	} ELSE {
	    ECHO 'NAN|';
	}
?>