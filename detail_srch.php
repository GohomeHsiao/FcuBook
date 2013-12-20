<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');



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
	function srch()
	{
			document.dsrch_form.dsrch_ctrl.value = 1;
			document.dsrch_form.action = "buy.php";
			document.dsrch_form.submit();
	}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<form method="POST"  name="dsrch_form" action="buy.php">
	<!--webbot bot="SaveResults" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
	<table border="0" width="50%" height="180" cellspacing="10">
		<tr>
			<td height="23" colspan="2">
			<p align="center" class="line30T">進階搜尋</td>
		</tr>
		<tr>
			<td align="right" width="15%">書名</td>
			<td width="77%"><input type="text" name="b_name" size="50" ></td>
		</tr>
		<tr>
			<td align="right" width="15%">作者</td>
			<td align="right" style="vertical-align: top" width="77%">
			<p align="left"><input type="text" name="author" size="50"></td>
		</tr>
		<tr>
			<td align="right" width="15%">出版社</td>
			<td width="77%"><input type="text" name="publisher" size="20"></td>
		</tr>
		<tr>
			<td align="right" width="15%">適用學院</td>
			<td width="77%"><select size="1" name="college">
			<?php 
						echo '<option value="0">全部</option>';
						for($i=0; $i< $college_num; $i++) echo '<option value="'.$college_list[$i][college_no].'">'.$college_list[$i][college_name].'</option>'; 
			?>
			</select></td>
		</tr>
		<tr>
			<td align="right" width="15%">適用課程</td>
			<td width="77%"><input type="text" name="course" size="30"></td>
		</tr>
		<tr>
			<td align="right" width="15%">新舊程度</td>
			<td width="77%"><select size="1" name="newold" >
			<?php 
						echo '<option value="0">--請選擇--</option>';
						for($i=0; $i< $newold_num; $i++) echo '<option value="'.$new_old_list[$i][new_old_no].'">'.$new_old_list[$i][new_old_name].'</option>'; 
			?>
			</select></td>
		</tr>
		<tr>
			<td align="right" width="15%">價格範圍</td>
			<td width="77%"><input type="text" name="low_price" size="20" > ~
			<input type="text" name="up_price" size="20" > 元</td>
		</tr>
	</table>
	<p><input type="button"  onClick="srch()" value="搜尋" name="B2"><input type="reset" value="清除" name="B3"></p>
		<input type="hidden"  name="dsrch_ctrl">
</form>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>