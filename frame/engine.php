<?php
CLASS ENGINE{
    PUBLIC STATIC FUNCTION START($GEAR="FAST"){
        $CO = "configs";
	    $CL = "classes";
        require_once($CO.'/database.conf.php');
		require_once($CO.'/twilio.conf.php');
	    require_once($CL.'/mysql.class.php');
	    require_once($CL.'/users.class.php');
	    require_once($CL.'/format.class.php');
		require_once($CL.'/tracking.class.php');
		require_once($CL.'/test.class.php');
 
		IF($GEAR=="BARCODE"){
			require_once($CL.'/barcode.class.php');
		} ELSE IF($GEAR=="HASH"){
			require_once($CL.'/hash.class.php');
		}
	}
	
	PUBLIC STATIC FUNCTION TICKET($TID,$STAGE){
	    IF($STAGE==1){$tbl = 'core_tickets_estimate';}
		ELSE IF($STAGE==2){$tbl = 'core_tickets_repair';}
		ELSE IF($STAGE==3){$tbl = 'core_tickets_checkout';}
		ELSE IF($STAGE==4){$tbl = 'core_tickets_walkout';}
	    $TICKET = MYSQL::QUERY("SELECT * FROM core_tickets_status a, $tbl b, core_customers c, device_models d WHERE a.t_id=? AND b.t_id=? AND c.c_id=a.t_customer AND d.m_id=b.t_model LIMIT 1", ARRAY($TID,$TID));
		RETURN $TICKET;
	}
	
	PUBLIC STATIC FUNCTION TICKETINFO($TID){
	    $T = ARRAY('STATUS','INFO','CUST','CORP','USER','MANU','MODE');
		$T['STATUS'] = MYSQL::QUERY("SELECT * FROM core_tickets_status WHERE t_id = ? LIMIT 1",ARRAY($TID));
		SWITCH($T['STATUS']['t_status']){
		    CASE 1: $TA = 'core_tickets_estimate';  BREAK;
			CASE 2: $TA = 'core_tickets_repair';    BREAK;
			CASE 3: $TA = 'core_tickets_checkout';  BREAK;
			CASE 4: $TA = 'core_tickets_processed'; BREAK;
			CASE 97:$TA = 'core_tickets_walkout';   BREAK;
			CASE 98:$TA = 'core_tickets_walkout';   BREAK;
			CASE 99:$TA = 'core_tickets_walkout';   BREAK;
		}
	    $T['INFO'] = MYSQL::QUERY("SELECT * FROM $TA WHERE t_id = ? LIMIT 1",ARRAY($TID));
		$T['CUST'] = MYSQL::QUERY("SELECT * FROM core_customers WHERE c_id = ? LIMIT 1",ARRAY($T['INFO']['t_customer']));
		$T['CORP'] = MYSQL::QUERY("SELECT * FROM core_corporate_accounts WHERE c_id=? LIMIT 1", ARRAY($T['CUST']['c_acc']));
		$T['USER'] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id=? LIMIT 1", ARRAY($T['INFO']['t_created_by']));
		$T['MANU'] = MYSQL::QUERY("SELECT * FROM device_manufacturers WHERE m_id=? LIMIT 1", ARRAY($T['INFO']['t_manufacturer']));
		$T['MODE'] = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id=? LIMIT 1", ARRAY($T['INFO']['t_model']));
		RETURN $T;
	}
	
	PUBLIC STATIC FUNCTION VOIP($USER){
	    require_once('controllers/v-controllers/twilio/Twilio/Capability.php');
		echo '<script type="text/javascript" src="//static.twilio.com/libs/twiliojs/1.1/twilio.min.js"></script>';
 
        $capability = new Services_Twilio_Capability($TWILIO['SID'], $TWILIO['TOKEN']);
        $capability->allowClientOutgoing($TWILIO['ASID']);
        $capability->allowClientIncoming($USER['username']);
        $token = $capability->generateToken();
	}
	
	PUBLIC STATIC FUNCTION ITEM($ticket, $user, $Model, $part, $type, $release, $bp = false){
	    $Item  = MYSQL::QUERY("SELECT * FROM device_parts WHERE p_id = ? LIMIT 1", array($part));
	    $Stock = MYSQL::QUERY("SELECT * FROM inventory_stock WHERE item = ? AND store = ? LIMIT 1", array($part,$user['store']));
		$Type  = MYSQL::QUERY("SELECT * FROM device_categories WHERE c_id = ? LIMIT 1", array($type));
		$reset = false;
		IF(EMPTY($Stock['quantity'])){$Stock['quantity'] = 0;}
		if($Stock['quantity'] <= 0){if($Stock['modified'] < Date("Y-m-d H:i:s", strtotime('-2 weeks')) || $Stock['price']<=0){$reset = true;}}
		if(!empty($Stock)){
		    if($reset == false){
		        $yearm = Date("Y") - $release[2];
				$yearm = $yearm * 10;
		        $yearm = $Type['c_fee'] - $yearm;
		        if(!$Model['m_override'] == "0"){
			        $price = explode("/",$Model['m_override']);
		            if($price[0] == "plus"){$total = number_format((ceil($Stock['price'] / 10) * 10) + ($yearm + $price[1]), 2, '.', '');}
		            else if($price[0] == "minus"){$total = number_format((ceil($Stock['price'] / 10) * 10) + ($yearm - $price[1]), 2, '.', '');}
		            else if($price[0] == "equal"){$total = number_format((ceil($Stock['price'] / 10) * 10) + $price[1], 2, '.', '');}
					else if($price[0] == "override"){$total = number_format($price[1], 2, '.', '');}
		        } else {
			        $total = number_format((ceil($Stock['price'] / 10) * 10) + $yearm, 2, '.', '');
		        }
				if($total <= 40){$total = 40;}
	            echo '
		            <div id="it-'.$part.'" data-price="'.$total.'" style="overflow:hidden;">
		                <img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveEstimate($(this), '."'".$ticket."'".')" />
		                <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Item['p_name'].'</font>
		                <font class="bname pprice">'.$total.'</font>
	                </div>
		        ';
			}
		} else { $reset = true; }
		if($reset == true){
		    $ses = FORMAT::SES(9);
		    echo '
		        <div id="it-'.$part.'" data-price="0" style="overflow:hidden;">
		            <img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveEstimate($(this), '.$ticket.')" />
		            <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Item['p_name'].'</font>
		            <font class="bname pprice" id="ses'.$ses.'">Waiting for Manager</font>
	            </div>
		    ';
			MYSQL::QUERY("REPLACE INTO inventory_stock (store,item,quantity,price,supplier,modified,ses) VALUES (?,?,?,?,?,?,?)", array($user['store'],$part,0,0,0,Date("Y-m-d H:i:s"),$ses));
			$sesclean = "'".$ses."'";
		    $template = '<b>From: '.$user['username'].'</b><br/>'.$Model['m_name'].' '.$Item['p_name'].'<br/><br/><input type="text" placeholder="0.00" id="send'.$ses.'"><button onClick="SendPrice('.$sesclean.', '.$user['user_id'].', '."'".$Model['m_date']."'".', '.$Model['m_type'].', $(this), '."'".$Model['m_override']."'".')">Send</button>';
		    $params = array($user['store_info']['s_manager'],"Price Request",$template,$user['user_id'],Date("Y-m-d H:i:s"));
		    $Main = MYSQL::QUERY("INSERT INTO core_messages (m_to,m_from,m_message,m_from_avatar,m_sent) VALUES (?,?,?,?,?)", $params, true);
		}
	}
	
	PUBLIC STATIC FUNCTION SERVICE($ticket, $user, $Model, $part, $type, $release){
		$Type    = MYSQL::QUERY("SELECT * FROM device_categories WHERE c_id = ? LIMIT 1", array($type));
		$Service = MYSQL::QUERY("SELECT * FROM device_services WHERE s_id = ? LIMIT 1", array($part));
		$yearm = Date("Y") - $release[2];
		$yearm = $yearm * 10;
		$price = explode("/",$Service['s_price']);
		if($price[0] == "+"){$total = number_format($price[1] + ($Type['c_fee'] - $yearm), 2, '.', '');}
		else if($price[0] == "-"){$total = number_format(($Type['c_fee'] - $yearm) -  $price[1], 2, '.', '');}
		else if($price[0] == "="){$total = $price[1];}
		if($total <= 40){$total = 40;}
	    echo '
		    <div id="sv-'.$part.'" data-price="'.$total.'" style="overflow:hidden;">
		        <img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveEstimate($(this), '."'".$ticket."'".')" />
		        <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Service['s_name'].'</font>
		        <font class="bname pprice">'.$total.'</font>
	        </div>
		';
	}
}
?>
