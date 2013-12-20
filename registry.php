<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');
if($_POST[control_reg]==1){
$id = $_POST[account];
$pwd = $_POST[pwd];
$pwdck = $_POST[pwdck];
$sn = $_POST[sn];
$m_name = $_POST[m_name];
$college = $_POST[college];
$m_class = $_POST[m_class];
$email = $_POST[email];
$phone = $_POST[phone];
$reg_time = date("Y-m-d H:i:s");
 //檢查重複帳號
$sql = "SELECT * FROM member WHERE id = '$_POST[account]'";
$tmp = mysql_query($sql, $link);
$check = mysql_fetch_array($tmp);
if($check != '')
	header("location:registry.php?NotFound=1");
else{
$sql = "INSERT INTO member ( id , pwd , sn , name , college_no , class , email , phone , state_no , registry_time )
				VALUES ( '$id', '$pwd', '$sn', '$m_name', '$college', '$m_class', '$email', '$phone', '1', '$reg_time')";
	mysql_query($sql, $link);
	die('<script>alert("註冊成功!");location.href="login.php"</script>');
}			
}

$sql = "SELECT * FROM college";
$tmp = mysql_query($sql, $link);
$college_list = array();
$college_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($college_list, $row);
}
?>
<html>
<script type="text/javascript">
function strcmp()
{
	var p1 = document.reg_form.pwd.value;
  var p2 = document.reg_form.pwdck.value;
  if(p1 == '')
  {  
  	alert("請輸入密碼");
		document.reg_form.pwd.focus();
	}
	else if(p2 == '')
	 {  
  	alert("請確認密碼");
		document.reg_form.pwdck.focus();
	}
	else
	{
		if(p1!=p2)
		 return false;
		else
			return true;
	}
}
function blankCheck()
{
	if(document.reg_form.account.value == '')
	{
		alert("請輸入帳號");
		document.reg_form.account.focus();		
	}
	else if(document.reg_form.pwd.value == '')
	{
		alert("請輸入密碼");
		document.reg_form.pwd.focus();		
	}
	else if(document.reg_form.sn.value == '')
	{
		alert("請輸入身分證字號");
		document.reg_form.sn.focus();	
	}	
		else if(document.reg_form.m_name.value == '')
	{
		alert("請輸入姓名");
		document.reg_form.m_name.focus();		
	}
	else if(document.reg_form.college.value == '')
	{
		alert("請輸入學院");
		document.reg_form.college.focus();		
	}
	else if(document.reg_form.m_class.value == '')
	{
		alert("請輸入系級");
		document.reg_form.m_class.focus();		
	}
	else if(document.reg_form.email.value == '')
	{
		alert("請輸入聯絡信箱");
		document.reg_form.email.focus();		
	}
	else
	{
		if(!strcmp())
		{
			alert("密碼不一致,請重新輸入");
			document.reg_form.pwdck.focus();
		}
		else
		{
			document.reg_form.submit();
		}
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

<form method="POST" name="reg_form" action="registry.php">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="0" width="75%" id="table1" cellspacing="9">
		<tr>
			<td colspan="2" height="30" class="line30T">
			<p align="center">新使用者註冊</td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*帳號</td>
			<td width="63%"><input type="text" name="account" size="20">
			<font color="#808080">註：請使用學號</font></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*密碼</td>
			<td width="63%"><input type="password" name="pwd" size="20"></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*密碼確認</td>
			<td width="63%"><input type="password" name="pwdck" size="20"></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*身分證字號</td>
			<td width="63%"><input type="text" name="sn" size="20"></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*姓名</td>
			<td width="63%"><input type="text" name="m_name" size="20"></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*學院</td>
			<td width="63%"><select size="1" name="college">
				<?php
							echo '<option value="0">--請選擇--</option>';
							for($i=0; $i< $college_num; $i++) echo '<option value="'.$college_list[$i][college_no].'">'.$college_list[$i][college_name].'</option>';
				?>
			</select></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*系級</td>
			<td width="63%"><input type="text" name="m_class" size="20"></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">*連絡信箱</td>
			<td width="63%"><input type="text" name="email" size="30">
			<font color="#808080">註：建議使用學校信箱</font></td>
		</tr>
		<tr>
			<td width="37%">
			<p align="right">手機</td>
			<td width="63%"><input type="text" name="phone" size="20"></td>
		</tr>
		<?php
			if($_GET[NotFound] == 1){
				echo '<tr>';
				echo '<td colspan="2"><p align="center"><font color="#FF0000">此帳號已註冊，請重新輸入!</font></td>';
				echo '</tr>';				
			}
		?>
	</table>
	<p><input type="button" onClick="blankCheck()" value="送出" name="B2">&nbsp;&nbsp;<input type="reset" value="重新設定" name="B3"></p>
	<input type="hidden" value="1" name="control_reg">
</form>

<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>