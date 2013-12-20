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

if($_POST[logout_control] == 1){
	session_start();
	session_unregister('admin');
	session_destroy();
	header("location:login.php");
}
?>
<html>
<script type="text/javascript">
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
<title>NEX</title>
</head>

<body>

<div id="header" >
	<div align="right" class="header">
		<form method="POST" name="h_form" action="header.php">
			<table border="0" width="100%" id="table1">
				<tr>
					<td><a href="index.php"><img border="0" src="images/logo.png" width="324" height="130"></a></td>
					<td><p align="right"><a href="index.php">回首頁</a>｜<a href="#" onClick="logout()">登出</a></td>
				</tr>
			</table>
			<input type="hidden" name="logout_control" value="0">
		</form>
	</div>
</div>
<div id="area"><div id="menu">
	<p class="menu"><a href="note.php?type=0">公告管理</a>｜<a href="book_index.php">二手書管理</a>｜<a href="request01.php">重刊登管理</a>｜<a href="member.php">會員管理</a>｜<a href="qa01_1.php">問題管理</a>｜<a href="account_day.php?today=<?php echo $today; ?>">會計系統</a></div>
</div>
</body>

</html>