<?php
class Core{		
	public function db(){
	    $pdo = new PDO("mysql:host=localhost;dbname=tempmi5_core", 'tempmi5_admin', 'bradde121');
		return $pdo;
	}
	
    public function pdoQuery($pdo, $sql, $params = array(), $html = false) {
	    if(!$sql==""){
	        if($html == false){$params = array_map('htmlentities',$params);}
            $reg = $pdo->prepare($sql);
		    if(empty($params)){$reg->execute();}else{$reg->execute($params);}
			if($reg->errorCode() == "00000"){return $reg->fetchAll();} else {echo "<pre>";print_r($reg->errorInfo());die("</pre>");}
			echo $pdo->lastInsertId();
		} else {
		    die("Error: pdoQuery[2] Not Defined!");
		}
    }
	
	public function log($user, $message){
	    $date = Date("Y-m-d H:i:s");
		$db = $this->db();
		$params = array($user,$message,$_SERVER['REMOTE_ADDR'],$date);
		$user = $this->pdoQuery($db,'INSERT INTO core_users_log (user,message,ip,date) VALUES (?,?,?,?)',$params);
	}
	
	public function GenerateItem($ticket, $user, $Model, $part, $type, $release, $bp = false){
	    $db = $this->db();
	    $Item  = $this->pdoQuery($db, "SELECT * FROM device_parts WHERE p_id = ? LIMIT 1", array($part));
	    $Stock = $this->pdoQuery($db, "SELECT * FROM inventory_stock WHERE item = ? AND store = ? LIMIT 1", array($part,$user['store']));
		$Type  = $this->pdoQuery($db, "SELECT * FROM device_categories WHERE c_id = ? LIMIT 1", array($type));
		$yearm = Date("Y") - $release[2];
		if($yearm > 1){
		    $yearm = $yearm * 10;
		    if($yearm > 30){$yearm = 30;}
		} else { $yearm = 0; }
		$yearm = $typedata[0]['c_fee'] - $yearm;
		if(!$Model[0]['m_override'] == "0"){
			$price = explode("/",$Model[0]['m_override']);
		    if($price[0] == "plus"){$total = number_format((ceil($Stock[0]['price'] / 10) * 10) + ($yearm + $price[1]), 2, '.', '');}
		    else if($price[0] == "minus"){$total = number_format((ceil($Stock[0]['price'] / 10) * 10) + ($yearm - $price[1]), 2, '.', '');}
		    else if($price[0] == "equal"){$total = number_format((ceil($Stock[0]['price'] / 10) * 10) + $price[1], 2, '.', '');}
		} else {
			$total = number_format((ceil($Stock[0]['price'] / 10) * 10) + $yearm, 2, '.', '');
		}
		$total = $total - 0.01;
	    echo '
		    <div id="it-'.$part.'" data-price="'.$total.'">
		        <img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveEstimate($(this), '."'".$ticket."'".')" />
		        <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Item[0]['p_name'].'</font>
		        <font class="bname pprice">'.$total.'</font>
	            <br/>
	        </div>
		';
	}
	
	public function GenerateService($ticket, $user, $Model, $part, $type, $release){
	    $db      = $this->db();
		$Type    = $this->pdoQuery($db, "SELECT * FROM device_categories WHERE c_id = ? LIMIT 1", array($type));
		$Service = $this->pdoQuery($db, "SELECT * FROM device_services WHERE s_id = ?", array($part));
		$yearm = Date("Y") - $release[2];
		if($yearm > 1){
		    $yearm = $yearm * 10;
		    if($yearm > 30){$yearm = 30;}
		} else { $yearm = 0; }
		$price = explode("/",$Service[0]['s_price']);
		if($price[0] == "+"){$total = number_format($price[1] + ($Type[0]['c_fee'] - $yearm), 2, '.', '');$total = $total - 0.01;}
		else if($price[0] == "-"){$total = number_format($price[1] - ($Type[0]['c_fee'] - $yearm), 2, '.', '');$total = $total - 0.01;}
		else if($price[0] == "="){$total = $price[1];$total = $total - 0.01;}
	    echo '
		    <div id="sv-'.$part.'" data-price="'.$total.'">
		        <img src="../core/images/iks.png" border="0" style="float:left;padding:2px;cursor:pointer;" onClick="RemoveEstimate($(this), '."'".$ticket."'".')" />
		        <font class="aname" style="width:70%;border-bottom: 1px solid #E0E0E0;">'.$Service[0]['s_name'].'</font>
		        <font class="bname pprice">'.$total.'</font>
	            <br/>
	        </div>
		';
	}
	
