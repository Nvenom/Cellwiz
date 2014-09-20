<?php
CLASS USER{
    PUBLIC STATIC FUNCTION INFO($U=''){
	    IF($U==''){$U = $_COOKIE['core_u'];}
		$I = MYSQL::QUERY('SELECT * FROM core_users WHERE user_id=? LIMIT 1',array($U));
		return $I;
	}
	
    PUBLIC STATIC FUNCTION VERIFY($L,$A=FALSE){
	    $F = FALSE;
	    $U = $_COOKIE['core_u'];
		$K = $_COOKIE['core_k'];
	    IF(ISSET($U) && ISSET($K)){
			$R = MYSQL::QUERY('SELECT session_key,session_experation,qas_time FROM core_users_sessions WHERE session_user = ? LIMIT 1', array($U));
			IF(!EMPTY($R) && $K == $R['session_key'] && TIME() < $R['session_experation']){
			    $R = MYSQL::QUERY('SELECT * FROM core_users WHERE user_id = ? LIMIT 1', array($U));
                IF(!EMPTY($R) && $R['level'] >= $L){
			        $S = MYSQL::QUERY('SELECT * FROM core_stores WHERE s_id = ? LIMIT 1', array($R['store']));
					DATE_DEFAULT_TIMEZONE_SET($S['s_timezone']);
					$R['store_info'] = $S;
					RETURN $R;
				} else {$F=TRUE;}
            } ELSE {
			    IF($K == $R['session_key']){
				    $F=TRUE;
				} ELSE {
				    die(json_encode(array("NA" => "qas")));
				}
			}
		} else {$F=TRUE;}
		IF($F==TRUE){
		    IF($A==FALSE){
			    setcookie("core_u", "", time() - 100000, '/');setcookie("core_k", "", time() - 100000, '/');
			    header("Location: https://secure.cellwiz.net/new");
			} ELSE {
			    die(json_encode(array("NA" => "true")));
			}
        }
	}
	
	PUBLIC STATIC FUNCTION LOG($M,$U=''){
	    IF($U==''){$U = $_COOKIE['core_u'];}
	    $D = Date("Y-m-d H:i:s");
		$P = array($U,$M,$_SERVER['REMOTE_ADDR'],$D);
		MYSQL::QUERY('INSERT INTO core_users_log (user,message,ip,date) VALUES (?,?,?,?)',$P);
	}
	
	PUBLIC STATIC FUNCTION NOTE($T,$N,$Y,$U=''){
	    IF($U==''){$U = $_COOKIE['core_u'];}
	    $D = Date("Y-m-d H:i:s");
		$P = array($T,$N,$U,$Y,$D);
		MYSQL::QUERY('INSERT IGNORE INTO core_tickets_note (t_id,t_note,t_note_by,t_type,t_date) VALUES (?,?,?,?,?)',$P);
	}
	
	PUBLIC STATIC FUNCTION STAT($STAT,$A=1,$U=''){
	    IF($U==''){$U = $_COOKIE['core_u'];}
		$DATE = Date("Y-m-d");
		$MONTH = Date("Y-m-00");
		MYSQL::QUERY("INSERT INTO core_users_".$STAT."_daily (u_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + ?;", ARRAY($U, $A, $DATE, $A));
		MYSQL::QUERY("INSERT INTO core_users_".$STAT."_monthly (u_id, d_key, d_date) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE d_key = d_key + ?;", ARRAY($U, $A, $MONTH, $A));
		IF($A > 0){
		    MYSQL::QUERY("UPDATE core_users SET total_".$STAT."=total_".$STAT."+? WHERE user_id=? LIMIT 1", ARRAY($A, $U));
		}
	}
	
	PUBLIC STATIC FUNCTION MEDAL($COLOR,$AMOUNT,$U=''){
	    IF($U==''){$U = $_COOKIE['core_u'];}
	    MYSQL::QUERY('UPDATE core_users SET '.$COLOR.'_medals = '.$COLOR.'_medals + ? WHERE user_id=? LIMIT 1',ARRAY($AMOUNT,$U));
	}
	
	PUBLIC STATIC FUNCTION REWARD(){
		IF(DATE('D') == 'Mon'){$INT = '-2 days';} ELSE {$INT = '-1 day';}
	    $D = DATE('Y-m-d', STRTOTIME($INT, TIME()));
		$DAY = DATE('d') - 0;
		IF($DAY==1){
		    $M = DATE('Y-m-00', STRTOTIME('-1 month', TIME()));
            $R = MYSQL::QUERY('SELECT * FROM core_users_leaderboard_rewards WHERE d_date=? AND r_id=? LIMIT 1',ARRAY($M,5));
		    IF(EMPTY($R)){
			    $MONTH = DATE('F', STRTOTIME('-1 month', TIME()));
				$DATE = DATE("Y-m-d H:i:s");
			    $RD = MYSQL::QUERY('SELECT * FROM core_users_repairs_monthly WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($M));
			    $TD = MYSQL::QUERY('SELECT * FROM core_users_tickets_monthly WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($M));
			    $CD = MYSQL::QUERY('SELECT * FROM core_users_checkouts_monthly WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($M));
			    $ED = MYSQL::QUERY('SELECT * FROM core_users_estimates_monthly WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($M));
			    MYSQL::QUERY('INSERT IGNORE INTO core_users_leaderboard_rewards (r_id,u_id,reward,d_date) VALUES (?,?,?,?),(?,?,?,?),(?,?,?,?),(?,?,?,?)',ARRAY(5,$RD['u_id'],'0-1-0',$D,6,$TD['u_id'],'0-1-0',$D,7,$CD['u_id'],'0-1-0',$D,8,$ED['u_id'],'0-1-0',$D));
				MYSQL::QUERY('INSERT INTO core_messages (m_to,m_from,m_message,m_from_avatar,m_sent) VALUES (?,?,?,?,?),(?,?,?,?,?),(?,?,?,?,?),(?,?,?,?,?)',ARRAY(
			        $RD['u_id'],'LeaderBoard Reward','You have earned 1 Silver Medal for the most Repairs in '.$MONTH,'02',$DATE,
				    $TD['u_id'],'LeaderBoard Reward','You have earned 1 Silver Medal for the most Tickets in '.$MONTH,'02',$DATE,
				    $CD['u_id'],'LeaderBoard Reward','You have earned 1 Silver Medal for the most Checkouts in '.$MONTH,'02',$DATE,
				    $ED['u_id'],'LeaderBoard Reward','You have earned 1 Silver Medal for the most Estimates in '.$MONTH,'02',$DATE
			    ));
				self::MEDAL('silver',1,$RD['u_id']);
				self::MEDAL('silver',1,$TD['u_id']);
				self::MEDAL('silver',1,$CD['u_id']);
				self::MEDAL('silver',1,$ED['u_id']);
		    }
		}
		$R = MYSQL::QUERY('SELECT * FROM core_users_leaderboard_rewards WHERE d_date=? AND r_id=? LIMIT 1',ARRAY($D,1));
		IF(EMPTY($R)){
		    $DATE = DATE("Y-m-d H:i:s");
			$RD = MYSQL::QUERY('SELECT * FROM core_users_repairs_daily WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($D));
			$TD = MYSQL::QUERY('SELECT * FROM core_users_tickets_daily WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($D));
			$CD = MYSQL::QUERY('SELECT * FROM core_users_checkouts_daily WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($D));
			$ED = MYSQL::QUERY('SELECT * FROM core_users_estimates_daily WHERE d_date=? ORDER BY d_key DESC LIMIT 1',ARRAY($D));
			MYSQL::QUERY('INSERT IGNORE INTO core_users_leaderboard_rewards (r_id,u_id,reward,d_date) VALUES (?,?,?,?),(?,?,?,?),(?,?,?,?),(?,?,?,?)',ARRAY(1,$RD['u_id'],'0-0-35',$D,2,$TD['u_id'],'0-0-35',$D,3,$CD['u_id'],'0-0-35',$D,4,$ED['u_id'],'0-0-35',$D));
			MYSQL::QUERY('INSERT INTO core_messages (m_to,m_from,m_message,m_from_avatar,m_sent) VALUES (?,?,?,?,?),(?,?,?,?,?),(?,?,?,?,?),(?,?,?,?,?)',ARRAY(
			    $RD['u_id'],'LeaderBoard Reward','You have earned 35 Bronze Medals for the most Repairs on '.$D,'01',$DATE,
				$TD['u_id'],'LeaderBoard Reward','You have earned 35 Bronze Medals for the most Tickets on '.$D,'01',$DATE,
				$CD['u_id'],'LeaderBoard Reward','You have earned 35 Bronze Medals for the most Checkouts on '.$D,'01',$DATE,
				$ED['u_id'],'LeaderBoard Reward','You have earned 35 Bronze Medals for the most Estimates on '.$D,'01',$DATE
			));
			self::MEDAL('bronze',35,$RD['u_id']);
			self::MEDAL('bronze',35,$TD['u_id']);
			self::MEDAL('bronze',35,$CD['u_id']);
			self::MEDAL('bronze',35,$ED['u_id']);
		}
	}
}
?>