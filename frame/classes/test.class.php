<?php
CLASS TEST{
    PUBLIC STATIC FUNCTION SCRIPT($FUNCTION,$PARAMS){
	    $mtime = microtime(); 
        $mtime = explode(' ', $mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $starttime = $mtime; 
		
		call_user_func($FUNCTION,$PARAMS);
	
	    $mtime = microtime(); 
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0]; 
        $endtime = $mtime; 
        $totaltime = ($endtime - $starttime); 
        echo '<script>console.log("Function ['.$FUNCTION.'] took '.$totaltime.' seconds")</script>';
	}
}
?>