	public function user(){
	    $db = $this->db();
		$params = array($_COOKIE['core_u']);
		$user = $this->pdoQuery($db,'SELECT * FROM core_users WHERE user_id=? LIMIT 1',$params);
		return $user;
	}
	
	public function clean_text($string){
	    $case = array(" ", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $return = str_replace($case, "", $string);
		$return = ucfirst(strtolower($return));
		return $return;
	}
	
	public function format_phone($phone)
    {
	    $phone = preg_replace("/[^0-9]/", "", $phone);
	    if(strlen($phone) == 7){
		    return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
	    }elseif(strlen($phone) == 10){
		    return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
	    }else{
		    return $phone;
		}
    }
	
	public function time_ago($tm,$rcs = 0) {
	    if (strpos($tm,':') !== false) {
	        $tm = strtotime($tm);
		}
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
    
        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= $this->time_ago($_tm);
        return $x;
    }
	
	public function Verify($level){
	    if(isset($_COOKIE['core_u'])){
		    if(isset($_COOKIE['core_k'])){
			    $db = $this->db();
			
			    $params = array($_COOKIE['core_u']);
			    $Main = $this->pdoQuery($db, 'SELECT session_key,session_experation FROM core_users_sessions WHERE session_user = ? LIMIT 1', $params);
				if(!empty($Main)){
				    if($_COOKIE['core_k'] == $Main[0]['session_key']){
					    if(time() < $Main[0]['session_experation']){
			                $params = array($_COOKIE['core_u']);
			                $Main = $this->pdoQuery($db, 'SELECT * FROM core_users WHERE user_id = ? LIMIT 1', $params);
                            if(!empty($Main)){
                                if($Main[0]['level'] >= $level){
									$params = array($Main[0]['store']);
			                        $Store = $this->pdoQuery($db, 'SELECT * FROM core_stores WHERE s_id = ? LIMIT 1', $params);
									date_default_timezone_set($Store[0]['s_timezone']);
									$Main[0]['store_info'] = $Store[0];
									return $Main[0];
							    } else {
							        die("You do not have the Account Level to View/Use this.");
							    }
                            } else {
					            unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					            header("Location: https://secure.cellwiz.net/new");
                            }
						} else {
					        unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					        header("Location: https://secure.cellwiz.net/new");						
						}
					} else {
					    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					    header("Location: https://secure.cellwiz.net/new/index.php?ses=failed");
					}
				} else {
					unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
				    header("Location: https://secure.cellwiz.net/new/index.php?ses=failed");
				}
			} else {
			    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
			    header("Location: https://secure.cellwiz.net/new");
			}
		} else {
		    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
		    header("Location: https://secure.cellwiz.net/new");
		}
	}
	
	public function AjaxVerify($level){
	    if(isset($_COOKIE['core_u'])){
		    if(isset($_COOKIE['core_k'])){
			    $db = $this->db();
			
			    $params = array($_COOKIE['core_u']);
			    $Main = $this->pdoQuery($db, 'SELECT session_key,session_experation FROM core_users_sessions WHERE session_user = ? LIMIT 1', $params);
				if(!empty($Main)){
				    if($_COOKIE['core_k'] == $Main[0]['session_key']){
					    if(time() < $Main[0]['session_experation']){
			                $params = array($_COOKIE['core_u']);
			                $Main = $this->pdoQuery($db, 'SELECT * FROM core_users WHERE user_id = ? LIMIT 1', $params);
                            if(!empty($Main)){
                                if($Main[0]['level'] >= $level){
							        $params = array($Main[0]['store']);
			                        $Store = $this->pdoQuery($db, 'SELECT * FROM core_stores WHERE s_id = ? LIMIT 1', $params);
									date_default_timezone_set($Store[0]['s_timezone']);
									$Main[0]['store_info'] = $Store[0];
									return $Main[0];
							    } else {
							        die(json_encode(array("NA" => "true")));
							    }
                            } else {
					            unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					            die(json_encode(array("NA" => "true")));
                            }
						} else {
					        unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					        die(json_encode(array("NA" => "true")));						
						}
					} else {
					    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
					    die(json_encode(array("NA" => "true")));
					}
				} else {
					unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
				    die(json_encode(array("NA" => "true")));
				}
			} else {
			    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
			    die(json_encode(array("NA" => "true")));
			}
		} else {
		    unset($_COOKIE['core_u']);unset($_COOKIE['core_k']);
		    die(json_encode(array("NA" => "true")));
		}
	}
}

class PasswordHash {
	var $itoa64;
	var $iteration_count_log2;
	var $portable_hashes;
	var $random_state;

	function PasswordHash($iteration_count_log2, $portable_hashes)
	{
		$this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
			$iteration_count_log2 = 8;
		$this->iteration_count_log2 = $iteration_count_log2;

		$this->portable_hashes = $portable_hashes;

		$this->random_state = microtime();
		if (function_exists('getmypid'))
			$this->random_state .= getmypid();
	}

