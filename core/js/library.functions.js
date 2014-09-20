/*! Initilization */
var timerz,$starttime,$endtime,latency,bodyLayout,westLayout,mpoints = [],mpoints_max = 10;
var range_map = $.range_map({
    '0:200'   : 'green',
    '201:300' : 'yellow',
    '301:'    : 'red'
})

/*! Main Initilization */
$(document).ready(function () {
    bodyLayout = $("body").layout({ 
		applyDefaultStyles: true,
		resizable: false,
		spacing_closed: 4,
		spacing_open: 4,
		west__size: 249,
		south__size: 30,
		south__onopen: function (){ CheckMessages(5000); },
		east__size: 249,
		north__size: "98%",
		north__initClosed: true,
	});	
		
	westLayout = $("div.ui-layout-west").layout({
		minSize:25,
		north__paneSelector:".inner-west-north",
		north__spacing_closed: 4,
		north__spacing_open: 4,
		north__resizable: false,
		center__paneSelector:".inner-west-center",
		south__paneSelector:".inner-west-south",
		south__size: 40,
		south__spacing_closed: 4,
		south__spacing_open: 4,
		south__resizable: false,
	});
		
    $("#manufacturer-list").listnav({showCounts: false});
	$( document ).tooltip();
});

/*! West Functions */
function ChangeCenter(strURL,Cng){
    if(!Cng == ''){
        $("blockquote .item").removeClass("selected");
        $("#" + Cng).addClass("selected");
	}
	$.ajaxSetup({cache: true});
	$.ajax({
	    url: strURL,
	    cache: true
	}).done(function( html ) {
	    $("#center-content").html(html);
	});
}
	
function OpenSideBar(meval,blockval){
	var classname = $("#" + meval + "-but").attr("class");
	if(classname == "close"){
	    $("#" + meval + " .close").removeClass("close").addClass("open");
	    $("#" + blockval).slideDown("fast");
	} else {
		$("#" + meval + " .open").removeClass("open").addClass("close");
	    $("#" + blockval).slideUp("fast");
	}
}

function SearchTicket(event){
    var $code = $("#barcode_search").val();
    if (event.keyCode==13 && $code.length > 0)
    {
		$(".ticketsearch" + $code).remove();
	    var newDiv = $(document.createElement('div'));
        $(newDiv).html("<p><Center>Searching for Ticket, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		$(newDiv).dialog({
		    title: 'Ticket Search...',
		    close: function(event, ui){$(this).dialog('destroy').remove()},
			dialogClass: "ticketsearch" + $code,
			resizable: false,
			height: 500,
			width: 528
		}).dialogExtend({
		    "close" : true,
            "maximize" : false,
            "minimize" : true,
		});
		if($code.length > 10){var $setting = 1;} else {if(isNaN($code)){var $setting = 1;} else {var $setting = 0;}}
		$.ajax({
		    url: "ajax/tid.php?setting=" + $setting + "&code=" + $code,
		    cache: false,
		}).done(function(html){
			if(html == "nan"){
				$.jGrowl("<label style=position:relative;>No ticket was found searching ["+$code+"]</label>", {
					header: "System Message",
				});
				$(newDiv).remove();
		    } else {
				$code = html;
				if($(document).find(".ticket" + $code).length){$(document).find(".ticket" + $code).find(".ui-dialog-titlebar-close").click()}
				$(newDiv).dialog("option", "dialogClass", "ticket" + $code);
			    $.ajax({
			        url: "ajax/ticket_status.php?code=" + $code,
		            cache: false
		        }).done(function(html){
			        $(newDiv).html(html);
					$(newDiv).find("button").button().css({"width": "100%"});
				    $(".ticket" + $code).find(".ui-dialog-title").html("Ticket #" + $code);
				    $(".forget").editable("ajax/notes/save_note.php", {cancel    : "Cancel",submit : "Save"});
		        });
		    }
		});
	}
}

