<?php
ini_set('max_execution_time', 120);
require("../../frame/engine.php");ENGINE::START();
$user = USER::VERIFY(0);
include('simple_html_dom.php');

$Main = MYSQL::QUERY('SELECT * FROM device_manufacturers');
$options = '';
foreach($Main as $a){
    $options .= "<option value='".$a['m_name'].",".$a['m_id']."'>".$a['m_name']."</option>";
}
$added = "";
if(isset($_POST['submit'])){
    $man = explode(",", $_POST['manu']);
	$man[0] = str_replace(" ", "+", $man[0]); 
	$type = 1;
	while($type < 5){
	    $a = 0;
	    while($a <= 100){
	        $query = 'INSERT IGNORE INTO device_models (m_manufacturer_id, m_type, m_name, m_link, m_known, m_date) VALUES';
        
		    if($type == 1){$r = '/Smart';} elseif ($type == 2){$r = '/Feature,Basic';} elseif ($type == 3){$r = '/Tablet';} elseif ($type == 4){$r = '';}
            $url = "http://www.phonearena.com/phones/manufacturer/".$man[0]."/view/list/page/".$a."/Class".$r;
            $html = file_get_html($url);
	        $i = 1;
            foreach($html->find('div.s_block_1') as $section){
			    $known = "";
			    $othernames = "";
			    $also = "";
                foreach($section->find('*') as $node){
	                if($node->tag=='blockquote'){
					    foreach($node->find('h3') as $namedirty){
		                    $name = $namedirty->plaintext;
				            $name = str_replace('"', "'", $name);
						}
						foreach($node->find('p.s_f_14') as $known){
							foreach($known->find('strong') as $othernames){
							    $also = $othernames;
                                $also = str_replace("<strong>", "", $also);
                                $also = str_replace("</strong>", "", $also);									
						    }
			            }
		            }
		            if($node->tag=='a'){
		                $img = $node->href;
		            }
					if($node->tag=='p'){
					    foreach($node->find('span') as $date){
						    $date = $date->plaintext;
						}
					}
	            }
		        if($i == 1){$comma = ' ';} else {$comma = ', ';}
		        $added .= "$name<br/><br/>";
		        $query .= $comma.'("'.$man[1].'", "'.$type.'", "'.mysql_escape_string($name).'", "'.mysql_escape_string($img).'", "'.mysql_escape_string($also).'", "'.$date.'")';
		        $i++;
            }
	        if($query == 'INSERT IGNORE INTO device_models (m_manufacturer_id, m_type, m_name, m_link, m_known, m_date) VALUES'){break;}
	        MYSQL::QUERY($query);
		    $a++;
	    }
	if($type == 4){break;}
	$type++;
	}
}
?>
<form method='post'>
<select name='manu'><?php echo $options;?></select><input type='submit' name='submit' value='submit'>
</form>
<?php echo $added;?>
  </body>
</html>