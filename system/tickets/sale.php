<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);
?>
<script>
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

function PassSale(){
    var ticket_data = $("#ticket_data");
    var valid = ticket_data.valid();
	ticket_data.find("select").each(function(){
	    if(valid == true){
		    valid = ticket_data.validate().element(this);
		}
	});
	if(valid == true){
        var oopa = $("<p>Are you sure you want to submit this Checkout?</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Sending Checkout, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        var $tick = ticket_data;
				var $cus = $tick.find("#customerid").val();
	            var $pm1 = $tick.find("#payselect1").val();
				var $pm2 = $tick.find("#payselect2").val();
				var $pm1cost = $tick.find("#pm1cost").val();
				var $pm2cost = $tick.find("#pm2cost").val();
				var $totalcost = $tick.find("#fp").html();
				var $totaltax = $tick.find("#fptax").html();
				var discount = $tick.find("#price-discount").val();
	            var $these = "";
	            var $form = "cus=" + $cus + "&pm1=" + $pm1 + "&pm2=" + $pm2 + "&pm1cost=" + $pm1cost + "&pm2cost=" + $pm2cost + "&totalcost=" + $totalcost + "&totaltax=" + $totaltax + "&discount=" + discount;
	            $tick.find("#estimation div").each(function(){
				    var brl = $(this).find(".bname").html();
					if(brl == ""){ brl = $(this).find(".bname").val(); }
	                $these += "|" + $(this).attr("id") + "/" + brl
	            });
	            $form += "&items=" + $these;
	            $.ajax({
		            type: "POST",
                    url: "tickets/ajax/sale_template.php",
		            data: $form,
                    cache: false
                }).done(function( html ) {
				    $("#printdiv").html(html);
					oopa.dialog('destroy').remove();
					window.print();
				});
			},
		    "No": function() {
				$(this).dialog('destroy').remove();
			}
		}
	    });
	} else {
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

$(function() {
	$("#centerframe select").chosen();
	$("#centerframe button").button();
	$("#ticket_data #estimation").droppable({
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        accept: ".draggableaccessories",
        drop: function( event, ui ) {
            $(this).find( ".placeholder" ).remove();
			$("#ticket_data #estimation").append(''+
			    '<div id="' + ui.draggable.attr("id") + '" data-price="" style="overflow:hidden;">' +
		            '<img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveAccessSales($(this), 0, <?php echo $user['store_info']['s_taxrate']; ?>)" />' +
		            '<font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">' + ui.draggable.find(".name").html() + '</font>' +
		            '<input class="bname pprice" data-type="as" value="0.00" style="width: 20%;font-size: 11px;text-align: right;height: 13px;padding-right: 1px;" onKeyUp="if(event.keyCode==13){FormulateCheckout($(' + "'" + '#ticket_data #estimation' + "'" + '), $(' + "'" + '#ticket_data' + "'" + '), <?php echo $user['store_info']['s_taxrate']; ?>, 0);}">' +
	            '</div>' +
			'');
		    FormulateCheckout($("#ticket_data #estimation"), $("#ticket_data"), <?php echo $user['store_info']['s_taxrate']; ?>, '0000000000');
        }
    });
	$("#inventory-list").accordion( "option", "active", 4 ).scrollTop(0);
});
</script>
<div class="centerfloat" style="width:100%;height:100%;overflow-x:hidden;overflow-y:auto;">
    <div style="width:100%; height:100%;overflow: auto;">
	    <h3 class="block-banner">Tickets - Sales Ticket</h3>
		<div style="width:98%;background-color:lightgrey;margin-bottom:14px;" class="final-container">
		    <div style="width: 98%; height: 100%;padding-top:10px;padding-bottom:10px;">
                <form id="ticket_data" style="display: block;" method="get">
				    <input type="text" id="custsearch" class="ignore" title="Type a name or phone number to search a customer by and than press enter to search it." placeholder="Customer Name..." onKeyUp="GetCustomers(event, $(this).val())">
					<select id="customerid" name="customerid" class="required" onChange='RemoveError($(this))' data-placeholder="Customer..." style="width:400px;text-align:left;" DISABLED>
					    <option value="">Customer...</option>
					</select><br/><br/>
					<div style="display:inline-block;width:49%;float:left;">
		                <input type="text" onKeyUp="SplitPrice('pm1cost','pm2cost', '');" id="pm1cost" placeholder="Payment Method 1 Charge..." style="width:98%;"><br/>
					    <select style="width:99%;" id="payselect1" class="required">
			                <option value="">Payment Method 1..</option>
			                <option value="Cash">Cash</option>
			                <option value="Check">Check</option>
			                <option value="American Express">American Express</option>
			                <option value="Discover">Discover</option>
			                <option value="MasterCard">MasterCard</option>
			                <option value="Visa">Visa</option>
			                <option value="Debit Card">Debit Card</option>
			            </select><br/>
			            <input type="text" onKeyUp="SplitPrice('pm2cost','pm1cost', '');" id="pm2cost" placeholder="Payment Method 2 Charge..." style="width:98%;" disabled="disabled"><br/>
			            <select style="width:99%;" id="payselect2" onChange="ps2($(this).val(), '');" class="required">
				            <option value="None">None</option>
			                <option value="Cash">Cash</option>
			                <option value="Check">Check</option>
			                <option value="American Express">American Express</option>
			                <option value="Discover">Discover</option>
			                <option value="MasterCard">MasterCard</option>
			                <option value="Visa">Visa</option>
			                <option value="Debit Card">Debit Card</option>
			            </select><br/>
					</div>
					<div style="display:inline-block;width:49%;">
			            <div style="width: 98%;height: 84px;text-align: center;padding-top: 5px;font-size: 12px;" class="aname">
			                final Cost:<br/>
				            <span id="fp" style="font-size:39px;">NaN</span><br/>
				            Tax: <span id="fptax">0.00</span>
			            </div><br/>
					</div><br/><br/>
			        <input type="text" id="price-discount" style="width:100%;" placeholder="Scan Discount Here..."><br/><br/>
					<div class="txt_dsb" style="width:100%;height:240px;margin:0px !important;text-align:left;" id="estimator">
                        <h4 class="ui-widget-header" style="margin:0px;border: 0px;border-top-right-radius: 3px;border-top-left-radius: 3px;">Refurbs / Accessories</h4>
                        <div id="estimation" style="padding:10px;height:167px;border-bottom: 1px solid #ACB1B7;"></div>
			            <input type="text" style="width:48%;float:left;margin:3px;" placeholder="Scan Ticket to Add it to Checkout..." id="checkoutat" >
			            <input type="text" style="width:49%;float:right;margin:3px;" placeholder="Scan Refurb ID to Add to Checkout..." id="checkoutri" onKeyUp="if(event.keyCode==13){ScannedRefurb($(this), '', <?php echo $user['store_info']['s_taxrate']; ?>)}">
                    </div><br/>
	                <button type="button" class="continue" onClick="PassSale()" style="width:100%;" DISABLED>Submit Checkout</button>
				</form>
			</div>
        </div>
    </div>
</div>