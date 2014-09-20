<?php 
    require("../../../frame/engine.php");ENGINE::START();
    $user = USER::VERIFY(0,TRUE);

	$t = "'";
	$params = array($_GET['m']);
	$Main = MYSQL::QUERY('SELECT * FROM device_parts WHERE p_model_id = ? AND p_entry = 1 ORDER BY p_name ASC', $params);
?>
<script>
function FilterParts() {
    var list = $("#part-list");
    var filter = $("#part_search").val().toUpperCase();
    if (filter) {
      $(list).find(".name:not(:contains(" + filter + "))").parent().parent().slideUp();
      $(list).find(".name:contains(" + filter + ")").parent().parent().slideDown();
	  $('#part_close').addClass('opened').removeClass('closed');
	  $('.ln-no-match').hide();
    } else {
      $(list).find("li").slideDown();
	  $('.ln-no-match').hide();
    }
}
</script>
        <div class="txt_dsb">
            <div class="txt_dsb_fix">
	            <input type="text" id="part_search" onKeyUp="FilterParts()" placeholder="Filter Parts...">
		        <div id="part_close" onClick="$('#part_search').val('');$('#part_close').removeClass('opened').addClass('closed');FilterParts();" class="close closed"></div>
	        </div>
        </div>
		<div class="side-divider"></div>
		<script>$("#part-list").listnav({showCounts: false});</script><center><div id="part-list-nav"></div></center><ul id="part-list">
<?php
	if(!empty($Main)){
	    foreach($Main as $a){
			$l = "'";
			if($user['level'] > 0){$fun = 'onClick="InventoryEdit('.ltrim($a['p_id'], '0').','.$t.$a['p_name'].$t.')"';} else {$fun = '';}
	        echo '
			    <li '.$fun.'>
				    <div class="sidebar-2 draggableitem'.$_GET['m'].'" id="it-'.$a['p_id'].'" style="height:auto !important;">
					    <div class="name" style="padding-left:10px;width:235px;">
						    '.strtoupper($a['p_name']).'
						</div>
				    </div>
				</li>
		    ';
	    }
	} else { echo '<li><div class="sidebar-2"><div class="name" style="width:100%;">No Parts Found!</div></div></li>'; }
	if($user['level'] > 0){
	    echo '<li onClick="AddItem('.$_GET['m'].')">
			<div class="sidebar-2"style="height:auto !important;" title="Click to add a new part to this model">
				<div class="name" style="padding-left:10px;width:235px;">
				    Add New Part...
			    </div>
		    </div>
		</li>';
	}
	echo '</ul><script>$(".draggableitem'.$_GET['m'].'").draggable({ revert: "false", helper: "clone", start: function(event, ui) { $(this).css("z-index", "99999"); }, appendTo: "body" });</script>';
?>