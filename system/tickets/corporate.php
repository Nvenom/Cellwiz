<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);
$version = $_GET['v'];

if($_GET['method'] == 'pre'){
    $method = 'style="display: none;"';
	$type = "<option value='$id'>$idn</option>";
} else {
    $type = "<option value=''></option>";
}
$Main = MYSQL::QUERY('SELECT m_id,m_name FROM device_manufacturers ORDER BY m_name ASC');
$options = "";
foreach ($Main as $b){
	$options .= '<option value="'.$b['m_id'].'">'.$b['m_name'].'</option>';
}
?>
<script>
var form_template = $("#form_template");
var device_amount = $("#device_amount");
var ticket_data = $("#ticket_data");
var createt = $("#createt");

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}

function increasePage(a) {
    for(a>1;a--;){
        var amnt = device_amount.val();
	    if(amnt == undefined || amnt <= 0){ 
	        var amnt = 1; 
	    } else {
	        var amnt = Number(amnt) + Number(1);
	    }
	    var nfun = "GetModels('tickets/ajax/model_template.php?load='+this.value,'model" + amnt + "',this);RemoveError($(this));";
	    var afun = "RemoveError($(this));";
	    var html = '<tr class="drow' + amnt + '">'+
		                '<td>'+
					        '<label id="devlabel' + amnt + '" style="vertical-align:top;">'+
						        '<b>Device ' + amnt + '</b>'+
							'</label>'+
						'</td>'+
					    '<td>'+
					        '<select id="manu' + amnt + '" name="manu' + amnt + '" class="required" onChange="' + nfun + '" style="vertical-align:top;width:130px;" data-placeholder="Manufacturer...">'+
							    '<option value=""></option><?php echo $options;?>'+
							'</select>'+
						'</td>'+
						'<td>'+
						    '<select id="model' + amnt + '" name="model' + amnt + '" class="required" style="vertical-align:top;width:200px;" data-placeholder="Model..." onChange="' + afun + '" DISABLED>'+
							    '<option value=""></option>'+
							'</select>'+
						'</td>'+
						'<td>'+
						    '<input type="text" id="imei' + amnt + '" size="8" name="imei' + amnt + '" placeholder="IDEN" title="Scan or Type a full IMEI/MEID or any other Device Identifyer(IDEN), Minimum 11 Characters. Double Click to Fill it if you cant get an Identifyer." maxlength="20" minlength="11" class="required" style="vertical-align: top;padding: 1px !important;margin: 0px !important;" ondblclick="$(this).val(' + "'???????????????'" + ')">'+
						'</td>'+
						'<td>'+
						    '<input type="text" id="pass' + amnt + '" size="8" name="pass' + amnt + '" placeholder="Password" title="If the device has no password type None or double click the input for it to Auto fill." minlength="2" class="required" style="vertical-align: top;padding: 1px !important;margin: 0px !important;" ondblclick="$(this).val(' + "'None'" + ')">'+
						'</td>'+
						'<td style="width:195px;">'+
						    '<div id="damages' + amnt + '" style="vertical-align:top;display: inline-block;width:100%;" class="required">'+
							    '<input type="checkbox" id="phy' + amnt + '" name="phy' + amnt + '" tabindex="-1" />'+
								'<label for="phy' + amnt + '" style="width:38%;">Physical</label>'+
								'<input type="checkbox" id="liq' + amnt + '" name="liq' + amnt + '" tabindex="-1" />'+
								'<label for="liq' + amnt + '" style="width:28%;">Liquid</label>'+
								'<input type="checkbox" id="sof' + amnt + '" name="sof' + amnt + '" tabindex="-1" />'+
								'<label for="sof' + amnt + '" style="width:37%;">Software</label>'+
							'</div>'+
						'</td>'+
					'</tr>'+
					'<tr class="drow' + amnt + '">'+
					    '<td colspan="6">'+
					        '<textarea placeholder="Describe Device Issue..." style="width:100%;height:16px;vertical-align:top;resize: vertical;padding:2px;margin:0px;" class="required" id="issue' + amnt + '" name="issue' + amnt + '"></textarea>'+
						'</td>'+
					'</tr>';
        form_template.append(html);
        device_amount.val(amnt);
	    $("#damages" + amnt).buttonset();
        $("#centerframe select").chosen();
	}
}

