<?php 
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$device = $_GET['device'];
	$mtid = $_GET['main'];
	
	$SELECT = MYSQL::QUERY("SELECT * FROM core_refurb_devices WHERE d_id=? LIMIT 1", ARRAY($device));
	if(!EMPTY($SELECT)){
	    $Model = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id = ? LIMIT 1", ARRAY($SELECT['d_model_id']));
		echo '
			<div id="de-'.$device.'" data-price="'.$SELECT['d_locked_price'].'" data-ulprice="'.$SELECT['d_unlocked_price'].'" title="<b>'.$Model['m_name'].' Refurb Notes:</b><br/>'.$SELECT['d_notes'].'" style="cursor:pointer;position:relative;">
				<img src="../frame/skins/default/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveAccess($(this), '."'".$mtid."'".', '."'".$user['store_info']['s_taxrate']."'".')" />
		        <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Model['m_name'].'</font>
			';
			    if($SELECT['d_unlocked_price'] > $SELECT['d_locked_price']){
				    echo '<font class="aname" style="position:absolute;top:0px;right:60px;">Unlocked</font><input type="checkbox" onChange="UnlockPrice($(this), '."'".$mtid."'".', '.$user['store_info']['s_taxrate'].')" style="position:absolute;top:-4px;right:40px;">';
		        }
		    echo '<font class="bname pprice" data-type="as">'.$SELECT['d_locked_price'].'</font>
	            <br/>
	        </div>
		';
	} else {
	    echo "No Device Found Using the Refurb ID [$device]";
	}
?>