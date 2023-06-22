<?php
    require_once '../../config/conf.php';
    header("Content-Type: text/html;charset=UTF-8");
	
	if(isset($_SERVER["REMOTE_ADDR"])) $ipaddr = $_SERVER["REMOTE_ADDR"];
	
	function wh_log($log_msg) {
		$log_filename = $_SERVER['DOCUMENT_ROOT']."/log";
		if (!file_exists($log_filename))
		{
			// create directory/folder uploads.
			mkdir($log_filename, 0777, true);
		}
		$log_file_data = $log_filename.'/log_' . date('Ymd') . '.log';
		file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
	}
	
	if(empty($ipaddr) || empty($_POST['token']) || empty($_POST['user_name'])) {
		wh_log("[error] ".date("Y-m-d H:i:s")." No access token.");
		echo "{\"result\":\"error\",\"error_message\":\"No access token.\"}";
	} else {
		wh_log("[info] ".date("Y-m-d H:i:s")." Parameters : REMOTE_ADDR=".$ipaddr.", token=".$_POST['token'].", user_name=".$_POST['user_name']);
		$db = new DBC; 

		$encodedText = $_POST['token'];
		$encodedText = base64_decode($encodedText);
		$user_id = openssl_decrypt($encodedText, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		$auth = explode('|', $user_id, 2);
		$today =  date("Ymd");
		
		if(count($auth) !== 2 || $today !== $auth[0] || empty($auth[1]) || "null" === $auth[1]) {
			wh_log("[error] ".date("Y-m-d H:i:s")." Invalid access token.");
			echo "{\"result\":\"error\",\"error_message\":\"Invalid access token.\"}";
			return;
		}
		
		$encodedText = $_POST['user_name'];
		$encodedText = base64_decode($encodedText);
		$user_name = openssl_decrypt($encodedText, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		
		$db->db_conn();
		
		$sql = "SELECT user_type, homepage, seller_id, cryptkey, period FROM users WHERE user_id = ?";
		$stmt = $db->conn->prepare($sql);
		if($stmt 
			&& $stmt->bind_param("s", $auth[1]) 
			&& $stmt->execute()
			&& $stmt->store_result() 
			&& $stmt->bind_result($user_type, $homepage, $seller_id, $cryptkey, $period)
		) {
			if($stmt->fetch()) {
				if($user_type === 1 || $user_type === 2) {
					wh_log("[info] ".date("Y-m-d H:i:s")." user_id=".$auth[1].", user_name=".$user_name.", user_type=".$user_type.", homepage=".$homepage.", period=".$period.", cryptkey=".$cryptkey);
					echo "{\"result\":\"success\",".
						"\"user_type\":".$user_type.",".
						"\"homepage\":\"".$homepage."\",".
						"\"seller_id\":\"".$seller_id."\",",
						"\"period\":\"".$period."\",",
						"\"cryptkey\":\"".$cryptkey."\"}";
				} else {
					wh_log("[info] ".date("Y-m-d H:i:s")." ".$auth." is not registered.");
					echo "{\"result\":\"error\",\"error_message\":\"You are not registered. Please contact to developer.\"}";
				}
				$stmt->close();
				
				$sql = "INSERT INTO login_history(user_id, login_type, ipaddr) VALUES (?,?,?)";
				$stmt = $db->conn->prepare($sql);
				if($stmt 
					&& $stmt->bind_param("sss", $auth[1], $user_type, $ipaddr)
					&& $stmt->execute()
				) {
					$success = true;
				} else {
					wh_log("[error] ".date("Y-m-d H:i:s")." SQL-ERROR : ".$db->get_error());
				}
			} else {
				$stmt->close();
				
				$sql = "INSERT INTO users(user_id, user_name) VALUES (?,?)";
				$stmt = $db->conn->prepare($sql);
				if($stmt 
					&& $stmt->bind_param("ss", $auth[1], $user_name)
					&& $stmt->execute()
				) {
					wh_log("[info] ".date("Y-m-d H:i:s")." insert user : user_id=".$auth[1].", user_name=".$user_name);
					$success = true;
				} else {
					wh_log("[error] ".date("Y-m-d H:i:s")." SQL-ERROR : ".$db->get_error());
				}
				echo "{\"result\":\"error\",\"error_message\":\"You are not registered. Please contact to developer.\"}";
			}
		} else {
			wh_log("[error] ".date("Y-m-d H:i:s")." SQL-ERROR : ".$db->get_error());
			echo "{\"result\":\"error\",\"error_message\":\"Cannot find user information. Please contact to developer.\"}";
		}
		
		$stmt->close();
		$db->db_close();
	}
?>