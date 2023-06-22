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
            return null;
        }
    }
    
    $db = new DBC; 
    $db->db_conn();
        
    if(isset($_POST['user_id'])) $user_id = xss_filter($_POST['user_id']);
    if(isset($_POST['passwd'])) $passwd = xss_filter($_POST['passwd']);
    $fetched = false;
    
    if(isset($user_id)) {
        $sql = "SELECT cryptkey "
              ."FROM users "
              ."WHERE user_id = ? ";
        $stmt = $db->conn->prepare($sql);
        
        if($stmt 
            && $stmt->bind_param("s", $user_id) 
            && $stmt->execute()
            && $stmt->store_result() 
            && $stmt->bind_result($cryptkey)
            && $stmt->fetch()
        ) {
            $fetched = true;
        }
    }
	
	$stmt->close();
	$db->db_close();

	$decryptkey = openssl_decrypt(base64_decode($cryptkey), 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());

	$e_passwd = openssl_encrypt($passwd, 'AES-256-CBC', $decryptkey, true, $db->getUserIv());
	$e_passwd = base64_encode($e_passwd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link type="text/css" href="../css/style_main.css" rel="stylesheet">
<title>사용자 비밀번호</title>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

</head>

<body>

<header>
    <h1>사용자 비밀번호</h1>
</header>

<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">

    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content" class="w100">
        <section class="sch_area">
            <div class="rud_frm">
                <div class="rud_frm2">
                    <table class="sch_grid" border="0">
                        <colgroup><col width="30%"><col width="*"></colgroup>
                        <tbody>
                            <tr>
                                <th scope="row">키</th>
                                <td><?=$decryptkey?></td>
                            </tr>
                            <tr>
                                <th scope="row">비밀번호</th>
                                <td><?=$e_passwd?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:close();" class="button small blu">닫기</a>
            </div>
        </section>
    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
</div>
<!-- //테블릿을 위한 섹션 -->

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>