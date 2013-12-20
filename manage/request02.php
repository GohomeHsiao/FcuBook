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

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

$sql = "SELECT * FROM request WHERE reply = 'y'";
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
$sql = "SELECT * FROM request,book WHERE request.book_no = book.book_no AND reply = 'y' ORDER BY request_time DESC LIMIT $start, $pens_per_page";
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
	重刊登管理 - 已回答</p>
	<form method="POST" name="request_form" action="request02.php">
		<table border="1" id="table1" width="650">
			<tr>
				<td class="tdT" width="392" align="center">申請書籍</td>
				<td class="tdT" width="88" align="center">申請人</td>
				<td class="tdT" width="140" align="center">申請時間</td>
			</tr>
			<?php
			for($i=0; $i< sizeof($request_list); $i++){
				echo '<tr>';
				echo '<td width="392" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$request_list[$i][book_name].'</td>';
				echo '<td width="88" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$request_list[$i][seller].'</td>';
				echo '<td width="140" align="center"'; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$request_list[$i][request_time].'</td>';
				echo '</tr>';
			}
			?>
			<tr>
			<td colspan="3">
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
	</form>
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>