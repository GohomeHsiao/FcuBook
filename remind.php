<?php
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認
mysql_query("SET NAMES 'UTF8'");
header('Content-type:text/html; charset=utf-8');



//處理刪除START
if($_POST[control_del] == 1){
	$del_list = $_POST[remind_no];	
	for($i=0; $i< sizeof($del_list); $i++){
		$sql = "DELETE FROM remind WHERE remind_no = ".$del_list[$i]." AND  member_id = '$_SESSION[user]'";		
		mysql_query($sql, $link);
	}	
	header("location:remind.php?type=".$_GET[type]);
}
//處理刪除END

//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;

if($_GET[type] == 0){
	$sql = "SELECT * FROM remind WHERE member_id = '$_SESSION[user]'";
}else{
	$sql = "SELECT * FROM remind, remind_type WHERE remind.type = remind_type.remind_type_no AND member_id = '$_SESSION[user]' AND remind.type ='$_GET[type]'";
}
$tmp = mysql_query($sql, $link);
$pens_total = mysql_num_rows($tmp);
$pages_total = ceil($pens_total/$pens_per_page);

if($_POST[page] >= 1 AND $_POST[page] <= $pages_total){
	$start = ($_POST[page]-1)*$pens_per_page;
}
else if($_POST[page] > $pages_total){
	$start = ($pages_total-1)*$pens_per_page;
}
else{
	$start = 0;
}
$page_now = ($start / $pens_per_page)+1;
$page_list_first = floor(($page_now-1)/$page_list_max)*$page_list_max + 1;
if($page_list_first + $page_list_max -1 > $pages_total){
	$page_list_last = $pages_total;
}
else{
	$page_list_last = $page_list_first + $page_list_max -1;
}
//處理頁數END

//分類名稱
$sql = "SELECT type_name FROM remind_type ";
$tmp = mysql_query($sql, $link);
$type_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($type_list, $row);	
}
//分類END

//處理中間列表START
if($_GET[type] == 0) $WHERE = '';
else $WHERE = 'AND remind.type ='.$_GET[type];
$sql = "SELECT * FROM remind, remind_type WHERE remind.type = remind_type.remind_type_no AND member_id = '$_SESSION[user]' ".$WHERE." ORDER BY remind.remind_no DESC LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$remind_num = mysql_num_rows($tmp);
$remind_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($remind_list, $row);
}
//處理中間列表END

//NEW START
session_start();
$sql = "UPDATE remind SET isRead = 'y' WHERE member_id = '$_SESSION[user]'";
mysql_query($sql, $link);
//NEW END
?>
<html>
	
<script type="text/javascript">
	function selAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("remind_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=true;
		}
	}
	function unselAll(){
		//變數checkItem為checkbox的集合
		var checkItem = document.getElementsByName("remind_no[]");
		for(var i=0;i<checkItem.length;i++){
			checkItem[i].checked=false;
		}
	}
	function delCheck(){	
		var answer = confirm ("確定要刪除已勾選的公告嗎?");
		if(answer)
		{
			document.remind_form.control_del.value = 1;
			document.remind_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.remind_form.page.value = pagenum;
		document.remind_form.submit();
	}
</script>
<head>
<meta http-equiv="Content-Language" content="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="poet.css" type="text/css">
<title>逢甲大學二手書交易平台</title>
</head>

<body>

<?php include("header.php");?><div id="area">
<div id="left"><?php include("left_remind.php"); ?></div>
<div id="main" class="main">
	<p style="text-align: left" class="line30T">提醒 - <?php if($_GET[type]==0)echo '綜合';else echo $type_list[$_GET[type]-1][type_name].'訊息';?></p>
	<form method="POST" name="remind_form" action="remind.php?type=<?php if($_GET[type]=='') echo '0';else echo $_GET[type]; ?>">
	<table border="0" width="650" >
		<tr>			
			<td colspan="1" height="26"><a href="#"  onClick="delCheck()"><img border="0" src="images/icon_del.gif" width="45" height="18" title="刪除"></a></td>			
			<td colspan="3" height="26"><a href="#" onClick="selAll()">全選</a> | <a href="#" onClick="unselAll()">取消全選</a></td>
		</tr>
		<tr>
			<td width="50" align="center" class="line30T" bgcolor="#FFFFCC" >選取</td>
			<td width="100" align="center" class="line30T" bgcolor="#FFFFCC" >分類</td>
			<td width="350" align="center" class="line30T" bgcolor="#FFFFCC" >主旨</td>
			<td width="150" align="center" class="line30T" bgcolor="#FFFFCC" >時間</td>
		</tr>
	
		<?php
			for($i=0; $i< $remind_num; $i++)
			{
				echo '<tr>';
				echo '<td width="50" align="center" height="32" ';if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} echo '><input type="checkbox" name="remind_no[]" value="'.$remind_list[$i][remind_no].'"></td>';
				echo '<td width="100" align="center" height="32" ';if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} if($remind_list[$i][isRead] == 'n'){echo '><b>'.$remind_list[$i][type_name].'</b></td>';} else {echo '>'.$remind_list[$i][type_name].'</td>';}
				echo '<td width="350" align="center" height="32" ';if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} if($remind_list[$i][isRead] == 'n'){echo '><b>'.$remind_list[$i][title].'</b></td>';} else {echo '>'.$remind_list[$i][title].'</td>';}
				echo '<td width="150" align="center" height="32" ';if($i%2 == 1){echo 'bgcolor="#DFDFDF"';} if($remind_list[$i][isRead] == 'n'){echo '><b>'.$remind_list[$i][release_time].'</b></td>';} else {echo '>'.$remind_list[$i][release_time].'</td>';}
				echo '</tr>';
			}
		?>
		<tr>
		<td colspan="5">
		<p align="center"><br>
			<a href="#" onClick="pageGO(1)"><img border="0" src="images/prev.gif" width="13" height="11" title="最前頁"></a>
			<a href="#" onClick="pageGO(<?php echo $page_now-1; ?>)"><img border="0" src="images/pre.gif" width="11" height="11" title="上一頁"></a>
			<?php
				for($i=$page_list_first; $i<= $page_list_last; $i++){
					echo '<a href="#" onClick="pageGO('.$i.')">'.$i.' </a>';
				}
			?>
			<a href="#" onClick="pageGO(<?php echo $page_now+1; ?>)"><img border="0" src="images/next.gif" width="11" height="11" title="下一頁"></a>
			<a href="#" onClick="pageGO(<?php echo $pages_total; ?>)"><img border="0" src="images/nexta.gif" width="11" height="11" title="最末頁"></a>
			<br>
			第 <?php echo $page_now.'/'.$pages_total; ?> 頁　共 <?php echo $pens_total; ?> 筆</td>
	</tr>
	</table>
<input type="hidden" name="page">
<input type="hidden" value="0" name="control_del">
</form>
</div>
<p>&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>