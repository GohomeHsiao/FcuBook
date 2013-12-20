<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 自 user table 中讀取資料
$sql = "SELECT * from mem_state";
$tmp = mysql_query($sql, $link);
$left_row_num = mysql_num_rows($tmp);
$left_list = array();
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
<form method="POST" name="left_form" action="member.php">
<div id="leftT" class="leftT">會員管理</div>
<div id="leftD">
		<table border="1">
			<tr>
				<td class="leftD">狀態</td>
				<td class="leftD"><select size="1" name="left_state">
				<option value="0" selected>全部</option>
				<?php for($i=0; $i< $left_row_num; $i++) echo '<option value="'.$left_list[$i][member_state_no].'">'.$left_list[$i][state_name].'</option>';	?>
				</select></td>
			</tr>
			<tr>
				<td class="leftD">學號</td>
				<td class="leftD"><input type="text" name="left_id" size="15"></td>
			</tr>
			<tr>
				<td class="leftD">&nbsp;</td>
				<td class="leftD"><input type="submit" value="搜尋" name="B1"></td>
			</tr>
		</table>
</div>
<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>
</form>