
<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//檢查資料庫
include ("connectDB/clean_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

putenv("TZ=Asia/Taipei");
$today = date("Y-m-d");

//NEW?
$new_flag = 0;
session_start();
if( session_is_registered('user') ){	
	$sql = "SELECT * FROM remind WHERE member_id = '$_SESSION[user]' AND isRead = 'n'";
	$tmp = mysql_query($sql, $link);
	$check = mysql_fetch_array($tmp);
	if($check != ''){
		$new_flag = 1;
	}
	
	$sql = "SELECT name FROM member WHERE id = '$_SESSION[user]'";
	$tmp = mysql_query($sql, $link);
	$user_name = mysql_fetch_array($tmp);

}
if($_POST[logout_control] == 1){	
	$logout_time = date("Y-m-d H:i:s");
	$sql = "UPDATE member SET member.last_logut_time = '$logout_time' WHERE member.id = '$_SESSION[user]'";
	mysql_query($sql, $link);
	session_unregister('user');
	session_unregister('power');
	session_destroy();
	header("location:index.php");
}
?>
<html>
<script type="text/javascript">
function sim_srch()
{
	document.h_form.simsrch_ctrl.value = 1;
	document.h_form.action = "buy.php";
	document.h_form.submit();	
}
function logout()
{
	document.h_form.logout_control.value = 1;
	document.h_form.submit();	
}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>
<form method="POST" name="h_form" action="header.php" >
<div id="header"><table border="0">
		<tr>
			<td width="350"><a href="index.php"><img border="0" src="images/blank.gif" width="350" height="133"></a></td>
			<td width="600" class="header" valign="bottom">

					<p>我要找書 <input type="text" name="srch_box" size="20"><input type="radio" value="n" checked name="choose">書名　<input type="radio" name="choose" value="a">作者 
					<input type="button" onClick="sim_srch()" value="查詢" name="B1"> ｜<a href="detail_srch.php">進階搜尋</a></p>
				<input type="hidden" name="simsrch_ctrl" >
				<input type="hidden" name="logout_control" value="0">
				<p align="right"><?php session_start();if( !session_is_registered('user') ) echo '<a href="registry.php">註冊</a>｜<a href="login.php">登入</a>';else {echo $user_name[name].' 您好!<a href="#" onClick="logout()">  登出</a>';}?> | <a href="index.php">回首頁</a>
				</td>
		</tr>
</table></div>
<div id="area_menu">
	<div id="menu" class="menu">
	<a href="rules.php">平台規章</a>｜<a href="system.php?type=0">系統公告</a>｜<a href="profile.php">會員中心</a>｜<a href="buy.php">我要買書</a>｜<a href="sell.php">我要賣書</a>｜<a href="qa.php?type=1">常見問題(Q&amp;A)</a>｜<a href="ask.php">客戶提問</a>｜<a href="remind.php">提醒</a>
		<?php if($new_flag == 1) echo '<img border="0" src="images/new_icon.gif" width="31" height="13">'; ?></div>
</div>
</form>
</body>

</html>