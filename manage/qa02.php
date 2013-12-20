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
	$sql = "SELECT * FROM qa";
}else{
	$sql = "SELECT * FROM qa WHERE type_no = $_GET[type]";
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
else $WHERE = 'AND qa.type_no = '.$_GET[type];
$sql = "SELECT * FROM qa, q_type WHERE qa.type_no = q_type.q_type_no ".$WHERE." ORDER BY qa.qa_no DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$qa_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($qa_list, $row);
}
//處理中間列表END

//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[qa_no];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM qa WHERE qa_no = ".$del_list[$i];		
		mysql_query($sql, $link);
	}	
	header("location:qa02.php?type=".$_GET[type]);
}
//處理刪除END

?>
<html>
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("qa_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("qa_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的問與答嗎?");
		if(answer)
		{
			document.qa_form.control_del.value = 1;
			document.qa_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.qa_form.page.value = pagenum;
		document.qa_form.submit();
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
<div id="left"><?php include("left_qa02.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	常見問題管理 - <?php if($_GET[type] == 0) echo "綜合"; else echo $left_list[$_GET[type]-1][q_name]; ?></p>
	<form method="POST" name="qa_form" action="qa02.php?type=<?php echo $_GET[type]; ?>">
		<table border="1" id="table1" width="650">
			<tr>
				<td style="vertical-align: top" align="center"><a href="#"  onClick="delCheck()">
				<img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a></td>
				<td style="vertical-align: top" align="center"><p align="center"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
				<td style="vertical-align: top" align="center" colspan="3"><p align="right">
					<a href="qa02_add.php"><img border="0" src="images/icon_add.png" width="32" height="32" title="新增"></a></td>
			</tr>
			<tr>
				<td class="tdT" width="45" align="center">選取</td>
				<td class="tdT" width="117" align="center">分類</td>
				<td class="tdT" width="284" align="center">主題</td>
				<td class="tdT" width="108" align="center">發布時間</td>
				<td class="tdT" width="45" align="center">管理</td>
			</tr>
			<?php
			for($i=0; $i< sizeof($qa_list); $i++){
				echo '<tr>';
				echo '<td width="45" align="center" '; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><input type="checkbox" name="qa_no[]" value="'.$qa_list[$i][qa_no].'"></td>';
				echo '<td width="117" align="center" '; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$qa_list[$i][q_name].'</td>';
				echo '<td width="284" align="center" '; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$qa_list[$i][question].'</td>';
				echo '<td width="108" align="center" '; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$qa_list[$i][release_time].'</td>';
				echo '<td width="45" align="center" '; if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><a href="qa02_edit.php?qa_no='.$qa_list[$i][qa_no].'&type='.$_GET[type].'"><img border="0" src="images/icon_edit.gif" width="29" height="18" title="編輯"></a></td>';
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