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
else {
	$WHERE = "ORDER BY book.finish_time DESC";
}
$sql = "SELECT book_no,book_name,author,finish_time,new_price,old_price, img FROM book WHERE  (buyer = '$_SESSION[user]' OR seller = '$_SESSION[user]') AND book.book_state_no IN (4,6) ".$WHERE;

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

if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY book.new_price DESC";
}
else {
	$WHERE = "ORDER BY book.finish_time DESC";
}
$sql = "SELECT book_no,book_name,author,finish_time,new_price,old_price, img FROM book WHERE  (buyer = '$_SESSION[user]' OR seller = '$_SESSION[user]') AND book.book_state_no IN (4,6) ".$WHERE." LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$my_num = mysql_num_rows($tmp);
$my_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($my_list, $row);
}	
?>
<html>
<script type="text/javascript">
	function sort( s )
	{	
		document.mylist_form.sort_ctrl.value = s;
		document.mylist_form.submit();
	}
	function pageGO( pagenum )
	{	
		document.mylist_form.page.value = pagenum;
		document.mylist_form.submit();
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
	<p class="line30T" style="text-align: left">會員中心 - 我的交易紀錄</p>
	<form method="POST" name="mylist_form" action="my_list.php" >
	<table border="0" width="650" >
	<tr>
		<td align="center" height="32" colspan="4">
		<p align="right">排序方式： <a href="#" onClick="sort(1)">售價</a> | <a href="#" onClick="sort(2)">完成時間</a></td>
	</tr>
	<tr>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="90">
		預覽圖</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="350">
		書名</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="104">
		原價/售價</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="98">
		完成時間</td>
	</tr>
	<?php
		for($i=0;$i < $my_num; $i++)
		{
				echo '<tr>';
				echo '<td width="90">';
				echo '<img border="0" src="'.$my_list[$i][img].'" width="90" height="110"></td>';
				echo '<td align="left" width="350">';
				echo '<p align="center">'.$my_list[$i][book_name].'</p>';
				echo '<p align="center"><font color="#808080">作者：'.$my_list[$i][author].'</font></td>';
				echo '<td align="center" width="104">'.$my_list[$i][old_price].'元 / '.$my_list[$i][new_price].'元</td>';
				echo '<td width="98" align="center">'.$my_list[$i][finish_time].'</td>';
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
		<input type="hidden" name="page">
	</div>
	</form>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>