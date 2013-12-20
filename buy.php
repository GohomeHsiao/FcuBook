
<?php 
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");

//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

session_start();
if(session_is_registered('power') AND $_SESSION[power] != 1){
	die('<script>alert("Your usage right has been suspended!");location.href="index.php"</script>');	
}
//處理頁數START
$pens_per_page = 5;
$page_list_max = 10;
if($_POST[left_ctrl] == 1 )
{
if($_POST[left_college] != 0 && $_POST[left_new_old] != 0 && $_POST[left_price] != 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_old >= '$_POST[left_new_old]' AND book.new_price <= '$_POST[left_price]' AND book_state_no=2 ";
else if ($_POST[left_college] != 0 && $_POST[left_new_old] != 0 && $_POST[left_price] == 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_old >= '$_POST[left_new_old]' AND book_state_no=2 ";
else if ($_POST[left_college] != 0 && $_POST[left_new_old] == 0 && $_POST[left_price] != 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_price <= '$_POST[left_price]' AND book_state_no=2 ";
else if($_POST[left_college] == 0 && $_POST[left_new_old] != 0 && $_POST[left_price] != 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE  book.new_old >= '$_POST[left_new_old]' AND book.new_old =new_old.new_old_no AND book.new_price <= '$_POST[left_price]' AND book_state_no=2 ";	
else if($_POST[left_college] != 0 && $_POST[left_new_old] == 0 && $_POST[left_price] == 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book_state_no=2 ";	
else if($_POST[left_college] == 0 && $_POST[left_new_old] != 0 && $_POST[left_price] == 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE book.new_old >= $_POST[left_new_old] AND book.new_old =new_old.new_old_no AND book_state_no=2 ";
else if($_POST[left_college] == 0 && $_POST[left_new_old] == 0 && $_POST[left_price] != 0)
		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE book.new_price <= '$_POST[left_price]' AND book.new_old =new_old.new_old_no AND book_state_no=2  ORDER BY book.on_time ";	
}
else if($_POST[simsrch_ctrl] == 1){
	
		if($_POST[choose] == 'n')
			$SimWHERE = " AND book_name LIKE '%$_POST[srch_box]%' ";
		else
			$SimWHERE =  " AND author LIKE '%$_POST[srch_box]%' ";
		
		$sql = "SELECT * FROM book ,new_old WHERE  book.new_old = new_old.new_old_no AND book_state_no=2 ".$SimWHERE." ".$WHERE;
}
else if($_POST[dsrch_ctrl] == 1)
{
		if($_POST[b_name] == '')
			$WHERE_bn = '';
		else 
			$WHERE_bn = " AND book_name LIKE '%$_POST[b_name]%' ";
		if($_POST[author] == '')
			$WHERE_auth = '';
		else 
			$WHERE_auth =  "AND author LIKE '%$_POST[author]%' ";
		if($_POST[publisher] == '')
			$WHERE_plsh = '';
		else 
			$WHERE_plsh =  "AND publisher LIKE '%$_POST[publisher]%' ";	
		if($_POST[college]==0)
			$WHERE_Col = '';
		else 
			$WHERE_Col = "AND college_no = ".$_POST[college];
		if($_POST[course] == '')
			$WHERE_cour = '';
		else 
			$WHERE_cour =  "AND course LIKE '%$_POST[course]%' ";	
		if($_POST[newold]==0)
			$WHERE_newold = '';
		else 
			$WHERE_newold =  "AND new_old >= ".$_POST[newold];
		if($_POST[low_price] == ''&& $_POST[up_price] == '')
			$WHERE_price = '';
		else if($_POST[low_price] != ''&& $_POST[up_price] == '')
			$WHERE_price =  "AND new_price >= $_POST[low_price]";	
		else if($_POST[low_price] == ''&& $_POST[up_price] != '')
			$WHERE_price =  "AND new_price <= $_POST[up_price]";
		else
			$WHERE_price =  "AND new_price >= $_POST[low_price] AND new_price <= $_POST[up_price]";
		
		$sql = "SELECT * FROM book ,new_old WHERE  book.new_old = new_old.new_old_no AND book_state_no=2 ".$WHERE_bn." ".$WHERE_auth." ".$WHERE_plsh." ".$WHERE_cour." ".$WHERE_Col." ".$WHERE_newold." ".$WHERE_price;
}
else		
 		$sql = "SELECT book_no, book_name, author, new_price, old_price, new_old_name, img  FROM book,new_old WHERE  book.new_old =new_old.new_old_no AND book_state_no=2 ";
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

//中間列表
if($_POST[sort_ctrl] == 1){
	$WHERE = "ORDER BY book.new_price ASC";
}
else if($_POST[sort_ctrl] == 2){
	$WHERE = "ORDER BY book.on_time DESC";
}
else if($_POST[sort_ctrl] == 3){
	$WHERE = "ORDER BY book.new_old DESC";	
}
else {
	$WHERE = "ORDER BY book.on_time DESC";
}

if($_POST[left_ctrl] == 1 )//左邊搜尋
{
if($_POST[left_college] != 0 && $_POST[left_new_old] != 0 && $_POST[left_price] != 0)
		$sql = "SELECT * FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_old >='$_POST[left_new_old]'  AND book.new_price <= $_POST[left_price] AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";
else if ($_POST[left_college] != 0 && $_POST[left_new_old] != 0 && $_POST[left_price] == 0)
		$sql = "SELECT *  FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_old >= '$_POST[left_new_old]' AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";
else if ($_POST[left_college] != 0 && $_POST[left_new_old] == 0 && $_POST[left_price] != 0)
		$sql = "SELECT *  FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book.new_price <= '$_POST[left_price]' AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";
else if($_POST[left_college] == 0 && $_POST[left_new_old] != 0 && $_POST[left_price] != 0)
		$sql = "SELECT * FROM book,new_old WHERE  book.new_old = '$_POST[left_new_old]' AND book.new_old =new_old.new_old_no AND book.new_price <= '$_POST[left_price]' AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";	
else if($_POST[left_college] != 0 && $_POST[left_new_old] == 0 && $_POST[left_price] == 0)
		$sql = "SELECT * FROM book,new_old WHERE book.college_no = '$_POST[left_college]' AND book.new_old =new_old.new_old_no AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";	
else if($_POST[left_college] == 0 && $_POST[left_new_old] != 0 && $_POST[left_price] == 0)
		$sql = "SELECT *  FROM book,new_old WHERE book.new_old >= '$_POST[left_new_old]' AND book.new_old =new_old.new_old_no AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";
else if($_POST[left_college] == 0 && $_POST[left_new_old] == 0 && $_POST[left_price] != 0)
		$sql = "SELECT * FROM book,new_old WHERE book.new_price <= '$_POST[left_price]' AND book.new_old =new_old.new_old_no AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page" ;	
}
else if($_POST[simsrch_ctrl] == 1){
	
		if($_POST[choose] == 'n')
			$SimWHERE = " AND book_name LIKE '%$_POST[srch_box]%' ";
		else
			$SimWHERE =  " AND author LIKE '%$_POST[srch_box]%' ";
		
		$sql = "SELECT * FROM book ,new_old WHERE  book.new_old = new_old.new_old_no AND book_state_no=2 ".$SimWHERE." ".$WHERE." LIMIT $start, $pens_per_page";
}
else if($_POST[dsrch_ctrl] == 1)//進階搜尋
{
		if($_POST[b_name] == '')
			$WHERE_bn = '';
		else 
			$WHERE_bn = " AND book_name LIKE '%$_POST[b_name]%' ";
		if($_POST[author] == '')
			$WHERE_auth = '';
		else 
			$WHERE_auth =  "AND author LIKE '%$_POST[author]%' ";
		if($_POST[publisher] == '')
			$WHERE_plsh = '';
		else 
			$WHERE_plsh =  "AND publisher LIKE '%$_POST[publisher]%' ";	
		if($_POST[college]==0)
			$WHERE_Col = '';
		else 
			$WHERE_Col = 'AND college_no = '.$_POST[college];
		if($_POST[course] == '')
			$WHERE_cour = '';
		else 
			$WHERE_cour =  "AND course LIKE '%$_POST[course]%' ";	
		if($_POST[newold]==0)
			$WHERE_newold = '';
		else 
			$WHERE_newold =  "AND new_old >= ".$_POST[newold];
		if($_POST[low_price] == ''&& $_POST[up_price] == '')
			$WHERE_price = '';
		else if($_POST[low_price] != ''&& $_POST[up_price] == '')
			$WHERE_price =  "AND new_price >= $_POST[low_price]";	
		else if($_POST[low_price] == ''&& $_POST[up_price] != '')
			$WHERE_price =  "AND new_price <= $_POST[up_price]";
		else
			$WHERE_price =  "AND new_price >= $_POST[low_price] AND new_price <= $_POST[up_price]";
		
		$sql = "SELECT * FROM book ,new_old WHERE  book.new_old = new_old.new_old_no AND book_state_no=2 ".$WHERE_bn." ".$WHERE_auth." ".$WHERE_plsh." ".$WHERE_cour." ".$WHERE_Col." ".$WHERE_newold." ".$WHERE_price." ".$WHERE." LIMIT $start, $pens_per_page";
}
else	//賣書首頁	
 		$sql = "SELECT * FROM book,new_old WHERE  book.new_old =new_old.new_old_no AND book_state_no=2 ".$WHERE." LIMIT $start, $pens_per_page";
$tmp = mysql_query($sql, $link);
$book_num = mysql_num_rows($tmp);
$book_list = array();
while($row = mysql_fetch_array($tmp)){
	array_push($book_list, $row);
}
//中間列表END

//算剩餘時間START
for($i=0;$i< $book_num;$i++){
	if($book_list[$i][book_state_no] == 2){
		putenv("TZ=Asia/Taipei");	
		$datetime = explode(" ",$book_list[$i][on_time]);
		$date = explode("-",$datetime[0]);
		$time = explode(":",$datetime[1]);
		$t1 = mktime($time[0],$time[1], $time[2], $date[1], $date[2]+14, $date[0]);
		$t2 = time();
	
		$remain_seconds = $t1 - $t2;
		if($remain_seconds/(60*60*24) >= 1){
			$remain_time[$i] = floor($remain_seconds/(60*60*24)).'天';		
		}
		else if($remain_seconds/(60*60) >= 1){
			$remain_time[$i] = floor($remain_seconds/(60*60)).'小時';		
		}
		else if($remain_seconds/60 >= 1){
		$remain_time[$i] = floor($remain_seconds/60).'分鐘';
		}
	
	} 
	else{
			$remain_time[$i] = '-';	
	}
}
//抓書本資料END


//處理追蹤start
if($_POST[trace_Control] == 1)
{
//檢查追蹤重複
	$sql = "INSERT INTO trace ( member_id , book_no )VALUES ( '$_SESSION[user]', '$_POST[trace_num]')";	
		mysql_query($sql, $link);	
//處理追蹤END
}
$sql = "SELECT book_no FROM trace WHERE member_id = '$_SESSION[user]'";
$tmp = mysql_query($sql, $link);
$trace_num = mysql_num_rows($tmp);
$find_trace = array();
while($row = mysql_fetch_array($tmp)){
	array_push($find_trace, $row);
}
$cmp = array();
for($i=0;$i< $book_num ; $i++){
	for($j=0;$j< $trace_num; $j++)
	{
		 if($book_list[$i][book_no] == $find_trace[$j][book_no])
		 		$cmp[$i] = 1;
	}
}

?>
<html>
<script type="text/javascript">
	function sort( s )
	{	
		document.buy_form.sort_ctrl.value = s;
		document.buy_form.submit();
	}
	function no_trace()
	{
		alert("請先登入會員!!!");
	}
	function trace(b_no)
	{
			var answer = confirm("確定要加入我的追蹤?");
		if(answer)
		{
			
			document.buy_form.trace_Control.value = 1;
			document.buy_form.trace_num.value = b_no;
			document.buy_form.submit();
		}
	}
	function pageGO( pagenum )
	{	
		document.buy_form.page.value = pagenum;
		document.buy_form.submit();
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
<div id="left"><?php include("left_book.php");?></div>
<div id="main" class="main">
<form method="POST" name="buy_form" action="buy.php">
<table border="0" width="650" >
	<tr>
		<td align="center" height="32" colspan="5">
		<p align="right">排序方式： <a href="#" onClick="sort(1)">價格</a> | <a href="#" onClick="sort(2)">剩餘時間</a> | <a href="#" onClick="sort(3)">新舊程度</a></td>
	</tr>
	
	<tr>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32">預覽圖</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="287">
		書名</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="94">
		原價/售價</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="82">
		剩餘時間</td>
		<td class="line30T" bgcolor="#FFFFCC" align="center" height="32" width="92">
		新舊程度</td>
	</tr>
	<?php
			for($i=0; $i< $book_num;$i++){
			echo '<tr>';
			echo '<td>';
			echo '<img border="0" src="'.$book_list[$i][img].'" width="90" height="110"></td>';
			echo '<td align="left" width="287">';
			echo '<p align="center"><a href="buy_detail.php?book_no='.$book_list[$i][book_no].'">'.$book_list[$i][book_name].'</a></p>';
			echo '<p align="center"><font color="#808080">作者：'.$book_list[$i][author].'</font></td>';
			echo '<td align="center" width="94">'.$book_list[$i][old_price].' / '.$book_list[$i][new_price].'</td>';
			echo '<td align="center" width="82">'.$remain_time[$i].'</td>';
			echo '<td width="92" align="center">'.$book_list[$i][new_old_name].'<p>';if(!session_is_registered('user'))echo '<input type="button" onClick="no_trace()" value="加入追蹤" name="B5"></td>';
																																								else{	 echo '<input type="button" onClick="trace('.$book_list[$i][book_no].')" ';if($cmp[$i]==1 || $book_list[$i][seller] == $_SESSION[user])echo 'disabled'; echo ' value="加入追蹤" name="B5"></td>';}
			echo '</tr>';
		}
	?>
	<tr>
		<td colspan="5">
		<p align="center">
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
	<input type="hidden" name="left_college" value="<?php echo $_POST[left_college]; ?>">
	<input type="hidden" name="left_new_old" value="<?php echo $_POST[left_new_old]; ?>">
	<input type="hidden" name="left_price" value="<?php echo $_POST[left_price]; ?>">
	<input type="hidden" name="left_ctrl" value="<?php echo $_POST[left_ctrl]; ?>">
	
	<input type="hidden" name="choose" value="<?php echo $_POST[choose] ; ?>">
	<input type="hidden" name="srch_box" value="<?php echo $_POST[srch_box]; ?>">
	<input type="hidden" name="simsrch_ctrl" value="<?php echo $_POST[simsrch_ctrl]; ?>">
	
	<input type="hidden" name="dsrch_ctrl" value="<?php echo $_POST[dsrch_ctrl]; ?>">
	<input type="hidden" name="b_name" value="<?php echo $_POST[b_name]; ?>">
	<input type="hidden" name="author" value="<?php echo $_POST[author]; ?>">
	<input type="hidden" name="publisher" value="<?php echo $_POST[publisher]; ?>">
	<input type="hidden" name="college" value="<?php echo $_POST[college]; ?>">
	<input type="hidden" name="course" value="<?php echo $_POST[course]; ?>">
	<input type="hidden" name="newold" value="<?php echo $_POST[newold]; ?>">
	<input type="hidden" name="low_price" value="<?php echo $_POST[low_price]; ?>">
	<input type="hidden" name="up_price" value="<?php echo $_POST[up_price]; ?>">
	
	<input type="hidden" name="sort_ctrl" value="<?php echo $_POST[sort_ctrl]; ?>">
	<input type="hidden" name="trace_Control">
	<input type="hidden" name="trace_num" >
	<input type="hidden" name="page">
	</form>
</div>
<p>&nbsp;</div>
<?php include("footer.php"); ?>
</body>

</html>