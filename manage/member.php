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
$pens_per_page = 10;
$page_list_max = 10;

if($_POST[left_id] != ''){
	$sql = "SELECT * FROM member WHERE id = '$_POST[left_id]'";
}
else if($_POST[left_state] == 0){
	$sql = "SELECT * FROM member";
}else{
	$sql = "SELECT * FROM member WHERE state_no = $_POST[left_state]";
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
if($_POST[left_id] != ''){
	$WHERE = "AND id = '$_POST[left_id]'";
}
else if($_POST[left_state] == 0){
	$WHERE = "";
}else{
	$WHERE = "AND state_no = $_POST[left_state]";
}
$sql = "SELECT * FROM member, mem_state WHERE member.state_no =mem_state.member_state_no ".$WHERE." ORDER BY registry_time ASC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$member_num = mysql_num_rows($tmp);
$member_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($member_list, $row);
}
//處理中間列表END

//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[mem_id];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM member WHERE id = '$del_list[$i]'";		
		mysql_query($sql, $link);
	}	
	header("location:member.php");
}
//處理刪除END

?>
<html>
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("mem_id[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("mem_id[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的會員嗎?");
		if(answer)
		{
			document.member_form.control_del.value = 1;
			document.member_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.member_form.page.value = pagenum;
		document.member_form.submit();
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
<div id="left">
	<?php include("left_member.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	會員管理</p>
<form method="POST" name="member_form" action="member.php">
<table border="1" id="table1" width="650">
	<tr>
		<td style="vertical-align: top"><a href="#"  onClick="delCheck()"><img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a></td>
		<td style="vertical-align: top">
		<p align="center"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
		<td colspan="3" align="right">
		&nbsp;</td>
	</tr>
	<tr>
		<td class="tdT" width="45" align="center">選取</td>
		<td class="tdT" width="82" align="center">狀態</td>
		<td class="tdT" width="290" align="center">學號</td>
		<td class="tdT" width="126" align="center">註冊時間</td>
		<td class="tdT" width="56" align="center">管理</td>
	</tr>
	<?php
	for($i=0; $i< $member_num; $i++){
		echo '<tr>';
		echo '<td width="45" align="center"'; if($i%2 == 1){ echo 'bgcolor="#DFDFDF"';}  echo '><input type="checkbox" name="mem_id[]" value="'.$member_list[$i][id].'"></td>';
		echo '<td width="82" align="center"'; if($i%2 == 1){ echo 'bgcolor="#DFDFDF"';}  echo '>'.$member_list[$i][state_name].'</td>';
		echo '<td width="290" align="center"'; if($i%2 == 1){ echo 'bgcolor="#DFDFDF"';} echo '>'.$member_list[$i][id].'</td>';
		echo '<td width="126" align="center"'; if($i%2 == 1){ echo 'bgcolor="#DFDFDF"';} echo '>'.$member_list[$i][registry_time].'</td>';
		echo '<td width="56" align="center"'; if($i%2 == 1){ echo 'bgcolor="#DFDFDF"';}  echo '><a href="member_edit.php?id='.$member_list[$i][id].'"><img border="0" src="images/icon_edit.gif" width="29" height="18" title="編輯"></a></td>';
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