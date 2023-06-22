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

<style>
#customer-list{float:left;list-style:none;margin-top:1px;padding:0;position: absolute;}
#customer-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#customer-list li:hover{background:#ece3d2;cursor: pointer;}
</style>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


</head>

<body onload="jusoCallBack('roadFullAddr','roadAddrPart1','addrDetail','roadAddrPart2','engAddr','jibunAddr','zipNo','admCd','rnMgtSn','bdMgtSn','detBdNmList','bdNm','bdKdcd','siNm','sggNm','emdNm','liNm','rn','udrtYn','buldMnnm','buldSlno','mtYn','lnbrMnnm','lnbrSlno','emdNo');">

<header>
    <h1>사용자 비밀번호</h1>
</header>

<form name="user" method="post" action="./v_pwd.php">
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
                                <th scope="row"><label for="passwd">비밀번호</label></th>
                                <td>
                                    <input name="passwd" value="" maxlength="100" id="passwd" autocomplete="off" class="input_ty1 w90" style="IME-MODE: inactive" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:document.user.submit();" class="button small blu">확인</a>
              <a href="javascript:close();" class="button small blu">취소</a>
            </div>
        </section>

    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
</div>
<!-- //테블릿을 위한 섹션 -->
                
</form>

</body>
</html>