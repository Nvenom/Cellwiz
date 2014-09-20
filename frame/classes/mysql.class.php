<?php
CLASS MYSQL{
    PUBLIC STATIC FUNCTION DB($P=''){
	    GLOBAL $MYSQL;
	    IF(EMPTY($MYSQL)){DIE("Error: 001B");}
		IF($P==''){$P=$MYSQL['db'];}
		STATIC $DB;
		IF(!$DB){
	        $DB = new PDO("mysql:host=".$MYSQL['host'].";dbname=$P", $MYSQL['user'], $MYSQL['pass']);
		}
		RETURN $DB;
	}
	
    PUBLIC STATIC FUNCTION QUERY($Q, $A=ARRAY(), $H=FALSE, $S=FALSE){
	    $PDO = self::DB();
	    IF(!EMPTY($Q)){
		    IF($H==FALSE){$A=ARRAY_MAP('htmlentities',$A);}
			$R = $PDO->prepare($Q);
			IF(EMPTY($A)){
			   $R->execute();
			} else {
			   $R->execute($A);
			}
			IF($R->errorCode()=="00000"){
			    $QE = EXPLODE(" ", $Q);
				IF($QE[0] == "INSERT"){
				    RETURN $PDO->lastInsertId(0);
				} ELSE IF($QE[0] == "UPDATE"){
                    RETURN TRUE;
				} ELSE {
			        $V = $R->fetchAll();
					IF(EMPTY($V)){
					    RETURN "";
					} ELSE {
				        IF(STRPOS($Q, "LIMIT 1")==FALSE || $S==TRUE){
				            RETURN $V;
				        } ELSE {
				            RETURN $V[0];
				        }
					}
				}
			} else {
			    ECHO "<PRE>";PRINT_R($R->errorInfo());DIE("</PRE>");
			}
		} ELSE {
		    DIE("Error: 001A");
		}
	}
}
?>