function decreasePage(a) {
    for(a>1;a--;){
        var amnt = device_amount.val();
	    if(amnt <= 1){} else {
	        var amntn = Number(amnt) - Number(1);
	        $(".drow" + amnt).remove();
	        device_amount.val(amntn);
	    }
	}
}

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

function GetCustomers(event, string)
{
    if(string.length > 2 || event.keyCode==13){
        $("#customerid").attr('disabled', true).trigger("liszt:updated");
        $.ajax({
            url: "tickets/ajax/customerid_template.php?string=" + string,
            cache: false
        }).done(function( html ) {
            $("#customerid").attr("disabled", false).html(html).trigger("liszt:updated");
        });
	}
}

function CreateTickets(){
    createt.attr("disabled", true).removeClass("ui-state-hover").addClass("ui-state-disabled");
    var valid = ticket_data.valid();
	ticket_data.find("select").each(function(){
	    if(valid == true){
		    valid = ticket_data.validate().element(this);
		}
	});
	if(valid == true){
	    var amount = device_amount.val();
	    var oopa = $("<p>Are you sure want to Continue?</p>").dialog({
		    title: 'Confirmation',
            close: function(event, ui){$(this).dialog('destroy').remove()},
			modal: true,
			buttons: {
				"Yes": function() {
				    $(this).html("<p><Center>Adding Tickets, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
				    var dataa = ticket_data.serialize();
					dataa += "&ufd-customerid=" + ticket_data.find(".chzn-single span").html();
		            $.ajax({
		                type: "POST",
                        url: "tickets/ajax/ticket_template.php",
			            data: dataa,
                        cache: false
                    }).done(function( html ) {
					    $("#printdiv").html(html);
						oopa.dialog('destroy').remove();
						ChangeCenter('tickets/add.php?v=<?php echo $version; ?>','');
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
	    createt.attr("disabled", false).removeClass("ui-state-hover").removeClass("ui-state-disabled");
		ticket_data.validate().form();
		ticket_data.find('select').each(function (i){
		    if($(this).val() == ""){
			    var thisid = $(this).attr("id");
			    $("#" + thisid + "_chzn a").addClass("error");
			}
		});
		form_template.find("label").addClass("ignore");
	}
}

function RemoveError(Element){
    var thisid = $(Element).attr("id");
	$("#" + thisid + "_chzn a").removeClass("error");
}

$(function() {
	createt.button();
	$("#centerframe select").chosen();
	$("#addmult").button({icons: {primary: "ui-icon-plus"},text: false}).css("height", "22px");
	$("#addmult .ui-button-text").css("line-height", "5px");
		
	$("#remmult").button({icons: {primary: "ui-icon-minus"},text: false}).css("height", "22px");
	$("#remmult .ui-button-text").css("line-height", "5px");
});
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 style="text-align:left;"><label style="margin-left:25px;">Tickets - Corporate Ticket</label></h3>
		<div style="width:98%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
                <form id="ticket_data" style="display: block;" method="get">
				    <input type="text" id="custsearch" class="ignore" title="Type a name or phone number to search a customer by and than press enter to search it." placeholder="Customer Name..." onKeyUp="GetCustomers(event, $(this).val())" <?php echo $method;?>>
					<select id="customerid" name="customerid" class="required" onChange='RemoveError($(this))' data-placeholder="Customer..." style="width:400px;text-align:left;" DISABLED>
					    <?php echo $type;?>
					</select>
					<img id="searching-customer" src="../core/images/searchcustomer.gif" style="display:none;vertical-align:bottom;">
					<br/>
                        <table id="form_template">
                        </table>
					<input type="hidden" id="device_amount" name="device_amount" value="0">
				</form>
				<button type="button" id="remmult" onClick="decreasePage($('#Runamnt').val() - 0);" title="Decrease">-</button><input type="text" style="width:30px;text-align:center;" maxlength="2" id='Runamnt' value="1" title="Increase & Decrease Amount"><button type="button" id="addmult" onClick="increasePage($('#Runamnt').val() - 0);" title="Increase">+</button>&nbsp;<button id="createt" onClick="CreateTickets()">Create Ticket/s</button>
			</div>
        </div>
    </div>
</div>
<script type="text/Javascript">increasePage(1);</script>