function AddCustomer(){
	$.ajax({
	    url: "ajax/customer/template.php",
	    cache: true
	}).done(function( html ) {
        var newDiv = $(document.createElement('div'));
        $(newDiv).html(html);
        $(newDiv).dialog({
		    title: 'Add Customer',
            close: function(event, ui){$(this).dialog('destroy').remove()},
			dialogClass: "addcustomer",
			width: 350,
			resizable: false,
			show: "drop",
			buttons: {
				"Add Customer": function() {
				    $(this).parent().children(".ui-dialog-buttonpane").children().children("button").attr("disabled", true).removeClass("ui-state-hover").addClass("ui-state-disabled");
					var valid = $(this).children().children("#createcustomernow").valid();
					if(valid == true){
					    var contain = $(this).children().children("#createcustomernow");
						var $Fname = contain.children("#Fname").val();
						var $Lname = contain.children("#Lname").val();
						var $zip = contain.children("#zip").val();
						var $market = contain.children("#market").val();
						var $market_location = contain.children("#market_location").val();
						var $corpacc = contain.children("#corpacc").val();
						var $emaila = contain.children("#emaila").val();
						var $phone = contain.children("#phone").val();
						$.ajax({
                            url: "ajax/customer/add.php",
							data: { Fn: $Fname, Ln: $Lname, Z: $zip, Ma: $market, MaLo: $market_location, Ca: $corpacc, Em: $emaila, Ph: $phone },
                            cache: false
                        }).done(function( html ) {
						    $.jGrowl("Customer [" + $Fname + " " + $Lname + "] Added", {header: "System Message"});
                        });
						$(this).dialog('destroy').remove();
					} else {
					    $(this).children().children("#createcustomernow").validate().form()
						$(this).parent().children(".ui-dialog-buttonpane").children().children("button").attr("disabled", false).removeClass("ui-state-hover").removeClass("ui-state-disabled");
					}
				},
				Cancel: function() {
					$(this).dialog('destroy').remove();
				}
			}
		}).dialogExtend({
		    "close" : true,
            "maximize" : false,
            "minimize" : true,
		});
	});
}

/*! Ticket Functions */
function CheckSendTicket(tid, event){
    if(event.keyCode == 13){ AddTicketNote(tid); }
}

function AddTicketNote(tid){
    $text = $(".ticket" + tid).find("#addnotetext").val();
	$box = $(".ticket" + tid).find("#notebox");
	if(!$text == ""){
	    $(".ticket" + tid).find("#addnotetext").val("");
	    $.ajax({
		    url: "ajax/notes/ticketnote.php?ticket=" + tid + "&note=" + $text,
			cache: false
		}).done(function(html){
		    $box.html(html);
		});
	} else {
	    $(".ticket" + tid).find("#addnotetext").focus();
	}
}

function FormulateCosts($element, $price){
	$element.children().tsort({ attr: 'data-price', order: 'desc' });
	$element.children("div:first").find(".ppricecut").removeClass("ppricecut").addClass("pprice").html(function(){
	    return $element.children("div:first").attr("data-price");
	});
	$element.children("div:not(:first)").find(".pprice").removeClass("pprice").addClass("ppricecut").html(function( index, oldhtml ) {
		if(!isNaN(oldhtml)){return (oldhtml / 2).toFixed(2);} else {return oldhtml;}
	});
	var sum = 0;
	$element.find(".pprice, .ppricecut").each(function(){
		sum += Number($(this).html());
	});
	if(!isNaN(sum) && !sum == "0"){
	    $price.find(".tc").html(sum.toFixed(2));
		$price.find(".continue").button("enable");
	} else {
	    $price.find(".tc").html(sum);
		$price.find(".continue").button("disable");
	}
}

function Estimate(tid, mid, type, release){
    $ticket = $(".ticket" + tid);
	$ticket.find('.ui-dialog-content').html("<p><Center>Loading Estimate Panel, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
    $.ajax({
	    url: "ajax/estimate/estimate.php?ticket=" + tid + "&model=" + mid,
		cache: false
	}).done(function(html){
	    $ticket.find('.ui-dialog-content').html(html);
		$ticket.find("button").button().css({"width": "494px"});
		$ticket.find("#simcard").buttonset();$ticket.find("#sdcard").buttonset();$ticket.find("#case").buttonset();$ticket.find("#charger").buttonset();$ticket.find("#power").buttonset();$ticket.find("#buttons").buttonset();
		$ticket.find("#inaudio").buttonset();$ticket.find("#exaudio").buttonset();$ticket.find("#touch").buttonset();$ticket.find("#housing").buttonset();$ticket.find("#charging").buttonset();$ticket.find("#service").buttonset();		
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableitem" + mid + ", .draggableservice",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				if($(this).find("#" + ui.draggable.attr("id")).length == 0){
				    $.ajax({
				        url: "ajax/inventory/checkstock.php?part=" + ui.draggable.attr("id") + "&ticket=" + tid + "&type=" + type + "&release=" + release,
					    cache: false
				    }).done(function(html){
				        $(".ticket" + tid + " #estimation").append(html);
					    FormulateCosts($(".ticket" + tid + " #estimation"), $(".ticket" + tid));
				    });
				}
            }
        });
		$("#timert" + tid).stopwatch().stopwatch('start');
	});
}

