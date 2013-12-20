<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');

//分類名稱
$sql = "SELECT q_name FROM q_type ";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);	
}
//分類END

//處理中間列表START
if($_GET[type] == 0) $WHERE = '';
else $WHERE = 'AND qa.type_no ='.$_GET[type];
$sql = "SELECT * FROM qa, q_type WHERE qa.type_no = q_type.q_type_no ".$WHERE." ORDER BY qa.release_time DESC ";
$tmp = mysql_query($sql, $link);
$qa_list = array();
$qa_num = mysql_num_rows($tmp);
while($row = mysql_fetch_array($tmp)){
	array_push($qa_list, $row);
}
//處理中間列表END
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_qa.php"); ?></div>
<div id="main" class="main">
	<p style="text-align: left" class="line30T">常見問題-<?php echo $type_list[$_GET[type]-1][q_name];?></p>
	<table border="0" width="650" >
		<?php
		for($i=0; $i< $qa_num; $i++){
			echo '<tr>';
			echo '<td width="70%" align="left" class="font11B" bgcolor="#FFFFCC" height="30">Q：'.$qa_list[$i][question].'</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td width="70%" align="left" height="60"><p class="line30">A：'.$qa_list[$i][answer].'</td>';
			echo '</tr>';
		}
		?>
	</table>
</div>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>