<?php 
include("conponent/loginCheck.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

//處理取消刊登START 
if($_POST[cancel_ctrl]==1)
{
	//取消刊登 狀態變滯銷
        $unsale_time = date("Y-m-d H:i:s");
	$sql = "UPDATE book SET book_state_no = 5, on_time = '0000-00-00 00:00:00',unsale_time = '$unsale_time' WHERE book.book_no = $_POST[cancel_num]";
	mysql_query($sql, $link);
	//寄送remind
	$on_time = date("Y-m-d H:i:s");
	$sql = "SELECT book_name FROM book WHERE book_no = $_POST[cancel_num]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您販賣的$book_row[book_name]已取消刊登。', '3', '$on_time', '$_SESSION[user]')";
	mysql_query($sql, $link);	
	
	//刪除該書留言
        
	/*$sql = "DELETE FROM message WHERE book_no = $_POST[cancel_num]";
	mysql_query($sql, $link);
	$sql = "DELETE FROM trace WHERE book_no = $_POST[cancel_num]";
	mysql_query($sql, $link);*/
        
}
//處理取消刊登END

//處理重新刊登START
if($_POST[repost_ctrl]==1)
{
        //重新刊登
        $request_time = date("Y-m-d H:i:s");
        $sql = "INSERT INTO request(request_time , book_no , seller) VALUES ('$request_time','$_POST[repost_num]', '$_SESSION[user]')";
        mysql_query($sql, $link);
        //寄送remind
        $sql = "SELECT book_name FROM book WHERE book_no = $_POST[repost_num]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
        $on_time = date("Y-m-d H:i:s");
        $sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您重新刊登$book_row[book_name]的申請已經成功送出。', '1', '$on_time', '$_SESSION[user]')";
	mysql_query($sql, $link);
}
//處理重新刊登END

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY b.new_price DESC";
}
else if($_POST[sort_ctrl] == 2){
	$WHERE = "ORDER BY b.on_time DESC";
}
else if($_POST[sort_ctrl] == 3){
	$WHERE = "ORDER BY b.new_old DESC";
}
else if($_POST[sort_ctrl] == 4){
	$WHERE = "ORDER BY b.book_state_no ASC";
}
else
	$WHERE = "ORDER BY b.on_time DESC";
$sql = "SELECT b.*,COUNT(b.book_no),book_state.state_name,msg_no,request_no  FROM  book AS b LEFT OUTER JOIN message ON b.book_no = message.book_no LEFT OUTER JOIN request ON b.book_no = request.book_no AND request.reply='n',book_state WHERE b.seller = '$_SESSION[user]' AND b.book_state_no = book_state.book_state_no AND b.book_state_no IN (1,2,5) GROUP BY b.book_no ".$WHERE;
$tmp = mysql_query($sql, $link);
$pens_total = mysql_num_rows($tmp);
$pages_total = ceil($pens_total/$pens_per_page);

if($_POST[page] >= 1 AND $_POST[page] <= $pages_total){
	$start = ($_POST[page]-1)*$pens_per_page;
}
else if($_POST[page] > $pages_total){
	$start = ($pages_total-1)*$pens_per_page;
}
else{
	$start = 0;
}
$page_now = ($start / $pens_per_page)+1;
$page_list_first = floor(($page_now-1)/$page_list_max)*$page_list_max + 1;
if($page_list_first + $page_list_max -1 > $pages_total){
	$page_list_last = $pages_total;
}
else{
	$page_list_last = $page_list_first + $page_list_max -1;
}
//處理頁數END


//中間列表
if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY b.new_price DESC";
}
else if($_POST[sort_ctrl] == 2){
	$WHERE = "ORDER BY b.on_time DESC";
}
else if($_POST[sort_ctrl] == 3){
	$WHERE = "ORDER BY COUNT(b.book_no) DESC";
}
else if($_POST[sort_ctrl] == 4){
	$WHERE = "ORDER BY b.book_state_no ASC";
}
else
	$WHERE = "ORDER BY b.on_time DESC";
$sql = "SELECT b.*,COUNT(b.book_no),book_state.state_name,msg_no,request_no  FROM  book AS b LEFT OUTER JOIN message ON b.book_no = message.book_no LEFT OUTER JOIN request ON b.book_no = request.book_no AND request.reply='n',book_state  WHERE b.seller = '$_SESSION[user]' AND b.book_state_no = book_state.book_state_no AND b.book_state_no IN (1,2,5)  GROUP BY b.book_no ".$WHERE." LIMIT $start, $pens_per_page";

$tmp = mysql_query($sql, $link);
$sell_book_num = mysql_num_rows($tmp);
$sell_book_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($sell_book_list, $row);
}	
for($i=0;$i< $sell_book_num;$i++)
{
	if($sell_book_list[$i][msg_no]==''&& $sell_book_list[$i]['COUNT(b.book_no)'] ==1)
		$sell_book_list[$i]['COUNT(b.book_no)'] = $sell_book_list[$i]['COUNT(b.book_no)'] -1;
	} 
