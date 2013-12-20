<?php
//檢查是否登入
include("conponent/loginCheck.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
// 抓資料
$sql = "SELECT * FROM announce WHERE ann_no =".$_GET[ann_no];
$tmp = mysql_query($sql, $link);
$anndata = mysql_fetch_array($tmp);

$sql = "SELECT * FROM announce_type";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);
}
//EDIT START
if($_POST[control_edit] == 1)
{
	// 處理FORM資料並UPDATE到資料庫
	putenv("TZ=Asia/Taipei");
	$type_no = $_POST[type];
	$release_time = date("Y-m-d H:i:s");
	$istop = $_POST[istop];
	$title = $_POST[subject];
	$context = $_POST[context];

	$sql = "UPDATE  announce SET  type_no='$type_no', release_time='$release_time', istop='$istop', title='$title', context='$context' WHERE  ann_no =".$_GET[ann_no];
	mysql_query($sql, $link);
	header("location:note.php?type=".$_GET[type]);
}
//EDIT END
?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.ann_form.subject.value == '')
	{
		alert("請輸入主旨!");
		document.ann_form.subject.focus();		
	}
	else if(document.ann_form.context.value == '')
	{
		alert("請輸入內文!");
		document.ann_form.context.focus();	
	}
	else
	{
		document.ann_form.submit();
	}
}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易管理後台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_note.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	修改公告</p>
	
	<form method="POST" name="ann_form" action="note_edit.php?type=<?php echo $_GET[type]; ?>&ann_no=<?php echo $_GET[ann_no]; ?>">
		<!--webbot bot="SaveResults" U-File="../_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><!--webbot bot="SaveResults" i-checksum="43374" endspan -->
		<table border="1" width="650" id="table2">
			<tr>
				<td width="38">
				<p class="font11B">分類</td>
				<td width="85"><select size="1" name="type">
				<?php for($i=0; $i< sizeof($type_list); $i++) echo '<option value="'.$type_list[$i][type_no].'">'.$type_list[$i][name].'</option>'; ?>
				</select></td>
				<td width="34">
				<p class="font11B">置頂</td>
				<td width="36"><input type="radio" name="istop" value="y" <?php if($anndata[istop] == 'y') echo 'checked'; ?> >是</td>
				<td width="407"><input type="radio" name="istop" value="n" <?php if($anndata[istop] == 'n') echo 'checked'; ?> >否</td>
			</tr>
			<tr>
				<td colspan="5">
				<p class="font11B">主題</td>
			</tr>
			<tr>
				<td colspan="5"><input type="text" name="subject" size="43" value="<?php echo $anndata[title];?>"></td>
			</tr>
			<tr>
				<td colspan="5">
				<p class="font11B">內文</td>
			</tr>
			<tr>
				<td colspan="5"><textarea rows="8" name="context" cols="66"><?php echo $anndata[context];?></textarea></td>
			</tr>
			<tr>
				<td width="38">&nbsp;</td>
				<td width="85">&nbsp;</td>
				<td width="34">&nbsp;</td>
				<td width="36"><input type="button" onClick="blankCheck()" value="送出" name="B1"></td>
				<td width="407"><input type="reset" value="重新設定" name="B2"></td>
			</tr>
		</table>
		<input type="hidden" value="1" name="control_edit">
	</form>
	
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>