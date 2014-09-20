<?php
require("../../../frame/engine.php");ENGINE::START();
include('../../ajax/simple_html_dom.php');
$user = USER::VERIFY(0);

$manu = $_GET['manu'];
$model = $_GET['model'];
$lp = $_GET['lp'];
$up = $_GET['up'];
$sp = str_replace(";", "&", $_GET['sp']);
$iden = $_GET['iden'];
$notes = $_GET['notes'];
$ses = FORMAT::SES(10);

$Model = MYSQL::QUERY("SELECT * FROM device_models WHERE m_id = ? LIMIT 1", ARRAY($model));
MYSQL::QUERY("INSERT INTO core_refurb_devices (d_manu_id, d_model_id, d_model, d_iden, d_locked_price, d_unlocked_price, d_service_provider, d_notes, d_by, d_store, d_ses, d_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);", ARRAY($manu, $model, $Model['m_name'], $iden, $lp, $up, $sp, $notes, $user['user_id'], $user['store'], $ses, Date("Y-m-d H:i:s")));
$SELECT = MYSQL::QUERY("SELECT d_id, d_ses FROM core_refurb_devices WHERE d_ses = ? LIMIT 1", ARRAY($ses));

$infoset = '';
$url = "http://www.phonearena.com".$Model['m_link'];
$html = file_get_html($url);
foreach($html->find('div.rightspecscol') as $section){
	foreach($section->find('div.s_specs_box') as $info){
		$info->onclick = null;
		$info->href = null;
		$infoset .= $info;
	}
}
echo "
<style>
#phone_specificatons a{display: none !important;font-family: Arial,Helvetica,sans-serif;}
.s_box_4{border:5px
solid #d0e8f5}.s_box_4
h2{margin:0px;padding:7px 10px 7px 10px;line-height:22px;font-size:18px;font-weight:normal;color:#0f6c9b;background-color:#d0e8f5;display:none;}.s_box_4_orange_1{border:5px
solid #ffc56b}.s_box_4_orange_1
h2{color:#6f3700;background-color:#ffc56b}.s_box_4_gray_1{border:5px
solid #ddd}.s_box_4_gray_1
h2{color:#666;background-color:#ddd}.s_box_4
.s_listing{margin-right:-15px}.s_box_4
.s_block_4{margin-right:10px;margin-bottom:10px;text-align:center}.s_block_1
.s_wall_to_wall{display:inline-block;color:#aaa;font-size:18px;vertical-align:top}.s_block_1
.s_show_hide{vertical-align:top;color:#999;font-size:14px}.s_block_1 a.s_show_hide:hover{color:#ff250f}.s_specs_box{margin-bottom:0px;font-size:12px;border:5px
solid #dff3fd;width:49%;float:left;display:inline-block;}.s_specs_box
h3{padding-left:9px;background-color:#dff3fd}.s_specs_box li
strong.s_lv_1{display:block;float:left;width:100px;color:#226290}.s_specs_box li
strong.s_lv_1.s_bullet_advanced{width:85px}.s_specs_box li li
strong{font-weight:normal;color:#777}.s_specs_box
li.s_lv_1{overflow:hidden;padding:5px
5px 0px 5px;border-bottom:1px solid #eee}.s_specs_box>ul>li:nth-child(even),.s_specs_box>ul>li.s_even{}.s_specs_box>ul>li:hover{background:#FFFCE0}.s_specs_box>ul>li:last-child{}.s_specs_box
ul.s_lv_1{padding-left:110px}.s_specs_box ul.s_lv_1
li{}.s_specs_box
strong.s_lv_2{position:relative;float:left;width:95px !important;margin-left:-96px;text-align:left;color:#4dafe1;color:#226290}.s_specs_box
strong.s_lv_2.s_bullet_advanced{width:80px !important;margin-left:-96px}.s_specs_box
li.s_lv_3{~overflow: hidden;width:100%;~border: 1px solid #fff}.s_specs_box
strong.s_lv_3{position:relative;float:left;margin-left:-86px;width:85px !important;text-align:left;color:#1B93D0}.s_specs_box
strong.s_lv_3.s_bullet_advanced{width:70px !important;margin-left:-86px}.s_specs_box
ul.s_lv_3{~overflow: hidden;width:100%;position:relative;margin-bottom:4px}.s_specs_box>ul>li>ul>li>ul{margin-bottom:4px}.s_specs_box>ul>li>ul>li>ul:last-child{margin-bottom:0}.s_specs_box>ul>li>ul>li.clearfix{padding-top:4px}.s_specs_box.s_form > ul > li > ul
li{padding-left:5px;padding-left:0px;text-align:left}.s_specs_box>ul>li>ul>li>ul>li{padding-left:0}.s_specs_box .s_text,
.s_specs_box textarea,
.s_specs_box
select{font-size:11px}.s_specs_box
select{width:100%}.s_specs_box .s_text,
.s_specs_box
textarea{padding:2px
!important}.s_specs_box label.s_radio,
.s_specs_box
label.s_checkbox{display:block !important;margin-bottom:2px}.s_specs_box{margin-bottom:0px;padding:1px;font-size:12px;border:none}.s_specs_box
h3{padding:5px
9px;font-size:16px;font-weight:bold;background:#fff;color:#505050;text-transform:uppercase;font-family:'Open Sans',Arial,Helvetica,sans-serif}.s_specs_box
ul{background:#f9f9f9;background:none;padding:0px;margin:0px;border:1px
solid #dadada;font-style:normal;font-size:13px;line-height:18px}.s_specs_box ul ul, .s_specs_box ul ul
ul{border:none;margin:0;padding:0}.s_specs_box ul
li{display:block !important;margin:0px;padding:4px
7px !important}.s_specs_box ul li + .s_specs_box ul
li{border-top:1px solid #ccc !important}.s_specs_box ul li ul li, .s_specs_box ul li ul li ul
li{border:none !important;margin:0;padding:0px
!important}.s_specs_box li strong.s_lv_1, .s_specs_box
strong.s_lv_2{font-style:normal;color:#505050;font-size:12px;text-align:left}.s_specs_box > ul > li > ul > li
strong{margin-left:-100px !important}.s_specs_box>ul>li:last-child{border-bottom:none}.s_specs_box input.text,
.s_specs_box
label{text-align:left}.s_specs_box
table{margin-bottom:0;border:none}.s_filter_box th,
.s_filter_box
td{border:none;border-top:1px solid #ddd}.s_filter_box
td{width:160px}.s_filter_box tr.s_level_2 td,
.s_filter_box tr.s_level_2 th,
.s_filter_box tr.s_level_3 th,
.s_filter_box tr.s_level_3
td{padding-top:0;padding-bottom:7px;border:none !important;vertical-align:top;background:none !important}.s_filter_box tr:first-child th,
.s_filter_box td:first-child
td{border:none !important}.s_filter_box
label{white-space:normal !important}.s_specs_box table th,
.s_specs_box table
strong.s_label{color:#226290;font-weight:bold}.s_specs_box table
strong.s_label{display:block;padding-bottom:5px}.s_specs_box table tr.s_level_2
th{font-weight:normal;padding-left:13px}.s_specs_box table tr.s_level_3
th{font-weight:normal;padding-left:18px;color:#52a1cc}.s_specs_box.s_1_col table tr.s_level_2
td{padding-left:13px}.s_specs_box.s_1_col table tr.s_level_2 td
strong.s_label{font-weight:normal}.s_specs_box.s_1_col table tr.s_level_3
td{padding-left:18px}.s_specs_box.s_1_col table tr.s_level_3 td
strong.s_label{font-weight:normal;color:#52a1cc}.s_specs_box table th:first-child{padding-left:8px}.s_specs_box table td:last-child{padding-right:8px}.s_specs_box table tr th:last-child,
.s_specs_box table tr td:last-child{border-right:none}.s_specs_box table tr:last-child
th{border-bottom:none}
.s_tooltip_content{display:none;}
.gray_9{display:none;}
.floatright{display:none;}
</style>
<div style='height:450px;width:49%;overflow:hidden;display:inline-block;float:left;font-size:16px;position:relative;'>
<center>
    <img src='https://my-cpr.com/barcode.php?encode=I25&height=20&scale=1&color=000000&bgcolor=FFFFFF&type=png&file=&bdata=".$SELECT['d_id']."' border='0' /><br/>
	Identifier<br/>".$iden."<br/>
    <img border='0' src='https://my-cpr.com/cprlogo.jpg' style='position:absolute;bottom:20px;left:0px;'>
	<div style='position:absolute;bottom:25px;right:5px;width:50%;'>
	    <b>Device:</b> ".$Model['m_name']."<br/>
	    <b>Carrier:</b> ".$sp."<br/>
	    <b>Price:</b> $".$lp."<br/>
	    ";
	        if($up > $lp){
		        echo "<b>UnLocked Price:</b> $".$up."<br/><br/>";
		    } else {
		        echo "<br/><br/>";
		    }
	    echo "
	</div>
</center>
</div>
<div style='height:500px;width:49%;overflow:hidden;display:inline-block;float:left;margin-left:2%;'><center>
<font style='font-size:24px;'>".$user['store_info']['s_header']."</font><br/>
<font style='font-size:16px;'>Contact our ".$user['store_info']['s_name']." Location at</font><br/>
<font style='font-size:20px;'>".FORMAT::PHONE($user['store_info']['s_phone'])."</font><br/>
<font style='font-size:18px;'>".$user['store_info']['s_website']."</font><br/><br/><br/>
<font style='font-size:16px;'>Warranty:</font><br/>
<font style='font-size:40px;'>6 Months</font><br/><br/>
<b>Physical Warranty</b> - This warranty covers any device purchased. If the device proves to be defective in any way we will replace it at no extra charge.
							This warranty is void if the device sustains any physical or liquid damage.
							<br/><br/>
							<b>Software Warranty</b> - This warranty guarantees any software service we provide for the device at sale (e.g. Unlocking). If for any reason the device is updated, modified (e.g. jailbreaking, unlocking, rooting) or sustains
							physical or liquid damage the warranty becomes void.
							<br/><br/>
							<b>Liquid Damage</b> - If your device sustains liquid damage its warranty becomes null and void. No Exceptions.
</center></div>
<div style='height:450px;width:100%;overflow:hidden;'>
".$infoset."
</div>
";
?>