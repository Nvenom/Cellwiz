<?php
header('Content-type: application/javascript');

require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(1);

$store = $_GET['s'];
$range = $_GET['r'];

$P = strpos($range, ' - ');

$I = array($store);
if($P === false){
	$R = str_replace("/", "-", $range);
	$I[] = $R." 00:00:00";
	$I[] = $R." 24:59:59";
} else {
    $R = explode(" - ", $range);
	$I[] = str_replace("/", "-", $R[0]);
	$I[] = str_replace("/", "-", $R[1]);
}

$PARTS = MYSQL::QUERY('SELECT t_repair_items FROM core_tickets_processed WHERE t_store = ? AND t_checkout_created >= ? AND t_checkout_created <= ?',$I);

$ARRAY = ARRAY();

FOREACH($PARTS AS $P){
    IF(!EMPTY($P)){
        $S = EXPLODE("|",$P['t_repair_items']);
		FOREACH($S AS $I){
		    $IS = EXPLODE("-",$I);
			IF($IS[0] == 'it'){
				$INFO = MYSQL::QUERY('SELECT dp.p_name, dm.m_name, ma.m_name as manu FROM device_parts dp JOIN device_models dm ON dp.p_model_id = dm.m_id JOIN device_manufacturers ma ON dm.m_manufacturer_id = ma.m_id WHERE dp.p_id = ? LIMIT 1',ARRAY($IS[1]));
				IF(!EMPTY($INFO)){
					$NAME = STR_REPLACE($INFO['manu']." ","",$INFO['m_name']);
				    $ARRAY[$INFO['manu']]['COUNT']++;
					$ARRAY[$INFO['manu']]['DEVICES'][$NAME]['COUNT']++;
				    $ARRAY[$INFO['manu']]['DEVICES'][$NAME]['PARTS'][$INFO['p_name']]['COUNT']++;
					$ARRAY[$INFO['manu']]['DEVICES'][$NAME]['PARTS'][$INFO['p_name']]['PARTN'] = $IS[1];
			    }
			}
		}
	}
}

ECHO 'var data = [';
FOREACH($ARRAY AS $K1 => $A){
    ECHO "{ name: '".$K1."', data: [{ y: ".$A['COUNT'].", drilldown: { label: '".$K1." Device',data: [";
	FOREACH($A['DEVICES'] AS $K2 => $D){
	    ECHO "{ name: '".$K2."', data: [{ y: ".$D['COUNT'].", drilldown: { label: '".$K1." ".$K2." Parts', data: [";
		FOREACH($D['PARTS'] AS $K3 => $P){
		    ECHO "{ name: '".$K3."', data: [".$P['COUNT']."] },";
		}
		ECHO "]}}]},";
	}
	ECHO "]}}]},";
}

ECHO <<<JS
    ];

	function genchen(){
	    var chart = new Highcharts.Chart({
            chart: {
                type: 'column',
				renderTo: 'container'
            },
            title: {
                text: '($store) Part Use for $range'
            },
            subtitle: {
                text: 'Click the columns to view a breakdown'
            },
            yAxis: {
                title: {
                    text: 'Total Used'
                }
            },
			xAxis: {
			    categories: ['Data'],
			    title: {
				    text: 'Manufacturers'
				},
				labels: {
				    formatter: function() {
                        return '';
                    }
				}
			},
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
							    var n = Highcharts.charts.length - 1;
				                Highcharts.charts[n].showLoading();
                                var drilldown = this.drilldown;
								console.log(this);
                                if (drilldown) {
                                    var ddaattaa = drilldown.data;
									Highcharts.charts[n].xAxis[0].setTitle({
                                        text: drilldown.label
                                    });
                                } else {
                                    var ddaattaa = data;
									Highcharts.charts[n].xAxis[0].setTitle({
                                        text: 'Manufacturers'
                                    });
                                }
								while (Highcharts.charts[n].series.length > 0){
                                    Highcharts.charts[n].series[0].remove(true);
                                }
                                for (var i = 0; i < ddaattaa.length; i++) {
                                    Highcharts.charts[n].addSeries({
					                    name: ddaattaa[i].name,
                                        data: ddaattaa[i].data
                                    }, false, false);
                                }
				                Highcharts.charts[n].redraw();
								Highcharts.charts[n].hideLoading();
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
							color: 'black'
                        },
                        formatter: function() {
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.series.name +':<b>'+ this.y +' parts repaired</b><br/>';
                    if (point.drilldown) {
                        s += 'Click to view a breakdown';
                    } else {
                        s += 'Click to return to Manufacturers';
                    }
                    return s;
                }
            },
            series: data,
            exporting: {
                enabled: false
            }
        });
		
		return chart;
	}
JS;
?>