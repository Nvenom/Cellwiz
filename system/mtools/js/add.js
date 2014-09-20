function CreateUser(){
    $("#createu").attr("disabled", true).removeClass("ui-state-hover").addClass("ui-state-disabled");
    var valid = $("#user_data").valid();
	if(valid == true){
	    var user = $("#username").val();
	    var oopa = $("<p>Are you sure want to Create " + user + "'s Account?</p>").dialog({
		    title: 'Confirmation',
            close: function(event, ui){$(this).dialog('destroy').remove()},
			modal: true,
			buttons: {
				"Yes": function() {
				    $(this).html("<p><Center>Adding Account, Please Wait...<br/><Br/><img src='tickets/image/add_c.gif' border='0'></center></p>");
				    var dataa = $("#user_data").serialize();
		            $.ajax({
		                type: "POST",
                        url: "mtools/ajax/user_template.php",
			            data: dataa,
                        cache: false
                    }).done(function( html ) {
					    oopa.dialog('destroy').remove();
                    });
				},
				"No": function() {
					$(this).dialog('destroy').remove();
                    $("#createu").attr("disabled", false).removeClass("ui-state-hover").removeClass("ui-state-disabled");
				}
			}
		});
	} else {
	    $("#createu").attr("disabled", false).removeClass("ui-state-hover").removeClass("ui-state-disabled");
		$("#user_data").validate().form();
	}
}