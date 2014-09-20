<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	include('../simple_html_dom.php');
	$link = $_GET['link'];
	
	$infoset = '';
	$url = "http://www.phonearena.com$link";
	$html = file_get_html($url);
	foreach($html->find('div.leftspecscol') as $section){
	    foreach($section->find('a.lead') as $img){
		    $img->onclick = null;
			$img->href = null;
		}
	}
	foreach($html->find('div.rightspecscol') as $section){
	    foreach($section->find('div.s_specs_box') as $info){
		    $info->onclick = null;
			$info->href = null;
			$infoset .= $info;
		}
	}
?>
<style>
#phone_specificatons a{display: none !important;font-family: Arial,Helvetica,sans-serif;}
.s_box_4{border:5px
solid #d0e8f5}.s_box_4
h2{margin:0px;padding:7px 10px 7px 10px;line-height:22px;font-size:18px;font-weight:normal;color:#0f6c9b;background-color:#d0e8f5}.s_box_4_orange_1{border:5px
solid #ffc56b}.s_box_4_orange_1
h2{color:#6f3700;background-color:#ffc56b}.s_box_4_gray_1{border:5px
solid #ddd}.s_box_4_gray_1
h2{color:#666;background-color:#ddd}.s_box_4
.s_listing{margin-right:-15px}.s_box_4
.s_block_4{margin-right:10px;margin-bottom:10px;text-align:center}.s_block_1
.s_wall_to_wall{display:inline-block;color:#aaa;font-size:18px;vertical-align:top}.s_block_1
.s_show_hide{vertical-align:top;color:#999;font-size:14px}.s_block_1 a.s_show_hide:hover{color:#ff250f}.s_specs_box{margin-bottom:15px;font-size:12px;border:5px
solid #dff3fd}.s_specs_box
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
label.s_checkbox{display:block !important;margin-bottom:2px}.s_specs_box{margin-bottom:15px;padding:1px;font-size:12px;border:none}.s_specs_box
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
th{border-bottom:none}.s_cpu_rating,
.s_ram_rating,
.s_pixel_density_rating,
.s_camera_rating,
.s_display_rating,
.s_battery_rating,
.s_size_rating,
.s_weight_rating,
.s_data_rating,
.s_resolution_rating,
.s_sar_rating,
.s_cpu_rating span,
.s_ram_rating span,
.s_pixel_density_rating span,
.s_camera_rating span,
.s_display_rating span,
.s_battery_rating span,
.s_size_rating span,
.s_weight_rating span,
.s_data_rating span,
.s_sar_rating span,
.s_resolution_rating
span{position:relative;display:block;float:left;line-height:0;font-size:0;background-image:url(http://s-cdn.phonearena.com/images/si-sprites.png);background-repeat:no-repeat}.s_camera_rating,
.s_camera_rating
span{height:18px}.s_camera_rating{width:67px;background-position:0 0}.s_camera_rating_s1
span{width:24px;background-position:0 -18px}.s_camera_rating_s2
span{width:20px;left:28px;background-position:-28px -18px}.s_camera_rating_s3
span{width:15px;left:52px;background-position:-52px -18px}.s_display_rating,
.s_display_rating
span{height:16px}.s_display_rating{width:61px;background-position:0 -36px}.s_display_rating_s1
span{width:21px;background-position:0 -52px}.s_display_rating_s2
span{width:18px;left:25px;background-position:-25px -52px}.s_display_rating_s3
span{width:14px;left:47px;background-position:-47px -52px}.s_battery_rating,
.s_battery_rating
span{height:22px}.s_battery_rating{width:38px;background-position:0 -68px}.s_battery_rating_s1
span{width:12px;background-position:0 -90px}.s_battery_rating_s2
span{width:10px;left:16px;background-position:-16px -90px}.s_battery_rating_s3
span{width:15px;left:30px;background-position:-30px -90px}.s_size_rating,
.s_size_rating
span{height:23px}.s_size_rating{width:40px;background-position:0 -112px}.s_size_rating_s1
span{width:12px;background-position:0 -135px}.s_size_rating_s2
span{width:11px;left:16px;background-position:-16px -135px}.s_size_rating_s3
span{width:9px;left:31px;background-position:-31px -135px}.s_weight_rating,
.s_weight_rating
span{height:21px}.s_weight_rating{width:58px;background-position:0 -158px}.s_weight_rating_s1
span{width:19px;background-position:0 -179px}.s_weight_rating_s2
span{width:17px;left:23px;background-position:-23px -179px}.s_weight_rating_s3
span{width:14px;left:44px;background-position:-44px -179px}.s_data_rating,
.s_data_rating
span{height:21px}.s_data_rating{width:59px;background-position:0 -200px}.s_data_rating_s1
span{width:21px;background-position:0 -221px}.s_data_rating_s2
span{width:18px;left:25px;background-position:-25px -221px}.s_data_rating_s3
span{width:15px;left:46px;background-position:-46px -221px}.s_sar_rating,
.s_sar_rating
span{height:21px}.s_sar_rating{width:59px;background-position:0 -274px}.s_sar_rating_s1
span{width:21px;background-position:0 -295px}.s_sar_rating_s2
span{width:18px;left:25px;background-position:-25px -295px}.s_sar_rating_s3
span{width:15px;left:46px;background-position:-46px -295px}.s_resolution_rating,
.s_resolution_rating
span{height:16px}.s_resolution_rating{width:61px;background-position:0 -242px}.s_resolution_rating_s1
span{width:21px;background-position:0 -258px}.s_resolution_rating_s2
span{width:18px;left:25px;background-position:-25px -258px}.s_resolution_rating_s3
span{width:14px;left:47px;background-position:-47px -258px}.s_cpu_rating,
.s_cpu_rating
span{height:17px}.s_cpu_rating{width:52px;background-position:0 -333px}.s_cpu_rating_s1
span{width:17px;background-position:0 -316px}.s_cpu_rating_s2
span{width:14px;left:22px;background-position:-22px -316px}.s_cpu_rating_s3
span{width:11px;left:41px;background-position:-41px -316px}.s_ram_rating,
.s_ram_rating
span{height:22px}.s_ram_rating{width:56px;background-position:0 -372px}.s_ram_rating_s1
span{width:15px;background-position:0 -350px}.s_ram_rating_s2
span{width:15px;left:23px;background-position:-23px -350px}.s_ram_rating_s3
span{width:12px;left:44px;background-position:-44px -350px}.s_pixel_density_rating,
.s_pixel_density_rating
span{height:19px}.s_pixel_density_rating{width:56px;background-position:0 -413px}.s_pixel_density_rating_s1
span{width:19px;background-position:0 -394px}.s_pixel_density_rating_s2
span{width:14px;left:25px;background-position:-25px -394px}.s_pixel_density_rating_s3
span{width:11px;left:45px;background-position:-45px -394px}.s_help_disabled_icon{display:none !important;}.gray_9 {color: #999 !important;}.s_f_11 {font-size: 11px;}.s_mb_15{display: none !important;}.s_mr_5 {
margin-right: 5px !important;
}
</style>
<div style='width:680px;overflow:hidden;'>
    <div style='float:left;width:240px;height:240px;overflow:hidden;'><?php echo $img; ?></div>
	<div style='float:left;width:420px;height:240px;overflow-y:scroll;overflow-x;'>
	<div id="phone_specificatons"><?php echo $infoset; ?></div></div>
</div>