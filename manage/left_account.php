<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

putenv("TZ=Asia/Taipei");
$today = date("Y-m-d");
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="javascript/common.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script>
    $(function() {
        $( "#datepicker1" ).datepicker();
        $( "#datepicker2" ).datepicker();
    });
</script>
<title>LEFT</title>
</head>

<body>
<div id="leftT" class="leftT">會計系統</div>
<div id="leftL"><a href="account_day.php?today=<?php echo $today; ?>">本日報表</a></div>
<div id="leftL"><a href="account_month.php">本月報表</a></div>
<div id="leftD">
	<form method="POST" name="acc_form" action="account_history.php">
		<!--webbot bot="SaveResults" U-File="../_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
		<p>&nbsp;</p>
		<table border="1" height="170">
			<tr>
				<td align="center">
				<p class="line30T">查詢歷史報表</td>
			</tr>
			<tr>
				<td height="33" align="center">
				<input type="text" name="day_start" size="20" value="滑鼠移至此處" onMouseOver="this.focus()" onFocus="this.select()" onclick="this.value=''" id="datepicker1"></td>
			</tr>
			<tr>
				<td align="center">
				<p class="font11">至</td>
			</tr>
			<tr>
				<td align="center">
				<p>
				<input type="text" name="day_end" size="20" value="滑鼠移至此處" onMouseOver="this.focus()" onFocus="this.select()" onclick="this.value=''" id="datepicker2"></td>
			</tr>
			<tr>
				<td align="center"><input type="submit" value="送出" name="B1"></td>
			</tr>
		</table>
	</form>
	<p></div>

<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>