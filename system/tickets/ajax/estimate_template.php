<?php
require("../../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0,TRUE);

$params = array($user['store']);
$Main = MYSQL::QUERY('SELECT s_timezone FROM core_stores WHERE s_id=?', $params);

function Timesince($original) {
    // array of time period chunks
    $chunks = array(
    array(60 * 60 * 24 * 365 , 'year'),
    array(60 * 60 * 24 * 30 , 'month'),
    array(60 * 60 * 24 * 7, 'week'),
    array(60 * 60 * 24 , 'day'),
    array(60 * 60 , 'hour'),
    array(60 , 'min'),
    array(1 , 'sec'),
    );
 
    $today = time(); /* Current unix time  */
    $since = $today - $original;
 
    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
 
    $seconds = $chunks[$i][0];
    $name = $chunks[$i][1];
 
    // finding the biggest chunk (if the chunk fits, break)
    if (($count = floor($since / $seconds)) != 0) {
        break;
    }
    }
 
    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
 
    if ($i + 1 < $j) {
    // now getting the second item
    $seconds2 = $chunks[$i + 1][0];
    $name2 = $chunks[$i + 1][1];
 
    // add second item if its greater than 0
    if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
        $print .= ($count2 == 1) ? ', 1 '.$name2 : " $count2 {$name2}s";
    }
    }
    return $print;
}

$db = $Core->db();
$params = array($user['store']);
$Main = $Core->pdoQuery($db, 'SELECT t_id,t_customer,t_manufacturer,t_model,t_imei,t_issue,t_password,t_date FROM core_tickets_estimate WHERE t_store=?'.$assets, $params);

foreach($Main as $a){
	$params = array($a['t_customer'],$a['t_manufacturer'],$a['t_model']);
	$Data = $Core->pdoQuery($db, 'SELECT core_customers.c_name,core_customers.c_phone,device_manufacturers.m_name,device_models.m_name FROM core_customers,device_manufacturers,device_models WHERE core_customers.c_id=? AND device_manufacturers.m_id=? AND device_models.m_id=?', $params);
    echo '
        <tr class="ajaxrow">
            <td>&nbsp;'.$a['t_id'].'</td>
            <td>&nbsp;'.$a['t_imei'].'</td>
            <td>&nbsp;'.$Data[0][0].'</td>
            <td>&nbsp;'.$Data[0][2].'</td>
            <td>&nbsp;'.$Data[0][3].'</td>
            <td>&nbsp;'.Timesince(strtotime($a['t_date'])).' ago</td>
        </tr>
        <tr class="ajaxrow">
            <td colspan="6" style="display:none;"></td>
        </tr>
    ';
}
?>