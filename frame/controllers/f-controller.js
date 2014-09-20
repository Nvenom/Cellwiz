/*! Variables ------------------------------------------------------------------- */
var timerz,$starttime,$endtime,latency,bodyLayout,cstable,westLayout,mpoints = [],mpoints_max = 10,purge,ganttTimeout,qas='';
var range_map = $.range_map({
    '0:200'   : 'green',
    '201:300' : 'yellow',
    '301:'    : 'red'
})


/*! Main Initilization ---------------------------------------------------------- */
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
		east__onresize:  $.layout.callbacks.resizePaneAccordions,
		north__onclose: function(){ $("#north-center").html("<div></div>"); },
		north__onopen: function(){ ChangeNorth('north/leaderboards.php','Loading LeaderBoards...'); },
		north__size: "98%",
		north__initClosed: true,
		north__spacing_closed: 10,
		north__spacing_open: 10,
		north__sliderTip: null,
	});	
		
	westLayout = $("div.ui-layout-west").layout({
		minSize:25,
		north__paneSelector:".inner-west-north",
		north__spacing_closed: 4,
		north__spacing_open: 4,
		north__resizable: false,
		north__size: 244,
		center__paneSelector:".inner-west-center",
		south__paneSelector:".inner-west-south",
		south__size: 40,
		south__spacing_closed: 4,
		south__spacing_open: 4,
		south__resizable: false,
	});
	
	northLayout = $("div.ui-layout-north").layout({
		minSize:25,
		west__paneSelector:".inner-north-west",
		west__spacing_closed: 4,
		west__spacing_open: 4,
		west__resizable: false,
		west__size: 248,
		center__paneSelector:".inner-north-center",
	});
		
    $("#manufacturer-list").listnav({showCounts: false});
	$( document ).tooltip();
	
	$("#navhome").button({ icons: {primary: "ui-icon-home"},text: false});
	$("#navmessages").button({ icons: {primary: "ui-icon-comment"},text: false });
	$("#navsettings").button({ icons: {primary: "ui-icon-wrench"},text: false });
	$("#navswitch").button({ icons: {primary: "ui-icon-transferthick-e-w"},text: false });
	$("#navlock").button({ icons: {primary: "ui-icon-locked"},text: false });
	$("#navlogout").button({ icons: {primary: "ui-icon-power"},text: false });
	
	ChangeCenter('home.php?v=0.1.6d');
	
	var delta = 300;
    var lastKeypressTime = 0;
	$(document).keydown(function(event) {
	    if(event.keyCode==17 && qas==''){
            var thisKeypressTime = new Date();
            if (thisKeypressTime - lastKeypressTime <= delta)
            {
                QAS();
                thisKeypressTime = 0;
            }
            lastKeypressTime = thisKeypressTime;
		}
    });
});


/*! General Functions ----------------------------------------------------------- */
function Loading(Text){
    return $("<div><center><font id='ldtext'>"+Text+"</font><br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></div>").dialog({modal: true,resizable: false, draggable: false}).dialogExtend({"close":false,"maximize":false,"minimize":false});
}

function SelectUpdate(URL,Target,Element){
    Target.attr('disabled', true).trigger("liszt:updated");
    $.get(URL, function(data){
        Target.attr("disabled", false).html(data).trigger("liszt:updated");
		var thisid = Element.attr("id");
	    $("#" + thisid + "_chzn a").removeClass("error");
    });
}

function Pulse(Element){
    Element.effect("highlight", {}, 3000);
}

function Logout(){
    window.location.replace("../");
}

function ExcludeRow(ele){
    if(ele.prop('checked')){
	    ele.parent().parent().addClass('excluded');
	} else {
	    ele.parent().parent().removeClass('excluded');
	}
}

function GeneratePO(store,today,sd){
    today = today.split(' ');
	t1 = today[0];
	sd = sd.split(' ');
	t2 = sd[0];
    var tbl = $('#listofinv');
	var pdi = $('#printdiv');
    var string = '<center><h2>'+store+' Purchase Order '+t1+'</h2><br/>Recommendations based on part use between '+t2+' and '+t1+'<br/><br/><table style="width:100%;">'+
	'<thead><th>Device</th><th>Part</th><th>Stock</th><th>Min</th><th>Rec</th><th>Pref</th><th>Price</th></thead><tbody>';
	var last = tbl.find('tr:not(.excluded)').length - 1;
	var recprice = 0,inpprice = 0,multr = 0;
	tbl.find('tr:not(.excluded)').each(function(i){
	    var model = $(this).find('.model').html();
		var part = $(this).find('.part').html();
		var quantity = $(this).find('.quantity').html();
		var minimum = $(this).find('.minimum').html();
		var recommended = $(this).find('.recommended').html();
		var input = $(this).find('.input').find('input').val();
		var price = $(this).find('.price').html();
        		
		if(model != undefined){
		    string += '<tr><td style="border-bottom:1px solid lightGrey;">'+model+'</td><td style="border-bottom:1px solid lightGrey;">'+part+'</td><td style="border-bottom:1px solid lightGrey;">'+quantity+'</td><td style="border-bottom:1px solid lightGrey;">'+minimum+'</td><td style="border-bottom:1px solid lightGrey;">'+recommended+'</td><td style="border-bottom:1px solid lightGrey;">'+input+'</td><td style="border-bottom:1px solid lightGrey;">'+price+'</td></tr>';
		    if(input == ''){
			    multr = price * recommended;
				recprice += multr;
				inpprice += multr;
			} else {
			    multr = price * input;
				inpprice += multr;
				multr = price * recommended;
				recprice += multr;
			}
		}
		if(i == last){
		    string += '<tr><td colspan="2">&nbsp;</td><td colspan="5" style="border-bottom:1px solid lightGrey;">Recommended Price: $'+recprice.toFixed(2)+'</td></tr><tr><td colspan="2">&nbsp;</td><td colspan="5" style="border-bottom:1px solid lightGrey;">Preferred Price: $'+inpprice.toFixed(2)+'</td></tr></tbody></table></center>';
			pdi.html(string);
			window.print();
		}
	});
}

