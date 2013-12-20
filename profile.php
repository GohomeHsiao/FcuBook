<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 抓資料
$sql = "SELECT * FROM member WHERE member.id = '$_SESSION[user]'";
$tmp = mysql_query($sql, $link);
$member_data = mysql_fetch_array($tmp);

//EDIT START
if($_POST[control_edit] == 1)
{
	if($_POST[newpwd]=='')
		$WHERE_PW = '';
	else
		$WHERE_PW = " ,pwd = '$_POST[newpwd]'";
	$sql = "UPDATE  member SET  name = '$_POST[m_name]', class = '$_POST[m_class]', email = '$_POST[email]', phone = '$_POST[phone]' ".$WHERE_PW." WHERE  id ='$_SESSION[user]' ";
	mysql_query($sql, $link);	
	header("location:profile.php");
}
?>
<html>
<script type="text/javascript">

function blankCheck(opwd)
{

	var p0 = document.prof_form.oldpwd.value;
	var p1 = document.prof_form.newpwd.value;
	var p2 = document.prof_form.newpwdck.value;
	
	if(document.prof_form.m_name.value == '')
	{
		alert("請輸入姓名");
		document.prof_form.m_name.focus();		
	}
	else if(document.prof_form.m_class.value == '')
	{
		alert("請輸入系級");
		document.prof_form.m_class.focus();		
	}
	else if(document.prof_form.email.value == '')
	{
		alert("請輸入EMAIL");
		document.prof_form.email.focus();		
	}
	else
	{
		
	  if(document.prof_form.oldpwd.value !='' && document.prof_form.newpwd.value != '' && document.prof_form.newpwdck.value != '')
		{
			
						if(opwd == p0)
						{
						    if(p1==p2)
						    {
						        alert("修改完成");
          					document.prof_form.control_edit.value = 1;
										document.prof_form.submit();
						    }
						    else
						    {
						    	  alert("新密碼不正確，請重新輸入");
						    	  document.prof_form.newpwdck.focus();		
						    }
						    
						}
						else
						{
										 alert("舊密碼不正確，請重新輸入");
						    	  document.prof_form.oldpwd.focus();	
						}	
		}
		else if(document.prof_form.oldpwd.value == '' && document.prof_form.newpwd.value == '' &&  document.prof_form.newpwdck.value == '')
		{
      alert("修改完成!");
      document.prof_form.control_edit.value = 1;
			document.prof_form.submit();
		}
		else
		{
			 alert("請輸入完整的修改密碼資料!!");
		}
	}
}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?include("header.php"); ?><div id="area">
<div id="left"><?php include("left_my.php"); ?></div>
<div id="main" class="main">

<form method="POST" name="prof_form" action="profile.php">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="0" width="650" id="table1" cellspacing="9" height="383">
		<tr>
			<td colspan="2" height="33" class="line30T">
			<p align="left">個人資料</td>
		</tr>
		<tr>
			<td width="16%" height="30">
			<p align="right">*帳號</td>
			<td width="79%" height="30"><?php echo $member_data[id];?></td>
		</tr>
		<tr>
			<td width="16%" height="30">
			<p align="right">*身分證字號</td>
			<td width="79%" height="30"><?php echo $member_data[sn];?></td>
		</tr>
		<tr>
			<td width="16%" height="30">
			<p align="right">*姓名</td>
			<td width="79%" height="30"><input type="text" name="m_name" size="20" value="<?php echo $member_data['name'];?>"></td>
		</tr>
		<tr>
			<td width="16%" height="30">
			<p align="right">*系級</td>
			<td width="79%" height="30"><input type="text" name="m_class" size="20" value="<?php echo $member_data['class'];?>"></td>
		</tr>
		<tr>
			<td width="16%" height="23">
			<p align="right">*連絡信箱</td>
			<td width="79%" height="23">
			<input type="text" name="email" size="30" value="<?php echo $member_data['email'];?>"></td>
		</tr>
		<tr>
			<td width="16%" height="37">
			<p align="right">手機</td>
			<td width="79%" height="37">
			<input type="text" name="phone" size="20" value="<?php echo $member_data['phone'];?>"></td>
		</tr>
		<tr>
			<td width="100%" colspan="2" height="30">
			<hr></td>
		</tr>
		<tr>
			<td width="16%" align="right" height="30">
			*輸入舊密碼</td>
			<td width="79%" height="30"><input type="password" id="oldpw" name="oldpwd" size="20" >
			<font color="#808080">註：不修改密碼請勿填此區資料</font></td>
		</tr>
		<tr>
			<td width="16%" align="right" height="30">
			*輸入新密碼</td>
			<td width="79%" height="30"><input type="password" name="newpwd" size="20" >
			<font color="#808080">註：最少六碼</font></td>
		</tr>
		<tr>
			<td width="16%" align="right" height="31">
			*確認新密碼</td>
			<td width="79%" height="31"><input type="password" name="newpwdck" size="20" ></td>
		</tr>
		<tr>
			<td width="16%" align="right" height="31">
			<p align="center">&nbsp;</td>
			<td width="79%" align="right" height="31">
			<p align="left"><input type="button" onClick="blankCheck('<?php echo $member_data[pwd]; ?>')" value="送出" name="B2"><input type="reset" value="復原" name="B3"></td>
		</tr>
	</table>	
		<input type="hidden"  name="control_edit">
</form>

<p>&nbsp;<p style="text-align: center">
	&nbsp;</div>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>