for($i=0;$i< $sell_book_num;$i++){
	if($sell_book_list[$i][book_state_no] == 2){
		putenv("TZ=Asia/Taipei");	
		$datetime = explode(" ",$sell_book_list[$i][on_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+14, $date[0]);
		$t2 = time();
	
		$remain_seconds = $t1 - $t2;
		if($remain_seconds/(60*60*24) >= 1){
			$remain_time[$i] = floor($remain_seconds/(60*60*24)).'天';		
		}
		else if($remain_seconds/(60*60) >= 1){
			$remain_time[$i] = floor($remain_seconds/(60*60)).'小時';		
		}
		else if($remain_seconds/60 >= 1){
		$remain_time[$i] = floor($remain_seconds/60).'分鐘';
		}
	
	} 
	else{
			$remain_time[$i] = '-';	
	}
}
//中間列表END

?>
<html>
<script type="text/javascript">
	function sort( s )
	{	
		document.my_sell_form.sort_ctrl.value = s;
		document.my_sell_form.submit();
	}
	function cancel(b_no)
	{
		var answer = confirm("確定要取消刊登?");
			if(answer)
			{	
				document.my_sell_form.cancel_ctrl.value = 1;
				document.my_sell_form.cancel_num.value = b_no;
				alert("已成功取消刊登!");
				document.my_sell_form.submit();
			}
	}
        function goedit( no )
	{		
		document.my_sell_form.action = "book_edit.php?book_no="+no;
		document.my_sell_form.submit();		
	}
        function repost(b_no)
	{
		var answer = confirm("確定要重新刊登?");
			if(answer)
			{	
				document.my_sell_form.repost_ctrl.value = 1;
				document.my_sell_form.repost_num.value = b_no;
				document.my_sell_form.submit();
                                alert("已成功申請重新刊登!");
			}
	}
	function pageGO( pagenum )
	{	
		document.my_sell_form.page.value = pagenum;
		document.my_sell_form.submit();
	}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php");?><div id="area">
<div id="left"><?php include("left_my.php");?></div>
<div id="main" class="main">
	<p class="line30T" style="text-align: left">會員中心 - 我賣的書</p>
	<form method="POST" name="my_sell_form" action="my_sell.php">
	<table border="0" width="650" >
	<tr>
		<td align="center" height="32" colspan="7">
		<p align="right">排序方式： <a href="#" onClick="sort(1)">售價 </a>| <a href="#" onClick="sort(2)">剩餘時間</a> | <a href="#" onClick="sort(3)">留言數</a> | <a href="#" onClick="sort(4)">狀態</a></td>
	</tr>
	<tr>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32">預覽圖</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="188">
		書名</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="80">
		原價/售價</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="75">
		剩餘時間</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="62">
		留言數</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="67">
		狀態</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="88">
		操作</td>
	</tr>
	<?php
		for($i=0;$i< $sell_book_num; $i++){
			echo '<tr>';
			echo '<td>';
			echo '<img border="0" src="'.$sell_book_list[$i][img].'" width="90" height="110"></td>';
			echo '<td align="left" width="188">';
			echo '<p align="center"><a href="sell_detail.php?book_no='.$sell_book_list[$i][book_no].'">'.$sell_book_list[$i][book_name].'</a></p>';
			echo '<p align="center"><font color="#808080">作者：'.$sell_book_list[$i][author].'</font></td>';
			echo '<td align="center" width="80">'.$sell_book_list[$i][old_price].' / '.$sell_book_list[$i][new_price].' 元</td>';
			echo '<td align="center" width="75">'.$remain_time[$i].'</td>';
			echo '<td width="62" align="center">'.$sell_book_list[$i]['COUNT(b.book_no)'].'</td>';
			echo '<td width="67" align="center">'.$sell_book_list[$i][state_name].'</td>';
			echo '<td width="88" align="center"><input type="button" onClick="goedit('.$sell_book_list[$i][book_no].')" ';if($sell_book_list[$i][book_state_no]==2)echo 'disabled';echo ' value="編輯內容"  name="B8">';
			echo '<p>';if($sell_book_list[$i][book_state_no]!=5) echo'<input type="button" onClick="cancel('.$sell_book_list[$i][book_no].')" value="取消刊登"  name="B6"></td>';
                                   else{ echo'<input type="button" onClick="repost('.$sell_book_list[$i][book_no].')"';if($sell_book_list[$i][request_no]!= '')echo ' disabled';echo ' value="重新刊登"  name="B7"></td>';}
			echo '</tr>';
		}
	?>
	<tr>
		<td colspan="5">
		<p align="center">
			<a href="#" onClick="pageGO(1)"><img border="0" src="images/prev.gif" width="13" height="11" title="最前頁"></a>
			<a href="#" onClick="pageGO(<?php echo $page_now-1; ?>)"><img border="0" src="images/pre.gif" width="11" height="11" title="上一頁"></a>
			<?php
				for($i=$page_list_first; $i<= $page_list_last; $i++){
					echo '<a href="#" onClick="pageGO('.$i.')">'.$i.' </a>';
				}
			?>
			<a href="#" onClick="pageGO(<?php echo $page_now+1; ?>)"><img border="0" src="images/next.gif" width="11" height="11" title="下一頁"></a>
			<a href="#" onClick="pageGO(<?php echo $pages_total; ?>)"><img border="0" src="images/nexta.gif" width="11" height="11" title="最末頁"></a>
			<br>
			第 <?php echo $page_now.'/'.$pages_total; ?> 頁　共 <?php echo $pens_total; ?> 筆</td>
	</tr>
	</table>
		<input type="hidden" name="sort_ctrl">
		<input type="hidden" name="cancel_ctrl">
		<input type="hidden" name="cancel_num">
                <input type="hidden" name="repost_ctrl">
                <input type="hidden" name="repost_num">
		<input type="hidden" name="page">
	</form>	
	</div>
<p>&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>	