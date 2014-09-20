<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$cid = $_GET['cid'];
	$note = $_GET['note'];
	$date = Date("Y-m-d H:i:s");
    $params = array($cid,$note,$user['user_id'],$date);
	MYSQL::QUERY('INSERT INTO core_customers_note (c_id, c_note, c_note_by, c_date) VALUES (?, ?, ?, ?)', $params);
	$note = MYSQL::QUERY("SELECT * FROM core_customers_note WHERE c_id = ? ORDER BY c_date DESC", array($cid));
	echo '<ul>';
	$ln = '';
    foreach($note as $n){
		if($ln == $n['c_note_by']){}else{
			if($n['c_note_by']==4){
				$noteuser = array('username' => 'System');
			} else {
			    $noteuser = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", array($n['c_note_by']));
				$ln = $n['c_note_by'];
			}
		}
		if($n['c_note_by'] == $user['user_id']){$ed = "forget";}  else {$ed = "fogret";}
		switch($n['c_type']){
			case 0:$color='usernote';break;
			case 1:$color='systemnote';break;
			case 2:$color='statusnote';break;
			case 3:$color='originalnote';break;
		}
		echo'
			<li class="'.$color.'"><b>'.date("[m/d/y] h:i A", strtotime($n['c_date'])).' - '.$noteuser['username'].': </b><font class="'.$ed.'" id="note'.$n['note_id'].'">'.$n['c_note'].'</font></li>
		';
	}
	echo '</ul>';
?>
<script>
$(".forget").editable("ajax/save_note.php", {cancel    : "Cancel",submit : "Save"});
</script>