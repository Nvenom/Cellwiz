<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$today = DATE('Y-m-d H:i:s');
$days_ago = date('Y-m-d H:i:s', strtotime('-7 days', strtotime($today)));
MYSQL::QUERY("UPDATE inventory_stock SET quantity = 0 WHERE quantity < 0");
?>
<script>
    var calcDataTableHeight = function() {
        return $(".centerfloat").height()-230;
    };
	var calcDataTableRows = function() {
	    return Number((calcDataTableHeight() / 43).toFixed(0));
	};
    var oTable = $('#listofinv').dataTable({
        "sScrollY": calcDataTableHeight(),
		"sPaginationType": "full_numbers",
		"iDisplayLength": 500
	});
    $(window).resize(function () {
        var oSettings = oTable.fnSettings();
        oSettings.oScroll.sY = calcDataTableHeight(); 
        oTable.fnDraw();
    }); 
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Manager Tools - Purchase Order</h3>
		<div style="width:98%;height:91%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
			    <center>
					<table style='width:100%;text-align:left;border-collapse:collapse;' id='listofinv' class="stylized">
					    <thead>
						    <th>Exclude</th>
							<th>Device</th>
							<th>Part</th>
							<th>Stock</th>
							<th>Minimum</th>
							<th>Recommended</th>
							<th>Preferred</th>
							<th>Price</th>
					    </thead>
						<tbody>
							<?php
							    $INV = MYSQL::QUERY("SELECT ins.stock_id,ins.quantity,ins.minimum,ins.price,dep.p_name,dvm.m_name,COUNT(ins.stock_id) FROM inventory_stock ins JOIN device_parts dep ON ins.item = dep.p_id JOIN device_models dvm ON dep.p_model_id = dvm.m_id LEFT JOIN core_tickets_processed ctp ON ctp.t_repair_items LIKE CONCAT('%|it-', ins.item, '%') AND ctp.t_checkout_created >= ? AND ctp.t_checkout_created <= ? AND ctp.t_store = ? WHERE ins.store = ? AND ins.quantity < ins.minimum GROUP BY ins.stock_id",ARRAY($days_ago,$today,$user['store'],$user['store']));
								IF(!EMPTY($INV)){
								    FOREACH($INV AS $I){
									    
										$REC = $I['COUNT(ins.stock_id)'];
										
										$REBOUND = $I['minimum'] - $I['quantity'];
										IF($REC < $I['minimum']){$REC = $REBOUND;}
										
								        ECHO <<<ROW
			                   		        <tr>
										        <td class="exclude"><input type="checkbox" onClick="ExcludeRow($(this))"></td>
										        <td class="model">{$I['m_name']}</td>
											    <td class="part">{$I['p_name']}</td>
											    <td class="quantity">{$I['quantity']}</td>
												<td class="minimum">{$I['minimum']}</td>
												<td class="recommended">$REC</td>
												<td class="input"><input type="text" style="width:50px;"></td>
											    <td class="price">{$I['price']}</td>
										    </tr>
ROW;
								    }
								}
							?>
						</tbody>
					</table><br/>
					<button style="width:100%;cursor:pointer;" onClick="GeneratePO('<?php echo $user['store_info']['s_name'];?>','<?php echo $today;?>','<?php echo $days_ago; ?>');">Generate Purchase Order</button>
				</center>
            </div>
        </div>
    </div>
</div>