<?php
require("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);

$CID = $_GET['cid'];
$CUST = MYSQL::QUERY("SELECT * FROM core_customers WHERE c_id = ? LIMIT 1",ARRAY($CID));

$PHONE = FORMAT::PHONE($CUST['c_phone']);

SWITCH($CUST['c_contact_method']){
    CASE 0:$CONT = FORMAT::PHONE($CUST['c_contact_info']); BREAK;
	CASE 1:$CONT = $CUST['c_contact_info'];                BREAK;
	CASE 2:$CONT = 'Customer Will Contact Us';             BREAK;
	CASE 3:$CONT = FORMAT::PHONE($CUST['c_phone']);        BREAK;
}

$CORP = MYSQL::QUERY("SELECT * FROM core_corporate_accounts WHERE c_id = ? LIMIT 1", ARRAY($CUST['c_acc']));

ECHO <<<STR
    <div style="width:240px;height:95px;display:inline-block;float:left;border-right: 1px solid #ACB1B7;padding-right:5px;">
		<div style="width:100%;">
			<div class="aname">Name:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_name']}</div>
			<br/>
			<div class="aname">Primary Phone:</div>
			<div class="bname" style="padding-right:2px;">$PHONE</div>
			<br/>
			<div class="aname">Contact:</div>
			<div class="bname" style="padding-right:2px;">$CONT</div>
			<br/>
			<div class="aname">ZIP:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_zip']}</div>
			<br/>
			<div class="aname">Corporation:</div>
			<div class="bname" style="padding-right:2px;">{$CORP['c_name']}</div>
			<br/>
			<div class="aname">Account Created:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_join_date']}</div>
		</div>
	</div>
	<div style="width:240px;height:95px;display:inline-block;float:right;padding-left:5px;">
		<div style="width:100%;">
			<div class="aname">Tickets:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_tickets']}</div>
			<br/>
			<div class="aname">Checkouts:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_checkouts']}</div>
			<br/>
			<div class="aname">Walkouts:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_walkouts']}</div>
			<br/>
			<div class="aname">Value:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_value']}</div>
			<br/>
			<div class="aname">Loss:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_loss']}</div>
			<br/>
			<div class="aname">Rating:</div>
			<div class="bname" style="padding-right:2px;">{$CUST['c_rating']}</div>
		</div>
	</div><br/><br/>
	<button style="width:49%;margin-top: 20px;margin-bottom:10px;float:left;" onClick="$('#customertickets{$CID}_wrapper').hide();$('#customernotes{$CID}').fadeIn();">Customer Notes</button>
	<button style="width:49%;margin-top: 20px;margin-bottom:10px;float:right;" onClick="$('#customertickets{$CID}_wrapper').fadeIn();$('#customernotes{$CID}').hide();">Customer Tickets</button><br/>
	<table id='customertickets$CID' class='stylized'>
	    <thead>
		    <th>Ticket</th>
			<th>Device</th>
			<th>Status</th>
			<th>Select</th>
		</thead>
		<tbody>
STR;

$TICKETS = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_customer = ? ORDER BY t_id DESC", ARRAY($CID));
FOREACH($TICKETS AS $T){
    $MODEL = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id = ? LIMIT 1", ARRAY($T['t_device']));
	SWITCH($T['t_status']){
		CASE 1:$ST = "Estimate";  BREAK;
		CASE 2:$ST = "Repair";    BREAK;
		CASE 3:$ST = "Checkout";  BREAK;
		CASE 4:$ST = "Processed"; BREAK;
		CASE 97:$ST = "Walkedout"; BREAK;
		CASE 98:$ST = "Walkedout"; BREAK;
		CASE 99:$ST = "Walkedout"; BREAK;
	}
	ECHO <<<STR
	    <tr>
		    <td>{$T['t_id']}</td>
			<td>{$MODEL['m_name']}</td>
			<td>$ST</td>
			<td><button onClick="LoadTicket('{$T['t_id']}')">LOAD</button></td>
		</tr>
STR;
}

ECHO <<<STR
        </tbody>
	    <tfoot>
	        <th>Ticket</th>
		    <th>Device</th>
		    <th>Status</th>
		    <th>Select</th>
	    </tfoot>
    </table>
	<div id="customernotes$CID" style="height:335px;border:1px solid black;display:none;margin-top:96px;border-radius: 3px;border: 1px solid #ACB1B7;">
<div class="txt_dsb" style="width:100%;height:auto;margin:0px !important;border:0px;">
					    <h4 class="ui-widget-header" style="margin:0px;border: 0px;border-top-right-radius: 3px;border-top-left-radius: 3px;">
						    Customer Notes
						    <select style="position: absolute;right: -2px;top: -2px;height: 24px;width: 140px;">
							    <option value="none">No Filter</option>
							    <option value="user" style="background-color: rgb(255, 255, 255);">User Messages</option>
								<option value="system" style="background-color: rgb(168, 168, 168);">System Messages</option>
								<option value="status" style="background-color: rgb(255, 150, 150);">Status Changes</option>
								<option value="original" style="background-color: rgb(150, 200, 255);">Original Message</option>
							</select>
						</h4>
						<div style="width:100%;height:280px;overflow-y: scroll;border-bottom: 1px solid #ACB1B7;margin-bottom: 2px;" id="notebox">
                            <ul style="list-style-type:none;">
STR;
                        $ln = '';
						$noteuser = '';
						$notes = MYSQL::QUERY("SELECT * FROM core_customers_note WHERE c_id = ? ORDER BY c_date DESC", ARRAY($CID));
						if($notes[0] != ''){
						foreach($notes as $n){
						    if($ln == $n['c_note_by']){}else{
							    if($n['c_note_by']==4){
								    $noteuser = array('username' => 'System');
								} else {
			                    $noteuser = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", array($n['c_note_by']));
								$ln = $n['c_note_by'];
								}
							}
							if($n['c_note_by'] == $USER['user_id']){$ed = "forget";} else {$ed = "fogret";}
							switch($n['c_type']){
							    case 0:$color='usernote';break;
							    case 1:$color='systemnote';break;
								case 2:$color='statusnote';break;
								case 3:$color='originalnote';break;
							}
						    echo'
							    <li class="'.$color.'"><b>'.date("[m/d/y] h:i A", strtotime($n['c_date'])).' - '.$noteuser['username'].': </b><font class="'.$ed.'" id="note'.$n['note_id'].'">'.$n['c_note'].'</font></li>
							';
						}}
						echo <<<STR
						    </ul>
						</div>
						<input type="text" style="width:405px;margin-left:2px;margin-right:2px;" placeholder="Type Note Here..." id="addnotetext" onKeyUp="CheckSendCNote('$CID', event)"><div style="width:76px;display:inline-block;"><button onClick="AddCustomerNote('$CID')">Add Note</button></div>
					</div>
	</div>
	<font style='display:none;' id='cname$CID'>{$CUST['c_name']}</font>
STR;
?>