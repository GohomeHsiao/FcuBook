
<?php 
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
//處理最新公告START
$sql = "SELECT * FROM announce ORDER BY release_time DESC";
$tmp = mysql_query($sql, $link);
$ann_num = mysql_num_rows($tmp);
$ann_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($ann_list, $row);	
}
$get_time =array();
	for($i=0;$i< $ann_num; $i++){
			$tmp1 = substr($ann_list[$i][2],0,10);
			array_push($get_time, $tmp1);
	}
//處理最新公告END

//處理最新上架START
$sql = "SELECT * FROM book WHERE book_state_no = 2 ORDER BY on_time DESC";
$tmp = mysql_query($sql, $link);
$book_num = mysql_num_rows($tmp);
$book_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($book_list, $row);	
}
$getb_time =array();
	for($i=0;$i< $book_num; $i++){
			$tmp1 = substr($book_list[$i][2],0,10);
			array_push($getb_time, $tmp1);
	}
//處理最新上架END

?>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_index.php"); ?></div>
<div id="main" class="main"><table border="0" width="650" id="table3">
	<tr>
		<td width="80%" class="line30T" bgcolor="#FFFCC7">最新公告</td>
		<td class="line30T" bgcolor="#FFFCC7">公告日期</td>
	</tr>
	<?php 
	for($i=0;$i < 5;$i++){
		echo '<tr>';
		echo '<td width="80%" class="line30"><a href="system_detail.php?type='.$ann_list[$i][type_no].'&ann_no='.$ann_list[$i][ann_no].'">'.$ann_list[$i][title].'</td>';
		echo '<td class="line30">'.$get_time[$i].'</td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="right"><br>
		<a href="system.php">
		<img border="0" src="images/icon-more.gif" width="48" height="13"></a></td>
	</tr>
	</table>
	<p>&nbsp;</p>
	<table border="0" width="650" id="table4">
		<tr>
		<td width="80%" class="line30T" bgcolor="#FFFCC7">最新上架書</td>
		<td class="line30T" bgcolor="#FFFCC7">上架日期</td>
	</tr>
	<?php 
	for($i=0;$i < 5;$i++){
		echo '<tr>';
		echo '<td width="80%" class="line30"><a href="buy_detail.php?book_no='.$book_list[$i][book_no].'">'.$book_list[$i][book_name].'</td>';
		echo '<td class="line30">'.$getb_time[$i].'</td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td width="80%">&nbsp;</td>
		<td align="right"><br>
		<a href="buy.php">
		<img border="0" src="images/icon-more.gif" width="48" height="13"></a></td>
	</tr>
	</table>
	<p style="text-align: center">
	<img border="0" src="images/map.png" width="650" height="927"></div>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>