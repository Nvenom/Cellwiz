<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $usera = USER::VERIFY(0,TRUE);
    $ses = "'".$_GET['ses']."'";
	$user = $_GET['user'];
	$pricea = $_GET['price'];
	$date = explode(" ",$_GET['date']);
	$type = $_GET['type'];
	
	MYSQL::QUERY("UPDATE inventory_stock SET price=?, modified=?, ses=? WHERE store=? AND ses=? LIMIT 1",array($pricea,Date("Y-m-d H:i:s"),"",$usera['store'],$_GET['ses']));
	$typedata = MYSQL::QUERY("SELECT * FROM device_categories WHERE c_id = ? LIMIT 1", array($type));
	$yearm = Date("Y") - $date[2];
	$yearm = $yearm * 10;
	$yearm = $typedata['c_fee'] - $yearm;
	if(!$_GET['ovr'] == "0"){
	    $price = explode("/",$_GET['ovr']);
		$raise = ceil($pricea / 10) * 10;
	    if($price[0] == "plus"){$total = number_format($raise + ($yearm + $price[1]), 2, '.', '');}
	    else if($price[0] == "minus"){$total = number_format($raise + ($yearm - $price[1]), 2, '.', '');}
	    else if($price[0] == "equal"){$total = number_format($raise + $price[1], 2, '.', '');}
		else if($price[0] == "override"){$total = number_format($price[1], 2, '.', '');}
	} else {
	    $total = number_format((ceil($pricea / 10) * 10) + $yearm, 2, '.', '');	    
	}
	if($total <= 40){$total = 40;}
	$template = '<img onLoad="RecievePrice('.$total.', '.$ses.');" src="../core/images/bg-overlay.png" style="display:none;"><b>You Have Recieved a Price back from the Manager</b>';
	$params = array($user,"Price Response",$template,$usera['user_id'],Date("Y-m-d H:i:s"));
	MYSQL::QUERY("INSERT INTO core_messages (m_to,m_from,m_message,m_from_avatar,m_sent) VALUES (?,?,?,?,?)", $params, true);
?>