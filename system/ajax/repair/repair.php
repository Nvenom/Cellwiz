<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
	$tid = $_GET['ticket'];
	$mid = $_GET['model'];
	$type = $_GET['type'];
	$release = explode(" ",$_GET['release']);
	MYSQL::QUERY("UPDATE core_tickets_status SET t_tech = ? WHERE t_id = ? LIMIT 1",ARRAY($user['user_id'],$tid));
	$Model = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id = ? LIMIT 1", array($mid));
	$ticket = MYSQL::QUERY("SELECT * FROM core_tickets_repair WHERE t_id = ? LIMIT 1", array($tid));
?>
	<form style="width:492px;height:200px;" id="repairf<?php echo $tid;?>">
	<button type="button" style="margin-bottom: 10px;" onCLick="bodyLayout.open('east');InventoryMod(<?php echo $Model['m_id'].",'".$Model['m_name']."'";?>,event)">Load Device in Inventory</button><br/>
	    <div style="width:47%;height:98%;float:left;">
		    <label class="aname" style="padding-top:5px;">SIM Card Included</label>
		    <div id="simcard" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>simcard1" name="simcard<?php echo $tid;?>" value="0" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>simcard1">No</label>
                <input type="radio" id="<?php echo $tid;?>simcard2" name="simcard<?php echo $tid;?>" value="1"/><label class='sensclick' for="<?php echo $tid;?>simcard2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">SD Card Included</label>
		    <div id="sdcard" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>sdcard1" name="sdcard<?php echo $tid;?>" value="0" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>sdcard1">No</label>
                <input type="radio" id="<?php echo $tid;?>sdcard2" name="sdcard<?php echo $tid;?>" value="1"/><label class='sensclick' for="<?php echo $tid;?>sdcard2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Case Included</label>
		    <div id="case" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>case1" name="case<?php echo $tid;?>" value="0" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>case1">No</label>
                <input type="radio" id="<?php echo $tid;?>case2" name="case<?php echo $tid;?>" value="1"/><label class='sensclick' for="<?php echo $tid;?>case2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Charger Included</label>
		    <div id="charger" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>charger1" name="charger<?php echo $tid;?>" value="0" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>charger1">No</label>
                <input type="radio" id="<?php echo $tid;?>charger2" name="charger<?php echo $tid;?>" value="1"/><label class='sensclick' for="<?php echo $tid;?>charger2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Powering On</label>
		    <div id="power" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>power1" name="power<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>power1">No</label>
                <input type="radio" id="<?php echo $tid;?>power2" name="power<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>power2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">All Buttons Working</label>
		    <div id="buttons" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>buttons1" name="buttons<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>buttons1">No</label>
                <input type="radio" id="<?php echo $tid;?>buttons2" name="buttons<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>buttons2">Yes</label>
		    </div>
		</div>
		<div style="width:47%;height:98%;float:right;margin-right:3px;">
		    <label class="aname" style="padding-top:5px;">Internal Audio Working</label>
		    <div id="inaudio" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>inaudio1" name="inaudio<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>inaudio1">No</label>
                <input type="radio" id="<?php echo $tid;?>inaudio2" name="inaudio<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>inaudio2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">External Audio Working</label>
		    <div id="exaudio" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>exaudio1" name="exaudio<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>exaudio1">No</label>
                <input type="radio" id="<?php echo $tid;?>exaudio2" name="exaudio<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>exaudio2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">LCD / Touch Working</label>
		    <div id="touch" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>touch1" name="touch<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>touch1">No</label>
                <input type="radio" id="<?php echo $tid;?>touch2" name="touch<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>touch2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Housing is fine</label>
		    <div id="housing" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>housing1" name="housing<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>housing1">No</label>
                <input type="radio" id="<?php echo $tid;?>housing2" name="housing<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>housing2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Charging okay</label>
		    <div id="charging" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>charging1" name="charging<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>charging1">No</label>
                <input type="radio" id="<?php echo $tid;?>charging2" name="charging<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>charging2">Yes</label>
		    </div><br/><br/>
			<label class="aname" style="padding-top:5px;">Service / WiFi / Bluetooth</label>
		    <div id="service" style="float:right;">
		        <input type="radio" id="<?php echo $tid;?>service1" name="service<?php echo $tid;?>" value="0"/><label class='sensclick' for="<?php echo $tid;?>service1">No</label>
                <input type="radio" id="<?php echo $tid;?>service2" name="service<?php echo $tid;?>" value="1" checked="checked"/><label class='sensclick' for="<?php echo $tid;?>service2">Yes</label>
		    </div>
		</div>
	</form>
    <div style="width:492px;height:215px;">
        <div class="txt_dsb" style="width:100%;height:180px;margin:0px !important;" id="estimator">
            <h4 class="ui-widget-header" style="margin:0px;border: 0px;border-top-right-radius: 3px;border-top-left-radius: 3px;">Repair parts used</h4>
            <div id="estimation" style="padding:10px;height:113px;">
                <?php
				$items = explode("|", $ticket['t_estimate_items']);
				foreach($items as $item){
				    if(!empty($item)){
					    $partid = explode("-",$item);
						if($partid[0] == "it"){
						    $partid = $partid[1];
						    ENGINE::ITEM($tid, $user, $Model, $partid, $type, $release);
						} else {
						    $serviceid = $partid[1];
						    ENGINE::SERVICE($tid, $user, $Model, $serviceid, $type, $release);
						}
					}
				}
				?>
            </div>
			<div id="total" class="ui-widget-header" style="border:0px;border-top:1px solid #ACB1B7;padding-right:10px;height:24px;">
			    <div class="bname">TOTAL: $<font class="tc"><?php echo $ticket['t_estimate_price'];?></font></div>
			</div>
        </div>
	    <button type="button" class="continue" onClick="PassRepair('<?php echo $tid;?>')">Submit Repair</button>
	</div>
	<div id="timert<?php echo $tid;?>" style="display:none;"></div>