<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $usera = USER::VERIFY(0,TRUE);
	
	$M = $_GET['m'];
	$OPTIONS = MYSQL::QUERY('SELECT dpl.id,dpl.name FROM device_models dm JOIN device_parts_list dpl ON dm.m_type = dpl.cat WHERE m_id = ? ORDER BY dpl.name ASC',ARRAY($M));
?>
<center>
    <form id="addnewpartform">
	    <select name="addpart" style="width:250px;" data-placeholder="Select Part..." class="required" onChange='RemoveError($(this))'>
		    <option value=""></option>
		    <?php
			    FOREACH($OPTIONS AS $O){
				    ECHO '<option value="'.$O["id"].'">'.$O["name"].'</option>';
				}
			?>
		</select><br/><br/>
		<select name="partcolor" style="width:250px;" data-placeholder="Select Color..." class="required" onChange='RemoveError($(this))'>
		    <option value=""></option>
			<option value="None">None</option>
			<option value="Black">Black</option>
			<option value="White">White</option>
			<option value="Red">Red</option>
			<option value="Blue">Blue</option>
			<option value="Green">Green</option>
			<option value="Orange">Orange</option>
			<option value="Gray">Gray</option>
			<option value="Purple">Purple</option>
			<option value="Yellow">Yellow</option>
		</select><br/><br/>
		<input type="text" name="model" style="display:none;" value="<?= $M ?>">
		<input type="text" name="partdesc" placeholder="Descriptor (Only Add if Multiple Variants)" style="width:243px;"><br/><br/>
	</form>
	<button style="width:250px;" onClick="SendNewPart($(this),<?= $M ?>);return false;">Add Part and Stock</button>
</center>