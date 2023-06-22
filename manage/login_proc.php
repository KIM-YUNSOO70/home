<?php
    session_start();
    require_once '../../config/conf.php';

    header("Content-Type: text/html;charset=UTF-8");
	
    function xss_filter($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$user_id = xss_filter($_POST['user_id']);
	$passwd = xss_filter($_POST['passwd']);

    if(isset($user_id) && isset($passwd)) {
        $db = new DBC; 
        $db->db_conn();
        
        $sql = "SELECT passwd FROM admin WHERE user_id = ? ";
        $stmt = $db->conn->prepare($sql);
        
        if($stmt 
            && $stmt->bind_param("s", $user_id) 
            && $stmt->execute()
            && $stmt->store_result() 
            && $stmt->bind_result($e_passwd)
            && $stmt->fetch()
        ) {
            $fetched = true;
        }
		
		$stmt->close();
	    $d_passwd = openssl_decrypt(base64_decode($e_passwd), 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		
        $db->db_close();
    }
	
	if($passwd === $d_passwd) {
		$_SESSION['user_id'] = $user_id;
		header("Location: ./list_user.php");
	} else {
		header("Location: ./index.php");
	}

?>