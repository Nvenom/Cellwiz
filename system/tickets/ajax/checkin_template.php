<?php
REQUIRE("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);
$TICKET = ENGINE::TICKETINFO($_GET['tid']);
$content = "
<style>
.escca td{
    border-top: 0px;
    border-right: 0px;
}
</style>
<center style='width:724px;'>
    <table border='1' cellspacing='0' id='Header-Table' class='escca' style='margin-top:10px;border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;'>
	    <thead>
		    <tr>
			    <td style='width:150px;height:150px;border-right:0px none;'><img src='https://my-cpr.com/cprlogo.jpg' border='0'></td>
				<td style='border-left:0px none;border-right:0px none;text-align:center;'>
				    <div style='font-size:28px;'>
					    <label style='font-size:32px;'><b>".$USER['store_info']['s_header']."</b></label><br/>
						<label style='font-size:24px;'>Contact our ".$USER['store_info']['s_name']." Location at</label><br/>
						<label>".FORMAT::PHONE($USER['store_info']['s_phone'])."</label><br/>
						<label><b>".$USER['store_info']['s_website']."</b></label>
					</div>
				</td>
			</tr>
		</thead>
	</table>
	<table border='1' cellspacing='0' id='Customer-Table' class='escca' style='border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;border-top:0px none;'>
	    <tbody>
		    <tr>
			    <td><b>Name</b></td>
				<td><b>Contact</b></td>
				<td><b>Date (M/D/Y)</b></td>
				<td><b>Time</b></td>
			</tr>
			<tr style='font-family:Courier, monospace;'>
			    <td>".$TICKET['CUST']['c_name']."</td>
				<td>".$TICKET['CUST']['c_contact_info']."</td>
				<td>".Date('m/d/y')."</td>
				<td>".Date('h:i A')."</td>
			</tr>
		</tbody>
	</table>
    <table border='1' cellspacing='0' id='Device-Table' class='escca' style='border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;border-top:0px none;'>
	    <thead>
		    <tr>
			    <td><b>Device</b></td>
			    <td><b>Ticket</b></td>
				<td><b>IMEI</b></td>
				<td style='width:60px'><center><b>Physical</b></center></td>
				<td style='width:60px'><center><b>Liquid</b></center></td>
				<td style='width:60px'><center><b>Software</b></center></td>
				<td style='width:60px'><center><b>Estimate</b></center></td>
				<td style='width:60px'><center><b>Est. Init.</b></center></td>
				<td style='width:60px'><center><b>QC. Init.</b></center></td>
			</tr>
		</thead>
		<tbody style='font-family:Courier, monospace;'>
";
		$Main = MYSQL::QUERY("SELECT * FROM core_tickets_estimate WHERE t_session=? ORDER BY t_id ASC", ARRAY($TICKET['INFO']['t_session']));
		$amount = MYSQL::QUERY("SELECT COUNT(t_id) FROM core_tickets_estimate WHERE t_session=? ORDER BY t_id ASC", ARRAY($TICKET['INFO']['t_session']));
        $i = 1;
		$nt = '';
        while($i <= $amount[0]['COUNT(t_id)']){
		    $a = $i - 1;
			if($Main[$a]['t_liq'] == 1){$liq='&#x2713;';} else {$liq='&#x2717;';}
			if($Main[$a]['t_phy'] == 1){$phy='&#x2713;';} else {$phy='&#x2717;';}
			if($Main[$a]['t_sof'] == 1){$sof='&#x2713;';} else {$sof='&#x2717;';}
			$params = array($Main[$a]['t_model']);
			$mod = MYSQL::QUERY("SELECT m_name FROM device_models WHERE m_id=? LIMIT 1", $params);
            $content .= "		
		    <tr>
			    <td rowspan='2'>$i</td>
				<td style='position:relative;'>
				    <center>
				        <img src='https://my-cpr.com/barcode.php?encode=I25&height=20&scale=1&color=000000&bgcolor=FFFFFF&type=png&file=&bdata=".$Main[$a]['t_id']."' border='0' />
			            <font style='position: absolute;bottom: 0px;left: 18px;'>".$Main[$a]['t_id']."</font>
					</center>
				</td>
				<td>".substr($Main[$a]['t_imei'], -4)."</td>
				<td style='width:60px;font-size:23px'><center>$phy</center></td>
				<td style='width:60px;font-size:23px'><center>$liq</center></td>
				<td style='width:60px;font-size:23px'><center>$sof</center></td>
				<td style='width:60px;position:relative;'><font style='position:absolute;bottom:0px;right:2px;font-size:6px;'>A$i</font></td>
				<td style='width:60px;position:relative;'><font style='position:absolute;bottom:0px;right:2px;font-size:6px;'>B$i</font></td>
				<td style='width:60px;position:relative;'><font style='position:absolute;bottom:0px;right:2px;font-size:6px;'>C$i</font></td>
			</tr>
		    <tr>
			    <td colspan='9'><b>Device:</b> ".$mod['m_name']."</td>
			</tr>";
			$nt .= $Main[$a]['t_id'].'/'.$Main[$a]['t_imei'].',';
			$i++;
		}		
$content .= "
		</tbody>
    </table>
	<table border='1' cellspacing='0' id='Disclaimer-Table' class='escca' style='border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;border-top: 0px none;'>
	    <tbody>
		    <tr style='text-align:center;'>
			    <td colspan='2'><b>Device Repair Disclaimer</b></td>
			</tr>
			<tr>
			    <td colspan='2'><br/>
				    By initialing here ________ you agree to allow us to take your device(s) apart
					in order to give you an accurate estimate for the repair. You understand that
					CPR will not be held responsible for any loss of functionality and or Data.
					<br/><br/>
					By initialing in the boxes (EX. B1) next to the estimates above (EX. A1) you 
					agree to the price associated with that device. Estimates are subject to change at any point 
					and you will be contacted for approval before any additional repairs are completed.
					<br/><br/>
					By initialing in the quality control box (EX. C1) you agree that the device has been returned to you in working order.
					You agree that the phone is fully functional and you release CPR from any and all liablity concering your device.<br/><Br/>
				</td>
			</tr>
		</tbody>
	</table>
</center>		
";

ECHO $content;
?>