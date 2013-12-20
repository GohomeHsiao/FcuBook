<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');

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
//分類名稱
$sql = "SELECT name FROM announce_type ";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);	
}
//分類END
//處理中間列表START
if($_GET[type] == 0) $WHERE = '';
else $WHERE = 'AND announce.type_no ='.$_GET[type];
$sql = "SELECT * FROM announce, announce_type WHERE announce.type_no = announce_type.type_no ".$WHERE." ORDER BY announce.release_time DESC LIMIT $start, $pens_per_page";
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
//處理中間列表END
$cnt = 0;
?>
<html>
	
<script type="text/javascript">

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
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php");?><div id="area">
<div id="left">
<?php include("left_system.php"); ?></div>
<div id="main" class="main">
	<p style="text-align: left" class="line30T">系統公告 - <?php if($_GET[type]==0)echo '綜合';else echo $type_list[$_GET[type]-1][name]; ?></p>
	<form method="POST" name="ann_form" action="system.php?type=<?php echo $_GET[type]; ?>">
	<table border="0" width="650" id="table1" >
		<tr>
			<td width="60" bgcolor="#FFFFCC" class="line30T" align="center"><b>分類</b></td>
			<td width="110" bgcolor="#FFFFCC" class="line30T" align="center"><b>公告日期</b></td>
			<td bgcolor="#FFFFCC" class="line30T">
			<p align="center"><b>主題</b></td>
		</tr>
		<?php 
				for($i=0;$i< $ann_num; $i++){
                                     if($ann_list[$i][istop]=='y'){
					echo '<tr>';
					echo '<td width="60" class="line30" align="center"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$ann_list[$i][name].'</td>';
					echo '<td width="110" class="line30" align="center"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$get_time[$i].'</td>';
					echo '<td class="line30"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><a href="system_detail.php?type='.$_GET[type].'&ann_no='.$ann_list[$i][ann_no].'">'.$ann_list[$i][title].'</a></td>';
					echo '</tr>';
                                        $cnt++;
                                     }
				}
                                for($i=0;$i< $ann_num; $i++){
                                      if($ann_list[$i][istop]!='y'){
					echo '<tr>';
					echo '<td width="60" class="line30" align="center"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$ann_list[$i][name].'</td>';
					echo '<td width="110" class="line30" align="center"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '>'.$get_time[$i].'</td>';
					echo '<td class="line30"';if($cnt%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><a href="system_detail.php?type='.$_GET[type].'&ann_no='.$ann_list[$i][ann_no].'">'.$ann_list[$i][title].'</a></td>';
					echo '</tr>';
                                        $cnt++;
                                      }
				}
		?>
		<tr>
		<td colspan="6" height="50">
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
	<input type="hidden" name="page">
</form>
</div>
<p>&nbsp;</div>
<?include("footer.php");?>
</body>

</html>