<?php
//檢查是否登入
include("conponent/loginCheck.php");
if($_POST[left_state] == '') header("location:book_index.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

//檢查找不到此人START
$sql = "SELECT * FROM member WHERE id = '$_POST[left_id]'";
$tmp = mysql_query($sql, $link);
$check = mysql_fetch_array($tmp);
if($check == '') header("location:book_index.php?NotFound=1");
//檢查找不到此人END
//取消登錄START
if($_POST[UpDownControl] == 3){
	putenv("TZ=Asia/Taipei");
	$on_time = date("Y-m-d H:i:s");
	$sql = "UPDATE book SET book_state_no = 6, unsale_time = '0000-00-00 00:00:00', finish_time = '$on_time', buy_time = '$on_time' , buyer = '$_POST[left_id]', new_price = fee + b_storage WHERE book_no = $_POST[dis_book_no]";
	mysql_query($sql, $link);
	
	//REMIND	
	$sql = "SELECT book_name, seller FROM book WHERE book_no = $_POST[dis_book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所登錄的$book_row[book_name]已被管理員取消。', '2', '$on_time', '$book_row[seller]')";
	mysql_query($sql, $link);
}
//取消登錄END
//處理收款START
if($_POST[control] == 1){
	//UPDATE
	$sql = "UPDATE book SET  trade_state = 'y' WHERE  book_no = $_POST[book_no]";
	mysql_query($sql, $link);
	
	//REMIND
	putenv("TZ=Asia/Taipei");
	$release_time = date("Y-m-d H:i:s");
	$sql = "SELECT book_name, seller FROM book WHERE book_no = $_POST[book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所販賣的$book_row[book_name]已經可以前來取款。', '2', '$release_time', '$book_row[seller]')";
	mysql_query($sql, $link);
}
//處理收款END

//處理付款START
else if($_POST[control] == 2){
	//UPDATE
	putenv("TZ=Asia/Taipei");
	$time = date("Y-m-d H:i:s");
	$sql = "UPDATE book SET  finish_time = '$time', book_state_no = 4, trade_state = 'n'  WHERE  book_no = $_POST[book_no]";
	mysql_query($sql, $link);
	
	//REMIND	
	$sql = "SELECT book_name, buyer FROM book WHERE book_no = $_POST[book_no]";	
	$tmp = mysql_query($sql, $link);
	$book_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您所購買的$book_row[book_name]已經付款成功。', '2', '$time', '$book_row[buyer]')";
	mysql_query($sql, $link);
}
//處理付款END

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

if($_POST[left_state] == 0){
	$sql = "SELECT * FROM book WHERE book_state_no IN (1,2,5) AND seller = '$_POST[left_id]'";
}else{
	$sql = "SELECT * FROM announce WHERE type_no = $_POST[left_state] AND seller = '$_POST[left_id]'";
}
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

//處理中間列表START
if($_POST[left_state] == 0){
	$WHERE = "AND book.book_state_no IN (1,2,5) AND seller = '$_POST[left_id]'";
}else{
	$WHERE = "AND book.book_state_no = $_POST[left_state] AND seller = '$_POST[left_id]'";
}
$sql = "SELECT book_no, state_name, book_name, reg_time FROM book, book_state WHERE book.book_state_no = book_state.book_state_no ".$WHERE." ORDER BY book.book_no DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$book_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($book_list, $row);
}

$sql = "SELECT book_no, book_name, buy_time, new_price FROM book WHERE book_state_no IN (3,6) AND trade_state = 'n' AND buyer = '$_POST[left_id]'";
$tmp = mysql_query($sql, $link);
$buy_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($buy_list, $row);
}

