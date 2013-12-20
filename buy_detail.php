<?php 
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
session_start();
if(session_is_registered('power') AND $_SESSION[power] != 1){
	die('<script>alert("Your usage right has been suspended!");location.href="index.php"</script>');	
}
$sql = "SELECT * FROM book,college,new_old WHERE book.book_no = '$_GET[book_no]' AND book.new_old = new_old.new_old_no AND college.college_no = book.college_no";

$tmp = mysql_query($sql, $link);
$book_detail = mysql_fetch_array($tmp);
//處理剩餘時間
if($book_detail[book_state_no] == 2){
	putenv("TZ=Asia/Taipei");	
	$datetime = explode(" ",$book_detail[on_time]);
	$date = explode("-",$datetime[0]);
	$time = explode(":",$datetime[1]);
	$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+14, $date[0]);
	$t2 = time();
	
	$remain_seconds = $t1 - $t2;
	if($remain_seconds/(60*60*24) >= 1){
		$remain_time = floor($remain_seconds/(60*60*24)).'天';		
	}
	else if($remain_seconds/(60*60) >= 1){
		$remain_time = floor($remain_seconds/(60*60)).'小時';		
	}
	else if($remain_seconds/60 >= 1){
		$remain_time = floor($remain_seconds/60).'分鐘';
	}
	
} 
else{
	$remain_time = '-';	
}
//處理剩餘時間END
session_start();
//處理追蹤start
if($_POST[trace_Control] == 1)
{
	
	$sql = "INSERT INTO trace ( member_id , book_no )VALUES ( '$_SESSION[user]', '$_POST[trace_num]')";	
		mysql_query($sql, $link);	
}
//處理追蹤END

//檢查追蹤重複
$sql = "SELECT book_no FROM trace WHERE member_id = '$_SESSION[user]' ";
$tmp = mysql_query($sql, $link);
$trace_num = mysql_num_rows($tmp);
$find_trace = array();
while($row = mysql_fetch_array($tmp)){
	array_push($find_trace, $row);
}
for($j=0;$j< $trace_num; $j++)
	{
		 if($find_trace[$j][book_no] == $_GET[book_no]){
		 		$Isfind = 1;
		 		break;
		 	}
	}
//檢查END

//處理購買START
if($_POST[buy_Control] == 1)
{
	
	$buy_time = date("Y-m-d H:i:s");
	$sql = "UPDATE book SET buy_time = '$buy_time', buyer = '$_SESSION[user]',book.book_state_no = 3 WHERE book.book_no = ".$_GET[book_no] ;
	mysql_query($sql, $link);
	
	$on_time = date("Y-m-d H:i:s");
	$sql = "SELECT book_name FROM book WHERE book_no = $_GET[book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您已成功下標$book_row[book_name]!請盡速至聯合中心繳款。', '2', '$on_time', '$_SESSION[user]')";
	mysql_query($sql, $link);	
	
	header("location:my_buy.php");
}
//處理購買END

//處理買家新增留言START
if($_POST[ask_msgctrl] == 1){
	
	$ask_time = date("Y-m-d H:i:s");
	$sql = "INSERT INTO message (book_no ,asker ,ask_time , msg  )VALUES ( $_GET[book_no], '$_SESSION[user]', '$ask_time', '$_POST[msgbox]')";
	mysql_query($sql, $link);
	
	putenv("TZ=Asia/Taipei");
	$on_time = date("Y-m-d H:i:s");
	$sql = "SELECT book_name, seller FROM book WHERE book_no = $_GET[book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您在$book_row[book_name]的留言已成功!。', '3', '$on_time', '$_SESSION[user]')";
	mysql_query($sql, $link);	
	
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您在$book_row[book_name]有新留言。', '3', '$on_time', '$book_row[seller]')";
	mysql_query($sql, $link);	

	header("location:buy_detail.php?book_no=".$_GET[book_no]);
}
//處理買家新增留言END

