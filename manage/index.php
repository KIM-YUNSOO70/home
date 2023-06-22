<?php
	session_start();
	session_unset();
    session_destroy();
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
<title>Dolly's Facebook Shop</title>
    
<script language="javascript">
    function loginUser() {
        var frm = document.login;
        if(frm.user_id.value == "") {
            alert("Please give your id.");
            return;
        }
        if(frm.passwd.value == "") {
            alert("Please give your password.");
            return;
        }

        frm.submit();
    }
</script></head>

<body>

<header>
    <h1>Login</h1>
</header>

<!-- 테블릿을 위한 섹션 -->
<div id="w_tablet">

    <!-- 탭과 컨텐츠 분리를 위한 섹션 -->
    <div id="content">

        <section class="sch_area">
            <form name="login" action="./login_proc.php" method="post">
            <div class="rud_frm">
                <div class="rud_frm2">
                    <table class="sch_grid" summary="">
                        <colgroup><col width="30%"><col width="*"></colgroup>
                        <tbody>
                            <tr>
                                <th scope="row"><label for="user_id">User ID</label></th>
                                <td>
                                    <input name="user_id" maxlength="200" id="user_id" class="input_ty1 w50" style="IME-MODE: inactive" type="text">
									<input name="remember" id="remember" type="checkbox">Remember id</input>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="passwd">Password</label></th>
                                <td>
                                    <input name="passwd" maxlength="20" id="passwd" class="input_ty1 w50" style="IME-MODE: inactive" type="password" onkeypress="if(event.keyCode == 13){loginUser();}">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="btn_area ce">
              <a href="javascript:loginUser();" class="button small blu">Login</a>
              <a href="javascript:history.back();" class="button small blu">Cancel</a>
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