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
	<Play loop="1">https://secure.cellwiz.net/new/frame/controllers/v-controllers/af/closed.wav</Play>
</Response>
<?
} ELSE {
?>
<Response>
	<Gather action="https://secure.cellwiz.net/new/frame/controllers/v-controllers/process_gather.php" method="GET" numDigits="1">
	    <Play loop="0">https://secure.cellwiz.net/new/frame/controllers/v-controllers/af/hello.wav</Play>
    </Gather>
</Response>
<?php
}
?>