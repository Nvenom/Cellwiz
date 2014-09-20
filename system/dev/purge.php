<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(8,TRUE);

$template = <<<TMP
<img onLoad="window.location.reload(true);" src="../frame/skins/default/images/bg-overlay.png" style="display:none;">
TMP;
$USERS = MYSQL::QUERY('SELECT * FROM core_users ORDER BY user_id ASC');
$D = Date("Y-m-d H:i:s");
$PARAMS = ARRAY();
$I = 1;
$Q = "INSERT INTO core_messages (m_to,m_from,m_message,m_sent) VALUES ";
FOREACH($USERS AS $U){
    $PARAMS[] = $U['user_id'];
    $PARAMS[] = "Purge";
    $PARAMS[] = $template;
    $PARAMS[] = $D;
    IF($I == 1){
        $Q .= "(?,?,?,?)";
    } ELSE {
        $Q .= ",(?,?,?,?)";
    }
	$I++;
}
$R = MYSQL::QUERY($Q, $PARAMS, true);
IF($R == TRUE){
    ECHO "Purge Request send Successfully";
}
?>