function RemoveEstimate(element, tid){
    element.parent().remove();
	FormulateCosts($(".ticket" + tid + " #estimation"), $(".ticket" + tid));
}

function SendPrice(ses, user, date, type, ele, ovr){
    var price = $("#send" + ses).val();
	if(!isNaN(price)){
        $.ajax({
	        url: "ajax/inventory/sendprice.php?ses=" + ses + "&user=" + user + "&price=" + price + "&date=" + date + "&type=" + type + "&ovr=" + ovr,
		    cache: false
	    }).done(function(html){
		    ele.parent().parent().parent().remove();
	    });
	} else {
	    alert("That is not a Valid Price");
	}
}

function RecievePrice(price, ses){
    $("#ses" + ses).html(price).removeClass("ppricecut").addClass("pprice");
	$("#ses" + ses).parent().attr("data-price",price);
	FormulateCosts($("#ses" + ses).parent().parent(), $("#ses" + ses).parent().parent().parent().parent());
}

function PassEstimate(tid){
    var oopa = $("<p>Are you sure you want to submit this estimate?</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Sending Estimate, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        var $tick = $(".ticket" + tid);
	            var $cost = $tick.find(".tc").html();
	            var $time = $("#timert" + tid).html();
                var $form = $("#estimatef" + tid).serialize();
	            var $this = "";
	            $form += "&price=" + $cost + "&time=" + $time;
	            $tick.find("#estimation div").each(function(){
	                $this += "|" + $(this).attr("id");
	            });
	            $form += "&items=" + $this + "&ticket=" + tid;
	            $ticket.find('.ui-dialog-content').html("<p><Center>Sending Estimate, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
	            $.ajax({
		            type: "POST",
                    url: "ajax/estimate/sendestimate.php",
		            data: $form,
                    cache: false
                }).done(function( html ) {
	                $ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/ticket_status.php?code=" + tid + "&from=estimate",
		                cache: false
		            }).done(function(html){
			            $ticket.find('.ui-dialog-content').html(html);
			            $ticket.find('.ui-dialog-content').find("button").button().css({"width": "100%"});
			            $(".forget").editable("ajax/notes/save_note.php", {cancel    : "Cancel",submit : "Save"});
						$(oopa).dialog('destroy').remove();
		            });
	            });
			},
		    "No": function() {
				$(this).dialog('destroy').remove();
			}
		}
	});
}

function AcceptEstimate(tid, mid, type, release){
    var oopa = $("<p>Are you sure?<br/> Accepting this will Modify Inventory, Only Accept if the Customer agrees.</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Pushing ticket through, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        $.ajax({
                    url: "ajax/estimate/acceptestimate.php?tid=" + tid,
                    cache: false
                }).done(function( html ) {
			        $ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/ticket_status.php?code=" + tid + "&from=acceptestimate",
		                cache: false
		            }).done(function(html){
			            $ticket.find('.ui-dialog-content').html(html);
			            $ticket.find('.ui-dialog-content').find("button").button().css({"width": "100%"});
			            $(".forget").editable("ajax/notes/save_note.php", {cancel    : "Cancel",submit : "Save"});
						$(oopa).dialog('destroy').remove();
		            });
                });
			},
		    "No": function() {
				$(this).dialog('destroy').remove();
			}
		}
	});
}

