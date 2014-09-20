<?php
date_default_timezone_set('America/New_York');
$DATE = DATE("N");
$TIME = DATE("H");
IF($DATE == 6){
    $OPEN = 11;
	$CLOSE = 16;
} ELSE {
    $OPEN = 9;
	$CLOSE = 19;
}
IF($TIME < $OPEN || $TIME > $CLOSE || $DATE == 7){
?>
<Response>
	<Sms>We are Closed: Current Time (<?PHP ECHO DATE("Y-m-d g:i A");?>)</Sms>
</Response>
<?
} ELSE {
?>
<Response>
	<Sms>We are Open: Current Time (<?PHP ECHO DATE("Y-m-d g:i A");?>)</Sms>
</Response>
<?php
}
?>