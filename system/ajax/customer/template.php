<?php
	require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
?>
<center>
    <form id="createcustomernow">
        <label style="font-weight:bold;">Customer Name</label><br/>
	    <input type="text" size="10" name="Fname" id="Fname" class="required" minlength="2" placeholder="First Name..." onChange="ChangeTitle($(this))" autofocus/>&nbsp;<input type="text" size="10" name="Lname" id="Lname" class="required" minlength="2" placeholder="Last Name..." onChange="ChangeTitle($(this))"/><br/><br/>
		
		<label style="font-weight:bold;">Primary Phone Number</label><br/>
	    <input type = "text" name="phone" id="phone" size="13" minlength="10" maxlength = "10" class="required number" placeholder="1234567890" onChange="CustomerCheck($(this), $(this).val())"/>
		<img id="searching-customer" src="../core/images/searchcustomer.gif" style="display:none;vertical-align:bottom;">
		<br/><br/>
		
		<label style="font-weight:bold;">Contact Method</label><br/>
		<select name="secondarymethod" id="secondarymethod" class="required" onChange="GenerateMethod($(this), $(this).val())">
		    <option value="">Select Method...</option>
			<option value="0">Contact Number</option>
			<option value="1">Email Address</option>
			<option value="2">Customer Will Contact us</option>
			<option value="3">Primary Phone</option>
		</select><br/><br/>
	
	    <label style="font-weight:bold;">ZIP Code</label><br/>
	    <input type="text" size="5" maxlength="5" name="zip" id="zip" placeholder="12345" class="required number" minlength="5" maxlength="5"/><br/><br/>
				
	    <label style="font-weight:bold;">How did They Hear about us?</label><br/>
	    <select name="market" id="market" onChange="PullLocation($(this), $(this).val())" class="required">
		    <option value="">Select Method...</option>
		    <?php
		        $Main = MYSQL::QUERY('SELECT a_id,a_name FROM core_advert_method ORDER BY a_name ASC');
		        foreach ($Main as $b){
			        echo "<option value='".$b['a_id']."'>".$b['a_name']."</option>";
		        }
		    ?>
	    </select><select id="market_location" name="market_location" class="required"><option value="">Method Location...</option></select><br/><br/>
		
		<label style="font-weight:bold;">Corporate Account</label><br/>	
	    <select name="corpacc" id="corpacc" class="required">
		    <option value="">Select..</option>
		    <?php
		        $Main = MYSQL::QUERY('SELECT c_id,c_name FROM core_corporate_accounts ORDER BY c_id ASC');
		        foreach ($Main as $b){
			        echo "<option value='".$b['c_id']."'>".$b['c_name']."</option>";
		        }
		    ?>
		</select><br/><br/>
	</form>
</center>