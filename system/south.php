<div class='txt_dsb' style="margin-top:0px !important;padding-left:2px;">
    <select id="message_to" style="width:170px;" data-placeholder="Message To..">
        <?php
		    $R = MYSQL::QUERY('SELECT user_id,username FROM core_users ORDER BY username ASC');
			Foreach($R as $a){
			    echo '<option value="'.$a['user_id'].'">'.$a['username'].'</option>';
			}
		?>
    </select>
    <div class='txt_dsb_fix' style='display:inline-block;width: 50% !important;'>
	    <input type='text' id='chat_message' placeholder='Message... (Press Enter to Send it)test' onKeyDown="SendMessage(event, '<?php echo $user['username'];?>')">
	</div>
	<span style="float: right;padding-right: 62px;padding-top: 5px;" id="curtime"></span>
	<span id='systemlatency' title="The Average time it takes to talk to the server. The lower this number the better your connection. Consistently high(300+) latencies may indicate an issue with your internet. You can click here to refresh your latency." style='position:absolute;top:5px;right:5px;' onClick="CheckMessages();"></span>
</div>
<div class='txt_dsb' id='chatbody' style='display: none;'>
</div>
<script>
CheckMessages();
</script>
