<?php 
	include_once('../../../core/inc/core.php');
	$Core = new Core;
	$user = $Core->AjaxVerify(0);
	$db = $Core->db();
	$ticket = $_GET['ticket'];
	$note = $_GET['note'];
	$date = Date("Y-m-d H:i:s");
    $params = array($ticket,$note,$user['user_id'],$date);
	$Core->pdoQuery($db, 'INSERT INTO core_tickets_note (t_id, t_note, t_note_by, t_date) VALUES (?, ?, ?, ?)', $params);
	$noteparams = array($ticket);
	$note = $Core->pdoQuery($db, "SELECT * FROM core_tickets_note WHERE t_id = ? ORDER BY t_date DESC LIMIT 10", $noteparams);
	foreach($note as $n){
		$noteuserparams = array($n['t_note_by']);
		$noteuser = $Core->pdoQuery($db, "SELECT * FROM core_users WHERE user_id = ? LIMIT 1", $noteuserparams);
		if($n['t_note_by'] == $user['user_id']){$ed = "forget";}  else {$ed = "fogret";}
		echo'
			<b>'.date("[m/d/y] h:i A", strtotime($n['t_date'])).' - '.$noteuser[0]['username'].': </b><font class="'.$ed.'" id="note'.$n['note_id'].'">'.$n['t_note'].'</font><br/>
		';
	}
?>
<script>
$(".forget").editable("ajax/save_note.php", {cancel    : "Cancel",submit : "Save"});
</script>