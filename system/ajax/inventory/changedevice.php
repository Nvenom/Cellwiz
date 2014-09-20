<?php
REQUIRE("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);

$TID = $_GET['tid'];
IF(ISSET($_GET['sta'])){
    $STA = $_GET['sta'];
} ELSE {
    $STA = 'A';
}

IF($STA == 'S'){
    $MANU = $_GET['manu'];
    $MODE = $_GET['mode'];
	$T_STATUS = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1",ARRAY($TID));
	SWITCH($T_STATUS['t_status']){
		CASE 1: $TA = 'core_tickets_estimate';  BREAK;
		CASE 2: $TA = 'core_tickets_repair';    BREAK;
		CASE 3: $TA = 'core_tickets_checkout';  BREAK;
		CASE 4: $TA = 'core_tickets_processed'; BREAK;
		CASE 97:$TA = 'core_tickets_walkout';   BREAK;
		CASE 98:$TA = 'core_tickets_walkout';   BREAK;
		CASE 99:$TA = 'core_tickets_walkout';   BREAK;
	}
	$A1 = MYSQL::QUERY("UPDATE core_tickets_status SET t_device = ? WHERE t_id = ? LIMIT 1",ARRAY($MODE, $TID));
	$A2 = MYSQL::QUERY("UPDATE $TA SET t_manufacturer = ?, t_model = ? WHERE t_id = ? LIMIT 1",ARRAY($MANU, $MODE, $TID));
	IF($A1 == TRUE && $A2 == TRUE){
	    ECHO "Device Updated";
	}
} ELSE {
    $MANU = MYSQL::QUERY('SELECT m_id,m_name FROM device_manufacturers ORDER BY m_name ASC');
    $OPT = "<option value=''>Select a Manufacturer...</option>";
    FOREACH($MANU AS $M){
	    $OPT .= '<option value="'.$M['m_id'].'">'.$M['m_name'].'</option>';
    }
	ECHO <<<STR
	    <div>
		    <form>
		    <center>
	            <select id="changemanu$TID" onChange="SelectUpdate('tickets/ajax/model_template.php?load='+this.value,$('#changemode$TID'),$(this));" style="width:250px;" class="required">
		            $OPT
		        </select><br/><br/>
		        <select id="changemode$TID" style="width:250px;" class="required" onChange="$(this)." disabled="disabled">
		        </select>
			</center>
			</form>
		</div>
STR;
}
?>