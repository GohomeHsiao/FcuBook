<?php
//檢查是否登入
include("conponent/loginCheck.php");
if($_POST[control_add] == 1){
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 處理FORM資料並INSERT到資料庫
putenv("TZ=Asia/Taipei");
$type_no = $_POST[type];
$release_time = date("Y-m-d H:i:s");
$question = $_POST[question];
$answer = $_POST[answer];

$sql = "INSERT INTO qa (type_no, release_time, question, answer) VALUES ('$type_no', '$release_time', '$question', '$answer')";
mysql_query($sql, $link);
header("location:qa02.php?type=0");
}
else{
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

$sql = "SELECT * FROM q_type";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);
}
}
?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.qa_form.question.value == '')
	{
		alert("請輸入問題!");
		document.qa_form.question.focus();		
	}
	else if(document.qa_form.answer.value == '')
	{
		alert("請輸入回答!");
		document.qa_form.answer.focus();	
	}
	else
	{
		document.qa_form.submit();
	}
}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易管理後台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_qa02.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	新增常見問題</p>
	
	<form method="POST" name="qa_form" action="qa02_add.php">
		<!--webbot bot="SaveResults" U-File="../_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="1" width="452">
	<tr>
		<td width="64" align="right">問題類型</td>
		<td width="368"><select size="1" name="type">
		<?php for($i=0; $i< sizeof($type_list); $i++) echo '<option value="'.$type_list[$i][q_type_no].'">'.$type_list[$i][q_name].'</option>'; ?>
		</select></td>
	</tr>
	<tr>
		<td width="64" align="right" valign="top">問題描述</td>
		<td width="368"><textarea rows="7" name="question" cols="39"></textarea></td>
	</tr>
	<tr>
		<td width="64" align="right" valign="top">管理者回答</td>
		<td width="368"><textarea rows="7" name="answer" cols="39"></textarea></td>
	</tr>
	<tr>
		<td colspan="2">
		<p align="center"><input type="button" onClick="blankCheck()" value="送出" name="B1"></td>
	</tr>
	</table>
	<input type="hidden" value="1" name="control_add">
	</form>
	
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>