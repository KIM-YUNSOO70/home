<?php
    require_once '../../config/conf.php';
    header("Content-Type: text/html;charset=UTF-8");

	$today = date("Y-m-d");
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
<title>페이스북 데이터 삭제 요청</title>

<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script language="javascript">

    function register() {
		document.req.action = "reg_board.php";
        document.req.submit();
    }
	
</script>

</head>

<body>

<header>
    <h1>데이터 삭제 요청</h1>
</header>


<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">

    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content">
        <section class="sch_area">
			<form name="req" method="post" >
			<input type="hidden" name="btype" value="01">
			<ul>
				<li>당사의 개인정보보호정책은 개인식별정보를 제3자에게 판매하는 것을 제한합니다.</li>
				<li>귀하는 당사가 귀하를 위해 파일에 보관할 수 있는 데이터에 관한 정보를 받을 수 있으며 해당 정보를 영구적으로 제거하고 당사 데이터베이스에서 삭제하도록 요청할 수 있습니다. </li>
				<li>아래 양식을 사용하여 정보를 작성해 주세요. </li>
				<li><b>*로 표시된 필드는 필수입니다.</b></li>
			</ul>
            <div class="rud_frm">
                <div class="rud_frm2">
                    <table class="sch_grid" summary="">
                        <colgroup><col width="30%"><col width="*"></colgroup>
                        <tbody>
                            <tr>
                                <th scope="row">사용자 이름*</th>
                                <td>
									<input name="creator" value="" maxlength="40" id="creator" autocomplete="off" class="input_ty1 w60" style="IME-MODE: inactive" type="text" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">이메일주소*</th>
                                <td>
									<input name="email" value="" maxlength="100" id="email" autocomplete="off" class="input_ty1 w90" style="IME-MODE: inactive" type="text" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">전화번호</th>
                                <td>
                                    <input name="phone" value="" maxlength="11" id="phone" autocomplete="off" class="input_ty1 w50" style="IME-MODE: inactive" type="tel" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">삭제요청일</th>
                                <td>
                                    <input name="requestdate" size="10" maxlength="10" id="requestdate" value="<?=$today?>" class="input_ty3" type="date">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:register();" class="button small blu">제출</a>
            </div>
			</form>
        </section>

    </div>
    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
</div>
<!-- //테블릿을 위한 섹션 -->
                

<div style="display: none;" class="loading" id="loading"></div>

</body>
</html>