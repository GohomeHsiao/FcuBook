<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.left_form.left_id.value == '')
	{
		alert("請輸入學號!");
		document.left_form.left_id.focus();		
	}
	else
	{
		document.left_form.submit();
	}
}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>LEFT</title>
</head>

<body>
<form method="POST" name="left_form" action="book.php">
<div id="leftT" class="leftT">二手書管理</div>
<div id="leftD">
		<table border="1">
			<tr>
				<td class="leftD">狀態</td>
				<td class="leftD"><select size="1" name="left_state">
				<option value="0">全部</option>
				<option value="1">待確認</option>
				<option value="2">銷售中</option>
				<option value="5">滯銷</option>
				</select></td>
			</tr>
			<tr>
				<td class="leftD">學號</td>
				<td class="leftD"><input type="text" name="left_id" size="15"></td>
			</tr>
			<tr>
				<td class="leftD">&nbsp;</td>
				<td class="leftD"><input type="button" onClick="blankCheck()" value="搜尋" name="B1"></td>
			</tr>
		</table>
</div>
<div><img border="0" src="images/left_bottom1.png" width="225" height="5"></div>
</form>