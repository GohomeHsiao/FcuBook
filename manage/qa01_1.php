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

//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[ask_no];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM ask WHERE ask_no = ".$del_list[$i];		
		mysql_query($sql, $link);
	}	
}
//處理刪除END

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

$sql = "SELECT * FROM ask WHERE NOT EXISTS (SELECT * FROM ask_ans WHERE ask.ask_no = ask_ans.ask_no)";
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
$sql = "SELECT * FROM ask WHERE NOT EXISTS (SELECT * FROM ask_ans WHERE ask.ask_no = ask_ans.ask_no) ORDER BY ask_time DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$ask_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($ask_list, $row);
}
//處理中間列表END
?>
<html>
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("ask_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("ask_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的問題嗎?");
		if(answer)
		{
			document.ask_form.control_del.value = 1;
			document.ask_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.ask_form.page.value = pagenum;
		document.ask_form.submit();
	}
	function goAnswer(ano)
	{	
		document.ask_form.action = "qa_reply.php?ask_no=" + ano;
		document.ask_form.submit();
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
<div id="left"><?php include("left_qa01.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	客戶提問管理 - 未回答</p>
	<form method="POST" name="ask_form" action="qa01_1.php">
		<table border="1" id="table1" width="650">
			<tr>
				<td style="vertical-align: top"><a href="#"  onClick="delCheck()">
				<img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a></td>
				<td style="vertical-align: top">
				<p align="left"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
				<td colspan="3" align="right">&nbsp;</td>
			</tr>
			<tr>
				<td class="tdT" width="45" align="center">選取</td>
				<td class="tdT" width="340" align="center">主題</td>
				<td class="tdT" width="80" align="center">發問者</td>
				<td class="tdT" width="78" align="center">發問時間</td>
				<td class="tdT" width="56" align="center">管理</td>
			</tr>
			<?php
			for($i=0; $i< sizeof($ask_list); $i++){
				echo '<tr>';
				echo '<td width="45" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><input type="checkbox" name="ask_no[]" value="'.$ask_list[$i][ask_no].'"></td>';
				echo '<td width="340" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$ask_list[$i][subject].'</td>';
				echo '<td width="80" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$ask_list[$i][asker].'</td>';
				echo '<td width="78" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '>'.$ask_list[$i][ask_time].'</td>';
				echo '<td width="56" align="center" '; if($i%2 == 1) echo 'bgcolor="#DFDFDF"'; echo '><input type="button" onClick="goAnswer('.$ask_list[$i][ask_no].')" value="回答"></td>';
				echo '</tr>';
			}
			?>
		<tr>
			<td colspan="5">
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
		<input type="hidden" value="0" name="control_del">
	</form>
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>