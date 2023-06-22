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
	
	if($_SESSION['user_id'] !== 'dolly') {
		header("Location:index.php");
		return;
	}

?>
<!-- 모바일앱 공통 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko"><head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>사용자 목록</title>
<link rel="stylesheet" type="text/css" href="../css/style_main.css">
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    
<script language="javascript">
    function goUser(user_id) {
		document.frm.user_id.value=user_id;
        document.frm.submit();
    }
</script>

</head>

<body oncontextmenu="return false">

<form name="frm" method="post" action="v_user.php">
<input type="hidden" name="user_id" value="" />
</form>

<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">
    <ul class="tab_sy1 tab_sz">
    <li class="active"><a href="./list_user.php">사용자 목록</a></li>
    <li><a href="./list_history.php">로그인 이력</a></li>
    <li><a href="./list_board.php">게시판</a></li>
    </ul>
    
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content">
        <section class="dtl_pdn_5">
            <div class="rud_dtl">
                <table class="grid_view view_list w100" border="1">
                    <colgroup><col width="30%"><col width="*"></colgroup>
                    <thead>
                        <tr>
                            <th class="ce">페이스북ID</th>
                            <th class="ce">페이스북이름</th>
                            <th class="ce">등록일</th>
                            <th class="ce">사용자구분</th>
                            <th class="ce">반복주기</th>
                        </tr>
                    </thead>
                    <tbody>
<?php

    $db = new DBC; 
    $db->db_conn();
    
    $sql = "SELECT a.user_id,a.user_name,a.createdate,a.user_type,a.period FROM users a ";
    $sql = $sql."ORDER BY a.user_name ASC ";

    $total_count = 0;
    $stmt = $db->conn->prepare($sql);
    if($stmt 
        && $stmt->execute()
        && $stmt->store_result() 
        && $stmt->bind_result($user_id,$user_name,$createdate,$user_type,$period)
    ) {
        while($stmt->fetch()){
            $total_count = $total_count+1;
?>                    
                        <tr>
                            <td class="lft"><a href="javascript:goUser('<?=$user_id?>');" ><?=$user_id?></a></td>
                            <td class="lft"><?=$user_name?></td>
                            <td class="ce" ><?=$createdate?></td>
                            <td class="ce" ><?=$user_type?></td>
							<td class="ce" ><?=$period?></td>
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
    
</div>

<script language="javascript">
    $("#total_count").html("&nbsp;&nbsp;&nbsp;Result : <?=strval($total_count)?>");
</script>

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>