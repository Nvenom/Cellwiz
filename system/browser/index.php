<?php
    require("../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
?>
<iframe src="http://www.bing.com" id="browser" style="width:100%;height:100%;"></iframe>