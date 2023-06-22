<?php
    session_start();

    if(!isset($_SESSION['user_id'])) {
        header("Location:index.php");
    }

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
    
    $user_id = xss_filter($_POST['user_id']);
    $user_type = xss_filter($_POST['user_type']);
    $period = xss_filter($_POST['period']);
    $homepage = xss_filter($_POST['homepage']);
    $cryptkey = xss_filter($_POST['cryptkey']);
    $seller_id = xss_filter($_POST['seller_id']);
	
    if(empty($user_id)) {
?>
<script language="javascript">
    alert("You have a missed information.");
    document.href = "./list_user.php";
</script>
<?php
    } else {
        $db = new DBC; 
        $db->db_conn();
        
        $success = false;
        
		$decryptkey = openssl_encrypt($cryptkey, 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
		$decryptkey = base64_encode($decryptkey);

		$sql = "UPDATE users SET "
			  ."  user_type=?,"
			  ."  homepage=?,"
			  ."  cryptkey=?,"
			  ."  seller_id=?,"
			  ."  period=? "
			  ."WHERE user_id = ?";
		$stmt = $db->conn->prepare($sql);
		if($stmt 
			&& $stmt->bind_param("isssis", $user_type,$homepage,$decryptkey,$seller_id,$period,$user_id)
			&& $stmt->execute()
			&& $stmt->affected_rows > 0
		) {
			$success = true;
		}
        
        $errno = $stmt->errno;
        $stmt->close();
        $db->db_close();

        if($success === true || $errno === 0) {
            header("Location: ./list_user.php");
        } else {
?>
<script language="javascript">
    alert('There are some error to save customer.');
    header("Location: ./list_user.php");
</script>
<?php
        } 
    }
?>