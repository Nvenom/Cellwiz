<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);

$RID = $_GET['r'];
$DEVICE = MYSQL::QUERY('SELECT * FROM core_refurb_devices WHERE d_id = ? LIMIT 1',ARRAY($RID));

?>

<center>
    Locked Price:<input type='text' name='lp' id='lp' class='required' placeholder='Locked Price... <?php ECHO $DEVICE['d_locked_price'];?>' maxlength="7" minlength="4">
	UnLocked Price:<input type='text' name='up' id='up' class='required' placeholder='UnLocked Price... <?php ECHO $DEVICE['d_unlocked_price'];?>' maxlength="7" minlength="4"><br/><br/>
	<button onClick="SaveRefurbEdit('<?php ECHO $RID;?>',$(this))">Save</button>
    <button onClick="RePrintRefurb('<?php ECHO $RID;?>',$(this))">Re-Print Refurb</button>
	<button onClick="DeleteRefurb('<?php ECHO $RID;?>',$(this))">Delete</button>
</center>