<?php
if($_POST[account] != ''){
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

$sql = "SELECT * FROM member WHERE id = '$_POST[account]'";
$tmp = mysql_query($sql, $link);
$check = mysql_fetch_array($tmp);
if($check == '')
	header("location:find_pwd.php?NotFound=1");
else if(strcmp($check[sn], $_POST[sn]) != 0) 
	header("location:find_pwd.php?NotFound=2");
else{
	//SEND MAIL
	$sql = "SELECT * FROM member WHERE id = '$_POST[account]'";
	$tmp = mysql_query($sql, $link);
	$member_data = mysql_fetch_array($tmp);	
	
	//設定MAIL內容 *必要*
	$RecipientMail = $member_data[email];
	$RecipientName = $member_data[id];
	$Title = "您忘記的密碼在此!";	
	$Context = '
	<b>您的密碼：</b><br>'.$member_data[pwd].'<br><br>	
	';
	
	include_once("conponent/mail.php");
	die('<script>alert("Your password is sent to you!");location.href="login.php"</script>'); 	
}
}
?>
<html>
<script type="text/javascript">
	function blankCheck(){
		if(document.find_form.account.value == ''){
			alert("帳號未填!");
			document.find_form.account.focus();
		}
		else if(document.find_form.sn.value == ''){
			alert("身份證字號未填!");
			document.find_form.sn.focus();
		}
		else{
			document.find_form.submit();
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

<?php include("header.php"); ?><div id="area" >

<form name="find_form" method="POST" action="find_pwd.php">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="0" width="75%" height="200" cellspacing="10">
		<tr>
			<td colspan="2">
			<p align="center" class="line30T">找回密碼</td>
		</tr>
		<tr>
			<td width="35%">
			<p align="right">你的帳號</td>
			<td width="60%">
			<p align="left"><input type="text" name="account" size="22"></td>
		</tr>
		<tr>
			<td width="35%">
			<p align="right">你的身分證字號</td>
			<td width="60%"><input type="text" name="sn" size="22"></td>
		</tr>		
		<tr>
			<td colspan="2"><p align="center"><font color="#808080">註：系統會將您的密碼寄到註冊時所填的信箱</font></td>			
		</tr>
		<?php
			if($_GET[NotFound] == 1){
				echo '<tr>';
				echo '<td colspan="2"><p align="center"><font color="#FF0000">找不到帳號!</font></td>';
				echo '</tr>';				
			}
			else if($_GET[NotFound] == 2){
				echo '<tr>';
				echo '<td colspan="2"><p align="center"><font color="#FF0000">身份證字號錯誤!</font></td>';
				echo '</tr>';				
			}
		?>
		<tr>
			<td colspan="2">
			<p align="center"><input type="button" onClick="blankCheck()" value="送出" name="B2"><input type="reset" value="重新設定" name="B3"></td>
		</tr>
	</table>
	<p>&nbsp;</p>
</form>

<p>&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>