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

if($_GET[type] == 0){
	$sql = "SELECT * FROM announce";
}else{
	$sql = "SELECT * FROM announce WHERE type_no = $_GET[type]";
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
if($_GET[type] == 0) $WHERE = '';
else $WHERE = 'AND announce.type_no = '.$_GET[type];
$sql = "SELECT * FROM announce, announce_type WHERE announce.type_no = announce_type.type_no ".$WHERE." ORDER BY announce.ann_no DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$ann_list = array();
$ann_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($ann_list, $row);
}
//處理中間列表END

//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[ann_no];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM announce WHERE ann_no = ".$del_list[$i];		
		mysql_query($sql, $link);
	}	
	header("location:note.php?type=".$_GET[type]);
}
//處理刪除END

?>
<html>
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("ann_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("ann_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的公告嗎?");
		if(answer)
		{
			document.ann_form.control_del.value = 1;
			document.ann_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.ann_form.page.value = pagenum;
		document.ann_form.submit();
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
<div id="left"><?php include("left_note.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	公告管理 - <?php if($_GET[type] == 0) echo "綜合"; else echo $left_list[$_GET[type]-1][name]; ?></p>
	<form method="POST" name="ann_form" action="note.php?type=<?php echo $_GET[type]; ?>">
<table border="1" id="table1" width="650">
	<tr>
		<td style="vertical-align: top"><a href="#"  onClick="delCheck()">
			<img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a></td>
		<td colspan="2" style="vertical-align: top">
		<p align="center"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
		<td colspan="3" align="right">
		<a href="note_add.php"><img border="0" src="images/icon_add.png" width="32" height="32" title="新增"></a></td>
	</tr>
	<tr>
		<td class="tdT" width="45" align="center">選取</td>
		<td class="tdT" width="41" align="center">置頂</td>
		<td class="tdT" width="44" align="center">分類</td>
		<td class="tdT" width="331" align="center">主題</td>
		<td class="tdT" width="78" align="center">公告時間</td>
		<td class="tdT" width="56" align="center">管理</td>
	</tr>
	<?php
	for($i=0; $i< $ann_num; $i++){
		echo '<tr>';
		echo '<td width="45" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><input type="checkbox" name="ann_no[]" value="'.$ann_list[$i][ann_no].'"></td>';
		echo '<td width="41" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo ($ann_list[$i][istop] == 'y') ? '>是</td>' : '>否</td>';
		echo '<td width="44" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$ann_list[$i][name].'</td>';
		echo '<td width="331" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$ann_list[$i][title].'</td>';
		echo '<td width="78" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$ann_list[$i][release_time].'</td>';
		echo '<td width="56" align="center"'; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><a href="note_edit.php?type='.$_GET[type].'&ann_no='.$ann_list[$i][ann_no].'"><img border="0" src="images/icon_edit.gif" width="29" height="18" title="編輯"></a></td>';
		echo '</tr>';
	}
	?>	
	<tr>
		<td colspan="6">
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
</div><?php include("footer.php"); ?>
</body>

</html>