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

//處理接受拒絕START
putenv("TZ=Asia/Taipei");
	$on_time = date("Y-m-d H:i:s");
	if($_POST[acpt] == 1){//接受上架		
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
	}
	if($_POST[acpt] == 2){//拒絕
		//REMIND	
		$sql = "SELECT book_name, seller FROM book WHERE book_no = $_POST[book_no]";
		$tmp = mysql_query($sql, $link);
		$book_row = mysql_fetch_array($tmp);
		$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('您申請重新刊登$book_row[book_name]的要求被拒絕，請檢查書籍內容是否有誤，有問題可用客戶提問。', '2', '$on_time', '$book_row[seller]')";
		mysql_query($sql, $link);
		
		$sql = "UPDATE request SET reply = 'y' WHERE book_no = $_POST[book_no]";
		mysql_query($sql, $link);
	}
//處理接受拒絕END

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

$sql = "SELECT * FROM request WHERE reply = 'n'";
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
$sql = "SELECT * FROM request,book WHERE request.book_no = book.book_no AND reply = 'n' ORDER BY request_time DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$request_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($request_list, $row);
}
//處理中間列表END
?>
<html>
<script type="text/javascript">	
	function pageGO( pagenum )
	{	
		document.request_form.page.value = pagenum;
		document.request_form.submit();
	}
	function accpt(bno)
	{
		var answer = confirm("確定要接受?");
		if(answer)
		{
			document.request_form.acpt.value = 1;
			document.request_form.book_no.value = bno;
			document.request_form.submit();
		}
	}
	function deny(bno)
	{
		var answer = confirm("確定要拒絕?");
		if(answer)
		{
			document.request_form.acpt.value = 2;
			document.request_form.book_no.value = bno;
			document.request_form.submit();
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
<div id="left"><?php include("left_request.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	重刊登管理 - 未回答</p>
	<form method="POST" name="request_form" action="request01.php">
		<table border="1" id="table1" width="650">
			<tr>
				<td class="tdT" width="320" align="center">申請書籍</td>
				<td class="tdT" width="67" align="center">申請人</td>
				<td class="tdT" width="137" align="center">申請時間</td>
				<td class="tdT" width="85" align="center">上架管理</td>
			</tr>
			<?php
			for($i=0; $i< sizeof($request_list); $i++){
				echo '<tr>';
				echo '<td width="320" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><a href="req_book_detail.php?book_no='.$request_list[$i][book_no].'">'.$request_list[$i][book_name].'</a></td>';
				echo '<td width="67" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$request_list[$i][seller].'</td>';
				echo '<td width="137" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$request_list[$i][request_time].'</td>';
				echo '<td width="85" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><input type="button" onClick="accpt('.$request_list[$i][book_no].')" value="接受" name="B1"><input type="button" onClick="deny('.$request_list[$i][book_no].')" value="拒絕" name="B2"></td>';
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
		<input type="hidden" name="acpt" value="0">
		<input type="hidden" name="book_no">
	</form>
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>