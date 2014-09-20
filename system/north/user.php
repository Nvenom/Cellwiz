<?php
REQUIRE("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);
?>
<span class="image-wrap" style="margin:5px;float:left;">
	<span style="background-color:#<?php ECHO $user['avatar_bg']?>;"></span>
	<img src="north/avatars/<?php echo $user['user_id'];?>.png?_=<?php echo time(); ?>" alt="No Avatar" border="0" style="height:80px;"/>
</span>
<div style="margin-top:5px;"><?php echo $user['username'];?></div>
<div style="margin-top:5px;">ID#<?php echo $user['user_id']; ?></div>
<div style="display:inline-block;margin-top:15px;width:60px;" title="Gold Medals: <?php echo $user['gold_medals'];?>"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" /><font id="uidgm"><?php echo $user['gold_medals'];?></font></div>
<div style="display:inline-block;width:40px;" title="Silver Medals: <?php echo $user['silver_medals'];?>"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" /><font id="uidsm"><?php echo $user['silver_medals'];?></font></div>
<div style="display:inline-block;width:40px;" title="Bronze Medals: <?php echo $user['bronze_medals'];?>"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" /><font id="uidbm"><?php echo $user['bronze_medals'];?></font></div>