function Repair(tid, mid, type, release){
    $ticket = $(".ticket" + tid);
	$ticket.find('.ui-dialog-content').html("<p><Center>Loading Repair Panel, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
    $.ajax({
	    url: "ajax/repair/repair.php?ticket=" + tid + "&model=" + mid + "&type=" + type + "&release=" + release,
		cache: false
	}).done(function(html){
	    $ticket.find('.ui-dialog-content').html(html);
		$ticket.find("button").button().css({"width": "494px"});
		$ticket.find("#simcard").buttonset();$ticket.find("#sdcard").buttonset();$ticket.find("#case").buttonset();$ticket.find("#charger").buttonset();$ticket.find("#power").buttonset();$ticket.find("#buttons").buttonset();
		$ticket.find("#inaudio").buttonset();$ticket.find("#exaudio").buttonset();$ticket.find("#touch").buttonset();$ticket.find("#housing").buttonset();$ticket.find("#charging").buttonset();$ticket.find("#service").buttonset();		
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableitem" + mid + ", .draggableservice",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				if($(this).find("#" + ui.draggable.attr("id")).length == 0){
				    $.ajax({
				        url: "ajax/inventory/checkstock.php?part=" + ui.draggable.attr("id") + "&ticket=" + tid + "&type=" + type + "&release=" + release,
					    cache: false
				    }).done(function(html){
				        $(".ticket" + tid + " #estimation").append(html);
					    FormulateCosts($(".ticket" + tid + " #estimation"), $(".ticket" + tid));
				    });
				}
            }
        });
		$("#timert" + tid).stopwatch().stopwatch('start');
	});
}

function PassRepair(tid){
    var oopa = $("<p>Are you sure you want to submit this Repair?</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Sending Repair, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        var $tick = $(".ticket" + tid);
	            var $cost = $tick.find(".tc").html();
	            var $time = $("#timert" + tid).html();
                var $form = $("#repairf" + tid).serialize();
	            var $this = "";
	            $form += "&price=" + $cost + "&time=" + $time;
	            $tick.find("#estimation div").each(function(){
	                $this += "|" + $(this).attr("id");
	            });
	            $form += "&items=" + $this + "&ticket=" + tid;
	            $ticket.find('.ui-dialog-content').html("<p><Center>Sending Estimate, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
	            $.ajax({
		            type: "POST",
                    url: "ajax/repair/sendrepair.php",
		            data: $form,
                    cache: false
                }).done(function( html ) {
	                $ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/ticket_status.php?code=" + tid + "&from=repair",
		                cache: false
		            }).done(function(html){
			            $ticket.find('.ui-dialog-content').html(html);
			            $ticket.find('.ui-dialog-content').find("button").button().css({"width": "100%"});
			            $(".forget").editable("ajax/notes/save_note.php", {cancel    : "Cancel",submit : "Save"});
						$(oopa).dialog('destroy').remove();
		            });
	            });
			},
		    "No": function() {
				$(this).dialog('destroy').remove();
			}
		}
	});
}

function Checkout(tid, mid, type, release){
    $ticket = $(".ticket" + tid);
	$ticket.find('.ui-dialog-content').html("<p><Center>Loading Checkout Panel, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
    $.ajax({
	    url: "ajax/checkout/checkout.php?ticket=" + tid + "&model=" + mid + "&type=" + type + "&release=" + release,
		cache: false
	}).done(function(html){
	    $ticket.find('.ui-dialog-content').html(html);
		$ticket.find("button").button().css({"width": "494px"});
		var $startingcost = $ticket.find("#fp").html();
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableitem" + mid + ", .draggableservice",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				if($(this).find("#" + ui.draggable.attr("id")).length == 0){
				    $.ajax({
				        url: "ajax/inventory/checkaccessory.php?part=" + ui.draggable.attr("id") + "&ticket=" + tid + "&type=" + type + "&release=" + release,
					    cache: false
				    }).done(function(html){
				        $(".ticket" + tid + " #estimation").append(html);
					    FormulateCosts($(".ticket" + tid + " #estimation"), $(".ticket" + tid));
				    });
				}
            }
        });
		$("#timert" + tid).stopwatch().stopwatch('start');
	});
}

function SplitPrice($Main, $Secondary, $Ticket){
    var $Panel = $(".ticket" + $Ticket);
    var $Price = $Panel.find("#fp").html();
	var $Split1 = $Panel.find("#" + $Main);
	var $Split2 = $Panel.find("#" + $Secondary);
	var $s1 = $Price - $Split1.val();
	if(parseFloat($s1).toFixed(2) < 0 || isNaN($s1)){
	    $Split1.val(parseFloat($Price).toFixed(2));
		$Split2.val("");
	} else {
	    $Split2.val(parseFloat($s1).toFixed(2));
	}
}

