<?php
CLASS TRACKING{
    PUBLIC STATIC FUNCTION ADVERT($METHOD, $USER, $PM='+'){
	    $D = Date("Y-m-d");
	    MYSQL::QUERY("INSERT INTO core_stores_daily_admethods (s_id, a_id, d_key, d_date) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + 1;", ARRAY($USER['store'], $METHOD, 1, $D));
	}
	
	PUBLIC STATIC FUNCTION CUSTOMERS($USER, $PM='+'){
	    $D = Date("Y-m-d");
	    MYSQL::QUERY("INSERT INTO core_stores_daily_customers (s_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + 1;", ARRAY($USER['store'], 1, $D));
	}
	
	PUBLIC STATIC FUNCTION TICKETS($DEV, $USER, $PM='+'){
	    $D = Date("Y-m-d");
		$QUERY = "INSERT INTO core_stores_daily_devices (s_id, d_key, m_id, d_date) VALUES";
		$PARAMS = ARRAY();
		$I=0;FOREACH($DEV AS $DE){
		    IF($I==0){$QUERY.=' (?,?,?,?)';}ELSE{$QUERY.=',(?,?,?,?)';}
			ARRAY_PUSH($PARAMS, $USER['store'], 1, $DE, $D);
			$I++;
		}
		$QUERY .= " ON DUPLICATE KEY UPDATE d_key = d_key + 1";
		MYSQL::QUERY($QUERY,$PARAMS);
	    MYSQL::QUERY("INSERT INTO core_stores_daily_tickets (s_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + ?", ARRAY($USER['store'], $I, $D, $I));
	}
	
	PUBLIC STATIC FUNCTION CHECKOUTS($COST, $TAXABLE, $TAX, $USER, $M1, $M1COST, $M2, $M2COST, $PM='+', $D=''){
	    IF($D==''){
	        $D = DATE("Y-m-d");
		} ELSE {
		    $D = EXPLODE(" ",$D);
		    $D = $D[0];
		}
		$CASH=0;$CHECK=0;$AMEX=0;$DISCOVER=0;$MASTER=0;$VISA=0;$DEBIT=0;
		IF($M1 == "Cash"){$CASH += $M1COST;} IF($M2 == "Cash"){$CASH += $M2COST;}
		IF($M1 == "Check"){$CHECK += $M1COST;} IF($M2 == "Check"){$CHECK += $M2COST;}
		IF($M1 == "American Express"){$AMEX += $M1COST;} IF($M2 == "American Express"){$AMEX += $M2COST;}
		IF($M1 == "Discover"){$DISCOVER += $M1COST;} IF($M2 == "Discover"){$DISCOVER += $M2COST;}
		IF($M1 == "MasterCard"){$MASTER += $M1COST;} IF($M2 == "MasterCard"){$MASTER += $M2COST;}
		IF($M1 == "Visa"){$VISA += $M1COST;} IF($M2 == "Visa"){$VISA += $M2COST;}
		IF($M1 == "Debit Card"){$DEBIT += $M1COST;} IF($M2 == "Debit Card"){$DEBIT += $M2COST;}
		$QUERY = "INSERT INTO core_stores_daily_checkouts (s_id, d_key, d_gross_nontaxable, d_gross_taxable, d_tax, d_cash, d_check, d_amex, d_discover, d_master, d_visa, d_debit, d_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
		 ON DUPLICATE KEY UPDATE d_key=d_key".$PM."1,d_gross_nontaxable=d_gross_nontaxable".$PM."?,d_gross_taxable=d_gross_taxable".$PM."?,d_tax=d_tax".$PM."?,d_cash=d_cash".$PM."?,d_check=d_check".$PM."?,d_amex=d_amex".$PM."?,d_discover=d_discover".$PM."?,d_master=d_master".$PM."?,d_visa=d_visa".$PM."?,d_debit=d_debit".$PM."?";
		MYSQL::QUERY($QUERY, ARRAY($USER['store'], 1, $COST, $TAXABLE, $TAX, $CASH, $CHECK, $AMEX, $DISCOVER, $MASTER, $VISA, $DEBIT, $D, $COST, $TAXABLE, $TAX, $CASH, $CHECK, $AMEX, $DISCOVER, $MASTER, $VISA, $DEBIT));
	}
	
	PUBLIC STATIC FUNCTION WALKOUTS($USER, $PM='+'){
	    $D = Date("Y-m-d");
	    MYSQL::QUERY("INSERT INTO core_stores_daily_walkouts (s_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + 1;", ARRAY($USER['store'], 1, $D));
	}
	
	PUBLIC STATIC FUNCTION REPAIRS($ITEMS, $USER, $PM='+'){
	    $D = Date("Y-m-d");
	    MYSQL::QUERY("INSERT INTO core_stores_daily_repairs (s_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + 1;", ARRAY($USER['store'], 1, $D));
	}
	
	PUBLIC STATIC FUNCTION ACCEPTS($USER, $PM='+'){
	    $D = Date("Y-m-d");
	    MYSQL::QUERY("INSERT INTO core_stores_daily_accepts (s_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + 1;", ARRAY($USER['store'], 1, $D));
	}
}
?>