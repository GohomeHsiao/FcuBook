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

//處理上下架START
putenv("TZ=Asia/Taipei");
	$on_time = date("Y-m-d H:i:s");
if($_POST[UpDownControl] == 1){
	  $sql = "UPDATE book SET on_time = '$on_time', unsale_time = '0000-00-00 00:00:00', book_state_no = 2, fee = fee + 10 WHERE book_no = $_POST[book_no]";
		mysql_query($sql, $link);
		$sql = "UPDATE request SET reply = 'y' WHERE book_no = $_POST[book_no]";
		mysql_query($sql, $link);
		
		//REMIND	
		$sql = "SELECT book_name, seller FROM book WHERE book_no = $_POST[book_no]";	
		$tmp = mysql_query($sql, $link);
		$book_row = mysql_fetch_array($tmp);
		$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您申請重新刊登的$book_row[book_name]已經上架成功。', '2', '$on_time', '$book_row[seller]')";
		mysql_query($sql, $link);
		header("location:request01.php");
}
else if($_POST[UpDownControl] == 2){
	//REMIND	
		$sql = "SELECT book_name, seller FROM book WHERE book_no = $_POST[book_no]";
		$tmp = mysql_query($sql, $link);
		$book_row = mysql_fetch_array($tmp);
		$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您申請重新刊登$book_row[book_name]的要求被拒絕，請檢查書籍內容是否有誤，有問題可用客戶提問。', '2', '$on_time', '$book_row[seller]')";
		mysql_query($sql, $link);
		
		$sql = "UPDATE request SET reply = 'y' WHERE book_no = $_POST[book_no]";
		mysql_query($sql, $link);
		header("location:request01.php");
}
//處理上下架END


//抓書本資料START
$sql = "SELECT * FROM book, college, new_old WHERE book_no = $_GET[book_no] AND book.college_no = college.college_no AND book.new_old = new_old.new_old_no";
$tmp = mysql_query($sql, $link);
$book_data = mysql_fetch_array($tmp);
//COUNT REMAIN TIME
if($book_data[book_state_no] == 2){
	putenv("TZ=Asia/Taipei");	
	$datetime = explode(" ",$book_data[on_time]);
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
//抓書本資料END


//抓留言資料START
$sql = "SELECT * FROM message WHERE book_no = $_GET[book_no] ORDER BY ask_time ASC";
$tmp = mysql_query($sql, $link);
$message_list = array();
while($row = mysql_fetch_array($tmp)){
	$row[ask_time] = substr($row[ask_time],5);
	$row[answer_time] = substr($row[answer_time],5);
	array_push($message_list, $row);
}
//抓留言資料END
?>
<html>
<script type="text/javascript">
	function UpDown( control )
	{
		if(control == 1){
			var answer = confirm("確定要接受?");
		  if(answer)
		  {
			    document.book_form1.UpDownControl.value = control;
			    document.book_form1.submit();
		  }
		}else if(control == 2){
			var answer = confirm("確定要拒絕?");
		  if(answer)
		  {
			    document.book_form1.UpDownControl.value = control;
			    document.book_form1.submit();
		  }
		}
	}
	function goback()
	{		
		document.book_form1.action = "request01.php";
		document.book_form1.submit();		
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
<div id="left"><?php include("left_request.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">二手書管理 - <?php echo $book_data[seller]; ?></p>
<form method="POST" name="book_form1" action="req_book_detail.php">
<table border="1" width="650" id="table1" cellspacing="10">
		<tr>
			<td colspan="3"><p class="line30T" align="center"><?php echo $book_data[book_name]; ?></td>
		</tr>
		<tr>
			<td width="122" rowspan="9"><img border="0" src="../<?php echo $book_data[img]; ?>" width="200" height="280"></td>
			<td width="81" align="right">作者</td>
			<td width="299"><?php echo $book_data[author]; ?></td>
		</tr>
		<tr>
			<td width="81" align="right">出版社</td>
			<td width="299"><?php echo $book_data[publisher]; ?></td>
		</tr>
		<tr>
			<td width="81" align="right">分類</td>
			<td width="299"><?php echo $book_data[college_name].' - '.$book_data[course]; ?></td>
		</tr>
		<tr>
			<td width="81" align="right">原價</td>
			<td width="299"><?php echo $book_data[old_price]; ?> 元</td>
		</tr>
		<tr>
			<td width="81" align="right">售價</td>
			<td width="299"><font color="#FF0000"><?php echo $book_data[new_price]; ?></font> 元</td>
		</tr>
		<tr>
			<td width="81" align="right">新舊程度</td>
			<td width="299"><?php echo $book_data[new_old_name]; ?></td>
		</tr>
		<tr>
			<td width="81" align="right">剩餘時間</td>
			<td width="299"><font color="#FF0000"><?php echo $remain_time; ?></font></td>
		</tr>
		<tr>
			<td width="81" align="right">賣家</td>
			<td width="299"><?php echo $book_data[seller]; ?></td>
		</tr>
		<tr>
			<td width="81" align="right" height="70" valign="top">敘述</td>
			<td width="299" height="70" valign="top"><?php echo addslashes(nl2br($book_data[b_describe])); ?></td>
		</tr>
		<tr>
			<td width="122"></td>
			<td width="81"><input type="button" onClick="UpDown(1)" value="接受"><input type="button" onClick="UpDown(2)" value="拒絕"></td>
			<td width="299"><input type="button" onClick="goback()" value="回列表"></td>
		</tr>
	</table>
	<input type="hidden" name="left_state" value="0">
	<input type="hidden" name="left_id" value="<?php echo $book_data[seller]; ?>">
	<input type="hidden" name="UpDownControl">
        <input type="hidden" name="book_no" value="<?php echo $_GET[book_no]; ?>">
	</form>
	<hr width="650">
	<form method="POST" name="book_form2" action="">
	<table border="1" width="650" height="96" id="table2" cellpadding="2" cellspacing="0">
		<tr>
			<td style="vertical-align: top" height="32" colspan="3">
			<p align="center" class="font11B">問與答</td>
		</tr>
		<?php
		for($i=0; $i< sizeof($message_list); $i++){
			echo '<tr>';
			echo '<td width="480" height="32"><b>'.$message_list[$i][asker].'</b>：'.$message_list[$i][msg].'</td>';
			echo '<td align="center" width="40"></td>';
			echo '<td align="center" width="100">'.$message_list[$i][ask_time].'</td>';
			echo '</tr>';
			if($message_list[$i][answer_time] != '00-00 00:00:00'){
				echo '<tr>';
				echo '<td width="480" height="32" bgcolor="#DFDFDF">';
				echo '<p style="text-indent: 40px; line-height: 100%"><b>賣家回覆：'.$message_list[$i][answer].'</b></td>';
				echo '<td align="center" bgcolor="#DFDFDF" width="40"></td>';
				echo '<td align="center" bgcolor="#DFDFDF" width="100">'.$message_list[$i][answer_time].'</td>';
				echo '</tr>';
			}
		}
		?>
		<input type="hidden" name="msg_control">		
		<input type="hidden" name="msg_no">		
		</table>
	</form>
</div></div>
<?php include("footer.php"); ?>
</body>

</html>						