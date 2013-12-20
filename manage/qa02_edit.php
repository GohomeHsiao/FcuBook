<?php
//檢查是否登入
include("conponent/loginCheck.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 抓資料
$sql = "SELECT * FROM qa WHERE qa_no =".$_GET[qa_no];
$tmp = mysql_query($sql, $link);
$qadata = mysql_fetch_array($tmp);

$sql = "SELECT * FROM q_type";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);
}

//EDIT START
if($_POST[control_edit] == 1)
{
	// 處理FORM資料並UPDATE到資料庫
	putenv("TZ=Asia/Taipei");
	$type_no = $_POST[type];
	$release_time = date("Y-m-d H:i:s");	
	$question = $_POST[question];
	$answer = $_POST[answer];

	$sql = "UPDATE qa SET type_no='$type_no', release_time='$release_time', question='$question', answer='$answer' WHERE qa_no =".$_GET[qa_no];
	mysql_query($sql, $link);
	header("location:qa02.php?type=".$_GET[type]);
}
//EDIT END
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
	編輯常見問題</p>
	
	<form method="POST" name="qa_form" action="qa02_edit.php?qa_no=<?php echo $_GET[qa_no]; ?>&type=<?php echo $_GET[type]; ?>">
		<!--webbot bot="SaveResults" U-File="../_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="1" width="417">
	<tr>
		<td width="64" align="right">問題類型</td>
		<td width="333"><select size="1" name="type">
		<?php for($i=0; $i< sizeof($type_list); $i++) echo '<option value="'.$type_list[$i][q_type_no].'">'.$type_list[$i][q_name].'</option>'; ?>
		</select></td>
	</tr>
	<tr>
		<td width="64" align="right" valign="top">問題描述</td>
		<td width="333"><textarea rows="7" name="question" cols="39"><?php echo $qadata[question]; ?></textarea></td>
	</tr>
	<tr>
		<td width="64" align="right" valign="top">管理者回答</td>
		<td width="333"><textarea rows="7" name="answer" cols="39"><?php echo $qadata[answer]; ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><p align="center"><input type="button" onClick="blankCheck()" value="送出" name="B1"></td>
	</tr>
	</table>
	<input type="hidden" value="1" name="control_edit">
	</form>	
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>