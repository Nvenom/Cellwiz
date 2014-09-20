<?php 
	require("../../frame/engine.php");ENGINE::START();
    $USER = USER::VERIFY(0,TRUE);

	$CODE = $_GET['tid'];
	$TICKET = ENGINE::TICKETINFO($CODE);
	if(!ISSET($_GET['from'])){USER::NOTE($_GET['tid'],"(".$USER['username'].") Loaded this Ticket",1,4);}
		$CO = "DISABLED";$RT = "DISABLED";$ET = "DISABLED";$AE = "DISABLED";$WT = 'OnClick="Walkout('."'".$CODE."'".')"';$LC = "DISABLED";$WTA = 'Walkout Ticket';
		$BUTTONS = ARRAY(
			'B1' => 'Load Customer'    ,  'B1_F' => 'onClick="LoadCustomer('."'".$TICKET['CUST']['c_id']."'".')"',
			'B2' => 'Load Corporation' ,  'B2_F' => 'DISABLED',
			'B3' => 'Walkout Ticket'   ,  'B3_F' => 'OnClick="Walkout('."'".$CODE."'".')"',
			'B4' => 'Estimate Ticket'  ,  'B4_F' => 'onClick="Estimate('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".')"',
			'B5' => 'Print Check-In'   ,  'B5_F' => 'onClick="PrintCheckIn('."'".$CODE."'".')"',
			'B6' => 'Load Device info'    ,  'B6_F' => 'DISABLED',
			'B7' => 'Change Device'    ,  'B7_F' => 'onClick="ChangeDevice('."'".$CODE."'".')"'
		);
		
		SWITCH($TICKET['STATUS']['t_status']){
		    CASE 1:
				$BUTTONS['B4']   = 'Estimate Ticket';
				$BUTTONS['B4_F'] = 'onClick="Estimate('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".')"';
				BREAK;
			CASE 2:
				IF($TICKET['STATUS']['t_reserved'] == 0){
					$BUTTONS['B4']   = 'Accept Estimate';
				    $BUTTONS['B4_F'] = 'onClick="AcceptEstimate('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".')"';			
				} ELSE IF($TICKET['STATUS']['t_reserved'] == 1){
				    $BUTTONS['B4']   = 'Repair Device';
					$BUTTONS['B4_F'] = 'onClick="Repair('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".')"';
					$BUTTONS['B5']   = 'Part on Order';
					$BUTTONS['B5_F'] = 'onClick="PartOnOrder('."'".$CODE."'".')"';
				} ELSE {
				    $BUTTONS['B4']   = 'Repair Device';
					$BUTTONS['B4_F'] = 'onClick="Repair('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".')"';
					$BUTTONS['B5']   = 'Part on Order';
					$BUTTONS['B5_F'] = 'DISABLED';
				}
				BREAK;
			CASE 3:
				$BUTTONS['B4']   = 'Checkout Ticket';
				$BUTTONS['B4_F'] = 'onClick="Checkout('."'".$CODE."'".', '.$TICKET['INFO']['t_model'].', '.$TICKET['MODE']['m_type'].', '."'".$TICKET['MODE']['m_date']."'".', '.$USER['store_info']['s_taxrate'].')"';
				BREAK;
			CASE 4:
				$BUTTONS['B3']   = 'Warranty Claim';
				$BUTTONS['B3_F'] = 'DISABLED';
				$BUTTONS['B4']   = 'Print Receipt';
				$BUTTONS['B4_F'] = 'onClick="PrintReceipt('."'".$CODE."'".')"';
				$BUTTONS['B5']   = 'Print Check-In';
				$BUTTONS['B5_F'] = 'DISABLED';
				$BUTTONS['B6']   = 'Load Device Info';
				$BUTTONS['B6_F'] = 'DISABLED';
				$BUTTONS['B7']   = '...';
				$BUTTONS['B7_F'] = 'DISABLED';
				BREAK;
			CASE 97:
			    $BUTTONS['B3_F'] = 'DISABLED';
			    $BUTTONS['B4']   = 'Re-Open Ticket';
				$BUTTONS['B4_F'] = 'onClick="ReopenTicket('."'".$CODE."'".')"';
			    BREAK;
			CASE 98:
			    $BUTTONS['B3_F'] = 'DISABLED';
			    $BUTTONS['B4']   = 'Re-Open Ticket';
				$BUTTONS['B4_F'] = 'onClick="ReopenTicket('."'".$CODE."'".')"';
			    BREAK;
			CASE 99:
			    $BUTTONS['B3_F'] = 'DISABLED';
			    $BUTTONS['B4']   = 'Re-Open Ticket';
				$BUTTONS['B4_F'] = 'onClick="ReopenTicket('."'".$CODE."'".')"';
			    BREAK;
		}
		
		$PHY = ($TICKET['INFO']['t_phy'] == 1 ? "Yes" : "No");
		$LIQ = ($TICKET['INFO']['t_liq'] == 1 ? "Yes" : "No");
		$SOF = ($TICKET['INFO']['t_sof'] == 1 ? "Yes" : "No");
		$MANU = $TICKET['MANU']['m_name'];
		$MODEL = str_replace($MANU." ", "", $TICKET['MODE']['m_name']);
		$PHONE = FORMAT::PHONE($TICKET['CUST']['c_phone']);
		$CD = date("M d, Y h:i A", strtotime($TICKET['INFO']['t_created']));
		IF(EMPTY($TICKET['INFO']['t_estimate_created'])){ $ED = ""; } ELSE {
		    $ED = date("M d, Y h:i A", strtotime($TICKET['INFO']['t_estimate_created']));
		}
		IF(EMPTY($TICKET['INFO']['t_repair_created'])){ $RD = ""; } ELSE {
		    $RD = date("M d, Y h:i A", strtotime($TICKET['INFO']['t_repair_created']));
		}
		IF(EMPTY($TICKET['INFO']['t_checkout_created'])){ $CHD = ""; } ELSE {
		    $CHD = date("M d, Y h:i A", strtotime($TICKET['INFO']['t_checkout_created']));
		}
		echo <<<STR
			<div style="width:275px;height:200px;float:left;" id="tsbt$CODE">
				<button type="button" {$BUTTONS['B7_F']} > {$BUTTONS['B7']} </button><br/>
				<button type="button" {$BUTTONS['B6_F']} > {$BUTTONS['B6']} </button><br/>
				<button type="button" {$BUTTONS['B5_F']} > {$BUTTONS['B5']} </button><br/>
				<button type="button" {$BUTTONS['B4_F']} > {$BUTTONS['B4']} </button><br/>
				<button type="button" {$BUTTONS['B3_F']} > {$BUTTONS['B3']} </button><br/>
				<button type="button" {$BUTTONS['B2_F']} > {$BUTTONS['B2']} </button><br/>
				<button type="button" {$BUTTONS['B1_F']} > {$BUTTONS['B1']} </button>
			</div>
			<div style="width:203px;height:190px;float:left;margin-left:15px;overflow-y:scroll;">
			        <div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>CUSTOMER</center></div><br/>
				    <div style="width:100%;">
					    <div class="aname">Name:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['CUST']['c_name']}</div>
						<br/>
					    <div class="aname">Contact#:</div>
						<div class="bname" style="padding-right:2px;">$PHONE</div>
					</div><br/><br/>
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>DEVICE</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">Manufacturer:</div>
						<div class="bname" style="padding-right:2px;">$MANU</div>
						<br/>
					    <div class="aname">Model:</div>
						<div class="bname" style="padding-right:2px;">$MODEL</div>
						<br/>
					    <div class="aname">Password:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_password']}</div>
						<br/>
					    <div class="aname">Physical Damage:</div>
						<div class="bname" style="padding-right:2px;">$PHY</div>
						<br/>
					    <div class="aname">Liquid Damage:</div>
						<div class="bname" style="padding-right:2px;">$LIQ</div>
					    <br/>
					    <div class="aname">Software Issues:</div>
						<div class="bname" style="padding-right:2px;">$SOF</div>
					    <br/>
					    <div class="aname">IDEN:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_imei']}</div>
					</div><br/><br/>
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>CREATED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">BY:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['USER']['username']}</div>
						<br/>
					    <div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$CD</div>
					</div><br/><br/>
