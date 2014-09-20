<?php
require("../frame/engine.php");ENGINE::START();
$XML = simplexml_load_file('../VERSION.xml');
$user = USER::VERIFY(0);
$H = 26;
$TOOLS = '';
$BUGS = '';
$V = $_GET['v'];

IF($V != '0.1.6d'){
    ECHO "<script>window.location.reload(true);</script>";
}

$R = MYSQL::QUERY("SELECT * FROM core_users_estimates_daily WHERE u_id=? AND d_date=? LIMIT 1",ARRAY($user['user_id'],Date("Y-m-d")));
IF(EMPTY($R)){
    USER::STAT('repairs',0);
	USER::STAT('checkouts',0);
	USER::STAT('tickets',0);
	USER::STAT('estimates',0);
}

USER::REWARD();

FOREACH($XML->TOOLS->TOOL AS $TOOL){
    $TOOLS .= '<li style="top:'.$H.'px;" class="tool"><b>Tool Added</b><i>'.$TOOL.'</i></li>';
	$H = $H + 26;
}
FOREACH($XML->BUGS->BUG AS $BUG){
    $BUGS .= '<li style="top:'.$H.'px;" class="bug"><b>Bug Fixed</b><i>'.$BUG.'</i></li>';
	$H = $H + 26;
}
ECHO <<<HOME
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	<br/>
		<div style="width:98%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
			    <center><img border="0" src="../frame/skins/default/images/login-header.png"/><br/><br/><br/><br/><br/></center>
				<b style="color:red;font-size:25px;">PLEASE READ</b><br/>
				<font>Version 0.1.6d<br/>(Make sure the version below matches this one; if not you need to clear your <a href="http://support.google.com/chrome/bin/answer.py?hl=en&answer=95582">Cache</a>)<font><br/><br/>
				<div>
				    <div class="block b100" style="height:400px;">
					    <h3 class="block-banner">Recent Updates</h3>
						<ul class="block-infolist">
						    <li style="top:0px;" class="system">
							    <b>Version</b><i>$V</i>
								<b style="left:300px;">Status</b><i style="left:305px;">{$XML->SYSTEM->STATUS}</i>
								<b style="left:600px;">Updated</b><i style="left:605px;">{$XML->SYSTEM->UPDATED}</i>
							</li>
						    $TOOLS
							$BUGS
						</ul>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
HOME;
?>