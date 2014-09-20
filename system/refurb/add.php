<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);

$Main = MYSQL::QUERY('SELECT m_id,m_name FROM device_manufacturers ORDER BY m_name ASC');
$options = "";
foreach ($Main as $b){
	$options .= '<option value="'.$b['m_id'].'">'.$b['m_name'].'</option>';
}
?>
<script>
function GetModels(strURL,elemente,tease)
{
    $("#" + elemente).attr('disabled', true).trigger("liszt:updated");
    $.ajax({
        url: strURL,
        cache: false
    }).done(function( html ) { 
        $("#" + elemente).attr("disabled", false).html(html).trigger("liszt:updated");
    });
}

function RemoveError(Element){
    var thisid = $(Element).attr("id");
	$("#" + thisid + "_chzn a").removeClass("error");
}

function AddDevice(){
    var valid = $("#refurbform").valid();
	var selectvalid = $("#refurbform").validate().element("select");
	if(valid == true && selectvalid == true){
	    var oopa = $("<p>Are you sure want to Continue?</p>").dialog({
		    title: 'Confirmation',
            close: function(event, ui){$(this).dialog('destroy').remove()},
			modal: true,
			buttons: {
				"Yes": function() {
				    $(this).html("<p><Center>Adding Device and Generating Form, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
	                var dataa = $("#refurbform").serialize();
	                $.ajax({
		                url: "refurb/ajax/new_device.php",
			            data: dataa,
			            cache: false
		            }).done(function(html){
		                $("#printdiv").html(html);
			            ChangeCenter('refurb/add.php','');
						oopa.dialog('destroy').remove();
			            window.print();
		            });
				},
				"No": function() {
					$(this).dialog('destroy').remove();
                    createt.attr("disabled", false).removeClass("ui-state-hover").removeClass("ui-state-disabled");
				}
			}
		});
	} else {
		$("#refurbform").validate().form();
		$("#refurbform").find('select').each(function (i){
		    if($(this).val() == ""){
			    var thisid = $(this).attr("id");
			    $("#" + thisid + "_chzn a").addClass("error");
			}
		});
		$("#refurbform").find("label").addClass("ignore");
	}
}

$(function() {
	$("#centerframe select").chosen();
	$("#adddevicebutton").button();
});
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Refurbishment - New Device</h3>
		<div style="width:98%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;" id="refurbbody">
                <form id='refurbform'>
				    <select id="manu" name="manu" class="required" onChange="GetModels('tickets/ajax/model_template.php?load=' + $(this).val(),'model',$(this));RemoveError($(this));" style="vertical-align:top;width:130px;" data-placeholder="Manufacturer...">
						<option value=""></option><?php echo $options;?>
					</select>
					<select id="model" name="model" class="required" style="vertical-align:top;width:200px;" data-placeholder="Model..." onChange="RemoveError($(this));" DISABLED>
						<option value=""></option>
					</select><br/><br/>
					<select id="sp" name="sp" class="required" data-placeholder="Carrier..." style="width:325px" onChange="RemoveError($(this));">
					    <option value=""></option>
						<option value="Verizon">Verizon</option>
						<option value="Sprint">Sprint</option>
						<option value="T-Mobile">T-Mobile</option>
						<option value="AT;T">AT & T</option>
						<option value="MetroPCS">MetroPCS</option>
						<option value="Boost Mobile">Boost Mobile</option>
						<option value="Virgin Mobile">Virgin Mobile</option>
					</select><br/><br/>
					
					<input type='text' name='lp' id='lp' class='required' placeholder='Locked Price... 0.00' maxlength="7" minlength="4">
					<input type='text' name='up' id='up' class='required' placeholder='UnLocked Price... 0.00' maxlength="7" minlength="4"><br/><br/>
					
					<input type='text' name='iden' id='iden' class='required' style='width:325px;' placeholder='Identifyer/IMEI/MEID...' maxlength="20" minlength="11"><br/><br/>
					
				    <textarea id='notes' name='notes' placeholder='Device Notes...' style='width:325px;'></textarea><br/>
				</form>
				<button onClick='AddDevice()' id='adddevicebutton'>Add Device</button>
			</div>
        </div>
    </div>
</div>