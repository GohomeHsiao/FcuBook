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
$sql = "SELECT * FROM member WHERE id = '$_GET[id]'";
$tmp = mysql_query($sql, $link);
$member_data = mysql_fetch_array($tmp);

$sql = "SELECT * from mem_state";
$tmp = mysql_query($sql, $link);
$mem_state_num = mysql_num_rows($tmp);
$state_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($state_list, $row);
}

//EDIT START
if($_POST[control_edit] == 1)
{
	putenv("TZ=Asia/Taipei");
	$release_time = date("Y-m-d H:i:s");
	
	// 處理FORM資料並UPDATE到資料庫		
	$state_no = $_POST[state_no];	
	$sql = "UPDATE  member SET  state_no = $_POST[state_no], punish_time = '$release_time' WHERE  id ='$_GET[id]' ";	
	mysql_query($sql, $link);	
	
	//REMIND	
	$sql = "SELECT * FROM mem_state WHERE member_state_no = $state_no";	
	$tmp = mysql_query($sql, $link);
	$state_row = mysql_fetch_array($tmp);
	$sql = "INSERT INTO remind (title, type, release_time, member_id) VALUES ('管理員將您的權限調整為$state_row[state_name]，即時生效，有問題可以至客戶提問進行申訴。', '1', '$release_time', '$_GET[id]')";
	mysql_query($sql, $link);
	
	header("location:member.php");
}
//EDIT END
?>
<html>
<script type="text/javascript">

</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易管理後台</title>
</head>

<body>

<?php include("header.php"); ?><div id="area">
<div id="left"><?php include("left_member.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">
	會員管理 - 處罰</p>
	<form method="POST" name="member_form" action="member_edit.php?id=<?php echo $_GET['id']; ?>">
	<table border="1" width="274" height="350">
	<tr>
		<td width="82">姓名</td>
		<td><?php echo $member_data['name']; ?></td>
	</tr>
	<tr>
		<td width="82" bgcolor="#DFDFDF">身分證字號</td>
		<td bgcolor="#DFDFDF"><?php echo $member_data['sn']; ?></td>
	</tr>
	<tr>
		<td width="82">帳號</td>
		<td><?php echo $member_data[id]; ?></td>
	</tr>
	<tr>
		<td width="82" bgcolor="#DFDFDF">系級</td>
		<td bgcolor="#DFDFDF"><?php echo $member_data['class']; ?></td>
	</tr>
	<tr>
		<td width="82">行動電話</td>
		<td><?php echo $member_data[phone]; ?></td>
	</tr>
	<tr>
		<td width="82" bgcolor="#DFDFDF">連絡信箱</td>
		<td bgcolor="#DFDFDF"><?php echo $member_data['email']; ?></td>
	</tr>	
	<tr>
		<td width="82">處罰</td>
		<td width="172"><select size="1" name="state_no">
		<?php for($i=0; $i< $mem_state_num; $i++){
			echo '<option value="'.$state_list[$i][member_state_no].'" '; 
			if($state_list[$i][member_state_no] == $member_data[state_no]) echo'selected';
			echo '>'.$state_list[$i][state_name].'</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td width="82">&nbsp;</td>
		<td width="172">&nbsp;</td>
	</tr>
	<tr>
		<td width="82">&nbsp;</td>
		<td width="172"><input type="submit" value="送出" name="B2"></td>
	</tr>
	</table>
	<input type="hidden" value="1" name="control_edit">
	</form>
	<p>&nbsp;</div>
</div>
<?php include("footer.php"); ?>
</body>

</html>