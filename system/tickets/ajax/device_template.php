<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$i = $_GET['nmb'];
?>
<tr class="drow<?php echo $i;?>">
    <td>
	    <label id="devlabel<?php echo $i;?>" style="vertical-align:top;"><b>Device <?php echo $i;?></b></label>
	</td>
	<td style="padding-top:6px;">
        <select id="manu<?php echo $i;?>" name="manu<?php echo $i;?>" class="required" onChange="GetModels('tickets/ajax/model_template.php?load='+this.value,'model<?php echo $i;?>',this);$(this).parent().removeClass('error');" style="vertical-align:top;">
	        <option value=""></option>
	        <?php
	        $db = $Core->db();
	        $Main = $Core->pdoQuery($db, 'SELECT m_id,m_name FROM device_manufacturers ORDER BY m_name ASC', '');
		    foreach ($Main as $b){
			    echo "<option value='".$b['m_id']."'>".$b['m_name']."</option>";
		    }
	        ?>
        </select>
	</td>
	<td style="padding-top:6px;">
        <select id="model<?php echo $i;?>" name="model<?php echo $i;?>" class="required" style="vertical-align:top;" onChange="$(this).parent().removeClass('error');" DISABLED><option value=""></option></select>
	</td>
	<td>
		<input type="text" id="imei<?php echo $i;?>" size="8" name="imei<?php echo $i;?>" placeholder="IMEI/MEID" title="Scan or Type a full IMEI or MEID" maxlength="18" minlength="14" class="required" style="vertical-align: top;padding: 1px !important;margin: 0px !important;">
	</td>
	<td>
		<input type="text" id="pass<?php echo $i;?>" size="8" name="pass<?php echo $i;?>" placeholder="Password" minlength="2" class="required" style="vertical-align: top;padding: 1px !important;margin: 0px !important;">
	</td>
	<td style="width:195px;">
		<div id="damages<?php echo $i;?>" style="vertical-align:top;display: inline-block;" class="required">
	        <input type="checkbox" id="phy<?php echo $i;?>" name="phy<?php echo $i;?>" tabindex="-1" /><label for="phy<?php echo $i;?>">Physical</label>
	        <input type="checkbox" id="liq<?php echo $i;?>" name="liq<?php echo $i;?>" tabindex="-1" /><label for="liq<?php echo $i;?>">Liquid</label>
	        <input type="checkbox" id="sof<?php echo $i;?>" name="sof<?php echo $i;?>" tabindex="-1" /><label for="sof<?php echo $i;?>">Software</label>
        </div>
	</td>
</tr>
<tr class="drow<?php echo $i;?>">
    <td colspan="6">	
		<textarea placeholder="Describe Device Issue..." style="width:100%;height:16px;vertical-align:top;resize: vertical;padding:2px;margin:0px;" class="required" id="issue<?php echo $i;?>" name="issue<?php echo $i;?>"></textarea>
	</td>
        <script id="devscr<?php echo $i;?>">
		    $(function () {
			    $("#damages<?php echo $i;?>").buttonset();
                $("#model<?php echo $i;?>").ufd({skin: "sexy",addEmphasis: true,minWidth: 80,});
				$("#ufd-model<?php echo $i;?>").attr("placeholder","Model");
				$("#manu<?php echo $i;?>").ufd({skin: "sexy",addEmphasis: true,minWidth: 80,});
				$("#ufd-manu<?php echo $i;?>").attr("placeholder","Manufacturer");
            });     
        </script>
</tr>