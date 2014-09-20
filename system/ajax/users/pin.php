<?php 
REQUIRE("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);

$STRING = $_POST['str'];

$OP = SUBSTR($STRING, -4);
$NP = SUBSTR($STRING,0,4);

IF($OP == '0000'){$OP = 0;} ELSE {$OP = SHA1($OP);}

$NP = SHA1($NP);
IF($OP == $USER['pin']){
    MYSQL::QUERY("UPDATE core_users SET pin=? WHERE user_id=?",ARRAY($NP,$USER['user_id']));
	ECHO 1;
}ELSE{
    ECHO 2;
}
?>