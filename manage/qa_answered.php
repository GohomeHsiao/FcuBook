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

//處理回答START
if($_POST[ans_control]==1){
	putenv("TZ=Asia/Taipei");
	$answer_time = date("Y-m-d H:i:s");
	$type_no = $_POST[type_no];
	$answer = $_POST[answer];
	
	$sql = "UPDATE ask SET type_no = $type_no WHERE ask_no = $_GET[ask_no]";
	mysql_query($sql, $link);
	
	$sql = "UPDATE ask_ans SET ans_time = '$answer_time', answer = '$answer' WHERE ask_no = $_GET[ask_no]";
	mysql_query($sql, $link);
	
	//REMIND
	putenv("TZ=Asia/Taipei");
	$answer_time = date("Y-m-d H:i:s");
	$sql = "SELECT * FROM ask WHERE ask_no = $_GET[ask_no]";	
	$tmp = mysql_query($sql, $link);
	$ask_data = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('管理員針對您的提問重新回覆了，請至您的信箱收信。', '1', '$answer_time', '$ask_data[asker]')";
	mysql_query($sql, $link);
	
	//SEND MAIL
	$sql = "SELECT * FROM ask, member WHERE asker = id AND ask_no = $_GET[ask_no]";
	$tmp = mysql_query($sql, $link);
	$member_data = mysql_fetch_array($tmp);	
	
	//設定MAIL內容 *必要*
	$RecipientMail = $member_data[email];
	$RecipientName = $member_data[id];
	$Title = "您之前的提問，管理員已經重新回覆。";
	$question = str_replace ("\n", "<br>", $member_data[context]);
	$answer = str_replace ("\n", "<br>", $answer);
	$Context = '
	<b>您的提問：</b><br>'.$question.'<br><br>
	<b>管理員回覆：</b><br>'.$answer.'<br>
	';
	
	include_once("conponent/mail.php");
	
	header("location:qa01_2.php");
}
//處理回答END

//抓資料START
$sql = "SELECT * FROM ask JOIN ask_ans ON ask.ask_no = ask_ans.ask_no WHERE EXISTS (SELECT * FROM ask_ans WHERE ask.ask_no = ask_ans.ask_no) AND ask.ask_no = $_GET[ask_no]";
$tmp = mysql_query($sql, $link);
$ask_row = mysql_fetch_array($tmp);

$sql = "SELECT * FROM q_type ORDER BY q_type_no ASC";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);
}
//抓資料END
?>
<html>
<script type="text/javascript">
function ansCheck(){	
		var answer = confirm ("確定要送出回答嗎?");
		if(answer)
		{
			document.ans_form.ans_control.value = 1;
			document.ans_form.submit();
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
<div id="left"><?php include("left_qa01.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	客戶提問管理 - 已回答</p>
	
	<form method="POST" name="ans_form" action="qa_answered.php?ask_no=<?php echo $_GET[ask_no]; ?>">
		<!--webbot bot="SaveResults" U-File="../_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="1" width="419">
	<tr>
		<td width="74">標題</td>
		<td width="325"><?php echo $ask_row[subject]; ?></td>
	</tr>
	<tr>
		<td width="74" bgcolor="#DFDFDF">發問人</td>
		<td width="325" bgcolor="#DFDFDF"><?php echo $ask_row[asker]; ?></td>
	</tr>
	<tr>
		<td width="74">問題類型</td>
		<td width="325"><select size="1" name="type_no">
		<?php
		for($i=0; $i< sizeof($type_list); $i++){
			echo '<option value="'.$type_list[$i][q_type_no].'"';
			if($ask_row[type_no] == $type_list[$i][q_type_no]) echo 'selected';
			echo '>'.$type_list[$i][q_name].'</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td valign="top" width="74" bgcolor="#DFDFDF">問題內容</td>
		<td width="325" bgcolor="#DFDFDF"><?php echo $ask_row[context]; ?></td>
	</tr>
	<tr>
		<td width="74">管理者回答</td>
		<td width="325">
		<p align="right">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><textarea rows="9" name="answer" cols="48"><?php echo $ask_row[answer]; ?></textarea></td>
	</tr>
	<tr>
		<td width="399" colspan="2">
		<p align="center">
		<input type="button" onClick="ansCheck()" value="送出" name="B1"></td>
	</tr>
	</table>
	<input type="hidden" name="ans_control">
	</form>
	
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>