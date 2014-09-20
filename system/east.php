<script>
    $(function() {
		$("#inventory-list").accordion({fillSpace: true, icons: null});
        $("#inventory-list div").bind("mousewheel",function(ev, delta) {
            var scrollTop = $(this).scrollTop();
            $(this).scrollTop(scrollTop-Math.round(delta * 50));
        });
	});
</script>
<div id="inventory-list" style='overflow: hidden !important;'>
	<h3><a href="#">Manufacturer</a></h3>
	<div id='inventory-manufacturer' style='overflow: hidden !important;border-top: 1px solid silver !important;'>
	    <center><div id="manufacturer-list-nav"></div></center>
		<ul id='manufacturer-list'>
		    <?php
	            $R = MYSQL::QUERY('SELECT m_id,m_name FROM device_manufacturers ORDER BY m_name ASC');
	            if(!empty($R)){
	                foreach($R as $a){
					    $manname = "'".$a['m_name']."'";
					    echo '
						    <li onClick="InventoryMan('.$a['m_id'].', '.$manname.')"><div class="sidebar-2" id="'.$a['m_name'].'"><div class="name" style="padding-left:10px;">'.$a['m_name'].'</div></div></li>
						';
					}
				} else { echo "Error Pulling Manufacturers!"; }
			?>
		</ul>
	</div>
	<h3><a href="#" id='ModMod'>Model</a></h3>
	<div id='inventory-model' style='overflow: hidden !important;border-top: 1px solid silver !important;'>
        <div class="txt_dsb">
            <div class="txt_dsb_fix">
	            <input type="text" id="model_search" onKeyUp="SearchModel(event)" placeholder="Search Model...">
		        <div id="model_close" onClick="$('#model_search').val('');$('#model_close').removeClass('opened').addClass('closed');" class="close closed"></div>
	        </div>
        </div>
		<div class="side-divider"></div>
	</div>
	<h3><a href="#" id='PartParts'>Parts</a></h3>
	<div id='inventory-part' style='overflow: hidden !important;border-top: 1px solid silver !important;'>

	</div>
	<h3><a href="#" id='ServiceServices'>Services</a></h3>
	<div id='inventory-services' style='overflow: hidden !important;border-top: 1px solid silver !important;'>
        <ul>
		    <?php
	            $R = MYSQL::QUERY('SELECT * FROM device_services ORDER BY s_name ASC');
	            if(!empty($R)){
	                foreach($R as $a){
					    echo '
						    <li>
				                <div class="sidebar-2 draggableservice" id="sv-'.$a['s_id'].'" style="height:auto !important;">
					                <div class="name" style="padding-left:10px;width:235px;">'.$a['s_name'].'</div>
				                </div>
			                </li>
						';
					}
				} else { echo "Error Pulling Services!"; }
			?>
		</ul>
	</div>
	<h3><a href="#" id='AccessAcessories'>Additional</a></h3>
	<div id='inventory-accessories' style='overflow: hidden !important;border-top: 1px solid silver !important;'>
        <ul>
		    <?php
	            $R = MYSQL::QUERY('SELECT * FROM device_accessories ORDER BY a_name ASC');
	            if(!empty($R)){
	                foreach($R as $a){
					    echo '
						    <li>
				                <div class="sidebar-2 draggableaccessories" id="ac-'.$a['a_id'].'" style="height:auto !important;">
					                <div class="name" style="padding-left:10px;width:235px;">'.$a['a_name'].'</div>
				                </div>
			                </li>
						';
					}
				} else { echo "Error Pulling Additional Items!"; }
			?>
		</ul>
	</div>
</div>
<script>
    $(".draggableservice").draggable({ revert: "false", helper: "clone", start: function(event, ui) { $(this).css("z-index", "99999"); }, appendTo: "body" });
    $(".draggableaccessories").draggable({ revert: "false", helper: "clone", start: function(event, ui) { $(this).css("z-index", "99999"); }, appendTo: "body" });
</script>