<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$CHECKOUT = MYSQL::QUERY("SELECT * FROM core_checkout_sessions WHERE qb_id = ? LIMIT 1",ARRAY($_GET['chid']));
$ITEMS = EXPLODE('|',$CHECKOUT['items']);
$LIST ='';
$IT = 1;
FOREACH($ITEMS AS $I){
    IF($I != ''){
	    $I = EXPLODE('/',$I);
		$LIST .= "<tr><td>".$I[0]."</td><td><input type='text' name='item".$IT."' value='".$I[1]."'></td></tr>";
	}
	$IT++;
}
ECHO <<<STR
    <form id='mod_checkout'>
	  <input type='text' name='chid' style='display:none' value='{$_GET['chid']}'>
	  <center>
	    *Dont Forget - The 15% off from multiple purchases is only removed in the Payment method Charges*<br/><br/>
	    <table style='width:300px;text-align:center;'>
		    <thead>
			    <th>Item</th><th>Charge</th>
			</thead>
			<tbody>
			    $LIST
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td colspan="2"><b>Payment Methods</b></td></tr>
				<tr>
				    <td>PM 1</td>
					<td>
					    <select name='pm_1'>
			                <option value="{$CHECKOUT['pm_1']}">{$CHECKOUT['pm_1']}</option>
							<optgroup label="Change it...">
			                    <option value="Cash">Cash</option>
			                    <option value="Check">Check</option>
			                    <option value="American Express">American Express</option>
			                    <option value="Discover">Discover</option>
			                    <option value="MasterCard">MasterCard</option>
			                    <option value="Visa">Visa</option>
			                    <option value="Debit Card">Debit Card</option>
							</optgroup>
			            </select>
					</td>
				</tr>
				<tr>
				    <td>PM 1 Charge</td>
					<td><input type='text' name='pm_1_charge' value='{$CHECKOUT['pm_1_cost']}'></td>
				</tr>
				<tr>
				    <td>PM 2</td>
					<td>
					    <select name='pm_2'>
			                <option value="{$CHECKOUT['pm_2']}">{$CHECKOUT['pm_2']}</option>
							<optgroup label="Change it...">
							    <option value="None">None</option>
			                    <option value="Cash">Cash</option>
			                    <option value="Check">Check</option>
			                    <option value="American Express">American Express</option>
			                    <option value="Discover">Discover</option>
			                    <option value="MasterCard">MasterCard</option>
			                    <option value="Visa">Visa</option>
			                    <option value="Debit Card">Debit Card</option>
							</optgroup>
			            </select>
					</td>
				</tr>
				<tr>
				    <td>PM 2 Charge</td>
					<td><input type='text' name='pm_2_charge' value='{$CHECKOUT['pm_2_cost']}'></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td colspan="2"><b>Checkout Date</b></td></tr>
				<tr>
				    <td>Date-Time</td>
					<td><input type='text' name='date' value='{$CHECKOUT['d_date']}'></td>
				</tr>
			</tbody>
		</table>
	  </center>
	</form>
STR;
?>