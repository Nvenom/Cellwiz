<?php
CLASS FORMAT{
    PUBLIC STATIC FUNCTION PHONE($N){
	    $N = PREG_REPLACE("/[^0-9]/", "", $N);
	    IF(STRLEN($N) == 7){
		    RETURN PREG_REPLACE("/([0-9]{3})([0-9]{4})/", "$1-$2", $N);
	    }ELSE IF(STRLEN($N) == 10){
		    RETURN PREG_REPLACE("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $N);
	    }ELSE{
		    RETURN $N;
		}
	}
	
	PUBLIC STATIC FUNCTION TIME_AGO($T,$R){
	    IF(STRPOS($T,':')!==FALSE) {
	        $T = STRTOTIME($T);
		}
        $C = TIME(); $D = $C-$T;
        $P = ARRAY('second','minute','hour','day','week','month','year','decade');
        $L = ARRAY(1,60,3600,86400,604800,2630880,31570560,315705600);
        FOR($V = SIZEOF($L)-1; ($V >= 0)&&(($N = $D/$L[$V])<=1); $V--); if($V < 0) $V = 0; $_T = $C-($D%$L[$V]);
    
        $N = FLOOR($N); IF($N <> 1) $P[$V] .='s'; $X=SPRINTF("%d %s ",$N,$P[$V]);
        IF(($R == 1)&&($V >= 1)&&(($C-$_T) > 0)) $X .= self::TIME_AGO($_T);
        RETURN $X;
	}
	
	PUBLIC STATIC FUNCTION TEXT($S){
	    $C = ARRAY(" ", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
		RETURN UCFIRST(STRTOLOWER(STR_REPLACE($C, "", $S)));
	}
	
	PUBLIC STATIC FUNCTION SES($L=10){
	    RETURN SUBSTR(STR_SHUFFLE("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $L);
	}
	
	PUBLIC STATIC FUNCTION CHECKLIST($LIST){
	    $EXPORT="<table><tbody><tr><td></td><td><b>IN</b></td><td><b>OUT</b></td></tr>";
		$COMPARE = ARRAY(
		    "Simcard Included", 
			"SD Card Included", 
			"Case Included", 
			"Charger Included", 
			"Phone Powers On", 
			"All Buttons Functioning", 
			"Internal Audio Working", 
			"External Audio Working", 
			"Digitizer/Touch Working", 
			"Damage Housing", 
			"Device Charging", 
			"Service/Wifi/Bluetooth"
		);
		$I = 0;
	    FOREACH($LIST AS $ITEM){
		    $ITEM = EXPLODE(".",$ITEM);
			IF($ITEM[0] == "0"){$ITEM[0] = "No";} ELSE {$ITEM[0] = "Yes";}
			IF($ITEM[1] == "0"){$ITEM[1] = "No";} ELSE {$ITEM[1] = "Yes";}
			$EXPORT .= "<tr><td><b>".$COMPARE[$I]."</b></td><td>".$ITEM[0]."</td><td>".$ITEM[1]."</td></tr>";
			$I++;
		}
		$EXPORT .= "</tbody></table>";
		RETURN $EXPORT;
	}
}
?>