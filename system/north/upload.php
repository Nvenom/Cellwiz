<?php
REQUIRE("../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0);

if (isset($GLOBALS["HTTP_RAW_POST_DATA"])){
    $imageData=$GLOBALS['HTTP_RAW_POST_DATA'];
    $filteredData=substr($imageData, strpos($imageData, ",")+1);
    $unencodedData=base64_decode($filteredData);
    $fp = fopen( 'avatars/'.$USER['user_id'].'.png', 'wb' );
    fwrite( $fp, $unencodedData);
    fclose( $fp );
	
	$bgcolor=substr($imageData, 0, 11);
	$bgcolor=str_replace(ARRAY("\\#","//"),"",$bgcolor);
	MYSQL::QUERY("UPDATE core_users SET avatar_bg = ?,avatar_last_updated = ? WHERE user_id = ? LIMIT 1", ARRAY($bgcolor,DATE("Y-m-d H:i:s"),$USER['user_id']));
}
?>