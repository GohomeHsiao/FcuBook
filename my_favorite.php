<?php 
include("conponent/loginCheck.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY book.new_price DESC";
}
else if($_POST[sort_ctrl] == 2){
	$WHERE = "ORDER BY book.on_time DESC";
}
else if($_POST[sort_ctrl] == 3){
	$WHERE = "ORDER BY book.book_state_no ASC";
}
else
	$WHERE = "ORDER BY book.on_time DESC"; 
$sql = "SELECT * FROM trace,book,book_state WHERE book.book_no = trace.book_no AND book.book_state_no = book_state.book_state_no AND member_id = '$_SESSION[user]' AND book.book_state_no = 2 ".$WHERE;

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
//中間列表START
if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY book.new_price DESC";
}
else if($_POST[sort_ctrl] == 2){
	$WHERE = "ORDER BY book.on_time DESC";
}
else if($_POST[sort_ctrl] == 3){
	$WHERE = "ORDER BY book.book_state_no ASC";
}
else
	$WHERE = "ORDER BY book.on_time DESC"; 
$sql = "SELECT * FROM trace,book,book_state WHERE book.book_no = trace.book_no AND book.book_state_no = book_state.book_state_no AND member_id = '$_SESSION[user]' AND book.book_state_no IN (2,3) ".$WHERE." LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$trace_num = mysql_num_rows($tmp);
$trace_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($trace_list, $row);
}	

for($i=0;$i< $trace_num;$i++){
	if($trace_list[$i][book_state_no] == 2){
		putenv("TZ=Asia/Taipei");	
		$datetime = explode(" ",$trace_list[$i][on_time]);
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

//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[book_no];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM trace WHERE book_no = '$del_list[$i]' AND '$_SESSION[user]' = member_id";		
		mysql_query($sql, $link);
	}	
	header("location:my_favorite.php");
}
//處理刪除END
?>
<html>
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("book_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("book_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的追蹤嗎?");
		if(answer)
		{
			document.trace_form.control_del.value = 1;
			document.trace_form.submit();
		}
	}
	function sort( s )
	{	
		document.trace_form.sort_ctrl.value = s;
		document.trace_form.submit();
	}
	function pageGO( pagenum )
	{	
		document.trace_form.page.value = pagenum;
		document.trace_form.submit();
	}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_my.php"); ?></div>
<div id="main" class="main">
	<p class="line30T" style="text-align: left">會員中心 - 我的追蹤</p>
	<form method="POST" name="trace_form" action="my_favorite.php" >
	<table border="0" width="650">
	<tr>
		<td align="center" height="32">
		<p align="left"><a href="#"  onClick="delCheck()">
		<img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a> </td>
		<td align="center" height="32"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
		<td align="center" height="32" colspan="4">
		<p align="right">排序方式：<a href="#" onClick="sort(1)">售價</a> | <a href="#" onClick="sort(2)">剩餘時間</a>&nbsp; | <a href="#" onClick="sort(3)">狀態</a></td>
	</tr>
	<tr>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="45">
		選取</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="90">
		預覽圖</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="269">
		書名</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="87">
		原價/售價</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="83">
		剩餘時間</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="67">
		狀態</td>
	</tr>
	<?php
		for($i=0;$i < $trace_num; $i++){
			echo '<tr>';
			echo '<td width="45">';
			echo '<p align="center"><input type="checkbox" name="book_no[]" value="'.$trace_list[$i][book_no].'"></p></td>';
			echo '<td width="90">';
			echo '<img border="0" src="'.$trace_list[$i][img].'" width="90" height="110"></td>';
			echo '<td align="left" width="269">';
			echo '<p align="center"><a href="buy_detail.php?book_no='.$trace_list[$i][book_no].'">'.$trace_list[$i][book_name].'</a></p>';
			echo '<p align="center"><font color="#808080">作者：'.$trace_list[$i][author].'</font></td>';
			echo '<td align="center" width="87">'.$trace_list[$i][old_price].'元 / '.$trace_list[$i][new_price].'元</td>';
			echo '<td align="center" width="83">'.$remain_time[$i].'</td>';
			echo '<td width="67" align="center">'.$trace_list[$i][state_name].'</td>';
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
	<input type="hidden" name="control_del">
	<input type="hidden" name="page">
	</div>
	</form>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>