/*! Inventory Functions */
function InventoryMan(ID, Manufacturer){
	$("#inventory-list").accordion( "option", "active", 1 ).scrollTop(0);
		
    $("#inventory-model").html("<center>Loading Models...<br/><img src='../core/images/indicator.gif' border='0' /></center>");
	$("#ModMod").html(Manufacturer + " Model's");
	var manuman = Manufacturer.replace("&","@")
	$.ajax({
		url: "ajax/inventory/models.php?m=" + ID + "&manu=" + manuman,
		cache: false
	}).done( function(html){
		$("#inventory-model").html(html);
	});
}
	
function SearchModel(event){
	$("#model_close").removeClass("closed").addClass("opened");
	if (event.keyCode==13)
    {
	    var newval = $('#model_search').val();
	    $("#inventory-list").accordion( "option", "active", 1 ).scrollTop(0);
		$("#inventory-model").html('<center>Searching "' + newval + '"<br/><img src="../core/images/indicator.gif" border="0" /></center>');
		$("#ModMod").html('"' + newval + '" Model Search');
	    $.ajax({
		    url: "ajax/inventory/modelsearch.php?m=" + newval,
			cache: false
		}).done( function(html){
		    $("#inventory-model").html(html);
		});
	}
}
	
function ShowModInfo(model, link){
	var newDiv = $(document.createElement('div'));
    $(newDiv).html('<center>Loading Device Information...<br/><img src="../core/images/indicator.gif" border="0" /></center>');
    $(newDiv).dialog({width: 700,title: model,close: function(event, ui){$(this).dialog('destroy').remove()}});
	$.ajax({
	    url: 'ajax/inventory/modelinfo.php?link=' + link,
	    cache: true
	}).done(function( html ) {
        $(newDiv).html(html);
	});
}
	
function InventoryMod(mid, mname){
    $("#inventory-list").accordion( "option", "active", 2 ).scrollTop(0);
		
	$("#inventory-part").html("<center>Loading Parts...<br/><img src='../core/images/indicator.gif' border='0' /></center>");
    $("#PartParts").html(mname + " Part's");
	$("#AccessAcessories").html(mname + " Accessories");
	var typutyp = mname.replace("&","@")
	$.ajax({
		url: "ajax/inventory/parts.php?m=" + mid + "&mod=" + typutyp,
	    cache: false
	}).done( function(html){
	    html = html.split("<<[SPLIT]>>");
		$("#inventory-part").html(html[0]);
		$("#inventory-accesories").html(html[1]);
	});
}

/*! Chat Functions */
function CheckMessages(){
    clearTimeout(timerz);
	$.ajax({
	    url: "ajax/chat/read_message.php",
        cache: false,
		timeout: 10000,
		dataType: 'json',
        error: function(){
		    timerz = setTimeout(function(){CheckMessages()}, 4000);
        },
		beforeSend: function(){
		    $starttime = +new Date();
		}
	}).done(function( html ) {
	    if(html.NA == "true")
		{
		    window.location.replace("../index.php"); 
		} else {
		    $endtime = +new Date();
		    var $signal = 0;
		    var latency = Math.round(($endtime - $starttime) - 3);
			mpoints.push(latency);
            if (mpoints.length > mpoints_max){mpoints.splice(0,1);}
		    $("#systemlatency").sparkline(mpoints, { type: 'bar', width: '60px', tooltipSuffix: 'ms' , colorMap: range_map});
			if(!html.message == ""){
			    if(html.from == "Price Request"){var headerm = html.from;var closer = "";var notation = true;}
				else if(html.from == "Price Response"){var notation = false;} else {var headerm = "Message From " + html.from; var closer = "x";var notation = true;}
			    $.jGrowl("<label style=position:relative;>" + html.message + "</label>", {
    			    sticky: notation, 
				    header: headerm,
					closeTemplate: closer
			    });
				$("#southnotification")[0].play();
			}
		    timerz = setTimeout(function(){CheckMessages()}, 4000);
		}
    });
}

function SendMessage(event){
    if (event.keyCode==13)
    {
	    $("#chatindicator").show();
		var username = "<?php echo $user['username']; ?>";
		var avatar = "";
		var message = $("#chat_message").val();
		var to = $("#message_to").val();
		
		$.ajax({
	    url: "ajax/chat/send_message.php?res=send&from=" + username + "&message=" + message + "&avatar=" + avatar + "&to=" + to,
        cache: true
	    }).done(function( html ) {
		    $("#chat_message").val("");
			$.jGrowl("<label style=position:relative;>" + message + "</label>", {
				header: "Message To " + to,
			});
			CheckMessages();
        });
    }
}