<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');
// 自 user table 中讀取資料
$sql = "SELECT * from remind_type";
$tmp = mysql_query($sql, $link);
$type_list = array();
$row_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);	
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>LEFT</title>
</head>

<body>
<div id="leftT" class="leftT">提醒</div>
<div id="leftL"><a href="remind.php?type=0">綜合</a></div>
<?php 
	for($i=0; $i< $row_num; $i++) 
		echo '<div id="leftL"><a href="remind.php?type='.$type_list[$i][remind_type_no].'">'.$type_list[$i][type_name].'訊息</a></div>'; 
?>
<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>