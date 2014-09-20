<?php
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

switch($user['level']){
    case 1:$rw = 's_manager';break;
	case 2:$rw = 's_gmanager';break;
	case 3:$rw = 's_owner';break;
	case 4:$rw = 's_region';break;
	case 5:$rw = 'all';break;
	case 6:$rw = 'all';break;
	case 7:$rw = 'all';break;
	case 8:$rw = 'all';break;
}

if($rw == 'all'){
    $stores = MYSQL::QUERY("SELECT s_id,s_name FROM core_stores");
} else {
    $stores = MYSQL::QUERY("SELECT s_id,s_name FROM core_stores WHERE $rw = ?", ARRAY($user['user_id']));
}

$locations = '<option value=""></option>';
foreach($stores as $s){
    $locations .= '<option value="'.$s['s_id'].'">'.$s['s_name'].'</option>';
}
?>
<!DOCTYPE HTML>
<html>
    <head>
		<META http-equiv='Content-Type' content='text/html; charset=utf-8'>
		<META NAME='copyright' CONTENT='Copyright ©Cellwiz.net <?php echo Date('Y'); ?>. All Rights Reserved.'>
		<META NAME='author' CONTENT='Christian John Clark'>
	    <META NAME='robots' CONTENT='noindex'>
		<META NAME='language' CONTENT='English'>
		<title>Cellwiz Reports</title>
		<link rel="stylesheet" href="css/ui.daterangepicker.css" type="text/css" />
		<link rel='stylesheet' type='text/css' href='../../frame/skins/default/css/main.css<?php echo $debug; ?>' />
		<link rel='stylesheet' type='text/css' href='../../frame/skins/default/css/default/ui.css<?php echo $debug; ?>' />
		<script src='../../frame/controllers/c-controller.js'></script>
		
		<script src="js/date.js"></script>
		<script src="js/daterangepicker.js"></script>
		<script src="js/highcharts.js"></script>
		<script src="js/themes/grid.js"></script>
		
		<script>
		    var chart,colors = Highcharts.getOptions().colors,categories,name,data,hcdefault;
		
		    $(function(){
				$('#range').daterangepicker({arrows: true});
				$("select").chosen();
			});
			
			function GenerateData(){
			    var s = $("#store").val();
				var r = $("#range").val();
				var d = $("#data").val();
				var t = $("#type").val();
				var ts = $("#store option:selected").text();
				var td = $("#data option:selected").text();
				var tt = $("#type option:selected").text();
				
				if(r.length > 10){
				    var mz = 24 * 3600000;
				} else {
				    var mz;
				}
				
				if(s == '' || r == '' || d == '' || t == ''){} else {
				    if(d < 50){
				        var options = {
                            chart: {
                                renderTo: 'container',
							    type: t,
							    zoomType: 'x',
                                spacingRight: 20
                            },
						    title: {
						        text: ts + ' ' + td + ' ' + tt
						    },
						    subtitle: {
						        text: r
						    },
                            xAxis: {
                                type: 'datetime',
                                dateTimeLabelFormats: {
                                    second: '(%I:%M:%S %p)',
						            minute: '(%I:%M %p)',
						            hour: '(%I:%M %p)',
						            day: '(%e. %b)',
						            week: '(%e. %b)',
						            month: '(%b \'%y)',
						            year: '(%Y)'
                                },
							    maxZoom: mz,
                            },
                            tooltip: {
                                formatter: function(){
                                    var s = '<b>'+ Highcharts.dateFormat('%b %e, %Y (%A %I:%M %p)', this.x) +'</b>';
                                    $.each(this.points, function(i, point) {
                                        s += '<br/>'+ point.series.name +': '+point.y;
                                    });
                                    return s;
                                },
                                shared: true,
							    crosshairs: [{
                                    width: 2,
                                    color: 'green',
                                    dashStyle: 'shortdot'
                                }, {
                                    width: 2,
                                    color: 'green',
                                    dashStyle: 'shortdot'
                                }]
                            },
						    plotOptions: {
                                series: {
                                    cursor: 'pointer',
                                    point: {
                                        events: {
                                            click: function(){
										        console.log(this.series.name);
										    }
                                        }
                                    },
                                    marker: {
                                        lineWidth: 1
                                    }
                                }
                            },
                            series: []
                        };
					    $.getJSON("ajax/report.php?s=" + s + "&r=" + r + "&d=" + d, function(data) {
					        $.each(data, function(key,value) {
					            var series = { data: []};
                                $.each(value, function(key,val) {
                                    if (key == 'name') {
                                        series.name = val;
								    } else if(key == 'type'){
								        series.type = val;
								    } else {
								        if(typeof(val[0]) == 'object'){
									        $.each(val, function(key,val) {
									            series.data.push(val);
										    });
									    } else {
                                            $.each(val, function(key,val) {
                                                var d = val.split(",");
											    if(d[5]){
											        var x = Date.UTC(d[0]-0,d[1]-1,d[2]-0,d[3]-0,d[4]-0);
                                                    series.data.push([x,d[5]-0]);
											    } else if(d[4]){
											        var x = Date.UTC(d[0]-0,d[1]-1,d[2]-0,d[3]-0);
                                                    series.data.push([x,d[4]-0]);
											    } else {
                                                    var x = Date.UTC(d[0]-0,d[1]-1,d[2]-0);
                                                    series.data.push([x,d[3]-0]);
											    }
                                            });
									    }
                                    }
                                });
							    options.series.push(series);
						    });
						    var chart = new Highcharts.Chart(options);
						});
					}  else {
					    $.getScript("ajax/"+d+".php?s=" + s + "&r=" + r + "&d=" + d,function(){
						    var chart = genchen();
						});
					}
				}
			}
		</script>
		<style>
            #options div{display:inline-block !important;vertical-align:top;}
			.chzn-single{height:24px !important;}
	        .wiz-buttons > a {position: relative;display:block;font: normal 14px Arial;text-decoration: none;cursor:default;}
            .wiz-buttons > a > .button {min-width: 75px;}
            .wiz-buttons > .next {color: #FFF;}
            .wiz-buttons > .next > .arrow-inner {display: block;position: absolute;top: 1px;right: 3px;z-index: 2000;width: 0;height: 0;border: 17px solid transparent;border-left-color: #E38A13;}
            .wiz-buttons > .next > .arrow {display: block;float: right;width: 0;height: 0;border:  18px solid transparent;border-left-color: #CC790B;}
            .wiz-buttons > .next > .button {display: block;float: right;line-height: 27px;background-color: #E38A13;border: 2px solid #CC790B;border-right: 0;padding: 3px 3px 3px 9px;text-align: center;-webkit-border-radius: 2px 0 0 2px;-moz-border-radius: 2px 0 0 2px;border-radius: 2px 0 0 2px;filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);}
		</style>
	</head>
	<body style='background-color:white;overflow:hidden;margin:0px;padding:0px;'>
	    <div id="options" style="width:100%;height:30px !important;padding-left: 130px !important; position:relative;" class="sidebar-1">
		    <div class="wiz-buttons" style="position:absolute;left:0px;top:-2px;">
                <a class="next" href="#">
                    <span class="arrow"></span>
                    <span class="arrow-inner"></span>
                    <span class="button">Report Builder</span>
                </a>
            </div>
		    <select id="store" data-placeholder="Choose a Store..." style="width:200px;" onChange="GenerateData()"><?php echo $locations; ?></select>
			<input type="text" id="range" placeholder="Choose Date/Range..." onChange="GenerateData()"/>
			<select id="data" data-placeholder="Choose the Data..." style="width:200px;" onChange="GenerateData()">
			    <option value="" ></option>
				<optgroup label="Tickets">
				    <option value="0">Ticket Ratio</option>
					<option value="1">Ticket Revenue Flow</option>
				</optgroup>
				<optgroup label="Sales">
				    <option value="2">Gross Revenue</option>
					<option value="3">Taxable Revenue</option>
					<option value="4">Payment Methods</option>
					<option value="5">Daily Gross Calculator</option>
				</optgroup>
				<optgroup label="Inventory">
				    <option value="51">Part Use</option>
					<option value="52">Service Use</option>
				</optgroup>
			</select>
			<select id="type" data-placeholder="Choose a Type..." style="width:200px;" onChange="GenerateData()">
			    <option value=""></option>
				<option value="spline">Spline Graph</option>
			    <option value="line">Line Graph</option>
				<option value="area">Area Graph</option>
				<option value="areaspline">Area Spline Graph</option>
				<option value="column">Column Graph</option>
				<option value="bar">Bar Graph</option>
				<option value="pie">Pie Chart</option>
				<option value="scatter">Scatter Plot</option>
			</select>
		</div>
	    <div id="container" style="width:100%;height:96%;" class='ui-widget-content'>
		</div>
	</body>
</html>