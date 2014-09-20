<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);
?>
<script>
    var calcDataTableHeight = function() {
        return $(".centerfloat").height()-207;
    };
	var calcDataTableRows = function() {
	    return Number((calcDataTableHeight() / 42).toFixed(0));
	};
    var oTable = $('#refurblist').dataTable({
        "sScrollY": calcDataTableHeight(),
		"sPaginationType": "full_numbers",
		"iDisplayLength": calcDataTableRows(),
	});
    $(window).resize(function () {
        var oSettings = oTable.fnSettings();
        oSettings.oScroll.sY = calcDataTableHeight(); 
        oTable.fnDraw();
    }); 
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow:hidden;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Refurbishment - Device List</h3>
		<div style="width:98%;height:91%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;" id="refurblista">
                <table id="refurblist" class="stylized">
	    <thead>
		    <th>Refurb ID#</th>
			<th>Device</th>
			<th>Identifyer</th>
			<th>Price</th>
			<th>Unlocked Price</th>
			<th>Service</th>
			<th>Store</th>
			<th>Edit</th>
		</thead>
		<tbody style="background-color:white;">
		    <?php
			    $RD = MYSQL::QUERY('SELECT * FROM core_refurb_devices WHERE d_sold = 0 ORDER BY d_store ASC');
				$STOREID = '';$STORE = '';
				FOREACH($RD as $D){
				    IF($STOREID == $D['d_store']){} ELSE {$STORE = MYSQL::QUERY('SELECT s_name FROM core_stores WHERE s_id = ? LIMIT 1', ARRAY($D['d_store']));}
					IF($D['d_unlocked_price'] > $D['d_locked_price']){ $ULP = $D['d_unlocked_price']; } ELSE { $ULP = ''; }
					ECHO '
					    <tr class="rrt'.$D['d_id'].'">
						    <td>'.$D['d_id'].'</td>
							<td>'.$D['d_model'].'</td>
							<td>'.$D['d_iden'].'</td>
							<td class="lop">'.$D['d_locked_price'].'</td>
							<td class="ulp">'.$ULP.'</td>
							<td>'.$D['d_service_provider'].'</td>
							<td>'.$STORE['s_name'].'</td>
							<td><button onClick="EditRefurbDevice('."'".$D['d_id']."'".')" style="cursor:pointer;">Edit</button></td>
						</tr>
					';
				}
			?>
		</tbody>
	            </table>
            </div>
        </div>
    </div>
</div>