$sql = "SELECT book_no, book_name, buy_time, new_price, fee, b_storage, (new_price - fee - b_storage) total FROM book WHERE book_state_no = 3 AND trade_state = 'y' AND seller = '$_POST[left_id]'";
$tmp = mysql_query($sql, $link);
$sell_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($sell_list, $row);
}
//處理中間列表END
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
	function pageGO( pagenum )
	{	
		document.book_form1.page.value = pagenum;
		document.book_form1.submit();
	}
	function doubleCheck1( row, bno )
	{
		var price = document.getElementById("table2").rows[row].cells[2].innerHTML;
		var answer = confirm("確認已收款項 " + price + " 元?");
		if(answer)
		{
			document.book_form2.book_no.value = bno;
			document.book_form2.submit();
		}
	}
	function doubleCheck2( row, bno )
	{
		var price = document.getElementById("table3").rows[row].cells[5].innerHTML;
		var answer = confirm("確認已付款項 " + price + " 元?");
		if(answer)
		{
			document.book_form3.book_no.value = bno;
			document.book_form3.submit();
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
<div id="left"><?php include("left_book.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	二手書管理 - <?php echo $_POST[left_id]; ?></p>
	<form method="POST" name="book_form1" action="book.php">
<table border="1" id="table1" width="650">	
	<tr>
		<td class="tdT" width="81" align="center">狀態</td>
		<td class="tdT" width="410" align="center">書名</td>
		<td class="tdT" width="128" align="center">登錄時間</td>
	</tr>
	<?php
	for($i=0; $i< sizeof($book_list); $i++){
		echo '<tr>';		
		echo '<td width="81" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$book_list[$i][state_name].'</td>';
		echo '<td width="410" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo ' align="center"><a href="book_detail.php?book_no='.$book_list[$i][book_no].'">'.$book_list[$i][book_name].'</a></td>';
		echo '<td width="128" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$book_list[$i][reg_time].'</td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td colspan="4">
		<p align="center">
			<font face="Webdings"><a href="#" onClick="pageGO(1)">7</a></font>
			<font face="Webdings"><a href="#" onClick="pageGO(<?php echo $page_now-1; ?>)">3</a></font>
			<?php
				for($i=$page_list_first; $i<= $page_list_last; $i++){
					echo '<a href="#" onClick="pageGO('.$i.')">'.$i.' </a>';
				}
			?>
			<font face="Webdings"><a href="#" onClick="pageGO(<?php echo $page_now+1; ?>)">4</a></font>
			<font face="Webdings"><a href="#" onClick="pageGO(<?php echo $pages_total; ?>)">8</a></font>
			<br>
			第 <?php echo $page_now.'/'.$pages_total; ?> 頁　共 <?php echo $pens_total; ?> 筆</td>
	</tr>
</table>
<input type="hidden" name="page">
<input type="hidden" name="left_state" value="<?php echo $_POST[left_state]; ?>">
<input type="hidden" name="left_id" value="<?php echo $_POST[left_id]; ?>">
</form>
	
<p class="line30T">已下標二手書</p>
<form method="POST" name="book_form2" action="book.php">
<table border="1" id="table2" width="650">
	<tr>
		<td class="tdT" width="357" align="center">書名</td>
		<td class="tdT" width="131" align="center">下標時間</td>
		<td class="tdT" width="65" align="center">價錢</td>
		<td class="tdT" width="56" align="center">管理</td>
	</tr>
	<?php
	for($i=0; $i< sizeof($buy_list); $i++){
		echo '<tr>';
		echo '<td width="357" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$buy_list[$i][book_name].'</td>';
		echo '<td width="131" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$buy_list[$i][buy_time].'</td>';
		echo '<td width="65" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$buy_list[$i][new_price].'</td>';
		echo '<td width="56" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><input type="button" onClick="doubleCheck1('.($i+1).','.$buy_list[$i][book_no].')" value="收款" name="B2"></td>';
		echo '</tr>';
	}
	?>
</table>
<input type="hidden" name="control" value="1">
<input type="hidden" name="book_no">
<input type="hidden" name="left_state" value="<?php echo $_POST[left_state]; ?>">
<input type="hidden" name="left_id" value="<?php echo $_POST[left_id]; ?>">
</form>
	
		<p class="line30T">
	已售出二手書</p>
		<form method="POST" name="book_form3" action="book.php">
<table border="1" id="table3" width="650">
	<tr>
		<td class="tdT" width="218" align="center">書名</td>
		<td class="tdT" width="80" align="center">售出時間</td>
		<td class="tdT" width="49" align="center">價錢</td>
		<td class="tdT" width="54" align="center">手續費</td>
		<td class="tdT" width="54" align="center">保管費</td>
		<td class="tdT" width="66" align="center">應付帳款</td>
		<td class="tdT" width="58" align="center">管理</td>
	</tr>
	<?php
	for($i=0; $i< sizeof($sell_list); $i++){
		echo '<tr>';
		echo '<td width="218" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][book_name].'</td>';
		echo '<td width="80" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][buy_time].'</td>';
		echo '<td width="49" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][new_price].'</td>';
		echo '<td width="54" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][fee].'</td>';
		echo '<td width="54" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][b_storage].'</td>';
		echo '<td width="66" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$sell_list[$i][total].'</td>';
		echo '<td width="58" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><input type="button" onClick="doubleCheck2('.($i+1).','.$sell_list[$i][book_no].')" value="付款" name="B2"></td>';
		echo '</tr>';
	}
	?>
</table>
<input type="hidden" name="control" value="2">
<input type="hidden" name="book_no">
<input type="hidden" name="left_state" value="<?php echo $_POST[left_state]; ?>">
<input type="hidden" name="left_id" value="<?php echo $_POST[left_id]; ?>">
</form>
</div></div>
<?php include("footer.php"); ?>
</body>

</html>