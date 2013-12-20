<?php 
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

$sql = "SELECT * FROM college";
$tmp = mysql_query($sql, $link);
$college_list = array();
$college_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($college_list, $row);
}

$sql = "SELECT * FROM new_old";
$tmp = mysql_query($sql, $link);
$new_old_list = array();
$newold_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($new_old_list, $row);
}
?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.leftb_form.left_college.value == 0 && document.leftb_form.left_new_old.value == 0 && document.leftb_form.left_price.value == 0)
	{
		alert("您未選擇任何一項!!請選擇!!");
		document.leftb_form.left_college.focus();		
	}
	else
	{
		document.leftb_form.left_ctrl.value = 1;
		document.leftb_form.submit();
	}
}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>LEFT</title>
</head>

<body>
<a href="buy.php">
<img border="0" src="images/btn_buy.png" width="225" height="80"></a><p>
<a href="sell.php">
<img border="0" src="images/btn_sell.png" width="225" height="80"></a></p>
<form method="POST" name="leftb_form" action="buy.php" >
<div id="leftT" class="leftT">縮小範圍顯示</div>
<div id="leftD">
		<table border="0">
			<tr>
				<td class="leftD" width="24">學院</td>
				<td class="leftD"><select size="1" name="left_college">
				<?php 
						echo '<option value="0">--請選擇學院--</option>';
						for($i=0; $i< $college_num; $i++) echo '<option value="'.$college_list[$i][college_no].'">'.$college_list[$i][college_name].'</option>'; 
				?>
				</select></td>
			</tr>
			<tr>
				<td class="leftD" width="24">新舊</td>
				<td class="leftD"><select size="1" name="left_new_old">
				<?php 
						echo '<option value="0">--請選擇--</option>';
						for($i=0; $i< $newold_num; $i++) echo '<option value="'.$new_old_list[$i][new_old_no].'">'.$new_old_list[$i][new_old_name].'</option>'; 
				?>
				</select></td>
			</tr>
			<tr>
				<td class="leftD" width="24">售價</td>
				<td class="leftD"><select size="1" name="left_price">
				<option value="0">-請選擇-</option>
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="300">300</option>
				<option value="400">400</option>
				<option value="500">500</option>
				<option value="600">600</option>
				<option value="700">700</option>
				<option value="800">800</option>
				<option value="900">900</option>
				<option value="1000">1000</option>
				</select> 以下</td>
			</tr>
			<tr>
				<td class="leftD" colspan="2" align="center"><input type="button" onClick="blankCheck()" value="搜尋" name="B1"></td>
			</tr>
		</table>
		<input type="hidden" name="left_ctrl">
</div>
<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>
</form>