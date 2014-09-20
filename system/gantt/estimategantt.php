<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);
require("gantt.class.php");
$VAR = ARRAY();
$SDATE = DATE("Y-m-d 00:00:00");
$EDATE = DATE("Y-m-d 24:59:59");
IF(ISSET($_GET['l'])){
    $CC = $_GET['l'];
} ELSE {
    $CC = 20;
}
$RE = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_status < 3 AND t_store = ? ORDER BY t_date DESC LIMIT $CC",ARRAY($user['store']));
FOREACH($RE AS $R){
    $CUST = MYSQL::QUERY("SELECT c_name FROM core_customers WHERE c_id = ? LIMIT 1", ARRAY($R['t_customer']));
	SWITCH($R['t_status']){
		CASE 1: $TA = 'core_tickets_estimate'; $ST='Estimate'; $CL='';                    BREAK;
		CASE 2: $TA = 'core_tickets_repair';   $ST='Repair';   $CL=',t_estimate_created'; BREAK;
		CASE 3: $TA = 'core_tickets_checkout'; $ST='Checkout'; $CL=',t_estimate_created'; BREAK;
		CASE 4: $TA = 'core_tickets_processed';$ST='Processed';$CL=',t_estimate_created'; BREAK;
		CASE 97:$TA = 'core_tickets_walkout';  $ST='Walkout';  $CL=',t_estimate_created'; BREAK;
		CASE 98:$TA = 'core_tickets_walkout';  $ST='Walkout';  $CL=',t_estimate_created'; BREAK;
		CASE 99:$TA = 'core_tickets_walkout';  $ST='Walkout';  $CL=',t_estimate_created'; BREAK;
	}
	$TICKET = MYSQL::QUERY("SELECT t_phy,t_liq,t_sof$CL FROM $TA WHERE t_id = ? LIMIT 1", ARRAY($R['t_id']));
	IF($R['t_tech'] > 0){
	    $TECH = MYSQL::QUERY("SELECT username FROM core_users WHERE user_id = ? LIMIT 1",ARRAY($R['t_tech']));
	} ELSE {
        $TECH['username'] = '&nbsp;';
    }
	IF($R['t_date'] >= $SDATE && $R['t_date'] <= $EDATE){} ELSE {$R['t_date'] = DATE("Y-m-d H:i:s"); $TICKET['t_estimate_created'] = DATE("Y-m-d H:i:s");}
	IF($R['t_status'] == 1){
	    $DAMAGE = 100;
		$TIME = EXPLODE(" ",$R['t_date']);
	    $time = strtotime($R['t_date']);
	    $ETIME = DATE("H:i:s",strtotime("+10 minutes",$time));
	} ELSE IF($R['t_status'] == 2 && $R['t_reserved'] == 0){
	    $DAMAGE = 101;
	    $TIME = EXPLODE(" ",$R['t_date']);
	    $time = strtotime($R['t_date']);
	    $ETIME = DATE("H:i:s",strtotime("+10 minutes",$time));
	} ELSE IF($R['t_status'] == 2 && $R['t_reserved'] == 2){
	    $DAMAGE = 102;
	    $TIME = EXPLODE(" ",$R['t_date']);
	    $time = strtotime($R['t_date']);
	    $ETIME = DATE("H:i:s",strtotime("+10 minutes",$time));
	} ELSE {
	    IF($TICKET['t_liq']==1){
	        $DAMAGE = 2;
		    $timestr = "+4 hours";
	    } ELSE IF($TICKET['t_phy']==1){
	        $DAMAGE = 1;
		    $timestr = "+45 minutes";
	    } ELSE IF($TICKET['t_sof']==1){
	        $DAMAGE = 3;
		    $timestr = "+60 minutes";
	    } ELSE {
		    $DAMAGE = 1;
		    $timestr = "+45 minutes";
	    }
		$TIME = EXPLODE(" ",$R['t_date']);
	    $time = strtotime($R['t_date']);
	    $ETIME = DATE("H:i:s",strtotime($timestr,$time));
	}
    $VAR[] = ARRAY('STIME' => $TIME[1],'ETIME' => $ETIME,'TICKET' => $R['t_id'],'MODEL' => $CUST['c_name'],'DAMAGE' => $DAMAGE, 'STATUS' => $ST, 'TECH' => $TECH['username']);
}
?>
<div style='width:100%;height:100%;overflow-x:scroll;overflow-y:hidden;text-align:center;' id="dailygantt">
	<table style="background-color: white !important;border-collapse:collapse;border-bottom:0px none;">
	    <tr>
		    <td>&nbsp;</td>
			<td style="position:absolute;left:0px;width:100%;background-color:white;border-bottom:0px solid black;height:17px;text-align:left;padding-top:0px;">
			    <div style="width:10%;font-weight:bold;display:inline-block;">Legend:</div>
				<div class='gantt-yellow' style="height:17px;width:14%;display:inline-block;border-bottom: 0px none;">Estimate Ticket</div>
				<div class='gantt-orange' style="height:17px;width:14%;display:inline-block;border-bottom: 0px none;">Accept/Walkout Ticket</div>
				<div class='gantt-purple' style="height:17px;width:14%;display:inline-block;border-bottom: 0px none;">Part on Order</div>
				<div class='gantt-red' style="height:17px;width:14%;display:inline-block;border-bottom: 0px none;">Physical Repair</div>
				<div class='gantt-blue' style="height:17px;width:14%;display:inline-block;border-bottom: 0px none;">Liquid Repair</div>
				<div class='gantt-green' style="height:17px;width:17%;display:inline-block;border-bottom: 0px none;">Software Repair</div>
			</td>
		</tr>
	</table>
    <table class="daily-gantt">
        <?php
            GANTT::RENDER(7,23,$VAR);
        ?>
    </table>
</div>
<script>
$(document).ready(function(){
    var position = $('.gantt-now').position();
    $("#dailygantt").scrollLeft(position.left - 500);
	var ch = $("#centerframe").height() - 90;
	ch = (ch/20).toFixed(0);
	ganttTimeout = setTimeout(function(){ChangeCenter('gantt/estimategantt.php?l='+ch)}, 15000);
	Pulse($(".pulse"));
});
</script>