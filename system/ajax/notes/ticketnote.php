<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$ticket = $_GET['ticket'];
	$note = $_GET['note'];
	$date = Date("Y-m-d H:i:s");
    $params = array($ticket,$note,$user['user_id'],$date);
	MYSQL::QUERY('INSERT INTO core_tickets_note (t_id, t_note, t_note_by, t_date) VALUES (?, ?, ?, ?)', $params);
	$note = MYSQL::QUERY("SELECT * FROM core_tickets_note WHERE t_id = ? ORDER BY t_date DESC", array($ticket));
	echo '<ul>';
    foreach($note as $n){
		if($ln == $n['t_note_by']){}else{
			if($n['t_note_by']==4){
				$noteuser = array('username' => 'System');
			} else {
			    $noteuser = MYSQL::QUERY("SELECT * FROM core_users WHERE user_id = ? LIMIT 1", array($n['t_note_by']));
				$ln = $n['t_note_by'];
			}
		}
		if($n['t_note_by'] == $user['user_id']){$ed = "forget";}  else {$ed = "fogret";}
		switch($n['t_type']){
			case 0:$color='usernote';break;
			case 1:$color='systemnote';break;
			case 2:$color='statusnote';break;
			case 3:$color='originalnote';break;
		}
		echo'
			<li class="'.$color.'"><b>'.date("[m/d/y] h:i A", strtotime($n['t_date'])).' - '.$noteuser['username'].': </b><font class="'.$ed.'" id="note'.$n['note_id'].'">'.$n['t_note'].'</font></li>
		';
	}
	echo '</ul>';
?>
<script>
$(".forget").editable("ajax/notes/save_note.php", {cancel: "Cancel",submit : "Save"});
</script>