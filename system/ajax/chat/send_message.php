<?php 
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$to = $_GET['to'];
	$date = Date("Y-m-d H:i:s");
    $params = array($to,$_GET['from'],$_GET['message'],$user['user_id'],$date);
	MYSQL::QUERY('INSERT INTO core_messages (m_to, m_from, m_message, m_from_avatar, m_sent) VALUES (?, ?, ?, ?, ?)', $params);
?>