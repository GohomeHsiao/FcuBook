<html>

<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="Author" content="http://poet.930.info/">
<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易管理後台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_book.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	修改二手書 - D9772780</p>
	<form method="POST" action="../_derived/nortbots.htm" onSubmit="location.href='../_derived/nortbots.htm';return false;" webbot-onSubmit enctype="multipart/form-data" webbot-action="--WEBBOT-SELF--">
	<table border="1" width="650" id="table2">
		<tr>
			<td valign="top">
			<table border="1" width="100%" id="table3">
				<tr>
		<td width="70">書名</td>
					<td><input type="text" name="T2" size="20"></td>
				</tr>
				<tr>
		<td width="70">作者</td>
					<td><input type="text" name="T3" size="20"></td>
				</tr>
				<tr>
		<td width="70">出版社</td>
					<td><input type="text" name="T4" size="20"></td>
				</tr>
				<tr>
		<td width="70">學院 - 課程</td>
					<td><select size="1" name="D2">
		<option selected>--請選擇學院--</option>
		<option>資訊電機學院</option>
		<option>商學院</option>
		</select><input type="text" name="T7" size="20"></td>
				</tr>
				<tr>
		<td width="70">授課教師</td>
					<td><input type="text" name="T5" size="20"></td>
				</tr>
				<tr>
		<td width="70">價格</td>
					<td><input type="text" name="T6" size="20"></td>
				</tr>
				<tr>
		<td width="70">新舊狀況</td>
					<td><select size="1" name="D5">
		<option>全新</option>
		<option>九成新</option>
		<option>八成新</option>
		</select></td>
				</tr>
				<tr>
		<td width="70">敘述</td>
					<td><textarea rows="10" name="S1" cols="30"></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="radio" value="V3" name="R1">上架 
						<input type="radio" name="R1" value="V4" checked>未上架<p><input type="button" value="送出" name="B2" style="float: right"></td>
				</tr>
			</table>
			</td>
			<td valign="middle" align="center">pic<p>圖片上傳：<input type="file" name="F1" size="18"></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	</form>
</div></div>
<?php include("footer.php"); ?>
</body>

</html>