	function get_random_bytes($count)
	{
		$output = '';
		if (is_readable('/dev/urandom') &&
		    ($fh = @fopen('/dev/urandom', 'rb'))) {
			$output = fread($fh, $count);
			fclose($fh);
		}

		if (strlen($output) < $count) {
			$output = '';
			for ($i = 0; $i < $count; $i += 16) {
				$this->random_state =
				    md5(microtime() . $this->random_state);
				$output .=
				    pack('H*', md5($this->random_state));
			}
			$output = substr($output, 0, $count);
		}

		return $output;
	}

	function encode64($input, $count)
	{
		$output = '';
		$i = 0;
		do {
			$value = ord($input[$i++]);
			$output .= $this->itoa64[$value & 0x3f];
			if ($i < $count)
				$value |= ord($input[$i]) << 8;
			$output .= $this->itoa64[($value >> 6) & 0x3f];
			if ($i++ >= $count)
				break;
			if ($i < $count)
				$value |= ord($input[$i]) << 16;
			$output .= $this->itoa64[($value >> 12) & 0x3f];
			if ($i++ >= $count)
				break;
			$output .= $this->itoa64[($value >> 18) & 0x3f];
		} while ($i < $count);

		return $output;
	}

	function gensalt_private($input)
	{
		$output = '$P$';
		$output .= $this->itoa64[min($this->iteration_count_log2 +
			((PHP_VERSION >= '5') ? 5 : 3), 30)];
		$output .= $this->encode64($input, 6);

		return $output;
	}

	function crypt_private($password, $setting)
	{
		$output = '*0';
		if (substr($setting, 0, 2) == $output)
			$output = '*1';

		$id = substr($setting, 0, 3);
		if ($id != '$P$' && $id != '$H$')
			return $output;

		$count_log2 = strpos($this->itoa64, $setting[3]);
		if ($count_log2 < 7 || $count_log2 > 30)
			return $output;

		$count = 1 << $count_log2;

		$salt = substr($setting, 4, 8);
		if (strlen($salt) != 8)
			return $output;

		if (PHP_VERSION >= '5') {
			$hash = md5($salt . $password, TRUE);
			do {
				$hash = md5($hash . $password, TRUE);
			} while (--$count);
		} else {
			$hash = pack('H*', md5($salt . $password));
			do {
				$hash = pack('H*', md5($hash . $password));
			} while (--$count);
		}

		$output = substr($setting, 0, 12);
		$output .= $this->encode64($hash, 16);

		return $output;
	}

	function gensalt_extended($input)
	{
		$count_log2 = min($this->iteration_count_log2 + 8, 24);
		$count = (1 << $count_log2) - 1;

		$output = '_';
		$output .= $this->itoa64[$count & 0x3f];
		$output .= $this->itoa64[($count >> 6) & 0x3f];
		$output .= $this->itoa64[($count >> 12) & 0x3f];
		$output .= $this->itoa64[($count >> 18) & 0x3f];

		$output .= $this->encode64($input, 3);

		return $output;
	}

	function gensalt_blowfish($input)
	{
		$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$output = '$2a$';
		$output .= chr(ord('0') + $this->iteration_count_log2 / 10);
		$output .= chr(ord('0') + $this->iteration_count_log2 % 10);
		$output .= '$';

		$i = 0;
		do {
			$c1 = ord($input[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;
			if ($i >= 16) {
				$output .= $itoa64[$c1];
				break;
			}

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);

		return $output;
	}

	function HashPassword($password)
	{
		$random = '';

		if (CRYPT_BLOWFISH == 1 && !$this->portable_hashes) {
			$random = $this->get_random_bytes(16);
			$hash =
			    crypt($password, $this->gensalt_blowfish($random));
			if (strlen($hash) == 60)
				return $hash;
		}

		if (CRYPT_EXT_DES == 1 && !$this->portable_hashes) {
			if (strlen($random) < 3)
				$random = $this->get_random_bytes(3);
			$hash =
			    crypt($password, $this->gensalt_extended($random));
			if (strlen($hash) == 20)
				return $hash;
		}

		if (strlen($random) < 6)
			$random = $this->get_random_bytes(6);
		$hash =
		    $this->crypt_private($password,
		    $this->gensalt_private($random));
		if (strlen($hash) == 34)
			return $hash;
		return '*';
	}

	function CheckPassword($password, $stored_hash)
	{
		$hash = $this->crypt_private($password, $stored_hash);
		if ($hash[0] == '*')
			$hash = crypt($password, $stored_hash);

		return $hash == $stored_hash;
	}
}
?>