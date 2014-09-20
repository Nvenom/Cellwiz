<?php
REQUIRE("../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0);
?>
<h3 class="block-banner">Avatars</h3>
<?php
$GOLD = $USER['gold_medals'];
$SILVER = $USER['silver_medals'];
IF($GOLD >= 10){$CAP = 10;}ELSE{$CAP = $GOLD;}
$I = 0;
WHILE($I <= $CAP){
    $IN = 0;
    $FILES = GLOB('../../frame/skins/avatars/'.$I.'/*.{png}', GLOB_BRACE);
    FOREACH($FILES AS $FILE) {
	    IF($SILVER >= $IN || $I < $CAP){
            ECHO '
	            <span class="image-wrap" style="margin:5px;float:left;cursor:pointer;" onClick="SetAvatar('.$USER['user_id'].', '."'new/".$FILE."'".')">
				    <span style="background-color:rgba(150, 150, 150, 0.2);"></span>
                    <img src="new/'.$FILE.'" border="0" style="height:128px;" />
		        </span>
            ';
		} ELSE IF($SILVER + 10 >= $IN){
		    ECHO '
			    <span class="image-wrap" style="margin:5px;float:left;width:128px;height:128px;background-color:rgba(150, 150, 150, 0.2);color:white;font-size: 15px;text-shadow: 2px 2px 1px black;border-radius: 10px;">
				    <center>
				        <h2>Unlock</h2>
                        <img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" /><label>'.$I.'</label><br/>
		                <img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" /><label>'.$IN.'</label><br/>
					</center>
		        </span>
			';
		}
        $IN = $IN + 10;
    }
	IF($SILVER >= 90 && $I == $CAP){
	    ECHO '
			<span class="image-wrap" style="margin:5px;float:left;width:128px;height:128px;background-color:rgba(150, 150, 150, 0.2);color:white;font-size: 15px;text-shadow: 2px 2px 1px black;border-radius: 10px;">
				<center>
				    <h2>Unlock</h2>
                    <img src="../frame/skins/default/images/medal-gold.png" border="0" style="height:20px;" /><label>'. ($I + 1) .'</label><br/>
		            <img src="../frame/skins/default/images/medal-silver.png" border="0" style="height:20px;" /><label>0</label><br/>
			    </center>
		    </span>
	    ';
	}
    $I++;
}
?>