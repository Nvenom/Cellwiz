<?php 
REQUIRE("../../../frame/engine.php");ENGINE::START("HASH");
$USER = USER::VERIFY(0,TRUE);
$Hash = new PasswordHash(8, true);

$NP = $_POST['str1'];
$OP = $_POST['str2'];

IF($Hash->CheckPassword($OP, $USER['password'])){
    $password = $Hash->HashPassword($NP);
	MYSQL::QUERY('UPDATE core_users SET password=? WHERE user_id=? LIMIT 1',ARRAY($password,$USER['user_id']));
	ECHO 1;
} ELSE {
    ECHO 2;
}
?>