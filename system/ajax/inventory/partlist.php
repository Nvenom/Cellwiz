<?php 
    REQUIRE("../../../frame/engine.php");ENGINE::START();
    $USER = USER::VERIFY(1,TRUE);

	$MID = $_GET['m'];
	$PARTS = MYSQL::QUERY("SELECT * FROM device_parts dp LEFT JOIN inventory_stock iv ON store = ? AND item = dp.p_id WHERE dp.p_model_id = ? ORDER BY dp.p_name ASC",ARRAY($USER['store'],$MID));
?>
    <table class="stylized">
	    <thead>
		    <tr>
			    <th>Part</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Minimum</th>
				<th>Save</th>
			</tr>
		</thead>
		<tbody>
	    <?php
		IF(!EMPTY($PARTS)){
		    FOREACH($PARTS AS $P){
		        IF(!EMPTY($P['stock_id'])){
			        $Q = $P['quantity'];
				    $M = $P['minimum'];
				    $PR = $P['price'];
			    } ELSE {
			        $Q = 'N/A';
				    $M = 'N/A';
				    $PR = 'N/A';
			    }
		        ECHO '
			    <tr>
			        <td>'.$P['p_name'].'</td>
				    <td><input type="text" name="quann" class="quan" placeholder="'.$Q.'" style="width:50px;"></td>
				    <td><input type="text" name="price" class="pric" placeholder="'.$PR.'" style="width:50px;"></td>
					<td><input type="text" name="minim" class="mini" placeholder="'.$M.'" style="width:50px;"></td>
					<td><button onClick="EditPartRow($(this),'."'".$P['p_id']."'".')">Save</button></td>
			    </tr>
			    ';
		    }
		}
		?>
		</tbody>
	</table>