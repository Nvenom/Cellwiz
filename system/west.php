<div class='inner-west-north'>
    <div id="usertag" class="bname" style="float:none;width:100%;font-size:15px;margin-top:2px;">
	    <span class="image-wrap" style="margin:5px;float:left;">
		    <span style="background-color:#<?php ECHO $user['avatar_bg']?>;"></span>
			<?php
			    IF(file_exists("north/avatars/".$user['user_id'].".png")){
		            ?><img src="north/avatars/<?php echo $user['user_id'];?>.png?_=<?php echo time(); ?>" alt="User Avatar" border="0" style="height:80px;"/><?php
				} ELSE {
				    ?><img src="north/avatars/noavatar.png" alt="No Avatar" border="0" style="height:80px;"/><?php
				}
			?>
		</span>
		<div style="margin-top:5px;"><?php echo $user['username'];?></div>
		<div style="margin-top:5px;">ID# <?php echo $user['user_id']; ?></div>
		<div style="display:inline-block;margin-top:15px;width:60px;" title="Gold Medals: <?= $user['gold_medals'];?>"><img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" /><font id="uidgm"><?php echo $user['gold_medals'];?></font></div>
		<div style="display:inline-block;width:40px;" title="Silver Medals: <?= $user['silver_medals'];?>"><img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" /><font id="uidsm"><?php echo $user['silver_medals'];?></font></div>
		<div style="display:inline-block;width:40px;" title="Bronze Medals: <?= $user['bronze_medals'];?>"><img src="../frame/skins/default/images/medal-bronze.png" border="0" style="height:20px;" /><font id="uidbm"><?php echo $user['bronze_medals'];?></font></div>
	</div>
    <div class="side-divider"></div>
    <button id="navhome" onClick="ChangeCenter('home.php')">Home</button>
	<button id="navmessages" onClick="Messages()" DISABLED>Messages</button>
	<button id="navsettings" onClick="$('#navigot').fadeToggle();$('#usersettings').fadeToggle();">Settings</button>
	<button id="navlogout" onClick="Logout()">Logout</button>
	<button id="navlock" onClick="LockAccount()">Lock Screen</button>
	<button id="navswitch" onClick="QAS()">Quick Account Switch</button>
    <div class="side-divider"></div>
    <div class="txt_dsb">
        <div class="txt_dsb_fix">
	        <input type="text" id="barcode_search" onKeyUp="SearchTicket(event,$(this).val());if($(this).val().length>0){$(this).next().fadeIn();}else{$(this).next().fadeOut();}" placeholder="Search Ticket...">
		    <div id="barcode_close" onClick="$(this).prev().val('');$(this).fadeOut();" class="close" style="display:none;"></div>
	    </div>
    </div>
    <div class="side-divider"></div>
	    <div class="txt_dsb">
        <div class="txt_dsb_fix">
	        <input type="text" id="customer_search" onKeyUp="SearchCustomer(event, $(this).val());if($(this).val().length>0){$(this).next().fadeIn();}else{$(this).next().fadeOut();}" placeholder="Search Customer...">
		    <div id="customer_close" onClick="$(this).prev().val('');$(this).fadeOut();" class="close" style="display:none;"></div>
	    </div>
    </div>
    <div class="side-divider"></div>
	<div class="bluebutton" onClick='AddCustomer()' id='AddCustomer'>
        <span>New Customer</span>
    </div>
    <div class="side-divider"></div>
</div>
<div class='inner-west-center'>
    <nav id="navigot" style="overflow-y:auto;overflow-x:hidden;height:100%;">
	    <?php
        $R = MYSQL::QUERY('SELECT * FROM nav_main WHERE val_level <= ?', array($user['level']));
	    foreach($R as $a){
		    echo '
			    <div class="sidebar-1" id="sb' . $a["ID"] . '" onMousedown="OpenSideBar('."'sb".$a['ID']."'".','."'block".$a['ID']."'".')">
                    <div class="close" id="sb' . $a["ID"] . '-but" ></div>
	                <div class="name">'  . $a["val_name"] . '</div>
                </div>
		    ';
		    echo '<blockquote id="block' . $a["ID"] . '" style="display:none;">';
			
		    $Item = MYSQL::QUERY('SELECT * FROM nav_item WHERE val_parent=?', array($a['ID']));
	        foreach($Item as $b){
		        echo '
                    <div class="item" onClick="ChangeCenter('."'".$b["val_file"]."'".');">
	                    <div class="arrow"></div>
		                <div class="spinner"></div>
		                <div class="name">' . $b["val_name"] . '</div>
		                <div class="info">' . $b["val_info"] . '</div>
	                </div>
			    ';
		    }
		    echo '</blockquote>';
	    }
	    ?>
    </nav>
	<div id="usersettings" style="overflow-y:auto;overflow-x:hidden;height:100%;display:none;">
	    <h3 class="block-banner" style="font-size:15px;">Change PIN</h3>
		    <input type="password" maxlength="4" style="margin:5px 18px;width:209px;" id="oldpin" onKeyUp="$(this).removeClass('error');" placeholder="Current Pin..."></br>
		    <input type="password" maxlength="4" style="margin:5px 5px 0px 18px;width:95px;" id="newpin" onKeyUp="$(this).removeClass('error');" placeholder="New Pin...">
            <input type="password" maxlength="4" style="margin:5px 18px 0px 5px;width:94px;" id="reppin" onKeyUp="$(this).removeClass('error');" placeholder="Repeat Pin..."></br>
            <button style="margin:5px 20px;width:209px;" onClick="SavePIN()">Save PIN</button>
	    <h3 class="block-banner" style="font-size:15px;">Change Password</h3>
		    <input type="password" maxlength="20" style="margin:5px 18px;width:209px;" id="oldpas" onKeyUp="$(this).removeClass('error');" placeholder="Current Password..."></br>
		    <input type="password" maxlength="20" style="margin:5px 18px;width:209px;" id="newpas" onKeyUp="$(this).removeClass('error');" placeholder="(Min 8) New Password..."></br>
            <input type="password" maxlength="20" style="margin:5px 18px;width:209px;" id="reppas" onKeyUp="$(this).removeClass('error');" placeholder="(Min 8) Repeat New Password..."></br>
            <button style="margin:5px 20px;width:209px;" onClick="SavePassword()">Save Password</button>		
	</div>
</div>
<div class='inner-west-south'>
    <div class="side-divider"></div>
	<div class="bluebutton" onClick="ChangeCenter('gantt/estimategantt.php','')"><span>Daily Gantt</span></div>
</div>