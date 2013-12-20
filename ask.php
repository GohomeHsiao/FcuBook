<?php
if($_POST[control_ask] == 1){
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');
// 處理FORM資料並INSERT到資料庫
putenv("TZ=Asia/Taipei");

$type_no = $_POST[type];
$ask_time = date("Y-m-d H:i:s");
$subject = $_POST[subject];
$context = $_POST[context];

$sql = "INSERT INTO ask ( subject ,type_no , asker , ask_time, context )VALUES ( '$subject' ,'$type_no','d9728419','$ask_time','$context')";
mysql_query($sql, $link);
$release_time = date("Y-m-d H:i:s");
$sql = "INSERT INTO remind( title ,type , release_time , member_id )VALUES ( '提問成功，您的問題我們會盡快回覆您，謝謝!' ,'1','$release_time','$_SESSION[user]')";
mysql_query($sql, $link);

header("Location:remind.php");
}
else{
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');

$sql = "SELECT * FROM q_type";
$tmp = mysql_query($sql, $link);
$type_list = array();
$type_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
		array_push($type_list, $row);
}
}

?>

<html>

<script type="text/javascript">
function blankCheck()
{
	if(document.ask_form.subject.value == '')
	{
		alert("請輸入主題!");
		document.ask_form.subject.focus();		
	}
	else if(document.ask_form.context.value == '')
	{
		alert("請輸入問題描述!");
		document.ask_form.context.focus();	
	}
	else
	{
               var answer = confirm("確定要提問?");
		if(answer)
		{
			document.ask_form.submit();
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

<?php include("header.php"); ?><div id="area" >

<form method="POST" name="ask_form" action="ask.php" >
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
<div align="center">
	<table border="0" width="70%" height="200" cellspacing="10">
		<tr>
			<td colspan="2">
			<p align="center" class="line30T">客戶提問</td>
		</tr>
		<tr>
			<td width="28%">
			<p align="right">問題類型</td>
			<td width="72%"><select size="1" name="type">
			<?php				
				echo '<option value="0">-請選擇-</option>';
				for($i=0; $i< $type_num;$i++){
        	echo '<option value="'.$type_list[$i][q_type_no].'">'.$type_list[$i][q_name].'</option>';
				}
			?>
			</select></td>
		</tr>
		<tr>
			<td width="28%">
			<p align="right">主題</td>
			<td width="72%"><input type="text" name="subject" size="34"></td>
		</tr>
		<tr>
			<td style="vertical-align: top" width="28%">
			<p align="right">問題描述</td>
			<td width="72%"><textarea rows="11" name="context" cols="53"></textarea></td>
		</tr>
		<tr>
			
				<td width="85">&nbsp;</td>
				<td colspan="2">
					<p align="center"><input type="button" onClick="blankCheck()" value="送出" name="B1">
							<input type="reset" value="重新設定" name="B2" ></td>
				<td width="34">&nbsp;</td>				
		</tr>
	</table></div>
	<input type="hidden" value="1" name="control_ask">
	</form>
	<p>&nbsp;</p>

<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>