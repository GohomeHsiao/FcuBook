<?php 
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

$sql = "SELECT book.*,college.*,new_old.*,request_no FROM book LEFT OUTER JOIN request ON book.book_no = request.book_no,college,new_old WHERE book.book_no = '$_GET[book_no]' AND book.new_old = new_old.new_old_no AND college.college_no = book.college_no";

$tmp = mysql_query($sql, $link);
$book_detail = mysql_fetch_array($tmp);

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
//抓書本資料END

//修改留言START
if($_POST[msg_control] == 1)
{
	$answer_time = date("Y-m-d H:i:s");
	$sql = "UPDATE message SET answer_time = '$answer_time', answer = '$_POST[msgbox]' WHERE message.msg_no = $_POST[msg_no] ";
	mysql_query($sql, $link);	
	header("location:sell_detail.php?book_no=".$_GET[book_no]);
}
else if($_POST[msg_control] == 2)
{
	$answer_time = date("Y-m-d H:i:s");
	$sql = "UPDATE message SET answer_time = '$answer_time', answer = '$_POST[msgbox]' WHERE message.msg_no = $_POST[msg_no] ";
	mysql_query($sql, $link);	
	header("location:sell_detail.php?book_no=".$_GET[book_no]);

}


//抓留言資料START
$sql = "SELECT * FROM message WHERE message.book_no = $_GET[book_no]  ORDER BY ask_time ASC";
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
	
	function modify1(mno)
	{
						document.msg_form.ctrl_flag.value = 1;
						document.msg_form.msg_no.value = mno;
						document.msg_form.submit();
	}
	function modify2(control,mno)
	{
						document.msg_form.msg_control.value = control;
						document.msg_form.msg_no.value = mno;
						document.msg_form.submit();
	}
	function answer1(mno)
	{
						document.msg_form.ctrl_flag.value = 2;
						document.msg_form.msg_no.value = mno;
						document.msg_form.submit();
	}
	function answer2(control,mno)
	{
						document.msg_form.msg_control.value = control;
						document.msg_form.msg_no.value = mno;
						document.msg_form.submit();
	}
	function goedit( no )
	{		
		document.sell_d_form.action = "book_edit.php?book_no="+no;
		document.sell_d_form.submit();		
	}
        function repost(b_no)
	{
		var answer = confirm("確定要重新刊登?");
			if(answer)
			{	
				document.sell_d_form.repost_ctrl.value = 1;
				document.sell_d_form.repost_num.value = b_no;
                                alert("已成功申請重新刊登!");
                                document.sell_d_form.action = "my_sell.php";
				document.sell_d_form.submit();
                               
			}
	}
	function cancel(b_no)
	{
		var answer = confirm("確定要取消刊登?");
			if(answer)
			{	
				document.sell_d_form.cancel_ctrl.value = 1;
				document.sell_d_form.cancel_num.value = b_no;
				alert("已成功取消刊登!");
				document.sell_d_form.action = "my_sell.php";
				document.sell_d_form.submit();
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

<?php include("header.php");?><div id="area">
<form method="POST" name="sell_d_form" action="sell_detail.php?book_no=<?php echo $_GET[book_no];?>">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	
	<table border="0" width="650" id="table1" cellspacing="10">
		<tr>
			<td colspan="3">
			<p class="line30T" align="center"><?php echo $book_detail[book_name]; ?></td>
		</tr>
		<tr>
			<td width="122" rowspan="9">
			<img border="0" src="<?php echo $book_detail[img]; ?>" width="200" height="280"></td>
			<td width="74" align="right">作者</td>
			<td width="370"><?php echo $book_detail[author]; ?></td>
		</tr>
		<tr>
			<td width="74" align="right">出版社</td>
			<td width="370"><?php echo $book_detail[publisher]; ?></td>
		</tr>
		<tr>
			<td width="74" align="right">分類</td>
			<td width="370"><?php echo $book_detail[college_name].'-'.$book_detail[course];?></td>
		</tr>
		<tr>
			<td width="74" align="right">原價</td>
			<td width="370"><?php echo $book_detail[old_price]; ?> 元</td>
		</tr>
		<tr>
			<td width="74" align="right">售價</td>
			<td width="370"><font color="#FF0000"><?php echo $book_detail[new_price]; ?></font> 元</td>
		</tr>
		<tr>
			<td width="74" align="right">新舊程度</td>
			<td width="370"><?php echo $book_detail[new_old_name]; ?></td>
		</tr>
		<tr>
			<td width="74" align="right">剩餘時間</td>
			<td width="370"><font color="#FF0000"><?php echo $remain_time; ?></font></td>
		</tr>
		<tr>
			<td width="74" align="right">賣家</td>
			<td width="370"><?php echo $book_detail[seller]; ?></td>
		</tr>
		<tr>
			<td width="74" align="right" height="70" valign="top">敘述</td>
			<td width="370" height="70" valign="top"><?php echo addslashes(nl2br($book_detail[b_describe])); ?></td>
		</tr>
		<tr>
			<td width="122">&nbsp;</td>
			<td width="74">&nbsp;</td>
                        <td width="370"><input type="button" <?php   echo 'onClick="goedit('.$book_detail[book_no].')" ';if($book_detail[book_state_no]==2)echo 'disabled';echo ' value="編輯內容" name="B5">'; 
                               if($book_detail[book_state_no]!=5) echo '<input type="button" onClick="cancel('.$book_detail[book_no].')" value="取消刊登" name="B6"></td>';
                                else{ echo '<input type="button" onClick="repost('.$book_detail[book_no].')"';if($book_detail[request_no]!='')echo 'disabled';echo ' value="重新刊登" name="B7"></td>';}
                        ?>
		</tr>
		
	</table>
		<input type="hidden" name="cancel_ctrl" value="0">
		<input type="hidden" name="cancel_num" value="0">
                 <input type="hidden" name="repost_ctrl" >
                 <input type="hidden" name="repost_num" >
	</form>
	<hr width="650">
	<form method="POST" name="msg_form" action="sell_detail.php?book_no=<?php echo $_GET[book_no];?>">
	<table border="0" width="650"  cellpadding="2" cellspacing="0">
		<tr>
			<td style="vertical-align: top" height="32" colspan="3">
			<p align="center" class="font11B">問與答</td>
		</tr>
		<?php
		for($i=0; $i< sizeof($message_list); $i++){
			if($_POST[msg_control] == 0 && $_POST[msg_no] == 0){
							echo '<tr>';
							echo '<td width="498" height="31" valign="middle" bgcolor="#DFDFDF">';
							echo '<b>'.$message_list[$i][asker].'</b>：'.$message_list[$i][msg].'</td>';
							echo '<td width="40" align="center" height="32" bgcolor="#DFDFDF">';
								if($message_list[$i][answer_time] == '00-00 00:00:00' ) echo '<input type="button" onClick="answer1('.$message_list[$i][msg_no].')" value="回覆" name="B8"></td>';
									else echo '&nbsp;</td>';
							echo '<td align="center" bgcolor="#DFDFDF">'.$message_list[$i][ask_time].'</td>';
							echo '</tr>';
			
				if($message_list[$i][answer_time] != '00-00 00:00:00' ){
				echo '<tr>';
				echo '<td width="498" height="33">';
					echo '<p style="text-indent: 40px; line-height: 100%"><b>賣家回覆：'.$message_list[$i][answer].'</b></td>';
					echo '<td align="center">';
					echo '<input type="button" onClick="modify1('.$message_list[$i][msg_no].')" value="修改" name="B7"></td>';
					echo '<td align="center">'.$message_list[$i][answer_time].'</td>';
					echo '</tr>';
				}
			}
			else{
					if($_POST[ctrl_flag] == 1  )
					{
							echo '<tr>';
							echo '<td width="498" height="31" valign="middle" bgcolor="#DFDFDF">';
							echo '<b>'.$message_list[$i][asker].'</b>：'.$message_list[$i][msg].'</td>';
							echo '<td width="40" align="center" height="32" bgcolor="#DFDFDF">';
							echo '&nbsp;</td>';
							echo '<td align="center" bgcolor="#DFDFDF">'.$message_list[$i][ask_time].'</td>';
							echo '</tr>';
						if($message_list[$i][answer_time] != '00-00 00:00:00' && $message_list[$i][msg_no] == $_POST[msg_no]){
							echo '<tr>';
							echo '<td width="498" height="33">';		
							echo '<p style="text-indent: 40px; line-height: 100%"><b>賣家回覆：';
							echo '<input type="text" name="msgbox" size="50" value="'.$message_list[$i][answer].'"></b></td>';
							echo '<td align="center">';
							echo '<input type="button" onClick="modify2(1,'.$message_list[$i][msg_no].')" value="修改" name="B8"></td>';
							echo '</tr>';
						}
				 }
				 else
				 {
				 			echo '<tr>';
							echo '<td width="498" height="31" valign="middle" bgcolor="#DFDFDF">';
							echo '<b>'.$message_list[$i][asker].'</b>：'.$message_list[$i][msg].'</td>';
							echo '<td width="40" align="center" height="32" bgcolor="#DFDFDF">';
							echo '&nbsp;</td>';
							echo '<td align="center" bgcolor="#DFDFDF">'.$message_list[$i][ask_time].'</td>';
							echo '</tr>';
						if($message_list[$i][answer_time] == '00-00 00:00:00' && $message_list[$i][msg_no] == $_POST[msg_no]){

							echo '<tr>';
							echo '<td width="498" height="33">';		
							echo '<p style="text-indent: 40px; line-height: 100%"><b>賣家回覆：';
							echo '<input type="text" name="msgbox" size="50"></b></td>';
							echo '<td align="center">';
							echo '<input type="button" onClick="answer2(2,'.$message_list[$i][msg_no].')" value="回覆" name="B8"></td>';
							echo '</tr>';
						}

					}
			
		}
	}
		?>
	</table>
	<p>&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; </p>
	
	<input type="hidden" name="msg_control" value="0">		
	<input type="hidden" name="msg_no" value="0">	
	<input type="hidden" name="ctrl_flag" >
	
       	
</form>
<p>&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>