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
    $fetched = false;
    
    if(isset($user_id)) {
        $sql = "SELECT user_id,user_name,createdate,user_type,homepage,seller_id,period,cryptkey "
              ."FROM users "
              ."WHERE user_id = ? ";
        $stmt = $db->conn->prepare($sql);
        
        if($stmt 
            && $stmt->bind_param("s", $user_id) 
            && $stmt->execute()
            && $stmt->store_result() 
            && $stmt->bind_result($user_id,$user_name,$createdate,$user_type,$homepage,$seller_id,$period,$cryptkey)
            && $stmt->fetch()
        ) {
            $fetched = true;
        }
    }
    
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
<title>사용자 정보</title>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script language="javascript">
    function goPwd() {
        pwd_win = window.open('','pwd_win','menubar=no,scrollbars=no,resizable=no,status=no,width=400,height=200,left=0,top=0');
		document.frmpwd.target = "pwd_win";
		document.frmpwd.submit();
	}

</script>

</head>

<body>

<form name="frmpwd" method="post" action="frm_pwd.php">
<input type="hidden" name="user_id" value="<?=$user_id?>" />
</form>

<form name="frm" method="post" action="frm_user.php">
<input type="hidden" name="user_id" value="<?=$user_id?>" />
</form>

<header>
    <h1>사용자 정보</h1>
    <div><a href="./list_user.php" class="h"><img src="./images/home.png" alt="홈"></a></div>
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
                                <th scope="row">페이스북 ID</th>
                                <td><?=$user_id?></td>
                            </tr>
                            <tr>
                                <th scope="row">페이스북 이름</th>
                                <td><?=$user_name?></td>
                            </tr>
                            <tr>
                                <th scope="row">등록일시</th>
                                <td><?=$createdate?></td>
                            </tr>
                            <tr>
                                <th scope="row">사용자구분</th>
                                <td><?=$user_type?></td>
                            </tr>
                            <tr>
                                <th scope="row">사용자 홈페이지</th>
                                <td><?=$homepage?></td>
                            </tr>
<?php
	$decryptkey = openssl_decrypt(base64_decode($cryptkey), 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());
?>
                            <tr>
                                <th scope="row">홈페이지 키</th>
                                <td><?=$decryptkey?></td>
                            </tr>
                            <tr>
                                <th scope="row">판매자 ID</th>
                                <td><?=$seller_id?></td>
                            </tr>
                            <tr>
                                <th scope="row">새로고침 주기</th>
                                <td><?=$period?>초</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:goPwd();" class="button small blu">비밀번호</a>
              <a href="javascript:document.frm.submit();" class="button small blu">수정</a>
              <a href="./list_user.php" class="button small blu">닫기</a>
            </div>
        </section>

        <section class="dtl_pdn_5">
            <div class="rud_dtl">
                <table class="grid_view view_list w100" border="1">
                    <colgroup><col width="20%"><col width="*"></colgroup>
                    <thead>
                        <tr>
                            <th class="ce">로그인ID</th>
                            <th class="ce">사용자구분</th>
                            <th class="ce">접속일시</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
	$stmt->close();

    $sql = "SELECT user_id, login_type, createdate FROM login_history "
			."WHERE user_id = ? "
            ."ORDER BY createdate DESC "
			."LIMIT 50 ";

    $stmt = $db->conn->prepare($sql);
    if($stmt 
        && $stmt->bind_param("s", $user_id) 
        && $stmt->execute()
        && $stmt->store_result() 
        && $stmt->bind_result($user_id,$login_type,$createdate)
    ) {
        while($stmt->fetch()){
?>                    
                        <tr>
                            <td class="ce"><?=$user_id?></td>
                            <td class="ce"><?=$login_type?></td>
                            <td class="ce"><?=$createdate?></td>
                        </tr>
<?php
        }
    }
	$stmt->close();
    $db->db_close();
?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
</div>
<!-- //테블릿을 위한 섹션 -->

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>