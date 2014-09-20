<?php 
REQUIRE("../../../frame/engine.php");ENGINE::START();

$STRING = $_POST['str'];
$PIN = SUBSTR($STRING, -4);
$USERA = STR_REPLACE($PIN,'',$STRING);
$PIN = SHA1($PIN);
$NEWUSER = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id=? AND pin=? LIMIT 1",ARRAY($USERA,$PIN));
IF(!EMPTY($NEWUSER)){
    USER::LOG("Switched Accounts",$NEWUSER['user_id']);
	$SES_EXP = TIME()+43200;
	$SES_GEN = FORMAT::SES(50);
	$params = ARRAY($SES_GEN,$NEWUSER['user_id'],$SES_EXP,$SES_GEN,$SES_EXP,DATE("Y-m-d H:i:00"));
	MYSQL::QUERY('INSERT INTO core_users_sessions (session_key,session_user,session_experation) VALUES (?,?,?) ON DUPLICATE KEY UPDATE session_key=?,session_experation=?,qas_time=?',$params);
	setcookie("core_u", $params[1], $SES_EXP, '/');
	setcookie("core_k", $params[0], $SES_EXP, '/');
	ECHO 1;
} ELSE {
    ECHO 2;
}
?>