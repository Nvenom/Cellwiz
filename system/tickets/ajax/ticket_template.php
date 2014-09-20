<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$amount = $_POST['device_amount'];
$customer = $_POST['customerid'];

$ses = FORMAT::SES(12);

$params = array();
$date = Date("Y-m-d H:i:s");
$query = 'INSERT INTO core_tickets_estimate (t_customer,t_manufacturer,t_model,t_imei,t_password,t_phy,t_liq,t_sof,t_created_by,t_store,t_session,t_created) VALUES ';

$modelsused = array();
if(isset($_POST["customerid"])){
    $i = 1;
    while($i <= $amount){
        if(isset($_POST["manu$i"])){
	        if(isset($_POST["model$i"])){
			    if(isset($_POST["imei$i"])){
				    if(isset($_POST["issue$i"])){
					    if(isset($_POST["pass$i"])){
                            if(isset($_POST["phy$i"])){$phy = 1;} else {$phy = 2;}
	                        if(isset($_POST["liq$i"])){$liq = 1;} else {$liq = 2;}
	                        if(isset($_POST["sof$i"])){$sof = 1;} else {$sof = 2;}
							$modelsused[] = $_POST["model$i"];
	                        array_push($params, $_POST["customerid"], $_POST["manu$i"], $_POST["model$i"], $_POST["imei$i"], $_POST["pass$i"], $phy, $liq, $sof, $user['user_id'], $user['store'], $ses, $date);
	                        if($i == 1){$query .= '(?,?,?,?,?,?,?,?,?,?,?,?)';} else {$query .= ',(?,?,?,?,?,?,?,?,?,?,?,?)';}
	                        $i++;
						} else {die("pass$i");}
					} else {die("issue$i");}
				} else {die("imei$i");}
	        } else {die("model$i");}
		} else {die("manu$i");}
    }
}

if(!empty($params)){
    if(!empty($query)){
	    $Main = MYSQL::QUERY($query, $params);
		TRACKING::TICKETS($modelsused,$user);
		$customer = MYSQL::QUERY("SELECT * FROM core_customers WHERE c_id = ? LIMIT 1", ARRAY($_POST["customerid"]));
		switch($customer['c_contact_method']){ 
		    CASE 0:$contactm = FORMAT::PHONE($customer['c_contact_info']);BREAK;
			CASE 1:$contactm = $customer['c_contact_info'];BREAK;
		    CASE 2:$contactm = 'Customer Will Contact Us';BREAK;
			CASE 3:$contactm = FORMAT::PHONE($customer['c_phone']);BREAK;
		}
echo "
<style>
.escca td{
    border-top: 0px;
    border-right: 0px;
}
</style>
";
$content = "
<center style='width:724px;'>
    <table border='1' cellspacing='0' id='Header-Table' class='escca' style='margin-top:10px;border: 1px solid black;width: 98% !important;border-left: 0px;border-bottom:0px none !important;'>
	    <thead>
		    <tr>
			    <td style='width:150px;height:150px;border-right:0px none;'><img src='https://my-cpr.com/cprlogo.jpg' border='0'></td>
				<td style='border-left:0px none;border-right:0px none;text-align:center;'>
				    <div style='font-size:28px;'>
					    <label style='font-size:32px;'><b>".$user['store_info']['s_header']."</b></label><br/>
						<label style='font-size:24px;'>Contact our ".$user['store_info']['s_name']." Location at</label><br/>
						<label>".FORMAT::PHONE($user['store_info']['s_phone'])."</label><br/>
						<label><b>".$user['store_info']['s_website']."</b></label>
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
			    <td>".$customer['c_name']."</td>
				<td>".$contactm."</td>
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
		$params = array($ses);
		$Main = MYSQL::QUERY("SELECT * FROM core_tickets_estimate WHERE t_session=? ORDER BY t_id ASC", $params);
        $i = 1;
		$nt = '';
        while($i <= $amount){
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
		$SubParams = array($_POST["customerid"]);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: no-reply@my-cpr.com' . "\r\n";
		if(!$customer['c_contact_method'] == 1){
			echo $content;
		} else {
		    echo $content; //mail($customer['c_contact_method'], 'Cell Phone Repair - Check In', $content, $headers);
		}
		
        $ticks = explode(',',$nt);
		$params = array();
		$params_note = array();
		$qu = 'INSERT INTO core_tickets_status (t_id,t_customer,t_device,t_imei,t_created_by,t_store,t_date) VALUES ';
		$qu_note = 'INSERT INTO core_tickets_note (t_id,t_note,t_note_by,t_type,t_date) VALUES ';
		$i = 1;
		foreach($ticks as $ti){
		    if($ti == ""){} else {
		        $ti = explode('/',$ti);
			    array_push($params, $ti[0], $_POST["customerid"], $modelsused[$i-1], $ti[1], $user['user_id'], $user['store'],$date);
				array_push($params_note, $ti[0], $_POST["issue$i"], $user['user_id'], 3, $date);
			    if($i == 1){$qu .= '(?,?,?,?,?,?,?)';$qu_note .= '(?,?,?,?,?)';} else {$qu .= ',(?,?,?,?,?,?,?)';$qu_note .= ',(?,?,?,?,?)';}
				$i++;
			}
		}
		MYSQL::QUERY($qu, $params);
		MYSQL::QUERY($qu_note, $params_note);
		$i--;
		$params = array($i,$_POST["customerid"]);
		MYSQL::QUERY('UPDATE core_customers SET c_tickets = c_tickets + ? WHERE c_id = ?', $params);
		USER::STAT('tickets',$i);
	    USER::MEDAL('bronze',$i);
	} else {
	    die('E1423');
	}
} else {
    die('E1424');
}
?>