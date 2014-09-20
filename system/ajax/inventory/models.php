<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);
	$manu = str_replace("@", "&", $_GET['manu']);

	$params = array($_GET['m']);
	$Main = MYSQL::QUERY('SELECT * FROM device_models WHERE m_manufacturer_id = ? ORDER BY m_name ASC', $params);
?>
        <div class="txt_dsb">
            <div class="txt_dsb_fix">
	            <input type="text" id="model_search" onKeyUp="SearchModel(event)" placeholder="Search Model...">
		        <div id="model_close" onClick="$('#model_search').val('');$('#model_close').removeClass('opened').addClass('closed');" class="close closed"></div>
	        </div>
        </div>
		<div class="side-divider"></div>
		<script>$("#model-list").listnav({showCounts: false});</script><center><div id="model-list-nav"></div></center><ul id="model-list">
<?php
	if(!empty($Main)){
	    foreach($Main as $a){
	        $newname = str_replace($manu." ", "", $a['m_name']);
			if(strlen($newname) > 25){
			    $newname_modded = substr($newname, 0, 25);
				$newname_modded = "$newname_modded...";
			} else {
			    $newname_modded = $newname;
			}
			$l = "'";
			if($a['m_link'] == ""){
			    $databutton = '';
			} else {
			    $databutton = '<div class="mod-info-button" onClick="ShowModInfo('.$l.''.$newname.''.$l.','.$l.''.$a['m_link'].''.$l.')"></div>';
			}
	        echo '
			    <li onClick="InventoryMod('.$a['m_id'].', '.$l.''.$newname.''.$l.', event)"><div class="sidebar-2" id="'.$a['m_name'].'"><div class="name" title="'.$newname.'" style="padding-left:10px;">'.$newname_modded.'</div>'.$databutton.'</div></li>
		    ';
	    }
	} else { echo '<li><div class="sidebar-2"><div class="name" style="width:100%;">No Models Found!</div></div></li>'; }
	echo '</ul>';
?>