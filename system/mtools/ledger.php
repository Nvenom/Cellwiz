<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);
?>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Manager Tools - Daily Ledger</h3>
		<div style="width:98%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
			    <form id='compareledgers'>
			        Date:<input type='text' name='date' placeholder='Year-Month-Day' value='<?php echo Date('Y-m-d'); ?>'> Store: <?php echo $user['store'];?><br/><br/>
			        <div id='KEY' style='display:inline-block;width:250px;vertical-align:top;'>Receipts:<br/><input type='text' name='rec' placeholder='Amount of Receipts' ></div>
				    <div id='CASH' style='display:inline-block;width:250px;vertical-align:top;'>Cash:<br/><input type='text' name='cas' placeholder='Total Cash'></div>
				    <div id='CHECK' style='display:inline-block;width:250px;vertical-align:top;'>Check:<br/><input type='text' name='che' placeholder='Total Check'></div>
				    <div id='AMEX' style='display:inline-block;width:250px;vertical-align:top;'>American Express:<br/><input type='text' name='ame' placeholder='Total Amex'></div><br/><br/>
				    <div id='DISCOVER' style='display:inline-block;width:250px;vertical-align:top;'>Discover:<br/><input type='text' name='dis' placeholder='Total Discover'></div>
				    <div id='MASTER' style='display:inline-block;width:250px;vertical-align:top;'>Master Card:<br/><input type='text' name='mas' placeholder='Total Master Card'></div>
				    <div id='VISA' style='display:inline-block;width:250px;vertical-align:top;'>Visa:<br/><input type='text' name='vis' placeholder='Total Visa'></div>
				    <div id='DEBIT' style='display:inline-block;width:250px;vertical-align:top;'>Debit:<br/><input type='text' name='deb' placeholder='Total Debit'></div><br/><br/>
					<center>
					<table style='width:80%;text-align:left;display:none;' id='listofticks'>
					    <thead>
						    <th>Valid</th><th>Item</th><th>Charge</th><th>PM 1</th><th>PM 1 Charge</th><th>PM 2</th><th>PM 2 Charge</th>
						</thead>
						<tbody>
						</tbody>
					</table>
					</center>
				</form>
				<button onClick='CompareLedgers()' id="compareledges">Compare Ledgers</button>
            </div>
        </div>
    </div>
</div>