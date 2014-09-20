<?php
REQUIRE("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);

$RESULT = MYSQL::QUERY("SELECT * FROM core_checkout_sessions WHERE items LIKE ? LIMIT 1",ARRAY('%|ti-'.$_GET['tid'].'%'));
$T = MYSQL::QUERY("SELECT * FROM core_customers WHERE c_id = ? LIMIT 1",ARRAY($RESULT['customer']));
$pm1 = $RESULT['pm_1'];
$pm2 = $RESULT['pm_2'];
$pm1cost = $RESULT['pm_1_cost'];
$pm2cost = $RESULT['pm_2_cost'];
if(!$pm2 == 'None'){
	$paymentmethod = $pm1.' ('.$pm1cost.'), '.$pm2.' ('.$pm2cost.')';
} else {
	$paymentmethod = $pm1;
}
$ITEMSCUT = explode("|", $RESULT['items']);
$CONTENT = "
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
				    <td><b>Phone Number</b></td>
				    <td><b>Date (M/D/Y)</b></td>
				    <td><b>Time</b></td>
			    </tr>
			    <tr style='font-family:Courier, monospace;'>
			        <td>".$T['c_name']."</td>
				    <td>".FORMAT::PHONE($T['c_phone'])."</td>
				    <td>".Date('m/d/y')."</td>
				    <td>".Date('h:i A')."</td>
			    </tr>
		    </tbody>
	    </table>
        <table border='1' cellspacing='0' id='Device-Table' class='escca' style='border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;border-top:0px none;'>
	        <thead>
		        <tr>
			        <td><b>Service/Item<b/></td>
					<td style='width:60px'><center><b>Physical</b></center></td>
				    <td style='width:60px'><center><b>Software</b></center></td>
					<td><b>Cost</b></td>
			    </tr>
		    </thead>
		    <tbody style='font-family:Courier, monospace;'>
				";
				$nontaxable = '';
				$taxable = '';
				foreach($ITEMSCUT as $item){
					$split = explode("/", $item);
					$b = explode("-", $split[0]);
					if($b[0] == "ti"){
						$NT = MYSQL::QUERY("SELECT t_phy, t_liq, t_sof FROM core_tickets_processed WHERE t_id = ? LIMIT 1;", ARRAY($b[1]));
						IF($NT['t_liq'] == 1){
							$warr = false;
						} else {
							$warr = true;
						}
						if($NT['t_liq'] == 1){$liq='None';$w=0;} else {$liq='None';$w=0;}
			            if($NT['t_phy'] == 1){if($warr == false){$phy='None';$w=0;} else {$phy='6 Months';$w=6;}} else {$phy='None';$w=0;}
			            if($NT['t_sof'] == 1){if($warr == false){$sof='None';$w=0;} else {$sof='6 Months';$w=6;}} else {$sof='None';$w=0;}
						$CONTENT .= "<tr>
						    <td>Ticket #".$b[1]."</td>
							<td style='width:60px;'><center>$phy</center></td>
							<td style='width:60px;'><center>$sof</center></td>
							<td>$".$split[1]."</td>
						</tr>";
						$nontaxable = $nontaxable + $split[1];
					}
					if($b[0] == "ac"){
						$taxable = $taxable + $split[1];
						$ITEM = MYSQL::QUERY('SELECT * FROM device_accessories WHERE a_id = ? LIMIT 1', ARRAY($b[1]));
						$CONTENT .= "<tr>
						    <td>".$ITEM['a_name']."</td>
							<td style='width:60px;'></td>
							<td style='width:60px;'></td>
						    <td>$".$split[1]."</td>
						</tr>";
					}
				}
				$TOTAL = $taxable + $nontaxable;
				$CONTENT .= "
			    <tr>
					<td colspan='3'>$paymentmethod</td>
					<td colspan='1'><center><b>Total:</b> $$TOTAL</center></td>
			    </tr>
		    </tbody>
        </table>
		<table border='1' cellspacing='0' id='Disclaimer-Table' class='escca' style='border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;border-top: 0px none;'>
	        <tbody>
		        <tr style='text-align:center;'>
			        <td><b>Warranty Information</b></td>
			    </tr>
			    <tr>
			        <td><br/>
						<b>Physical Warranty</b> - This warranty covers any parts replaced in the repair proccess. If the part we replaced proves to be defective in any way we will replace it at no extra charge.
						This warranty is void if the part sustains any physical or liquid damage or if it is removed from the device. If your phone came in with liquid damage there will be no warranty offered on any
						part that we install. Any aftermarket parts that are brought in by customers are not covered by any warranties.
						<br/><br/>
						<b>Software Warranty</b> - This warranty guarantees any software service we provide you. If for any reason the phone is updated, modified (ie. jailbreaking, unlocking, rooting) or sustains
						physical or liquid damage the warranty becomes void.
						<br/><br/>
					    <b>Liquid Damage</b> - We <b>do not</b> provide a warranty for any liquid damaged devices that we repair. This include any parts replaced or software services done on any device. Furthermore
						any device that we provide a service for that becomes liquid damaged will lose its warranty. No Exceptions.<br/><br/>
				    </td>
			    </tr>
		    </tbody>
	    </table>
    </center>		
";

ECHO $CONTENT;
?>