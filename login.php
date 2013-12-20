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

$sql = "SELECT * FROM member WHERE id = '$id' AND pwd = '$pwd'";
$tmp = mysql_query($sql, $link);
$user = mysql_fetch_array($tmp);
$NoFoundFlag = 0;


if($user == ''){
	$NoFoundFlag = 1;
}
else{
	session_start();
	session_register('user');
	$_SESSION[user] = $user[id];
	session_register('power');
	$_SESSION[power] = $user[state_no];
	$login_time = date("Y-m-d H:i:s");
	$sql = "UPDATE member SET member.last_login_time = '$login_time' WHERE member.id = '$_SESSION[user]'";
	mysql_query($sql, $link);
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

<?php include("header.php"); ?><div id="area">
<table border="0" width="100%" height="297" cellspacing="20">
	<tr>
		<td height="30" bgcolor="#FFFFCC">
		<p align="center" class="line30T">平台規章</td>
		<td height="30" width="383" bgcolor="#FFFFCC">
		<p align="center" class="line30T">此處登入</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<p align="LEFT">  一、只有本平台之會員享有買賣書功能的服務。</p>
		<p align="LEFT">  二、上架書籍有14天的販賣時間。</p>
		<p align="LEFT">  三、書籍每次上架都會收取10元手續費。</p>
		<p align="LEFT">  四、賣家書籍滯銷超過7日後若不取回，每超過一天加收5元保管費。</p>
		<p align="LEFT">  五、若保管費超過書籍本身價格，本平台有權將書籍沒收。</p>
		<p align="LEFT">  六、下標後請於三日內至聯合服務中心付款。</p>
		<p align="LEFT">  七、棄標者系統會給予懲罰，第一次停權三天、第二次停權七天、第三次永久停權。</p></td>
		<td width="383">		
		<form method="POST"  name="login_form" action="login.php">
			<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
			<table border="0" width="100%" id="table1" height="165" cellspacing="10">
				<tr>
					<td>
					<p align="right">帳號</td>
					<td width="300"><input type="text" name="account" size="20"><font color="#808080"> 註：學號</font></td>
				</tr>
				<tr>
					<td>
					<p align="right">密碼</td>
					<td width="300"><input type="password" name="pwd" size="20"></td>
				</tr>
				<tr>
					
					<td>&nbsp;</td>
					<td width="300"><?php
							if($NoFoundFlag == 1){
								echo '<font color="#FF0000">帳號密碼有誤!</font>';
							}
						       ?>
					<br><input type="button" onClick="blankCheck()" value="登入" name="B2"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td width="300">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td width="300">忘記密碼了嗎? <a href="find_pwd.php">找回密碼</a></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td width="300">您還不是會員嗎? <a href="registry.php">立即註冊</a></td>
				</tr>
			</table>	
						<input type="hidden" value="0" name="login_control">			
		</form>
		</td>
	</tr>
</table>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>