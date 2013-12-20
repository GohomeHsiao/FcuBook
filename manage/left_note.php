<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 自 user table 中讀取資料
$sql = "SELECT * from announce_type";
$tmp = mysql_query($sql, $link);
$left_list = array();
$left_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($left_list, $row);	
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>LEFT</title>
</head>

<body>
<div id="leftT" class="leftT">公告管理</div>
<div id="leftL"><a href="note.php?type=0">綜合</a></div>
<?php for($i=0; $i< $left_num; $i++) echo '<div id="leftL"><a href="note.php?type='.$left_list[$i][type_no].'">'.$left_list[$i][name].'</a></div>'; ?>
<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>