<?php
require("../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);
$error = $_GET['e'];
if($error == 400){
    $en = "Bad Request";
	$ed = "The request had bad syntax or was impossible to be satisified.";
	$ec = "err0r4o0";
} else if($error == 401){
    $en = "Unauthorized";
	$ed = "User failed to provide a valid user name / password required for access to file / directory.";
	$ec = "erorr041";
} else if($error == 403){
    $en = "Forbidden";
	$ed = "The directory or the file does not have the permission that allows the pages to be viewed from the web.";
	$ec = "er4rr0o3";
} else if($error == 404){
    $en = "No File Found";
	$ed = "The requested file was not found.";
	$ec = "er0ror44";
}

$value = unpack('H*', "$ec");
$binary = base_convert($value[1], 16, 2);
?>
<html>
    <head>
		<META http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<META NAME='copyright' CONTENT='Copyright ©Cellwiz.net <?php echo Date('Y'); ?>. All Rights Reserved.'>
		<META NAME='author' CONTENT='Christian John Clark'>
	    <META NAME='robots' CONTENT='noindex'>
		<META NAME='language' CONTENT='English'>
        <link rel='stylesheet' type='text/css' href='../frame/skins/default/css/main.css<?php echo $debug; ?>' />
		<link rel='stylesheet' type='text/css' href='../frame/skins/default/css/default/ui.css<?php echo $debug; ?>' />
		<link rel='stylesheet' type='text/css' href='../frame/skins/default/css/print.css<?php echo $debug; ?>' media='print' />
		<script src='../frame/controllers/c-controller.js<?php echo $debug; ?>'></script>
	</head>
	<body style="overflow:hidden !important;background-image: url(../core/images/bg/low_contrast_linen.png) !important;margin:0px;padding:0px;">
        <center>
	        <font style="color:white;font-size:80px;"><b><?php echo $error;?></b></font><br/>
			<font style="color:white;font-size:12px;"><?php echo "<b>".$en."</b><br/>".$ed;?></font><br/><br/>
	        <div style="color:rgb(83, 83, 83);width:50%;"><?php echo $binary; ?></div><br/>
		    <form>
		        <input type="password" size="8" maxlength="8"><input type="submit" value="Check">
		    </form>
	    </center>
	</body>
</html>