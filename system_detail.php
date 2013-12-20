<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');

$sql = "SELECT * FROM announce WHERE ann_no =".$_GET[ann_no];
$tmp = mysql_query($sql, $link);
$anndata = mysql_fetch_array($tmp);
$get_time = substr($anndata[release_time],0,10);  //分割時間
//分類
$sql = "SELECT * FROM announce_type WHERE type_no =".$anndata[type_no];
$tmp = mysql_query($sql, $link);
$anntype = mysql_fetch_array($tmp);
//完成分類
?>
<html>

<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left">
	<?php include("left_system.php"); ?></div>
<div id="main" class="main">
	
	<p class="line30T" style="text-align: left">系統公告</p>
	
	<table border="0" width="650" id="table1" height="212">
		<?php
			echo '<tr>';
			echo '<td width="47" height="30" bgcolor="#FFFFCC" class="line30T" align="center">'.$anntype[name].'</td>';
			echo '<td width="132" height="30" bgcolor="#FFFFCC" class="line30T" align="center">'.$get_time.'</td>';
			echo '<td width="465" height="30" bgcolor="#FFFFCC" class="line30T" align="center">';
			echo '<p align="left">'.$anndata[title].'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td colspan="3"  height="150" style="vertical-align: top">&nbsp;<p>'.nl2br($anndata[context]).'</p>';
			echo '</tr>';
			echo '<tr>';
			echo '<td width="644" height="30" colspan="3" bordercolor="#000000">';
			echo '<p align="right">平台管理團隊敬上 '.$anndata[release_time].'</td>';
			echo '</tr>';
		?>
		<tr>
			<td width="644" height="30" colspan="3">
			<p align="center"><a href="system.php?type=<?php echo $_GET[type]; ?>">回列表</a></td>
		</tr>
	</table>
	<p>
	
	</div>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>