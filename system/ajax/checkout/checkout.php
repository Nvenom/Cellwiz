<?php 
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$tid = $_GET['ticket'];
	$mid = $_GET['model'];
	$type = $_GET['type'];
	$release = explode(" ",$_GET['release']);
	$T = ENGINE::TICKETINFO($tid);
	$repair_price = $T['INFO']['t_repair_price'];
	$arr = FORMAT::CHECKLIST(array($T['STATUS']['t_simcard'], $T['STATUS']['t_sdcard'], $T['STATUS']['t_case'], $T['STATUS']['t_charger'], $T['STATUS']['t_power'], $T['STATUS']['t_buttons'], $T['STATUS']['t_inaudio'], $T['STATUS']['t_exaudio'], $T['STATUS']['t_touch'], $T['STATUS']['t_housing'], $T['STATUS']['t_charging'], $T['STATUS']['t_service']));
	$title = "
		<b>Device:</b> ".$T['MODE']['m_name']."<br/>
		<b>Estimate:</b> $".$T['INFO']['t_estimate_price']."<br/>
		<b>Repair Time:</b> ".$T['INFO']['t_repair_time']."<br/>
		".$arr."
	";
	$PHY = ($T['INFO']['t_phy'] == 1 ? "Yes" : "No");
	$LIQ = ($T['INFO']['t_liq'] == 1 ? "Yes" : "No");
	$SOF = ($T['INFO']['t_sof'] == 1 ? "Yes" : "No");
	$MANU = $T['MANU']['m_name'];
	$MODEL = str_replace($MANU." ", "", $T['MODE']['m_name']);
	$PHONE = FORMAT::PHONE($T['CUST']['c_phone']);
	$CD = date("M d, Y h:i A", strtotime($T['INFO']['t_created']));
	$ED = date("M d, Y h:i A", strtotime($T['INFO']['t_estimate_created']));
	$RD = date("M d, Y h:i A", strtotime($T['INFO']['t_repair_created']));
?>
    <form style="width:492px;height:170px;" id="checkoutf<?php echo $tid;?>">
	    <div style="width:52%;height:157px;float:left;overflow-y:scroll;">
            <?php
			    echo <<<STR
                    <div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>CUSTOMER</center></div><br/>
				    <div style="width:100%;">
					    <div class="aname">Name:</div>
						<div class="bname" style="padding-right:2px;">{$T['CUST']['c_name']}</div>
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
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_password']}</div>
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
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_imei']}</div>
					</div><br/><br/>
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>CREATED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">BY:</div>
						<div class="bname" style="padding-right:2px;">{$T['USER']['username']}</div>
						<br/>
					    <div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$CD</div>
					</div><br/><br/>
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>ESTIMATED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">PRICE:</div>
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_estimate_price']}</div>
						<br/>
						<div class="aname">TIME:</div>
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_estimate_time']}</div>
						<br/>
						<div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$ED</div>
					</div><br/><br/>
					<div style="width:100%;border-bottom: 1px solid #ACB1B7;" class="aname"><center>REPAIRED</center></div><br/>
					<div style="width:100%;">
					    <div class="aname">PRICE:</div>
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_repair_price']}</div>
						<br/>
						<div class="aname">TIME:</div>
						<div class="bname" style="padding-right:2px;">{$T['INFO']['t_repair_time']}</div>
						<br/>
						<div class="aname">ON:</div>
						<div class="bname" style="padding-right:2px;">$RD</div>
					</div>
STR;
			?>
		</div>
		<div style="width:47%;height:160px;float:right;margin-left:3px;">
		    <div style="text-align:center !important;width:48%;display:inline-block;float:none !important;margin-right:1%;" class="bname"><input type="text" onKeyUp="SplitPrice('pm1cost','pm2cost', '<?php echo $tid;?>');" id="pm1cost" placeholder="PM1 Charge" style="width:98%;" value="<?php echo $repair_price;?>"></div>
			<div style="text-align:center !important;width:48%;display:inline-block;float:none !important;" class="bname"><input type="text" onKeyUp="SplitPrice('pm2cost','pm1cost', '<?php echo $tid;?>');" id="pm2cost" placeholder="PM2 Charge" style="width:98%;" disabled="disabled"></div><br/>
			<select style="width:49%;" id="payselect1" class="required">
			    <option value="">Pay Method..</option>
			    <option value="Cash">Cash</option>
			    <option value="Check">Check</option>
			    <option value="American Express">American Express</option>
			    <option value="Discover">Discover</option>
			    <option value="MasterCard">MasterCard</option>
			    <option value="Visa">Visa</option>
			    <option value="Debit Card">Debit Card</option>
			</select>
			<select style="width:49%;" id="payselect2" onChange="ps2($(this).val(), '<?php echo $tid;?>');">
				<option value="None">None</option>
			    <option value="Cash">Cash</option>
			    <option value="Check">Check</option>
			    <option value="American Express">American Express</option>
			    <option value="Discover">Discover</option>
			    <option value="MasterCard">MasterCard</option>
			    <option value="Visa">Visa</option>
			    <option value="Debit Card">Debit Card</option>
			</select><br/>
			<div style="width: 98%;height: 84px;text-align: center;padding-top: 5px;font-size: 12px;" class="aname">
			    final Cost:<br/>
				<span id="fp" style="font-size:39px;"><?php echo $repair_price;?></span><br/>
				Tax: <span id="fptax">0.00</span>
			</div><br/>
			<input type="text" id="price-discount" style="width:47%;" placeholder="Discount Code..."><input type="text" id="my-cpr-card-code" onKeyUp="ValidateCard($(this),'<?php echo $tid;?>','<?php echo $T['CUST']['c_id']; ?>',event)" style="width:47%;margin-left:3px;" placeholder="My-CPR Card#">
		</div>
	</form>
    <div style="width:492px;height:270px;">
        <div class="txt_dsb" style="width:100%;height:240px;margin:0px !important;" id="estimator">
            <h4 class="ui-widget-header" style="margin:0px;border: 0px;border-top-right-radius: 3px;border-top-left-radius: 3px;">Tickets / Accessories</h4>
            <div id="estimation" style="padding:10px;height:169px;border-bottom: 1px solid #ACB1B7;">
                <div id="ti-<?php echo $tid;?>" data-price="<?php echo $repair_price;?>" title="<?php echo $title; ?>" style="cursor:pointer;">
		            <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">Ticket #<?php echo $tid;?></font>
		            <font class="bname pprice"><?php echo $repair_price;?></font>
	                <br/>
	            </div>
            </div>
			<input type="text" style="width:47%;margin-left:1%;margin-right:1%;float:left;margin-top:3px;" placeholder="Scan Ticket to Add it to Checkout..." id="checkoutat" onKeyUp="if(event.keyCode==13){ScannedTicket($(this), '<?php echo $tid;?>', <?php echo $user['store_info']['s_taxrate']; ?>)}">
			<input type="text" style="width:47%;margin-right:1%;float:right;margin-top:3px;" placeholder="Scan Refurb ID to Add to Checkout..." id="checkoutri" onKeyUp="if(event.keyCode==13){ScannedRefurb($(this), '<?php echo $tid;?>', <?php echo $user['store_info']['s_taxrate']; ?>)}">
        </div>
	    <button type="button" class="continue" onClick="PassCheckout('<?php echo $tid;?>')">Submit Checkout</button>
	</div>
	<div id="timert<?php echo $tid;?>" style="display:none;"></div>