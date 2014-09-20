<?php
require("../frame/engine.php");ENGINE::START(); 
$user = USER::VERIFY(0);
IF(ISSET($_GET['mode'])){
    if($_GET['mode'] == 'purge'){$purge = time();} else {$purge = '';}
} ELSE {
    $purge = '';
}
?>
<!DOCTYPE HTML>
<html>
    <head>
		<META http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<META NAME='copyright' CONTENT='Copyright ©Cellwiz.net <?php echo Date('Y'); ?>. All Rights Reserved.'>
		<META NAME='author' CONTENT='Christian John Clark'>
	    <META NAME='robots' CONTENT='noindex'>
		<META NAME='language' CONTENT='English'>
		<title>Cellwiz System</title>
        <link rel='stylesheet' type='text/css' href='../frame/skins/default/css/main.css?<?php echo $purge; ?>' />
		<link rel='stylesheet' type='text/css' href='../frame/skins/default/css/default/ui.css?<?php echo $purge; ?>' />
		<link rel='stylesheet' type='text/css' href='../frame/skins/default/css/print.css?<?php echo $purge; ?>' media='print' />
		<script src='../frame/controllers/c-controller.js?<?php echo $purge; ?>'></script>
	    <script src='../frame/controllers/f-controller.js?<?php echo $purge; ?>'></script>
		<script src='../frame/controllers/p-controller.js?<?php echo $purge; ?>'></script>
		<script>
		    jQuery(document).ready(function($) {
                $('#tilda').tilda(function(command, terminal) {
                    <?php
					    if($user['level'] >= 8){
					    echo <<<JVS
						    if(command == 'dev -p purge'){
		                        $.get('dev/purge.php',function(data){
								    terminal.echo(data);
								});
                            } else
JVS;
}
?>                  if(command == 'cmd -s time'){
		                terminal.echo('System Time: <?php echo time();?> // <?php echo date("Y-m-d H:i:s"); ?>\nSystem Timezone: <?php echo date_default_timezone_get(); ?>')
                    } else if(command == 'cmd -s purge'){
					    window.location.href = window.location.href+'?mode=purge';
					}
                });
            });
		</script>
	</head>
	<body style="width:100%; height: 100%; margin: 0 auto">
	    <div id="tilda" class="tilda noprint" style='display:none;'></div>
	    <div class="navigation ui-layout-west noprint" style="position: relative;overflow:hidden !important;">
	        <?php include "west.php";?>
	    </div>
		
	    <div id="northframe" class="ui-layout-north noprint">
		    <?php include "north.php";?>
		</div>
		
        <div id="centerframe" class="ui-layout-center noprint">
		    <center style="height: 100%;">
                <div id="center-content" style="padding:0px;margin:0px;height:100% !important;width:100% !important;overflow:hidden;">
			    </div>
			</center>
	    </div>		

		<div id="eastframe" class="ui-layout-east noprint" style="overflow: visible !important;">
		    <?php include "east.php";?>
		</div>
		
		<div id="southframe" class="ui-layout-south noprint">
		    <?php include "south.php";?>
			<audio id="southnotification" src="../frame/skins/sounds/sounds-913-served.mp3" preload="auto" style="display:none;"/>
		</div>
		
		<div class='print closed' id='printdiv'>
		</div>
		
	</body>
    <footer style='display:none;'>Created and Designed by Christian John Clark</footer>
</html>