STR;
IF(!EMPTY($ED)){ECHO <<<STR
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>ESTIMATED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">PRICE:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_estimate_price']}</div>
						<br/>
						<div class="aname">TIME:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_estimate_time']}</div>
						<br/>
						<div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$ED</div>
					</div><br/><br/>
STR;
}IF(!EMPTY($RD)){ECHO <<<STR
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>REPAIRED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">PRICE:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_repair_price']}</div>
						<br/>
						<div class="aname">TIME:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_repair_time']}</div>
						<br/>
						<div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$RD</div>
					</div><br/><br/>
STR;
}IF(!EMPTY($CHD)){ECHO <<<STR
				    <div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>CHECKED OUT</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">PRICE:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_checkout_price']}</div>
						<br/>
						<div class="aname">TIME:</div>
						<div class="bname" style="padding-right:2px;">{$TICKET['INFO']['t_checkout_time']}</div>
						<br/>
						<div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$CHD</div>
					</div>
STR;
} ECHO <<<STR
				</div>
				<div style="width:491px; height:235px;">
				    <div class="txt_dsb" style="width:100%;height:235px;margin:0px !important;">
					    <h4 class="ui-widget-header" style="margin:0px;border: 0px;border-top-right-radius: 3px;border-top-left-radius: 3px;">
						    Ticket Notes
						    <select style="position: absolute;right: -2px;top: -2px;height: 24px;width: 140px;">
							    <option value="none">No Filter</option>
							    <option value="user" style="background-color: rgb(255, 255, 255);">User Messages</option>
								<option value="system" style="background-color: rgb(168, 168, 168);">System Messages</option>
								<option value="status" style="background-color: rgb(255, 150, 150);">Status Changes</option>
								<option value="original" style="background-color: rgb(150, 200, 255);">Original Message</option>
							</select>
						</h4>
						<div style="width:100%;height:180px;overflow-y: scroll;border-bottom: 1px solid #ACB1B7;margin-bottom: 2px;" id="notebox">
                            <ul style="list-style-type:none;">
