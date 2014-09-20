<?php
require("frame/engine.php");ENGINE::START();
IF(ISSET($_COOKIE['core_u'])){
    setcookie("core_u", "", time() - 100000, '/');
	setcookie("core_k", "", time() - 100000, '/');
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<META http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<META NAME='copyright' CONTENT='Copyright © <?php echo Date('Y'); ?> CCNetTech. All Rights Reserved.'>
	<META NAME='author' CONTENT='Christian John Clark'>
	<META NAME='robots' CONTENT='noindex'>
	<META NAME='language' CONTENT='English'>
	<title>Cellwiz System</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script>
        jQuery.fn.vibrate = function (axis, distance, repetition, duration) {var i = 0;var o = distance / distance;switch (axis) {case 'x':while (i < repetition) {$(this).animate({marginLeft: '-' + distance + 'px'}, duration);$(this).animate({marginLeft: distance}, duration);i++;if (i == repetition) {$(this).animate({marginLeft: o},   duration);}}break;case 'y':while (i < repetition) {$(this).animate({marginTop: '-' + distance + 'px'}, duration);$(this).animate({marginTop: distance}, duration);i++;}break;}};
        var loade;
        function loaddot(){loade = setInterval(function(){var g = $("input:button").val();if(g == '.' || g == '..'){var e = g + '.';} else {var e = '.';}$("input:button").val(e);},350);}
        function Failed(ele){$("#" + ele).vibrate('x', 10, 5, 40);clearInterval(loade);$("input:button").val(">");}
        function Success(){clearInterval(loade);$("input:button").val(">");$("input:button").stop().animate({left: '45%'},1000, function(){window.location.href = window.location.href+"system";});}
        function CheckLogin(){loaddot();var usr = $("input:text").val();var pas = $("input:password").val();if(usr == ''){Failed('username');} else {if(pas == ''){Failed('password');} else {$.ajax({type: "POST",url: "system/ajax/verify.php",data: "usr=" + usr + "&pas=" + pas,cache: false}).done(function(html){if(html == 'e1435'){Failed('username');}if(html == 'e1436'){Failed('password');}if(html == 'e1437'){Failed('boxx');}if(html == 's1434'){Success();}});}}}
        function check(event){if (event.keyCode==13){CheckLogin();}}
    </script>
    <style>
        body {background-color: #AAA;font-family:Arial, Helvetica, sans-serif;}
        #container {position:absolute;width:450px;top: 25%;}
        #boxx {position:relative;width:355px;height:96px;}
        fieldset {border:none; margin:0; padding:0}
        input[type="button"] {position:absolute;left:92%;top:23px;width:50px;height:50px;background-color: #ff0000;border: 5px solid #AAA;border-radius: 25px;-moz-border-radius: 25px;-webkit-border-radius: 25px;color: white;cursor: pointer;text-shadow: black 1px 1px 2px;font-size: 14px;font-weight: bold;-webkit-box-shadow: none;background-image: -webkit-gradient(linear, left top, right bottom, color-stop(0.25, #FF0000), color-stop(1.50, #000000));background-image: -webkit-linear-gradient(top left, #FF0000 25%, #000000 150%);}
        input[type="button"]:hover {-webkit-box-shadow: rgba(0, 0, 0, 0.7) 2px 2px 10px inset;box-shadow: rgba(0, 0, 0, 0.7) 2px 2px 10px inset;}
        input[type="text"] {position: absolute;top: 0px;left: 0px;width:335px;height:40px;color: white;background-color: #FF0000;margin:3px 0px;padding-left:20px;padding-right:0px;border: 0px;border-top-right-radius: 10px;border-top-left-radius: 10px;border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;-webkit-border-top-right-radius: 10px;-webkit-border-top-left-radius: 10px;-webkit-border-bottom-right-radius: 0px;-webkit-border-bottom-left-radius: 0px;-webkit-box-shadow: rgba(0, 0, 0, 0.8) 3px 3px 12px 1px inset, rgba(255, 255, 255, 0.746094) 0 1px 0 0;box-shadow: rgba(0, 0, 0, 0.8) 3px 3px 12px 1px inset, rgba(255, 255, 255, 0.746094) 0 1px 0 0;text-shadow: black 1px 1px 2px;font-size: 14px;}
        input[type="password"] {position: absolute;bottom: 0px;left: 0px;width:335px;height:40px;color:white;background-color: #FF0000;margin:3px 0px;padding-left:20px;padding-right:0px;border: 0px;border-top-right-radius: 0px;border-top-left-radius: 0px;border-bottom-right-radius: 10px;border-bottom-left-radius: 10px;-webkit-border-top-right-radius: 0px;-webkit-border-top-left-radius: 0px;-webkit-border-bottom-right-radius: 10px;-webkit-border-bottom-left-radius: 10px;-webkit-box-shadow: rgba(0, 0, 0, 0.8) 3px 3px 12px 1px inset, rgba(255, 255, 255, 0.746094) 0 1px 0 0;box-shadow: rgba(0, 0, 0, 0.8) 3px 3px 12px 1px inset, rgba(255, 255, 255, 0.746094) 0 1px 0 0;text-shadow: black 1px 1px 2px;font-size: 14px;}
        :focus{outline:0px none !important;border:0px none;}
        input::-webkit-input-placeholder {color:#990000;text-shadow: none;}
        input:-moz-placeholder {color:#990000;text-shadow: none;}
        input:-ms-input-placeholder {color:#990000;text-shadow:none;}
		
    </style>
</head>
<body style='margin:50% 50%;overflow:hidden;'>
    <center>
        <div id='container'>
		    <img src='frame/skins/default/images/login-header.png' border='0' id='logoz' style='position:absolute;left:-177.5px;'>
			<div id='boxx' style='position:absolute;left:-177.5px;top:150px;'>
        	    <input type="text" id='username' name="username" placeholder='Username...' onKeyUp='check(event)'/>
        	    <input type="password" id='password' name="password" placeholder='Password...' onKeyUp='check(event)' />
        	    <input type="button" name="submit" value=">" onClick='CheckLogin()'/>
		    </div><font style='position: absolute;left: -177.5px;top: 253px;color: #CCC;width: 355px;'>Optimized for 1920x1080 Monitor Resolution or Higher</font>
	    </div>
	</center>
</body>
<footer style='display:none;'>Created and Designed by Christian John Clark</footer>
</html>