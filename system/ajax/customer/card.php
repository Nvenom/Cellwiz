<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$CID = $_GET['cid'];
$CARD = $_GET['card'];
$TID = $_GET['tid'];

$CUSTOMER = MYSQL::QUERY('SELECT * FROM core_customers WHERE c_id = ? LIMIT 1',ARRAY($CID));
IF(EMPTY($CUSTOMER['c_card']) || $CUSTOMER['c_card'] == $CARD){
    IF(EMPTY($CUSTOMER['c_card'])){
	    $CS = MYSQL::QUERY('SELECT c_id FROM core_customers WHERE c_card = ? LIMIT 1',ARRAY($CARD));
		IF(EMPTY($CS)){
		    ECHO "This customer has no card and this card is not in use. Please Enter their Primary email.<br/><br/><input type='email' placeholder='Customers Email Address..' style='width:280px;'><br/>
			<button style='cursor:pointer;width:287px;' onClick='AttachCard($(this),".'"'.$CID.'"'.",".'"'.$CARD.'"'.",".'"'.$TID.'"'.",".'"'.$user['store_info']['s_taxrate'].'"'.")'>Attach Card</button>";
		} ELSE {
		    ECHO "This card belongs to someone else...";
		}
	} ELSE {
	    ECHO "Valid Card. 5% Off Applied.
        <script>AddDiscount('5','Membership Card','.ticket".$TID."','".$user['store_info']['s_taxrate']."','".$TID."');</script>";
	}
} ELSE {
    ECHO "This Customer has a different Card..";
}
?>