STR;
                        $ln = '';
						$noteuser = '';
						$notes = MYSQL::QUERY("SELECT * FROM core_tickets_note WHERE t_id = ? ORDER BY t_date DESC", ARRAY($CODE));
						foreach($notes as $n){
						    if($ln == $n['t_note_by']){}else{
							    if($n['t_note_by']==4){
								    $noteuser = array('username' => 'System');
								} else {
			                    $noteuser = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", array($n['t_note_by']));
								$ln = $n['t_note_by'];
								}
							}
							if($n['t_note_by'] == $USER['user_id']){$ed = "forget";}  else {$ed = "fogret";}
							switch($n['t_type']){
							    case 0:$color='usernote';break;
							    case 1:$color='systemnote';break;
								case 2:$color='statusnote';break;
								case 3:$color='originalnote';break;
							}
						    echo'
							    <li class="'.$color.'"><b>'.date("[m/d/y] h:i A", strtotime($n['t_date'])).' - '.$noteuser['username'].': </b><font class="'.$ed.'" id="note'.$n['note_id'].'">'.$n['t_note'].'</font></li>
							';
						}
						echo '
						    </ul>
						</div>
						<input type="text" style="width:405px;margin-left:2px;margin-right:2px;" placeholder="Type Note Here..." id="addnotetext" onKeyup="AddTicketNote(event, '."'".$CODE."'".')"><div style="width:76px;display:inline-block;"><button id="AddTicketNote'.$CODE.'" onClick="AddTicketNote(13, '."'".$CODE."'".')">Add Note</button></div>
					</div>
				</div>';
?>