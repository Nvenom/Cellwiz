<?php
REQUIRE("../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0);
$USER_STATS = ARRAY();
$TIME = '.png?_='.time();
?>
<script>
function imgError(image){
    image.onerror = "";
    image.src = "north/avatars/noavatar.png";
    return true;
}
</script>
<h3 class="block-banner">Leader Boards</h3>
<span class='block-wrap'>
    <span class='block-span' style="background-color:rgba(150, 150, 150, 0.25);"></span>
	<div class='block-div' style="width:100%;">
	    <?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_repairs_daily WHERE d_key > (SELECT d_key FROM core_users_repairs_daily WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-d")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />35</font>Daily Repairs<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_repairs_daily WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-d")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
		<?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_repairs_monthly WHERE d_key > (SELECT d_key FROM core_users_repairs_monthly WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-00")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />1</font>Monthly Repairs<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_repairs_monthly WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-00")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
	</div>
</span>
<span class='block-wrap'>
    <span class='block-span' style="background-color:rgba(150, 150, 150, 0.25);"></span>
	<div class='block-div' style="width:100%;">
	    <?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_tickets_daily WHERE d_key > (SELECT d_key FROM core_users_tickets_daily WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-d")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />35</font>Daily Tickets<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_tickets_daily WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-d")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
		<?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_tickets_monthly WHERE d_key > (SELECT d_key FROM core_users_tickets_monthly WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-00")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />1</font>Monthly Tickets<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_tickets_monthly WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-00")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
	</div>
</span>
<span class='block-wrap'>
    <span class='block-span' style="background-color:rgba(150, 150, 150, 0.25);"></span>
	<div class='block-div' style="width:100%;">
	    <?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_estimates_daily WHERE d_key > (SELECT d_key FROM core_users_estimates_daily WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-d")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />35</font>Daily Estimates<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_estimates_daily WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-d")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
		<?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_estimates_monthly WHERE d_key > (SELECT d_key FROM core_users_estimates_monthly WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-00")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />1</font>Monthly Estimates<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_estimates_monthly WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-00")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
	</div>
</span>
<span class='block-wrap'>
    <span class='block-span' style="background-color:rgba(150, 150, 150, 0.25);"></span>
	<div class='block-div' style="width:100%;">
	    <?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_checkouts_daily WHERE d_key > (SELECT d_key FROM core_users_checkouts_daily WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-d")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />35</font>Daily Checkouts<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_checkouts_daily WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-d")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
		<?php
		    $RANK = MYSQL::QUERY("SELECT COUNT(u_id) FROM core_users_checkouts_monthly WHERE d_key > (SELECT d_key FROM core_users_checkouts_monthly WHERE u_id = ? LIMIT 1) AND d_date=?",ARRAY($USER['user_id'],DATE("Y-m-00")));
			$RANK = $RANK['COUNT(u_id)']+1;
		?>
	    <h3 class="block-banner" style="position:relative;"><font style="position:absolute;left:17px;font-size:12px;top:7px;"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />1</font>Monthly Checkouts<font style="position:absolute;right:17px;font-size:12px;top:17px;">Your Rank #<?php echo $RANK;?></font></h3>
		    <ol class="lb-ol">
		    <?php
		        $DR = MYSQL::QUERY("SELECT * FROM core_users_checkouts_monthly WHERE d_date = ? ORDER BY d_key DESC LIMIT 5",ARRAY(DATE("Y-m-00")));
                $I=1;
				IF(EMPTY($DR)){
				    ECHO "<font class='lb-user'>No Results Yet.</font>";
				} ELSE {
			    FOREACH($DR AS $USR){
				    IF(!ISSET($USER_STATS[$USR['u_id']])){
					    $USER_STATS[$USR['u_id']] = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", ARRAY($USR['u_id']));
					}
			        ECHO '
				        <li style="margin-top: 5px;">
						    <font class="lb-rank">#'.$I.'</font>
					        <span class="image-wrap">
						        <span style="background-color:#'.$USER_STATS[$USR['u_id']]['avatar_bg'].';bottom:-4px;"></span>
								<img src="north/avatars/'.$USR['u_id'].'.png?lastupdated='.$USER_STATS[$USR['u_id']]['avatar_last_updated'].'" style="height:50px;" border="0" onerror="imgError(this);"/>
						    </span>
					        <font class="lb-user">
							    '.$USER_STATS[$USR['u_id']]['username'].'
								<div style="position:absolute;top:50%;right:120px;" title="Gold Medals: '.$USER_STATS[$USR['u_id']]['gold_medals'].'"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['gold_medals'].'</div>
							    <div style="position:absolute;top:50%;right:60px;" title="Silver Medals: '.$USER_STATS[$USR['u_id']]['silver_medals'].'"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['silver_medals'].'</div>
							    <div style="position:absolute;top:50%;right:20px;" title="Bronze Medals: '.$USER_STATS[$USR['u_id']]['bronze_medals'].'"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" />'.$USER_STATS[$USR['u_id']]['bronze_medals'].'</div>
								<div style="position:absolute;top:50%;right:50%;">'.$USR['d_key'].'</div>
							</font>
					    </li>
				    ';
					$I++;
			    }
				}
		    ?>
		    </ol>
	</div>
</span>