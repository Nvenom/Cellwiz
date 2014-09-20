<?php 
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$tid = $_GET['tid'];
	$mtid = $_GET['main'];
	
	$mainticket = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", ARRAY($mtid));
	$newticket = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1", ARRAY($tid));
	
	if($newticket['t_customer'] == $mainticket['t_customer']){
	    if($newticket['t_status'] == 3){
		    $T = ENGINE::TICKET($tid,3);
			$arr = FORMAT::CHECKLIST(array($T['t_simcard'], $T['t_sdcard'], $T['t_case'], $T['t_charger'], $T['t_power'], $T['t_buttons'], $T['t_inaudio'], $T['t_exaudio'], $T['t_touch'], $T['t_housing'], $T['t_charging'], $T['t_service']));
			$title = "
			    <b>Device:</b> ".$T['m_name']."<br/>
			    <b>Estimate:</b> $".$T['t_estimate_price']."<br/>
				<b>Repair Time:</b> ".$T['t_repair_time']."<br/>
				".$arr."
			";
			echo '
			    <div id="ti-'.$T['t_id'].'" data-price="'.$T['t_repair_price'].'" title="'.$title.'" style="cursor:pointer;">
				    <img src="../frame/skins/default/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveAccess($(this), '."'".$mtid."'".', '."'".$user['store_info']['s_taxrate']."'".')" />
		            <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">Ticket #'.$T['t_id'].'</font>
		            <font class="bname pprice">'.$T['t_repair_price'].'</font>
					<script>RemoveAccess($(this), '."'".$mtid."'".', '."'".$user['store_info']['s_taxrate']."'".')</script>
	                <br/>
	            </div>
			';
		} else {
		    echo "Ticket not in Checkout Stage";
		}
	} else {
	    echo "Ticket does not belong to same customer";
	}
?>