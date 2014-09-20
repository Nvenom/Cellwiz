<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);
?>
<script type="text/Javascript" src="mtools/js/add.js"></script>
<script type="text/Javascript">
    $(function() {
	    $("#createu").button();
    });
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Manager Tools - Add User</h3>
		<div style="width:98%;background-color:lightgrey;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
                <form id="user_data" style="display: block;">
                    <input type='text' id='username' name='username' placeholder='Username...' class='required'><br/>
					<input type='text' id='password' name='password' placeholder='Password...' class='required' style='margin-top:6px;margin-bottom:6px;'>
				</form>
				<button id="createu" onClick="CreateUser()">Create User</button>
			</div>
        </div>
    </div>
</div>