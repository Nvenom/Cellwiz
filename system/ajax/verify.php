<?php
REQUIRE("../../frame/engine.php");ENGINE::START("HASH");
$Hash = new PasswordHash(8, true);

$USER = $_POST['usr'];
$USER_CLEAN = STRTOLOWER($USER);
$PASS = $_POST['pas'];

IF(EMPTY($USER) && EMPTY($PASS)){
    DIE('e1437');
} ELSE {
    $R = MYSQL::QUERY("SELECT * FROM core_users WHERE username_clean = ? LIMIT 1",array($USER_CLEAN));
    IF(EMPTY($R)){
        DIE('e1435');
    } ELSE {
        IF(!$Hash->CheckPassword($PASS, $R['password'])) {
            DIE('e1436');
        } ELSE {
		    $S = MYSQL::QUERY('SELECT * FROM core_stores WHERE s_id = ? LIMIT 1', array($R['store']));
			DATE_DEFAULT_TIMEZONE_SET($S['s_timezone']);
		    USER::LOG("Logged In",$R['user_id']);
		    $SES_EXP = TIME()+43200;
	        $SES_GEN = FORMAT::SES(50);
	        $params = ARRAY($SES_GEN,$R['user_id'],$SES_EXP,$SES_GEN,$SES_EXP);
	        MYSQL::QUERY('INSERT INTO core_users_sessions (session_key,session_user,session_experation) VALUES (?,?,?) ON DUPLICATE KEY UPDATE session_key=?,session_experation=?',$params);
		    setcookie("core_u", $params[1], $SES_EXP, '/');
		    setcookie("core_k", $params[0], $SES_EXP, '/');
	        ECHO 's1434';
		}
	}
}
?>