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
    
    if(isset($_POST['user_id'])) $user_id = xss_filter($_POST['user_id']);
    $fetched = false;
    
    if(isset($user_id)) {
        $db = new DBC; 
        $db->db_conn();
        
        $sql = "SELECT user_name,user_type,homepage,seller_id,period,cryptkey "
              ."FROM users "
              ."WHERE user_id = ? ";
        $stmt = $db->conn->prepare($sql);
        
        if($stmt 
            && $stmt->bind_param("s", $user_id) 
            && $stmt->execute()
            && $stmt->store_result() 
            && $stmt->bind_result($user_name,$user_type,$homepage,$seller_id,$period,$cryptkey)
            && $stmt->fetch()
        ) {
            $fetched = true;
        }

		$stmt->close();
	    $decryptkey = openssl_decrypt(base64_decode($cryptkey), 'AES-256-CBC', $db->getCipherKey(), true, $db->getIv());

        $db->db_close();
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
    
<script language="javascript">
    function goUser() {
		document.user.action = "v_user.php";
        document.user.submit();
    }
</script>


<title>사용자 정보</title>

<style>
#customer-list{float:left;list-style:none;margin-top:1px;padding:0;position: absolute;}
#customer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#customer-list li:hover{background:#ece3d2;cursor: pointer;}
</style>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


</head>

<body onload="jusoCallBack('roadFullAddr','roadAddrPart1','addrDetail','roadAddrPart2','engAddr','jibunAddr','zipNo','admCd','rnMgtSn','bdMgtSn','detBdNmList','bdNm','bdKdcd','siNm','sggNm','emdNm','liNm','rn','udrtYn','buldMnnm','buldSlno','mtYn','lnbrMnnm','lnbrSlno','emdNo');">

<header>
    <h1>사용자 정보 수정</h1>
    <div><a href="./list_user.php" class="h"><img src="./images/home.png" alt="홈"></a></div>
</header>

<form name="user" method="post" action="./reg_user.php">
<input type="hidden" name="user_id" value="<?=$user_id?>">

<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">

    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content">
        <section class="sch_area">
            <div class="rud_frm">
                <div class="rud_frm2">
                    <table class="sch_grid" summary="">
                        <colgroup><col width="30%"><col width="*"></colgroup>
                        <tbody>
                            <tr>
                                <th scope="row">사용자 ID</th>
                                <td><?=$user_id?></td>
                            </tr>
                            <tr>
                                <th scope="row">사용자 이름</th>
                                <td><?=$user_name?></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="user_type">사용자 구분</label></th>
                                <td>
									<select name="user_type" id="user_type">
										<option value="0" <?=$user_type===0?"selected":""?> >데모 사용자</option>
										<option value="1" <?=$user_type===1?"selected":""?> >타임라인 사용자</option>
										<option value="2" <?=$user_type===2?"selected":""?> >페이지 사용자</option>
										<option value="3" <?=$user_type===3?"selected":""?> >판매자 스태프</option>
										<option value="-1" <?=$user_type<0?"selected":""?> >부적격 사용자</option>
									</select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="homepage">사용자 홈페이지</label></th>
                                <td>
                                    <input name="homepage" value="<?=$fetched?$homepage:"010"?>" maxlength="100" id="homepage" autocomplete="off" class="input_ty1 w90" style="IME-MODE: inactive" type="tel" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="cryptkey">홈페이지 비밀번호</label></th>
                                <td>
                                    <input name="cryptkey" value="<?=$fetched?$decryptkey:"010"?>" maxlength="100" id="cryptkey" autocomplete="off" class="input_ty1 w90" style="IME-MODE: inactive" type="text" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="seller_id">Seller ID</label></th>
                                <td>
                                    <input name="seller_id" value="<?=$fetched?$seller_id:""?>" maxlength="5" id="seller_id" class="input_ty1 w30" style="IME-MODE: inactive" type="text" readonly />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="period">새로고침 주기</label></th>
                                <td>
									<select name="period" id="period">
										<option value="60" <?=$period==60?"selected":""?> >1분</option>
										<option value="20" <?=$period==20?"selected":""?> >20초</option>
										<option value="10" <?=$period==10?"selected":""?> >10초</option>
										<option value="7" <?=$period==7?"selected":""?> >7초</option>
										<option value="5" <?=$period==5?"selected":""?> >5초</option>
									</select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:document.user.submit();" class="button small blu">Save</a>
              <a href="javascript:goUser();" class="button small blu">Cancel</a>
            </div>
        </section>

    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
</div>
<!-- //테블릿을 위한 섹션 -->
                
</form>

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>