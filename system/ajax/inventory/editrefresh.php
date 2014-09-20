<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(1,TRUE);
	
	$pid = $_GET['pid'];
	
	$string = '';
	
	$Stock = MYSQL::QUERY('SELECT * FROM inventory_stock WHERE item = ? AND quantity > 0', ARRAY($pid));
	$yStock = MYSQL::QUERY('SELECT * FROM inventory_stock WHERE item = ? AND store = ? LIMIT 1', ARRAY($pid, $user['store']));
	IF(EMPTY($yStock)){$yStock['quantity'] = 'N/A';$yStock['price'] = 'N/A';$yStock['minimum'] = 'N/A';}
	$string .= "<Table id='es".$pid."' class='stylized' style='width:100%;text-align:center;border-collapse:collapse;'><tr><td><b>Store</b></td><td><b>Quantity</b></td><td><b>Price</b></td><td><b>Minimum Stock</b></td></tr>";
	$string .= "<tr>
	    <td>".$user['store']."</td>
		<td>
		    <input type='text' placeholder=' ".$yStock['quantity']." Currently' style='width:100px;' onFocus='$(this).next().next().fadeIn();' onBlur='$(this).next().next().delay(600).fadeOut();' onKeyUp='AddInvEnter($(this),event)'><br/>
			<button style='width:106px;display:none;' onClick='UpdateInventory(".'"'.$pid.'"'.", ".'"'.$yStock['quantity'].'"'.", ".'"Q"'.", $(this))'>Save</button>
		</td>
		<td>
		    <input type='text' placeholder=' ".$yStock['price']." Currently' style='width:140px;' onFocus='$(this).next().next().fadeIn();' onBlur='$(this).next().next().delay(600).fadeOut();' onKeyUp='onKeyUp='AddInvEnter($(this),event)'><br/>
			<button style='width:146px;display:none;' onClick='UpdateInventory(".'"'.$pid.'"'.", ".'"'.$yStock['price'].'"'.", ".'"P"'.", $(this))'>Save</button>
		</td>
		<td>
		    <input type='text' placeholder=' ".$yStock['minimum']." Currently' style='width:100px;' onFocus='$(this).next().next().fadeIn();' onBlur='$(this).next().next().delay(600).fadeOut();' onKeyUp='AddInvEnter($(this),event)'><br/>
			<button style='width:106px;display:none;' onClick='UpdateInventory(".'"'.$pid.'"'.", ".'"'.$yStock['minimum'].'"'.", ".'"M"'.", $(this))'>Save</button>
		</td>
	</tr>";
	IF(!$Stock == ''){
	    foreach($Stock as $S){
	        $string .= "<tr><td>".$S['store']."</td><td>".$S['quantity']."</td><td>".$S['price']."</td><td>".$S['minimum']."</td></tr>";
	    }
	}
	$string .= "</table>";
	
	if($user['level'] >= 7){
	    $delete = true;
	} else {
	    $delete = false;
	}
	
	if($delete == true){
	    //$string .= "<button onClick='DeleteItem(".$pid.")'>Delete Item</button>";
	}
	echo $string;
?>