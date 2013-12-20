<?php
if($_POST[login_control] == 1){
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 處理FORM資料並INSERT到資料庫
$id = $_POST[account];
$pwd = $_POST[pwd];

$sql = "SELECT * FROM admin WHERE id = '$id' AND pwd = '$pwd'";
$tmp = mysql_query($sql, $link);
$admin = mysql_fetch_array($tmp);
$NoFoundFlag = 0;
if($admin == ''){
	$NoFoundFlag = 1;
}
else{
	session_start();
	session_register('admin');
	$_SESSION[admin] = $admin[id];
	header("location:index.php");
}
}
?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.login_form.account.value == '')
	{
		alert("帳號未填!");
		document.login_form.account.focus();		
	}
	else if(document.login_form.pwd.value == '')
	{
		alert("密碼未填!");
		document.login_form.pwd.focus();	
	}
	else
	{
		document.login_form.login_control.value = 1;
		document.login_form.submit();
	}
}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>
<div id="header" >
	<div align="right" class="header">
	<table border="0" width="100%" id="table1">
		<tr>
			<td><a href="index.php"><img border="0" src="images/logo.png" width="324" height="130"></a></td>						
		</tr>
	</table>
	</div>
</div>
<div id="area"><div id="menu"></div></div>
<div id="area">
<form method="POST" name="login_form" action="login.php">
	<p>&nbsp;</p>
	<table border="1" width="270" id="table2" style="border-bottom-width: 1px" bordercolor="#000000" height="148">
		<tr>
			<td colspan="2" bgcolor="#DFDFDF" style="border-style: solid; border-width: 1px">
			<p class="font11B" align="center">管理者登入</td>
		</tr>
		<tr>
			<td width="63" style="border-left-style: solid; border-left-width: 1px; border-top-style: solid; border-top-width: 1px" height="41">
			<p align="right">帳號</td>
			<td width="191" style="border-right-style: solid; border-right-width: 1px" height="40">
			<input type="text" name="account" size="18"></td>
		</tr>
		<tr>
			<td width="63" style="border-left-style: solid; border-left-width: 1px">
			<p align="right">密碼</td>
			<td width="191" style="border-right-style: solid; border-right-width: 1px">
			<input type="password" name="pwd" size="20"></td>
		</tr>		
		<tr>
			<td width="256" style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-bottom-style: solid; border-bottom-width: 1px" colspan="2" align="center">
			<input type="button" onClick="blankCheck()" value="登入" name="B1"></td>
		</tr>
	</table>
	<?php
		if($NoFoundFlag == 1){
			echo '<tr>';
			echo '<p><font color="#FF0000">帳號密碼有誤!</font></p>';
			echo '</tr>';
		}
	?>	
	<input type="hidden" value="0" name="login_control">
</form>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>