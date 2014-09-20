<?php 
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	
    $params = array($user['user_id'],0);
	$b = MYSQL::QUERY('SELECT * FROM core_messages WHERE m_to = ? AND m_read = ? ORDER BY m_sent ASC LIMIT 5', $params);
	IF($user['bronze_medals'] >= 100 || $user['silver_medals'] >= 100){
		IF($user['bronze_medals'] >= 100){
		    $br = $user['bronze_medals'] % 100;
		    $user['bronze_medals'] = $br;
		    $user['silver_medals']++;
		}
		IF($user['silver_medals'] >= 100){
		    $sr = $user['silver_medals'] % 100;
		    $user['silver_medals'] = $sr;
		    $user['gold_medals']++;
		}
		MYSQL::QUERY('UPDATE core_users SET gold_medals=?,silver_medals=?,bronze_medals=? WHERE user_id=? LIMIT 1',ARRAY($user['gold_medals'],$user['silver_medals'],$user['bronze_medals'],$user['user_id']));
	}
	if(!empty($b)){
		$message = array(
			"NA"       => "message",
			"messages" => ARRAY(),
			"gold"     => $user['gold_medals'], 
			"silver"   => $user['silver_medals'], 
			"bronze"   => $user['bronze_medals']
		);
		foreach($b as $m){
		    $message['messages'][] = ARRAY(
			    "from"    => $m['m_from'], 
	            "message" => $m['m_message'], 
			    "avatar"  => $m['m_from_avatar'], 
			    "time"    => date("h:i A M,d",strtotime($m['m_sent'])),
			);
			$DL_FROM = ARRAY("Price Request","Price Response","LeaderBoard Reward","Achievement Earned","Purge");
			IF(IN_ARRAY($m['m_from'],$DL_FROM)){
			    MYSQL::QUERY('DELETE FROM core_messages WHERE m_id = ? LIMIT 1',ARRAY($m['m_id']));
			} ELSE {
	            MYSQL::QUERY('UPDATE core_messages SET m_read = ? WHERE m_id = ? LIMIT 1',ARRAY(1,$m['m_id']));
			}
		}
		echo json_encode($message);
	} else {
	    echo json_encode(array("NA" => "false", "messages" => ARRAY(), "gold" => $user['gold_medals'], "silver" => $user['silver_medals'], "bronze" => $user['bronze_medals']));
	}
?>