<?php
    session_start();

    require_once '../../config/conf.php';
    header("Content-Type: text/html;charset=UTF-8");

    function xss_filter($data) {
        if(isset($data)) {        
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        } else {
            return "";
        }
    }
    
    $creator = xss_filter($_POST['creator']);
    $email = xss_filter($_POST['email']);
    $phone = xss_filter($_POST['phone']);
    $requestdate = xss_filter($_POST['requestdate']);
	
    if(empty($creator) || empty($email)) {
?>
<script language="javascript">
    alert("You have a missed information.");
    document.location.href = "./frm_board.php";
</script>
<?php
    } else {

		$db = new DBC; 
        $db->db_conn();
		
        $success = false;
		$btype = "01";
        
		$e_creator = openssl_encrypt($creator, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		$e_creator = base64_encode($e_creator);
		$e_email = openssl_encrypt($email, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		$e_email = base64_encode($e_email);
		$e_phone = openssl_encrypt($phone, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		$e_phone = base64_encode($e_phone);

		$sql = "INSERT INTO board(board_type,username,email,phone,requestdate) VALUES (?,?,?,?,?)";
			  
		$stmt = $db->conn->prepare($sql);
		if($stmt 
			&& $stmt->bind_param("sssss", $btype,$e_creator,$e_email,$e_phone,$requestdate)
			&& $stmt->execute()
		) {
			$success = true;
		}
        
        $errno = $stmt->errno;
        $stmt->close();
        $db->db_close();

        if($success === true || $errno === 0) {
?>
<script language="javascript">
    alert('귀하의 요청이 접수되었습니다. 삭제요청일까지 귀하의 모든 정보는 삭제됩니다.');
	document.location.href = "../index.php";
</script>
<?php
		} else {
?>
<script language="javascript">
    alert('귀하의 요청이 접수되지 않았습니다. 잠시 후 다시 요청해주세요.');
    document.location.href = "./frm_board.php";
</script>
<?php
        } 
    }
?>