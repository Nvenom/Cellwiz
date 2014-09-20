<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);
?>
<script>
    var calcDataTableHeight = function() {
        return $(".centerfloat").height()-210;
    };
	var calcDataTableRows = function() {
	    return Number((calcDataTableHeight() / 42).toFixed(0));
	};
    var oTable = $('#listofinv').dataTable({
        "sScrollY": calcDataTableHeight(),
		"sPaginationType": "full_numbers",
		"iDisplayLength": calcDataTableRows()
	});
    $(window).resize(function () {
        var oSettings = oTable.fnSettings();
        oSettings.oScroll.sY = calcDataTableHeight(); 
        oTable.fnDraw();
    }); 
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Manager Tools - Negative Stock</h3>
		<div style="width:98%;height:91%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
			    <center>
					<table style='width:100%;text-align:left;border-collapse:collapse;' id='listofinv' class="stylized">
					    <thead>
						    <th>Order</th><th>Device</th><th>Part</th><th>Stock</th><th>Price</th><th>Reset</th>
					    </thead>
						<tbody>
							<?php
							    $INV = MYSQL::QUERY('SELECT ins.stock_id,ins.quantity,ins.price,dep.p_name,dvm.m_name FROM inventory_stock ins JOIN device_parts dep ON ins.item = dep.p_id JOIN device_models dvm ON dep.p_model_id = dvm.m_id WHERE ins.store = ? AND ins.quantity < 0',ARRAY($user['store']));
								FOREACH($INV AS $I){
								    ECHO <<<ROW
			                   		    <tr>
										    <td><input type="checkbox"></td>
										    <td>{$I['m_name']}</td>
											<td>{$I['p_name']}</td>
											<td>{$I['quantity']}</td>
											<td>{$I['price']}</td>
											<td><button onClick="$(this).parent().parent().fadeOut();return false;">Zero Out</button></td>
										</tr>
ROW;
								}
							?>
						</tbody>
					</table>
				</center>
            </div>
        </div>
    </div>
</div>