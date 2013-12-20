<?php
//檢查是否登入
include("../conponent/loginCheck.php");
// 取得系統組態
include ("../connectDB/configure.php");
// 連結資料庫
include ("../connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

putenv("TZ=Asia/Taipei");
$today =  $_GET[today];
$ex_today = explode("-",$today);
$tomarrow  = getdate(mktime(0,0,0, $ex_today[1], $ex_today[2]+1, $ex_today[0]));
$tomarrow = $tomarrow[year].'-'.$tomarrow[mon].'-'.$tomarrow[mday];

//處理中間列表START
$sql = "SELECT * FROM book WHERE finish_time BETWEEN '$today' AND '$tomarrow'";
$tmp = mysql_query($sql, $link);
$acc_list = array();
$total_revenue = 0;
$total_expediture = 0;
$total_profit = 0;
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
	$row = array_merge($row, $tail);
	$total_revenue += $row[revenue];
	$total_expediture += $row[expediture];
	$total_profit += $row[profit];
	array_push($acc_list, $row);	
}

//處理中間列表END
?>
<html>

<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Author" content="http://poet.930.info/">
<link rel="stylesheet" href="../poet.css" type="text/css">
<script type="text/javascript">  
	function printPage()
	{
       window.print();
	}
</script>
<style type='text/css'> 
.pagebreak{page-break-after:always} 
.pagenobreak{page-break-after:avoid} 
</style>;
<title>逢甲大學二手書交易管理後台</title>
</head>

<body onLoad="printPage()">

<div id="area"><div class="pagebreak">
<table border="1" id="table1" width="650">	
	<tr>
		<p class="line30T">日報表 <?php echo $_GET[today]; ?></p>
	</tr>
	<tr>
		<td class="font11B" width="45" align="center">序號</td>
		<td class="font11B" width="200" align="center">書名</td>
		<td class="font11B" width="185" align="center">完成時間</td>
		<td class="font11B" width="60" align="center">收入</td>
		<td class="font11B" width="60" align="center">支出</td>
		<td class="font11B" width="40" align="center">盈餘</td>
	</tr>
	<?php
	for($i=0; $i< 100; $i++){
		echo '<tr>';
		echo '<td width="45" align="center" class="font11">'.$i.'</td>';
		echo '<td width="200" align="center" class="font11">'.$acc_list[0][book_name].'</td>';
		echo '<td width="185" align="center" class="font11">'.$acc_list[0][finish_time].'</td>';
		echo '<td width="60" align="center" class="font11">'.$acc_list[0][revenue].'</td>';
		echo '<td width="60" align="center" class="font11">'.$acc_list[0][expediture].'</td>';
		echo '<td width="40" align="center" class="font11">'.$acc_list[0][profit].'</td>';
		echo '</tr>';		
		if(($i+1) % 40 == 0){
			echo '</div>';
			echo '<div class="pagebreak">';
			echo '<tr>';
			echo '<td class="font11B" width="45" align="center">序號</td>';
			echo '<td class="font11B" width="200" align="center">書名</td>';
			echo '<td class="font11B" width="185" align="center">完成時間</td>';
			echo '<td class="font11B" width="60" align="center">收入</td>';
			echo '<td class="font11B" width="60" align="center">支出</td>';
			echo '<td class="font11B" width="40" align="center">盈餘</td>';
			echo '</tr>';
		}
	}
	?>	
	<tr>
		<td colspan="6">
		<p align="right" class="line30T">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="6">
		<p align="right" class="font11B">總收入 <?php echo $total_revenue; ?>元 / 總支出 <?php echo $total_expediture; ?>元 / 總盈餘 <?php echo $total_profit; ?>元</td>
	</tr>
	
	</table>	</div>
</div>
</body>
</html>