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

putenv("TZ=Asia/Taipei");
$day_start = explode("/", $_POST[day_start]);
$day_end = explode("/", $_POST[day_end]);

$sec1 = mktime(0,0,0, $day_start[0], $day_start[1], $day_start[2]);
$sec2 = mktime(0,0,0, $day_end[0], $day_end[1], $day_end[2]);

$total_sec = $sec2 - $sec1;
$total_min = (int)($total_sec / 60);
$total_hrs = (int)($total_min / 60);
$total_day = (int)($total_hrs / 24) + 1;
/*
//處理頁數START
$pens_per_page = 10;
$page_list_max = 10;

$pens_total = $total_day;
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

*/
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
$month_list = array();
$total_revenue = 0;
$total_expediture = 0;
$total_profit = 0;
if($day_start[0] < 10){
	$day_start[0] = substr($day_start[0],1);
}
if($day_start[1] < 10){
	$day_start[1] = substr($day_start[1],1);
}
$today =  $day_start[2].'-'.$day_start[0].'-'.$day_start[1];
for($i=1; $i<= $total_day; $i++){	
	$ex_today = explode("-",$today);
	$tomarrow  = getdate(mktime(0,0,0, $ex_today[1], $ex_today[2]+1, $ex_today[0]));
	$tomarrow = $tomarrow[year].'-'.$tomarrow[mon].'-'.$tomarrow[mday];
	
	$sql = "SELECT * FROM book WHERE finish_time BETWEEN '$today' AND '$tomarrow'";
	$tmp = mysql_query($sql, $link);
	
	$revenue = 0;
	$expediture = 0;
	$profit = 0;	
	while($row = mysql_fetch_array($tmp)){
		if($row[book_state_no] == 4){ //交易完成
			$tail = array('revenue' => $row[new_price],
										'expediture' => $row[new_price] - $row[fee] - $row[b_storage],
										'profit' => $row[fee] + $row[b_storage]
										);
		}
		else if($row[book_state_no] == 6){ //取消登錄
			$tail = array('revenue' => $row[fee] + $row[b_storage],
										'expediture' => 0,
										'profit' => $row[fee] + $row[b_storage]
										);
		}		
		$revenue += $tail[revenue];
		$expediture += $tail[expediture];
		$profit += $tail[profit];		
	}
	$day_row = array('ymd' => $today,
									 'revenue' => $revenue,
									 'expediture' => $expediture,
									 'profit' => $profit
									 );
	$total_revenue += $revenue;
	$total_expediture += $expediture;
	$total_profit += $profit;
	array_push($month_list, $day_row);
	
	$tomarrow = explode("-",$tomarrow);
	$today  = getdate(mktime(0,0,0, $tomarrow[1], $tomarrow[2], $tomarrow[0]));
	$today = $today[year].'-'.$today[mon].'-'.$today[mday];
}
//處理中間列表END
?>
<html>
<script type="text/javascript">
  function pageGO( pagenum )
	{	
		document.acc_form.page.value = pagenum;
		document.acc_form.submit();
	}
	function printPage()
	{
       window.print();
	}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="javascript/common.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<title>逢甲大學二手書交易管理後台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_account.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	查詢歷史報表</p>
	<form method="POST" name="acc_form" action="account_history.php">
<table border="1" id="table1" width="650">
	<tr>
		<td style="vertical-align: top">
		<a href="#" onClick="printPage()"><img border="0" src="images/print.gif" width="45" height="20"></a></td>
		<td colspan="4" style="vertical-align: top">
		<p align="center">&nbsp;</td>
	</tr>
	<tr>
		<td class="tdT" width="45" align="center">序號</td>
		<td class="tdT" width="221" align="center">日期</td>
		<td class="tdT" width="108" align="center">收入</td>
		<td class="tdT" width="104" align="center">支出</td>
		<td class="tdT" width="122" align="center">盈餘</td>
	</tr>
	<?php
	for($i=0; $i< sizeof($month_list); $i++){
		echo '<tr>';
		echo '<td width="45" align="center">'.($i+1).'</td>';
		echo '<td width="221" align="center"><a href="account_day.php?today='.$month_list[$i][ymd].'">'.$month_list[$i][ymd].'</a></td>';
		echo '<td width="108" align="center">'.$month_list[$i][revenue].'</td>';
		echo '<td width="104" align="center">'.$month_list[$i][expediture].'</td>';
		echo '<td width="122" align="center">'.$month_list[$i][profit].'</td>';
		echo '</tr>	';
	}
	?>	
	<tr>
		<td colspan="5">
		<p align="right" class="line30T">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">
		<p align="right" class="font11B">總收入 <?php echo $total_revenue; ?>元 / 總支出 <?php echo $total_expediture; ?>元 / 總盈餘 <?php echo $total_profit; ?>元</td>
	</tr>
</table>
<input type="hidden" name="page">
	</form>
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>