/*! Navigation Functions -------------------------------------------------------- */
function ChangeCenter(strURL,Cng){
    $("blockquote .item").removeClass("selected");
	clearTimeout(ganttTimeout);
    if(!Cng == ''){$("#" + Cng).addClass("selected");}
	$.get(strURL, function(data){$("#center-content").html(data);});
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

function SearchTicket(e,$code){
    if(e.keyCode==13 || e==13){
	    var ts = $.jGrowl("<div id='jgt"+$code+"'><div style='text-align:center;' id='prgn'>Searching For Ticket</div><br/><div id='prgs' class='ui-progressbar'><div class='ui-progressbar-value' style='width:1%;background-color: #003296;'></div></div></div>", {header: $code, sticky: true, speed: 0, open: function(){
		    var $s = ($code.length > 10 || isNaN($code)) ? 0 : 1;
	        $.get('ajax/tid.php?setting='+$s+'&code='+$code, function(data){
			    if(data == 'nan'){
				    $("#jgt"+$code).find('#prgn').html('No Ticket Found');
				    $("#jgt"+$code).parent().parent().fadeOut(1000, function(){
				        $(this).remove();
				    });
			    } else {
			        $("#jgt"+$code).find('#prgn').html('Loading Ticket Info');
			        $("#jgt"+$code).attr('id', 'jgt'+data).find('#prgs').children().animate({width:'75%'}, 120, "linear");
				    LoadTicket(data);
			    }
		    });
		}});
		
	}
}

function LoadTicket(tid){
    if(tid.length <= 10){
	    $('.ticket'+tid).find('.ui-dialog-titlebar-close').click();
	    $.get('ajax/tpanel.php?tid='+tid,function(data){
		    $("#jgt"+tid).find('#prgn').html('Ticket Loaded');
			$('#jgt'+tid).find('#prgs').children().animate({width:'100%'}, 120, "linear", function(){
		        $(this).parent().parent().parent().parent().fadeOut(1000, function(){
			        $(this).remove();
			    });
		    });
			var TPanel = $("<div>"+data+"</div>").dialog({
			    resizable: false,
				width: 528,
				height: 500,
				dialogClass: 'ticket'+tid,
				title: 'Ticket# '+tid,
				close: function(event, ui){$(this).dialog('destroy').remove();},
			}).dialogExtend({"close":true,"maximize":false,"minimize":true});
			TPanel.find("button").button().css('width','100%');
		});
	}
}

function SearchCustomer(e,string){
    if(e.keyCode==13 || e==13){
	    var ts = $.jGrowl("<div id='jgcs'><div style='text-align:center;' id='prgn'>Searching For Customer</div><br/><div id='prgs' class='ui-progressbar'><div class='ui-progressbar-value' style='width:25%;background-color: #009619;'></div></div></div>", {header: string, speed: 0, sticky: true, open: function(){
		    $.get("ajax/cid.php?string=" + string, function(data){
		        var data = data.split("|");
			    if(data[0] == 'NAN'){
				    $('#jgcs').find('#prgn').html('No Customers Found');
				    $('#jgcs').parent().parent().fadeOut(1000, function(){
				        $(this).remove();
				    });
			    } else {
			        $('#jgcs').find('#prgn').html('Loading Customers');
			        $('#jgcs').find('#prgs').children().animate({width:'100%'}, 120, "linear", function(){
		                $(this).parent().parent().parent().parent().fadeOut(1000, function(){
			                $(this).remove();
			            });
		            });
				    if(data[0] == 'SIN'){
				        LoadCustomer(data[1]);
			        } else if(data[0] == 'MUL'){
			            $(data[1]).dialog({
		                    title: 'Customer Search...',
		                    close: function(event, ui){$(this).dialog('destroy').remove();cstable.fnDestroy();},
			                dialogClass: "customersearch",
			                resizable: false,
			                width: 600,
			                height: 630
		                }).dialogExtend({"close":true,"maximize":false,"minimize":true});
				        cstable = $("#custTable").dataTable({
				            "sPaginationType": "full_numbers",
                            "bProcessing": true,
                            "bServerSide": true,
						    "iDeferLoading": data[2],
                            "sAjaxSource": "ajax/customer/cid.php?string=" + string,
					        "sScrollY": "430",
					        "aoColumns": [
					            null,
						        null,
                                { 
						            "sWidth": "50px",
							        "asSorting": [  ]
						        }
                            ]
                        });
				        $("#custTable_filter").find("input").val(string);
				    }
			    }
		    });
		}});
	}
}

function LoadCustomer(cid){
	var ts = $.jGrowl("<div id='jgc"+cid+"'><div style='text-align:center;' id='prgn'>Loading Customer</div><br/><div id='prgs' class='ui-progressbar'><div class='ui-progressbar-value' style='width:1%;background-color: #009619;'></div></div></div>", {header: cid, sticky: true, speed: 0, open: function(){
	    $.get('ajax/customer/cpanel.php?cid='+cid, function(data){
	        $('<div>'+data+'</div>').dialog({
		        title: 'Customer Panel',
		        close: function(event, ui){$(this).dialog('destroy').remove()},
	            dialogClass: "customer" + cid,
	            resizable: false,
		        width: 528,
		        height: 541
	        }).dialogExtend({
		        "close" : true,
                "maximize" : false,
                "minimize" : true,
	        });
		    $('#jgc'+cid).find('#prgn').html('Loading Customers');
		    $('#jgc'+cid).find('#prgs').children().animate({width:'100%'}, 120, "linear", function(){
		        $(this).parent().parent().parent().parent().fadeOut(1000, function(){
			        $(this).remove();
			    });
		    });
		    var CName = $("#cname"+cid).html();
		    $(".customer" + cid).find(".ui-dialog-title").html("Customer: "+CName);
		    $("#customertickets"+cid).dataTable({
			    "sPaginationType": "full_numbers",
			    "iDisplayLength": 5,
			    "sScrollY": "215",
			    "aoColumns": [
				    null,
				    null,
				    null,
                    { 
					    "sWidth": "50px",
					    "asSorting": [  ]
				    }
                ]
            });
	    });
	}});
	
}

function ChangeNorth(url,text){
    $('#north-center').html(''+
	    '<center><span class="block-wrap" style="margin-top:20%;">'+
		    '<span class="block-span" style="background-color:rgba(150, 150, 150, 0.25);"></span>'+
			'<div class="block-div" style="height:200px;"><font class="lb-user"><br/><br/>'+text+'<br/><img src="tickets/image/add_c.gif" border="0"></font></div>'+
		'</span></center>'+
	'');
    $.get(url,function(data){
	    $('#north-center').html(data);
	});
}



/*! New Customer Functions ------------------------------------------------------ */
function AddCustomer(){
	$.get('ajax/customer/template.php',function(html){
        var newDiv = $(document.createElement('div'));
        $(newDiv).html(html);
        $(newDiv).dialog({
		    title: 'Add Customer',
            close: function(event, ui){$(this).dialog('destroy').remove()},
			dialogClass: "addcustomer",
			width: 350,
			resizable: false,
			buttons: {
				"Add Customer": function() {
				    $(this).parent().children(".ui-dialog-buttonpane").children().children("button").attr("disabled", true).removeClass("ui-state-hover").addClass("ui-state-disabled");
					var valid = $(this).children().children("#createcustomernow").valid();
					if(valid == true){
					    var contain = $(this).find("#createcustomernow").serialize();
						var $Fname = $(this).find("#Fname").val();
						var $Lname = $(this).find("#Lname").val();
						$.ajax({
                            url: "ajax/customer/add.php",
							data: contain,
                            cache: false
                        }).done(function( html ) {
						    $.jGrowl("Customer [" + $Fname + " " + $Lname + "] Added", {header: "System Message"});
							if($("#customerid").val() == ''){
							    $("#customerid").attr("disabled", false).html(html).trigger("liszt:updated");
							}
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

function CustomerCheck(element, value){
    $.get('ajax/customer/number.php?val='+value, function(data){
	    html = data.split("|");
		if(html[0] == '0'){
		    element.attr("title",html[1]);
			element.tooltip({ tooltipClass: "error", position: { my: "center bottom", at: "center top" } }).tooltip('open');
		} else {
		    element.removeAttr("title").tooltip( "destroy" );
		}
	});
}

function PullLocation(select, value){
    $.get('tickets/ajax/location_template.php?nmb='+value,function(html){ 
        select.parent().find("#market_location").html(html);
    });
}

function ChangeTitle(element){
    if(element.attr("id") == "Fname"){
        var Fname = element.val();
	    var Lname = element.next().val();
	} else {
        var Lname = element.val();
	    var Fname = element.prev().val();	
	}
    element.parents(".ui-dialog").children(".ui-dialog-titlebar").children("span").text("Add Customer - " + Fname + " " + Lname);
}

function GenerateMethod(element, value){
	element.parent().find(".secinfo").remove();
    if(value == 0){
	    element.after('<div class="secinfo"><br/><label style="font-weight:bold;">Contact Number</label><br/><input type = "text" name="secinfo" id="secinfo" size="13" minlength="10" maxlength = "10" class="required number" placeholder="1234567890" /></div>');
	} else if(value == 1){
	    element.after('<div class="secinfo"><br/><label style="font-weight:bold;">EMAIL Address</label><br/><input name="secinfo" id="secinfo" type="text" placeholder="email@email.com" class="required email"/></div>');
	}
}


/*! Add Ticket Functions -------------------------------------------------------- */
function UnlockForm(){
    $.get('tickets/ajax/unlock_template.php', function(data){
	    $("#printdiv").html(data);
		window.print();
	});
}


/*! Ticket Panel Functions ------------------------------------------------------ */
function AddTicketNote(e, tid){
    if(e.keyCode==13 || e==13){
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
}

function CheckSendCNote(cid, event){
    if(event.keyCode == 13){ AddCustomerNote(cid); }
}

function AddCustomerNote(cid){
    $text = $(".customer" + cid).find("#addnotetext").val();
	$box = $(".customer" + cid).find("#notebox");
	if(!$text == ""){
	    $(".customer" + cid).find("#addnotetext").val("");
	    $.ajax({
		    url: "ajax/notes/customernote.php?cid=" + cid + "&note=" + $text,
			cache: false
		}).done(function(html){
		    $box.html(html);
		});
	} else {
	    $(".customer" + cid).find("#addnotetext").focus();
	}
}

function Walkout(tid){
    var oopa = $("<center>Why is this Customer walking out?<br/><textarea id='reason" + tid + "'></textarea></center>").dialog({
	    title: 'Walkout',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
		    "Submit": function(){
			    $.ajax({
				    url: "ajax/walkout/sendwalkout.php?tid=" + tid + "&note=" + $("#reason" + tid).val(),
					cache: false
				}).done(function(html){
				    $.jGrowl(html);
					$ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/tpanel.php?tid=" + tid + "&from=estimate",
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
	    sum = (sum-0.01).toFixed(2)
	    $price.find(".tc").html(sum);
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
        $ticket.find('.sensclick').click(function() {
            var $chk = $("#" + $(this).attr("for"));
            $chk[0].checked = !$chk[0].checked;
            $chk.button("refresh");
            $chk.change();
            return false;
        });		
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableitem" + mid + ", .draggableservice",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				if($(this).find("#" + ui.draggable.attr("id")).length == 0){
				    $.ajax({
				        url: "ajax/inventory/checkstock.php?part=" + ui.draggable.attr("id") + "&ticket=" + tid + "&type=" + type + "&release=" + release + "&mid=" + mid,
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
    var valid = $("#estimatef" + tid).valid();
	if(valid == true){
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
			            url: "ajax/tpanel.php?tid=" + tid + "&from=estimate",
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
	} else {
	    $("#estimatef" + tid).validate().form();
		$("#estimatef" + tid).find('.error').each(function (i){
		    $(this).parent().addClass("error");
		});
	}
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
			            url: "ajax/tpanel.php?tid=" + tid + "&from=acceptestimate",
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

function PartOnOrder(tid){
    var oopa = $("<p>Are you sure this ticket has a part on order?</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Pushing ticket through, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        $.ajax({
                    url: "ajax/estimate/partonorder.php?tid=" + tid,
                    cache: false
                }).done(function( html ) {
			        $ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/tpanel.php?tid=" + tid + "&from=acceptestimate",
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
		FormulateCosts($(".ticket" + tid + " #estimation"), $(".ticket" + tid));
		$ticket.find("#simcard").buttonset();$ticket.find("#sdcard").buttonset();$ticket.find("#case").buttonset();$ticket.find("#charger").buttonset();$ticket.find("#power").buttonset();$ticket.find("#buttons").buttonset();
		$ticket.find("#inaudio").buttonset();$ticket.find("#exaudio").buttonset();$ticket.find("#touch").buttonset();$ticket.find("#housing").buttonset();$ticket.find("#charging").buttonset();$ticket.find("#service").buttonset();		
		$ticket.find('.sensclick').click(function() {
            var $chk = $("#" + $(this).attr("for"));
            $chk[0].checked = !$chk[0].checked;
            $chk.button("refresh");
            $chk.change();
            return false;
        });
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableitem" + mid + ", .draggableservice",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				if($(this).find("#" + ui.draggable.attr("id")).length == 0){
				    $.ajax({
				        url: "ajax/inventory/checkstock.php?part=" + ui.draggable.attr("id") + "&ticket=" + tid + "&type=" + type + "&release=" + release + "&mid=" + mid,
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
			            url: "ajax/tpanel.php?tid=" + tid + "&from=repair",
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

function Checkout(tid, mid, type, release, taxrate){
    $ticket = $(".ticket" + tid);
	$ticket.find('.ui-dialog-content').html("<p><Center>Loading Checkout Panel, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
    $.ajax({
	    url: "ajax/checkout/checkout.php?ticket=" + tid + "&model=" + mid + "&type=" + type + "&release=" + release + "&taxrate=" + taxrate,
		cache: false
	}).done(function(html){
	    $ticket.find('.ui-dialog-content').html(html);
		$ticket.find("button").button().css({"width": "494px"});
		$ticket.find(".pprice").dblclick(function(){
		    ChangePrice(tid, $(this), taxrate);
		});
		var $startingcost = $ticket.find("#fp").html();
		$(".ticket" + tid + " #estimation").droppable({
            activeClass: "ui-state-default",
            hoverClass: "ui-state-hover",
            accept: ".draggableaccessories",
            drop: function( event, ui ) {
                $(this).find( ".placeholder" ).remove();
				$(".ticket" + tid + " #estimation").append(''+
					'<div id="' + ui.draggable.attr("id") + '" data-price="" style="overflow:hidden;">' +
		                '<img src="../frame/skins/default/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveAccess($(this), '+ "'" + tid + "'" +', '+ taxrate +')" />' +
		                '<font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">' + ui.draggable.find(".name").html() + '</font>' +
		                '<input class="bname pprice" data-type="as" value="0.00" style="width: 20%;font-size: 11px;text-align: right;height: 13px;padding-right: 1px;" onKeyUp="if(event.keyCode==13){FormulateCheckout($(' + "'" + '.ticket' + tid + ' #estimation' + "'" + '), $(' + "'" + '.ticket' + tid + '' + "'" + '), '+ taxrate +', ' + "'" + tid + "'" + ');}">' +
	                '</div>' +
			    '');
				FormulateCheckout($(".ticket" + tid + " #estimation"), $(".ticket" + tid), taxrate, tid);
            }
        });
		$("#timert" + tid).stopwatch().stopwatch('start');
	});
}

function SplitPrice($Main, $Secondary, $Ticket){
    if($Ticket == ''){
	    var $Panel = $("#ticket_data");
	} else {
        var $Panel = $(".ticket" + $Ticket);
	}
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

function RemoveAccess(element, tid, tax){
    element.parent().remove();
	FormulateCheckout($(".ticket" + tid + " #estimation"), $(".ticket" + tid), tax);
}

function RemoveAccessSales(element, tid, tax){
    element.parent().remove();
	FormulateCheckout($("#ticket_data #estimation"), $("#ticket_data"), tax, '0000000000');
}

function ValidateCard(ele,tid,cid,e){
    if(e.keyCode == 13){
        var card = ele.val();
        var oopa = $("<center style='padding:0px !important;'>Validating...</center>").dialog({title: 'My-CPR Card Validator',dialogClass:'cardvalidator',width:300,height:140,close: function(event, ui){$(this).dialog('destroy').remove()},modal: true});
	    $.get("ajax/customer/card.php",{cid: cid, card: card, tid: tid},function(html){
	        oopa.html(html);
	    });
	}
}

function AttachCard(ele,cid,card,tid,taxrate){
    var email = ele.prev().prev().val();
	if(email.length < 6){
	    ele.prev().prev().addClass('error');
	} else {
	    ele.prev().prev().removeClass('error');
		$.get("ajax/customer/attachcard.php",{email: email, cid: cid, card: card},function(amnt){
		    AddDiscount('5','Membership Card','.ticket'+tid,taxrate,tid);
			ele.parent().parent().find('.ui-dialog-titlebar-close').click();
		});
	}
}

function AddDiscount(p,t,e,taxrate,tid){
    var box = $(e).find('#estimation');
    if(box.find('#discount').html() == undefined){
	    box.append('<div id="discount" data-price="'+p+'" style="cursor:pointer;">'+
		    '<font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'+t+'</font>'+
		    '<font class="bname">'+p+'% Discount</font>'+
	        '<br/>'+
	    '</div>');
	}
	FormulateCheckout($(".ticket" + tid + " #estimation"), $(".ticket" + tid), taxrate, tid);
}

function ScannedTicket(ele,tid,taxrate){
    var value = ele.val();
    if($(".ticket" + tid + " #estimation").find("#ti-" + value).length == 0){
        if(!isNaN(value) && value.length<=10){
	        $.ajax({
	            url: "ajax/checkout/scannedticket.php?tid=" + value + "&main=" + tid,
		        cache: false
	        }).done(function(html){
		        if(html.length>50){
		            $(".ticket" + tid + " #estimation").append(html);
					$(".ticket" + tid).find(".ppricecut, .pprice").unbind('dblclick').dblclick(function(){
		                ChangePrice(tid, $(this), taxrate);
		            });
					ele.val("");
			    } else {
			        $.jGrowl(html, {
					    header: "System Message",
				    });
			    }
	        });
	    }
	}
}

function ScannedRefurb(ele,tid,taxrate){
    var value = ele.val();
	if(tid == ''){
	    var conta = $("#ticket_data #estimation");
		var conte = $("#ticket_data");
	} else {
	    var conta = $(".ticket" + tid + " #estimation");
		var conte = $(".ticket" + tid);
	}
    if(conta.find("#de-" + value).length == 0){
        if(!isNaN(value) && value.length<=10){
	        $.ajax({
	            url: "ajax/checkout/scannedrefurb.php?device=" + value + "&main=" + tid,
		        cache: false
	        }).done(function(html){
		        if(html.length>50){
		            conta.append(html);
					FormulateCheckout(conta, conte, taxrate, tid);
					conte.find(".ppricecut, .pprice").unbind('dblclick').dblclick(function(){
		                ChangePrice(tid, $(this), taxrate);
		            });
					ele.val("");
			    } else {
			        $.jGrowl(html, {
					    header: "System Message",
				    });
			    }
	        });
	    }
	}
}

function UnlockPrice(ele, tid, taxrate){
    if(tid == ''){
	    var conta = $("#ticket_data #estimation");
		var conte = $("#ticket_data");
	} else {
	    var conta = $(".ticket" + tid + " #estimation");
		var conte = $(".ticket" + tid);
	}

    var dataprice = ele.parent().attr("data-price");
	var dataulprice = ele.parent().attr("data-ulprice");
	
	ele.parent().attr("data-price", dataulprice);
	ele.parent().attr("data-ulprice", dataprice);
	
	ele.parent().find(".pprice").html(dataulprice);
	
	FormulateCheckout(conta, conte, taxrate, tid);
}

function ChangePrice(tid, fp, taxrate){
    var value = fp.html();
    var oopa = $("<center><input type='password' id='pinapp" + tid + "' placeholder='PIN..' style='width:100px;'><br/><input type='text' id='pinprice" + tid + "' value='" + value + "' style='width:100px;'></center>").dialog({
	    title: "Change Price",
		close: function(event, ui){$(this).dialog('destroy').remove()},
		modal: true,
		buttons: {
			"Change": function(){
			    if($("#pinapp" + tid).val() == '80085'){
			        fp.html($("#pinprice" + tid).val());
					FormulateCheckout($(".ticket" + tid + " #estimation"), $(".ticket" + tid), taxrate, tid);
					oopa.remove();
			    }
			}
		}
	});
}

function FormulateCheckout($element, $price, $taxrate, tid){
	$element.children().tsort({ attr: 'data-price', order: 'desc' });
	$element.children("div:first").find(".ppricecut").removeClass("ppricecut").addClass("pprice").html(function(){
	    return $element.children("div:first").attr("data-price");
	});
	$element.children("div:not(:first)").find(".pprice").removeClass("pprice").addClass("ppricecut").html(function( index, oldhtml ) {
		if(!isNaN(oldhtml)){return (oldhtml * 0.85).toFixed(2);} else {return oldhtml;}
	});
	var sum = 0,tax = 0,ntax = 0,max = 100,discount = 0;
	$element.find("#discount").each(function(){
	    discount += $(this).attr("data-price");
	});
	var discount = max - discount;
	var discount = Number('0.'+discount);
	if(discount == '0.100'){discount = 1;}
	$element.find(".pprice, .ppricecut").each(function(){
	    if($(this).attr("data-type") == "as"){
		    if($(this).val() == ''){
			    var value = Number($(this).html() * discount);
			} else {
			    var value = Number($(this).val() * discount);
			}
            if(value <= 0.00){sum = "NaN";} else {
		        sum += Number(value);
			    ntax = Number((value / 100) * $taxrate);
				tax += ntax;
				sum += ntax;
			}
		} else {
		    if(sum == "NaN"){} else {
		        sum += Number($(this).html() * discount);
			}
		}
	});
	if(!isNaN(sum) && !sum == "0"){
	    sum = (sum).toFixed(2);
		tax = (tax).toFixed(2);
	    $price.find("#fp").html(sum);
		$price.find("#fptax").html(tax);
		$price.find(".continue").button("enable");
	} else {
	    $price.find("#fp").html(sum);
		$price.find(".continue").button("disable");
	}
    var PR = $price.find("#fp").html();
	$price.find("#pm1cost").val(PR);
	$price.find("#pm2cost").val("");
}

function PassCheckout(tid){
    var valid = $("#checkoutf" + tid).valid();
	if(valid == true){
        var oopa = $("<p>Are you sure you want to submit this Checkout?</p>").dialog({
	    title: 'Confirmation',
        close: function(event, ui){$(this).dialog('destroy').remove()},
	    modal: true,
		buttons: {
			"Yes": function() {
			    $(this).html("<p><Center>Sending Checkout, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		        var $tick = $(".ticket" + tid);
	            var $pm1 = $tick.find("#payselect1").val();
				var $pm2 = $tick.find("#payselect2").val();
				var $pm1cost = $tick.find("#pm1cost").val();
				var $pm2cost = $tick.find("#pm2cost").val();
				var $totalcost = $ticket.find("#fp").html();
				var $totaltax = $ticket.find("#fptax").html();
				var $checkouttime = $ticket.find("#timert" + tid).html();
				var discount = $ticket.find("#price-discount").val();
	            var $this = "";
	            var $form = "pm1=" + $pm1 + "&pm2=" + $pm2 + "&pm1cost=" + $pm1cost + "&pm2cost=" + $pm2cost + "&totalcost=" + $totalcost + "&totaltax=" + $totaltax + "&discount=" + discount + "&checkouttime=" + $checkouttime;
	            $tick.find("#estimation div").each(function(){
				    var brl = $(this).find(".bname").html();
					if(brl == ""){ brl = $(this).find(".bname").val(); }
	                $this += "|" + $(this).attr("id") + "/" + brl
	            });
	            $form += "&items=" + $this + "&ticket=" + tid;
	            $ticket.find('.ui-dialog-content').html("<p><Center>Sending Estimate, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
	            $.ajax({
		            type: "POST",
                    url: "ajax/checkout/sendcheckout.php",
		            data: $form,
                    cache: false
                }).done(function( html ) {
				    $("#printdiv").html(html);
	                $ticket = $(".ticket" + tid);
		            $ticket.find('.ui-dialog-content').html("<p><Center>Returning to Ticket Status...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
		            $.ajax({
			            url: "ajax/tpanel.php?tid=" + tid + "&from=repair",
		                cache: false
		            }).done(function(html){
			            $ticket.find('.ui-dialog-content').html(html);
			            $ticket.find('.ui-dialog-content').find("button").button().css({"width": "100%"});
			            $(".forget").editable("ajax/notes/save_note.php", {cancel    : "Cancel",submit : "Save"});
						$(oopa).dialog('destroy').remove();
						window.print();
		            });
	            });
			},
		    "No": function() {
				$(this).dialog('destroy').remove();
			}
		}
	    });
	} else {
	    $("#checkoutf" + tid).validate().form();
	}
}

function ps2(value, tid){
    if(tid == ''){
	    var $tick = $("#ticket_data");
	} else {
        var $tick = $(".ticket" + tid);
	}
	if(value == "None"){
	    $tick.find("#pm2cost").attr("disabled", true);
    } else {
	    $tick.find("#pm2cost").attr("disabled", false);
	}
}

function PrintReceipt(tid){
    var ele = Loading("Generating Receipt...");
	$.get('ajax/checkout/receipt.php?tid='+tid, function(data){
	    $("#printdiv").html(data);
		ele.dialog('destroy').remove();
		window.print();
	});
}

function ChangeDevice(tid){
    $.get('ajax/inventory/changedevice.php?tid='+tid, function(data){
	    var ele = $(data).dialog({
		    title:'Change Device TID:('+tid+')',
			modal: true,
			resizable: false,
			draggable: false,
			close: function(event, ui){$(this).dialog('destroy').remove()},
			buttons: {
			    "Submit Changed Device": function() {
				    var manu = $("#changemanu" + tid);
					var mode = $("#changemode" + tid);
					if(manu.valid() == true && mode.valid() == true){
					    $.get('ajax/inventory/changedevice.php?tid='+tid+'&sta=S&manu='+manu.val()+'&mode='+mode.val(), function(data){
						    ele.remove();
							LoadTicket(tid);
						});
					} else {
					    if(manu == false){
						    $("#changemanu" + tid + "_chzn").children("a").addClass('error');
						}
						if(mode == false){
						    $("#changemode" + tid + "_chzn").children("a").addClass('error');
						}
					}
				}
			}
		}).dialogExtend({"close":true,"maximize":false,"minimize":false});
		ele.find("select").chosen();
	});
}

function ReopenTicket(tid){
	var ele = $("<div><center>Are you sure you want to Re-Open this ticket?</center></div>").dialog({
		title:'Change Device TID:('+tid+')',
	    modal: true,
	    resizable: false,
		close: function(event, ui){$(this).dialog('destroy').remove()},
		buttons: {
			"Yes": function() {
			    $.get('ajax/reopen.php?tid='+tid, function(){
				    ele.remove();
					LoadTicket(tid);
				});
			},
			"No": function() {
			    ele.remove();
			}
		}
	}).dialogExtend({"close":true,"maximize":false,"minimize":false});
}

function PrintCheckIn(tid){
	var ele = $("<div><center>Are you sure you want to print this tickets Check in form?</center></div>").dialog({
		title:'Print Check In TID:('+tid+')',
	    modal: true,
	    resizable: false,
		close: function(event, ui){$(this).dialog('destroy').remove()},
		buttons: {
			"Yes": function() {
			    $.get('tickets/ajax/checkin_template.php?tid='+tid, function(data){
				    $("#printdiv").html(data);
				    ele.remove();
					window.print();
				});
			},
			"No": function() {
			    ele.remove();
			}
		}
	}).dialogExtend({"close":true,"maximize":false,"minimize":false});
}


/*! Inventory Functions --------------------------------------------------------- */
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
    $(newDiv).dialog({width: 700,title: model,dialogClass:"noprint",close: function(event, ui){$(this).dialog('destroy').remove()}});
	$.ajax({
	    url: 'ajax/inventory/modelinfo.php?link=' + link,
	    cache: true
	}).done(function( html ) {
        $(newDiv).html(html);
	});
}
	
function InventoryMod(mid, mname, event){
    if(event.shiftKey==true){
	    var oopa = $("<center style='padding:0px !important;'>Loading...</center>").dialog({title: mname+' Part Manager',dialogClass:"pm" + mid,width:700,height:600,close: function(event, ui){$(this).dialog('destroy').remove()},modal: true});
	    $.get("ajax/inventory/partlist.php",{m:mid},function(html){
		    oopa.html(html);
			var oopaTable = oopa.find('table').dataTable({
                "sScrollY": 475,
		        "sPaginationType": "full_numbers",
		        "iDisplayLength": 10,
				"aoColumns": [
				    null,
				    { "asSorting": [ "none" ] },
					{ "asSorting": [ "none" ] },
					{ "asSorting": [ "none" ] },
					{ "asSorting": [ "none" ] }
                ]
	        });
			oopa.find('.dataTables_wrapper').css('margin','2px 0px');
		});
	} else {
        $("#inventory-list").accordion( "option", "active", 2 ).scrollTop(0);
		
	    $("#inventory-part").html("<center>Loading Parts...<br/><img src='../core/images/indicator.gif' border='0' /></center>");
        $("#PartParts").html(mname + " Part's");
	    var typutyp = mname.replace("&","@");
	    $.ajax({
		    url: "ajax/inventory/parts.php?m=" + mid + "&mod=" + typutyp,
	        cache: false
	    }).done( function(html){
		    $("#inventory-part").html(html);
	    });
	}
}

function EditPartRow(ele, pid){
    var row = ele.parent().parent();
	var dat = row.find('input').serialize();
	dat += '&pid='+pid;
	row.find('input').val('').attr('placeholder','Sending');
	$.get("ajax/inventory/rowupdate.php",dat,function(resp){
	    var ar = resp.split("|");
		row.find('.quan').attr('placeholder', ar[0]);
		row.find('.pric').attr('placeholder', ar[1]);
		row.find('.mini').attr('placeholder', ar[2]);
	})
}

function InventoryEdit(pid, part){
	$.ajax({
	    url: "ajax/inventory/edit.php?pid=" + pid,
		cache: false
	}).done(function(html){
        var oopa = $(html).dialog({title: part+' Stock Status',dialogClass:"edit" + pid,width:600,close: function(event, ui){$(this).dialog('destroy').remove()},modal: true});
	});
}

function RefreshInventoryEdit(pid){
    var con = $('.edit'+pid);
	var box = con.find('center');
	box.html('Refreshing...');
	$.get("ajax/inventory/editrefresh.php?pid=" + pid,function(html){box.html(html);});
}

function AddInvEnter(ele,e){
    if(e.keyCode==13){
	    ele.next().next().click();
	}
}

function UpdateInventory(pid, cv, ty, ele){
    var target = ele.prev().prev();
	var val = target.val();
    $.get("ajax/inventory/update.php",{p: pid, v: val, t: ty},function(resp){
		RefreshInventoryEdit(pid);
	});
}

function AddQuantity(value, pid){
    value = encodeURIComponent(value);
    $.ajax({
	    url: "ajax/inventory/quantity.php?value=" + value + "&pid=" + pid,
		cache: false
	}).done(function(html){
	    $(".edit" + pid).remove();
		$.jGrowl(html);
	});
}

function DeleteItem(pid){
    $.ajax({
	    url: "ajax/inventory/delete.php?pid=" + pid,
		cache: false
	}).done(function(html){
	    $(".edit" + pid).remove();
		$.jGrowl(html);
	});
}

function AddItem(model){
    $.get('ajax/inventory/part_template.php',{m: model},function(html){
        var oopa = $(html).dialog({title: 'Add New Part',dialogClass:"addpart" + model,close: function(event, ui){$(this).dialog('destroy').remove()},modal: true});
		$('#addnewpartform').find('select').chosen();
	});
}

function SendNewPart(ele, model){
    var form = ele.parent().find('#addnewpartform');
    var valid = form.valid();
	form.find("select").each(function(){
	    if(valid == true){
		    valid = form.validate().element(this);
		}
	});
	if(valid == true){
	    var datas = form.serialize();
	    $.get('ajax/inventory/add.php',datas,function(retur){
		    $.jGrowl(retur);
			form.parent().parent().find('.ui-dialog-titlebar-close').click();
			var mname = $("#PartParts").html().split("Part's");
			InventoryMod(model, mname[0]);
		});
	} else {
	    form.validate().form();
		form.find('select').each(function (i){
		    if($(this).val() == ""){
			    var thisid = $(this).attr("id");
			    $("#" + thisid + "_chzn a").addClass("error");
			}
		});
		form.find("label").addClass("ignore");
	}
}

function RemoveError(Element){
    var thisid = $(Element).attr("id");
	$("#" + thisid + "_chzn a").removeClass("error");
}

function EditRefurbDevice(rid){
    $.get("refurb/ajax/edit.php?r="+rid,function(html){
	    var oopa = $(html).dialog({title: 'Refurb #'+rid+' Edit',dialogClass:"er" + rid,width:600,close: function(event, ui){$(this).dialog('destroy').remove()},modal: true});
	});
}

function SaveRefurbEdit(rid, ele){
    var lp = ele.prev().prev().prev().prev().val();
	var up = ele.prev().prev().prev().val();
	var rr = $(".rrt"+rid);
	rr.find('.lop').html(lp);
	rr.find('.ulp').html(up);
	ele.parent().parent().find('.ui-dialog-titlebar-close').click();
	$.get("refurb/ajax/save_edit.php",{r: rid, l: lp, u: up},function(html){
	    $("#printdiv").html(html);
		window.print();
	});
}

function RePrintRefurb(rid, ele){
    ele.parent().parent().find('.ui-dialog-titlebar-close').click();
	$.get("refurb/ajax/reprint.php",{r: rid},function(html){
	    $("#printdiv").html(html);
		window.print();
	});
}

function DeleteRefurb(rid, ele){
    ele.parent().parent().find('.ui-dialog-titlebar-close').click();
	$.get("refurb/ajax/delete.php",{r: rid},function(){
	    $(".rrt"+rid).remove();
	});
}

/*! Chat Functions -------------------------------------------------------------- */
function CurTime(){
    var date    = new Date();
	var month   = date.getMonth();
	var day     = date.getDate();
    var hours   = date.getHours()
	var minutes = date.getMinutes()
	var m = '';
	var d = '';
	switch(month){case 0:m = "Jan";break;case 1:m = "Feb";break;case 2:m = "Mar";break;case 3:m = "Apr";break;case 4:m = "May";break;case 5:m = "Jun";break;case 6:m = "Jul";break;case 7:m = "Aug";break;case 8:m = "Sep";break;case 9:m = "Oct";break;case 10:m = "Nov";break;case 11:m = "Dec";break;}
    switch(day.toString().substr(-1,1)){case "1":d = "st";break;case "2":d = "nd";break;case "3":d = "rd";break;case "4":d = "th";break;case "5":d = "th";break;case "6":d = "th";break;case "7":d = "th";break;case "8":d = "th";break;case "9":d = "th";break;case "0":d = "th";break;}
	if (minutes < 10){minutes = "0" + minutes;}var suffix = "AM";if (hours >= 12) {suffix = "PM";hours = hours - 12;}if (hours == 0) {hours = 12;}
	return m + " " + day + "" + d + ", " + hours + ":" + minutes + " " + suffix;
}

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
	}).done(function( json ) {
	    if(json.NA == "true"){
		    window.location.replace("../"); 
		} else if(json.NA == "qas"){
		    clearTimeout(timerz);
		    QASuser();
        } else if(json.NA == "message") {
		    $.each(json.messages,function(key,mess){
			    if(mess.from == "Price Request"){var headerm = mess.from;var closer = "";var notation = true;}
				else if(mess.from == "Price Response"){var notation = false;} else {var headerm = "Message From " + mess.from; var closer = "x";var notation = true;}
			    $.jGrowl("<label style=position:relative;><img src='north/avatars/"+ mess.avatar +".png?_="+ $endtime +"' style='height:50px;float:left;'/>" + mess.message + "<br/><br/><i style='font-size:10px;'>Sent at " + mess.time + "</i></label>", {
    			    sticky: notation, 
				    header: headerm,
					closeTemplate: closer
			    });
			});
			$("#southnotification")[0].play();
			timerz = setTimeout(function(){CheckMessages()}, 2000);
		} else if(json.NA == "false"){
		    timerz = setTimeout(function(){CheckMessages()}, 2000);
		}
		var latency = Math.round(((+new Date()) - $starttime) - 3);
	    mpoints.push(latency);
	    $("#curtime").html(CurTime());
        if (mpoints.length > mpoints_max){mpoints.splice(0,1);}
		$("#systemlatency").sparkline(mpoints, { type: 'bar', width: '60px', tooltipSuffix: 'ms' , colorMap: range_map});
		$("#uidgm").html(json.gold).parent().attr("title", "Gold Medals: "+json.gold);
		$("#uidsm").html(json.silver).parent().attr("title", "Silver Medals: "+json.silver);
	    $("#uidbm").html(json.bronze).parent().attr("title", "Bronze Medals: "+json.bronze);
    });
}

function SendMessage(event, me){
    if (event.keyCode==13)
    {
	    $("#chatindicator").show();
		var username = me;
		var avatar = "";
		var message = $("#chat_message").val();
		var to = $("#message_to").val();
		var toname = $("#message_to option:selected").text();
		
		$.ajax({
	    url: "ajax/chat/send_message.php?res=send&from=" + username + "&message=" + message + "&avatar=" + avatar + "&to=" + to,
        cache: true
	    }).done(function( html ) {
		    $("#chat_message").val("");
			$.jGrowl("<label style=position:relative;>" + message + "</label>", {
				header: "Message To " + toname,
			});
			CheckMessages();
        });
    }
}


/*! Manager Functions ----------------------------------------------------------- */
function CompareLedgers(){
    var form = $('#compareledgers');
	var table = form.find('#listofticks');
	var data = form.serialize();
	form.find('font').remove();
	$.post('mtools/ajax/compare_ledgers.php',data,function(json){
	    if(json.TICKETS != ''){
		    var row = '';
		    $.each(json.TICKETS,function(key,value){
			    $.each(value,function(key2,value2){
				    var items = value2.items.split("|");
					row += '<tr style="background-color:#C89696;border:1px solid black;"><td><input type="checkbox" onChange="if(this.checked){$(this).parent().parent().css('+"'background-color'"+','+"'#96C896'"+');} else {$(this).parent().parent().css('+"'background-color'"+','+"'#C89696'"+');}"></td>';
					var i=0;
					var t='<td style="cursor:pointer;" onClick="ModLedgerItem('+"'"+value2.qb_id+"'"+')">';var c='<td>';
					$.each(items,function(blank,data){
					    if(data != 0){
						    var ticket = data.split("/");
							if(i > 2){
							    t += '<br/>'+ticket[0];
								c += '<br/>'+ticket[1];
							} else {
							    t += ticket[0];
								c += ticket[1];
								i = 3;
							}
						}
					});
					t+='</td>';c+='</td>';
					row += t+''+c+'<td>'+value2.pm_1+'</td><td>'+value2.pm_1_cost+'</td><td>'+value2.pm_2+'</td><td>'+value2.pm_2_cost+'</td></tr>';
				});
			});
			table.find('tbody').html('').append(row);
			table.fadeIn();
		} else {
		    table.fadeOut();
			$("#compareledges").html("Queue And Print Report").attr("onclick","ReportLedger()");
		}
		$.each(json,function(key,value){
		    if(key != 'TICKETS'){
			    if(value > 0){
		            $('#'+key).append('<font color="red"><br/>+'+value+'</font>');
		        } else if(value < 0){
		            $('#'+key).append('<font color="red"><br/>'+value+'</font>');
		        } else if(value == 0){
		            $('#'+key).append('<font color="green"><br/>0</font>');
		        }
			}
		});
	}, 'json');
}

function ModLedgerItem(iid){
    $.get('mtools/ajax/mod_checkout.php',{chid: iid}, function(response){
	    var diag = $(response).dialog({
		    modal: true,
		    resizable: false, 
		    draggable: false,
		    width:400,
		    title:"Checkout Session: "+iid,
		    close: function(event, ui){$(this).dialog('destroy').remove();},
			buttons: {
			    "Update": function() {
				    var data = $('#mod_checkout').serialize();
					$.get('mtools/ajax/update_checkout.php',data,function(response){
					    CompareLedgers();
					    diag.remove();
					});
			    }
		    }
	    }).dialogExtend({"close":true,"maximize":false,"minimize":false});
	});
}

function ReportLedger(){
    var form = $('#compareledgers');
	var data = form.serialize();
    $.post('mtools/ajax/ledger_report.php',data,function(form){
	    $('#printdiv').html(form);
		window.print();
	});
}


/*! Avatar Functions ------------------------------------------------------------ */
function SetAvatar(uid, file){
	fsplit = file.split("/");
	if(fsplit[0] != 'new' && fsplit[1] != '..'){var div='<div><center>Non-Valid Avatar Filed Detected, Please reload avatars.</center></div>';} else {
    var div = '<div><button onclick="ResetColor()" style="width:100%;">Reset</button>'+
	'<div class="color-options" style="float:left;">'+
        '<div>Hue<br/><input type="range" id="value-hue" value="0" min="-180" max="180" onChange="AdjustColor()"/></div>'+
        '<div>Saturation<br/><input type="range" id="value-saturation" value="0" min="-100" max="100" onChange="AdjustColor()"/></div>'+
        '<div>Lightness<br/><input type="range" id="value-lightness" value="0" min="-100" max="100" onChange="AdjustColor()"/></div>'+
    '</div><span id="av-bg-span" class="image-wrap" style="float:right;border-radius:3px;">'+
	'<span style="background-color:#969696;"></span><img src="' + file + '" id="selectedavatar" style="width:128px;" alt=""/></span>'+
	'<div>Background Color<br/><input type="color" id="value-bg-color" value="#969696" onChange="SetBgColor($(this).val())" style="width:98%;"></div>'+
    '<button onclick="SaveAvatar(' + uid + ')" style="width:100%;">Save</button><br/>'+
	'</div>';
	}
	var ele = $(div).dialog({close: function(event, ui){$(this).dialog('destroy').remove();},modal: true,width:300,title:"Customize Avatar",dialogClass:"CustomAvatar"}).dialogExtend({"close":true,"maximize":false,"minimize":false});
    Pixastic.process(document.getElementById("selectedavatar"), "hsl", {hue:0,saturation:0,lightness:0});
}

function SetBgColor(value){
    $("#av-bg-span span").css("background-color",value);
}

function ResetColor(){
    Pixastic.revert(document.getElementById("selectedavatar"));
	Pixastic.process(document.getElementById("selectedavatar"), "hsl", {hue:0,saturation:0,lightness:0});
	$("#value-hue").val(0);
	$("#value-saturation").val(0);
	$("#value-lightness").val(0);
}

function AdjustColor() {
    Pixastic.revert(document.getElementById("selectedavatar"));
	Pixastic.process(document.getElementById("selectedavatar"), "hsl", {
		hue : $("#value-hue").val(),
		saturation : $("#value-saturation").val(),
		lightness : $("#value-lightness").val()
	});
}

function SaveAvatar(uid, ele){
    var Canvas = document.getElementById("selectedavatar");
	var canvasData = Canvas.toDataURL("image/png");
	var postData = "\\"+ $("#value-bg-color").val()+"//canvasData="+canvasData;
    $.ajax({
        url: 'north/upload.php',
        data: postData,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST'
    }).done(function(response){
	    $(".CustomAvatar").find(".ui-dialog-titlebar-close").click();
		$.get('north/user.php',function(response){
		    $("#usertag").html(response);
		});
	});
}


/*! User Settings Functions ----------------------------------------------------- */
function QAS(){
    qas = $("<div><center>(Example: User ID = 1; PIN = 1234; PIN-Combo = 11234)<br/><br/><input type='password' id='qasinput' placeholder='Type PIN-Combo press Enter' onKeyUp='SwitchAccounts($(this).val(),event)' style='width:175px;'></center></div>").dialog({
	    modal: true,
		resizable: false, 
		draggable: false,
		width:400,
		title:"Quick Account Switch",
		close: function(event, ui){$(this).dialog('destroy').remove();qas='';}
	}).dialogExtend({"close":true,"maximize":false,"minimize":false});
	$("#qasinput").focus();
}

function QASuser(){
    qas = $("<div><center>(Example: User ID = 1; PIN = 1234; PIN-Combo = 11234)<br/><br/><input type='password' id='qasinput' placeholder='Type PIN-Combo press Enter' onKeyUp='SwitchAccountsUser($(this).val(),event)' style='width:175px;'></center></div>").dialog({
	    modal: true,
		resizable: false, 
		draggable: false,
		width:400,
		title:"Quick Account Switch",
		close: function(event, ui){$(this).dialog('destroy').remove();qas='';CheckMessages();}
	}).dialogExtend({"close":false,"maximize":false,"minimize":false});
	$("#qasinput").focus();
}

function SwitchAccountsUser(value,e){
    if(e.keyCode==13){
	    $.post('ajax/users/qas.php',{ str: value },function(){
		    CheckMessages();
		    qas.remove();
			qas='';
			$.get('north/user.php',{l: value},function(response){
		        $("#usertag").html(response);
		    });
		});
	}
}

function SwitchAccounts(value,e){
    if(e.keyCode==13){
	    $.post('ajax/users/qas.php',{ str: value },function(){
		    qas.remove();
			qas='';
			$.get('north/user.php',{l: value},function(response){
		        $("#usertag").html(response);
		    });
		});
	}
}

function SavePIN(){
    var op = $('#oldpin');
	var np = $('#newpin');
	var rp = $('#reppin');
	if((op.val().length == 4 && np.val().length == 4 && rp.val().length == 4) && (np.val() == rp.val())){
	    $.post('ajax/users/pin.php',{ str: np.val()+''+op.val()},function(response){
		    if(response==1){
			    op.before("<center id='pinnote'>PIN Updated</center>");
				op.removeClass('error');
				op.val('');np.val('');rp.val('');
			} else if(response==2){
			    op.before("<center id='pinnote'>Old Pin not Correct</center>");
				op.addClass('error');
			}
			$("#pinnote").fadeOut(5000,function(){$(this).remove();});
		});
	}
}

function SavePassword(){
    var op = $('#oldpas');
	var np = $('#newpas');
	var rp = $('#reppas');
	if((np.val().length >= 8 && rp.val().length >= 8) && (np.val() == rp.val())){
	    $.post('ajax/users/password.php',{ str1: np.val(), str2: op.val()},function(response){
		    if(response==1){
			    op.before("<center id='pasnote'>Password Updated</center>");
				op.removeClass('error');
				op.val('');np.val('');rp.val('');
			} else if(response==2){
			    op.before("<center id='pasnote'>Old Password not Correct</center>");
				op.addClass('error');
			}
			$("#pasnote").fadeOut(5000,function(){$(this).remove();});
		});
	}
}