//處理買家移除留言START
if($_POST[msg_control] == 1){
	//REMIND	
	putenv("TZ=Asia/Taipei");
	$on_time = date("Y-m-d H:i:s");
	$sql = "SELECT book_name, seller FROM book WHERE book_no = $_GET[book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	$sql = "SELECT asker FROM message WHERE msg_no = $_POST[msg_no]";	
	$tmp = mysql_query($sql, $link);
	$msg_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您在$book_row[book_name]的留言已移除。', '3', '$on_time', '$msg_row[asker]')";
	mysql_query($sql, $link);	
	
	$sql = "DELETE FROM message WHERE msg_no = $_POST[msg_no]";
	mysql_query($sql, $link);
}
//處理買家移除留言END


//抓留言資料START
$sql = "SELECT * FROM message WHERE message.book_no = $_GET[book_no]  ORDER BY ask_time ASC";
$tmp = mysql_query($sql, $link);
$message_list = array();
while($row = mysql_fetch_array($tmp)){
	$row[ask_time] = substr($row[ask_time],5);
	$row[answer_time] = substr($row[answer_time],5);
	array_push($message_list, $row);
}
$sql ="SELECT seller FROM book WHERE book_no = $_GET[book_no]";
$tmp = mysql_query($sql, $link);
$b_seller = mysql_fetch_array($tmp);
//抓留言資料END
?>
<html>
<script type="text/javascript">
	function trace(b_no)
	{
			var answer = confirm("確定要加入我的追蹤?");
		if(answer)
		{
			document.buy_d_form.trace_Control.value = 1;
			document.buy_d_form.trace_num.value = b_no;
			document.buy_d_form.submit();
		}
	}
	function buyIt()
	{
			var answer = confirm("確定要購買?");
		if(answer)
		{
			document.buy_d_form.buy_Control.value = 1;
			document.buy_d_form.submit();
		}
	}
	function msgRemove(control, mno)
	{
			var answer = confirm("確定要刪除此留言?");
				if(answer)
				{
						document.msg_form.msg_control.value = control;
						document.msg_form.msg_no.value = mno;
						document.msg_form.submit();
				}
	}
	function no_login()
	{
		alert("請先登入會員!");
	}
	function leaveMsg()
	{
		if(document.msg_form.msgbox.value == ''){
				alert("請輸入留言!!");
				document.msg_form.msgbox.focus();
		}
		else{
			var answer = confirm("確定要新增此留言?");
				if(answer)
				{
						document.msg_form.ask_msgctrl.value = 1;
						document.msg_form.submit();
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
<form method="POST" name="buy_d_form" action="buy_detail.php?book_no=<?php echo $_GET[book_no]; ?>">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	
	<div align="center">
	<table border="0" width="700" id="table1" cellspacing="9">
		<tr>
			<td colspan="3">
			<p class="line30T" align="center"><?php echo $book_detail[book_name]; ?></td>
		</tr>
		<tr>
			<td width="200" rowspan="9">
			<img border="0" src="<?php echo $book_detail[img]; ?>" width="200" height="280"></td>
			<td width="100" align="right">作者</td>
			<td><?php echo $book_detail[author]; ?></td>
		</tr>
		<tr>
			<td width="100" align="right">出版社</td>
			<td><?php echo $book_detail[publisher]; ?></td>
		</tr>
		<tr>
			<td width="100" align="right">分類</td>
			<td><?php echo $book_detail[college_name].'-'.$book_detail[course];?></td>
		</tr>
		<tr>
			<td width="100" align="right">原價</td>
			<td><?php echo $book_detail[old_price]; ?>元</td>
		</tr>
		<tr>
			<td width="100" align="right">售價</td>
			<td><font color="#FF0000"><?php echo $book_detail[new_price]; ?></font> 元</td>
		</tr>
		<tr>
			<td width="100" align="right">新舊程度</td>
			<td><?php echo $book_detail[new_old_name]; ?></td>
		</tr>
		<tr>
			<td width="100" align="right">剩餘時間</td>
			<td><font color="#FF0000"><?php echo $remain_time;?></font></td>
		</tr>
		<tr>
			<td width="100" align="right">賣家</td>
			<td><?php echo $book_detail[seller]; ?></td>
		</tr>
		<tr>
			<td width="100" align="right" height="70" valign="top">敘述</td>
			<td height="70" valign="top"><?php echo addslashes(nl2br($book_detail[b_describe])); ?></td>
		</tr>
		<tr>
			<td width="122">&nbsp;</td>
			<td width="100">&nbsp;</td>
			<td><input type="button" <?php  if(!session_is_registered('user'))echo '<input type="button" onClick="no_login()" value="加入追蹤" name="B5"><input type="button" onClick="no_login()" value="我要購買" name="B6"></td>'; 
																									else{	 echo '<input type="button" onClick="trace('.$book_detail[book_no].')" ';if($Isfind||$book_detail[seller]==$_SESSION[user])echo 'disabled'; echo ' value="加入追蹤" name="B5">';
																										     if($book_detail[seller]!=$_SESSION[user])echo '<input type="button" onClick="buyIt()" value="我要購買" name="B6"></td>'; 
																										  }
																						?>
		</tr>
		
	</table>
			<input type="hidden" name="trace_Control">
			<input type="hidden" name="trace_num">
			<input type="hidden" value="0" name="buy_Control">
	</form>
	<hr width="700">
	<form method="POST" name="msg_form" action="buy_detail.php?book_no=<?php echo $_GET[book_no]; ?>">	
	<table border="0" width="650" height="96" cellpadding="2" cellspacing="0">
		<tr>
			<td style="vertical-align: top" height="32" colspan="3">
			<p align="center" class="font11B">問與答</td>
		</tr>
		<?php
		for($i=0; $i< sizeof($message_list); $i++){
				echo '<tr>';
				echo '<td height="32" bgcolor="#DFDFDF">';
				echo '<b>'.$message_list[$i][asker].'</b>：'.$message_list[$i][msg].'</td>';
				echo '<td align="center" bgcolor="#DFDFDF">';if(!strcmp($message_list[$i][asker],$_SESSION[user])) echo '<input type="button" onClick="msgRemove(1,'.$message_list[$i][msg_no].')" value="移除" name="B8"></td>';
				echo '<td align="center" bgcolor="#DFDFDF">'.$message_list[$i][ask_time].'</td>';
				echo '</tr>';
				if($message_list[$i][answer_time] != '00-00 00:00:00'){
					echo '<tr>';
					echo '<td width="517" height="32">';
					echo '<p style="text-indent: 40px; line-height: 100%"><b>賣家回覆：'.$message_list[$i][answer].'</b></td>';
					echo '<td align="center">&nbsp;</td>';
					echo '<td align="center">'.$message_list[$i][answer_time].'</td>';
					echo '</tr>';
				}
		}
	
	  if(session_is_registered('user') && (strcmp($b_seller[seller] ,$_SESSION[user])!=0) )
		 {
			echo '<tr>';
			echo '<td width="517" height="32">';
			echo '<p align="center"><input type="text" name="msgbox" size="67"></td>';
			echo '<td width="40" align="center" height="32">';
			echo '<input type="button" onClick="leaveMsg()" value="留言" name="ask_msg"></td>';
			echo '<td width="90" align="center" height="32">';
			echo '<p align="left">';
			echo '&nbsp;</td>';
			echo '</tr>';
		}
	?>
	</table></div>
		
		<input type="hidden" name="msg_control">		
		<input type="hidden" name="msg_no">	
		<input type="hidden" name="ask_msgctrl" >
	<p>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; </p>
</form>
<p>&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>