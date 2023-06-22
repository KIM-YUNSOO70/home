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
    if(empty($user_id)) $user_id = "All";
    if(isset($_POST['cpage'])) {
		$cpage = (int) xss_filter($_POST['cpage']);
	} else {
		$cpage = 1;
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
    
    function Search() {
		document.list_history.submit();
    }
    
</script>

</head>

<body>

<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">
    <ul class="tab_sy1 tab_sz">
    <li><a href="./list_user.php">사용자 목록</a></li>
    <li class="active"><a href="./list_history.php">로그인 이력</a></li>
    <li><a href="./list_board.php">게시판</a></li>
    </ul>
    
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content">
        <section class="dtl_pdn_5">
            <div class="rud_dtl">
                <h2>
					<form name="list_history" method="post" action="list_history.php">
					<input type="hidden" name="cpage" value="<?=$cpage?>" />
					<select name="user_id">
					  <option <?=($user_id==="All")?"selected='selected'":""?> value="All">전체</option>
<?php
    $db = new DBC; 
    $db->db_conn();
    
	// 사용자 목록 조회
    $sql = "SELECT user_id,user_name "
          ."FROM users "
          ."ORDER BY user_name ASC";
    $stmt = $db->conn->prepare($sql);
    if($stmt 
        && $stmt->execute()
        && $stmt->store_result() 
        && $stmt->bind_result($login_id,$user_name) ) 
	{
        while($stmt->fetch()){
?>
					  <option <?=($login_id==$user_id)?"selected='selected'":""?> value="<?=$login_id?>"><?=$user_name?>(<?=$login_id?>)</option>
<?php
        }
	}
	$stmt->close();
?>
					</select>
					</span>
					<a href="#" class="button xsmall2 gray ml1" type="button" onclick="Search();">검색</a>
					</form>
                </h2>
<?php
    $totalCnt = 0;

	// 전제 이력건수 조회
	$sql = "SELECT COUNT(1) "
	      ."FROM users a, login_history b "
	      ."WHERE a.user_id = b.user_id ";
	if($user_id !== "All") {
        $sql = $sql."WHERE b.user_id = ? ";
	}
	
    $stmt = $db->conn->prepare($sql);
	if($stmt) {
		if($user_id !== "All") {
			$stmt->bind_param("s", $user_id);
		}
	    if($stmt->execute()
			&& $stmt->store_result() 
			&& $stmt->bind_result($totalCnt)
		) {
			$stmt->fetch();
		}
		$stmt->close();
	}
?>	
<center><?php include './pagelist.php';?></center>

                <table class="grid_view view_list w100" border="1">
                    <colgroup><col width="20%"><col width="*"></colgroup>
                    <thead>
                        <tr>
                            <th class="ce">로그인ID</th>
                            <th class="ce">사용자이름</th>
                            <th class="ce">사용자구분</th>
                            <th class="ce">접속일시</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
	// 로그인 이력 조회
	$sql = "SELECT b.user_id, a.user_name, b.login_type, b.createdate "
	      ."FROM users a, login_history b "
	      ."WHERE a.user_id = b.user_id ";
	if($user_id !== "All") {
        $sql = $sql."AND b.user_id = ? ";
	}
    $sql = $sql."ORDER BY b.createdate DESC "
			   ."LIMIT ".$db->getPerPage()." OFFSET ".(($cpage-1)*$perPage);
	
	$stmt = $db->conn->prepare($sql);
	if($stmt) {
		if($user_id !== "All") {
			$stmt->bind_param("s", $user_id);
		}
		if($stmt->execute()
			&& $stmt->store_result() 
			&& $stmt->bind_result($login_id,$user_name,$login_type,$createdate)
		) {
			while($stmt->fetch()){
?>                    
                        <tr>
                            <td class="ce"><?=$login_id?></td>
                            <td class="ce"><?=$user_name?></td>
                            <td class="ce"><?=$login_type?></td>
                            <td class="ce"><?=$createdate?></td>
                        </tr>
<?php
			}
		}
		$stmt->close();
	}
    $db->db_close();
?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
<center><?php include './pagelist.php';?></center>
</div>
<!-- //테블릿을 위한 섹션 -->

<script language="javascript">
    $("#total_count").html("&nbsp;&nbsp;&nbsp;Result : <?=strval($totalCnt)?>");
	
	function goPageMove(page) {
		var frm = document.list_history;
		frm.cpage.value = page;
		frm.submit();
	}
</script>

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>