<?php
include("conponent/loginCheck.php");
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
putenv("TZ=Asia/Taipei");

$sql = "SELECT * FROM book WHERE book_no =".$_GET[book_no];

$tmp = mysql_query($sql, $link);
$bookdata = mysql_fetch_array($tmp);

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

if($_POST[control_edit] == 1){
$sql = "SELECT img FROM book WHERE book_no =".$_GET[book_no];
$tmp = mysql_query($sql, $link);
$oldDIIR = mysql_fetch_array($tmp);
unlink("$oldDIIR[img]");
//抓上傳圖片路徑START
if(is_uploaded_file($_FILES['file']['tmp_name'])){
	$DestDIR = "book_images";
		$File_Extension = explode(".", $_FILES['file']['name']);
	
	$File_Extension = $File_Extension[count($File_Extension)-1];
	$ServerFilename =date("YmdHis").".".$File_Extension;
	copy($_FILES['file']['tmp_name'] , $DestDIR."/".$ServerFilename );
}

$pic = $DestDIR."/".$ServerFilename;
//抓上傳圖片路徑END
$b_name = $_POST[b_name];
$author = $_POST[author];
$publisher = $_POST[publisher];
$college = $_POST[college];
$course = $_POST[course];
$old_price = $_POST[old_price];
$new_price = $_POST[new_price];
$new_old = $_POST[new_old];
$describe = $_POST[describe];
$reg_time = date("Y-m-d H:i:s");
$sql = "UPDATE book SET  book_name = '$b_name' , publisher = '$publisher' , author = '$author', college_no = '$college' , course = '$course' , new_old = '$new_old',b_describe = '$describe' , old_price = '$old_price', new_price = '$new_price',img = '$pic' WHERE book_no=".$_GET[book_no];
mysql_query($sql, $link);
header("location:sell_detail.php?book_no=".$_GET[book_no]);
}



?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.sell_form.b_name.value == '')
	{
		alert("請輸入書名");
		document.sell_form.b_name.focus();		
	}
	else if(document.sell_form.author.value == '')
	{
		alert("請輸入作者!");
		document.sell_form.author.focus();	
	}
	else if(document.sell_form.publisher.value == '')
	{
		alert("請輸入出版社!");
		document.sell_form.publisher.focus();	
	}
	else if(document.sell_form.college.value == 0)
	{
		alert("請選擇學院!");
		document.sell_form.college.focus();	
	}
	else if(document.sell_form.old_price.value == '')
	{
		alert("請輸入原價!");
		document.sell_form.old_price.focus();	
	}
	else if(document.sell_form.new_price.value == '')
	{
		alert("請輸入售價!");
		document.sell_form.new_price.focus();	
	}
	else if(document.sell_form.new_old.value == 0)
	{
		alert("請輸入新舊程度!");
		document.sell_form.new_old.focus();	
	}
	else if(document.sell_form.describe.value == '')
	{
		alert("請輸入敘述!");
		document.sell_form.describe.focus();	
	}
	else
	{
		document.sell_form.submit();
	}

}
function previewUploadPic(sender, previewDiv, width) {
    var v = "";
    if (document.selection) {
        sender.select();      
        v = document.selection.createRange().text;
    } else {
        v = sender.value;
    }
    
    if (sender.files) { 
        var img_preview = document.createElement("img");
        img_preview.src = sender.files[0].getAsDataURL();
        img_preview.width = width;
        previewDiv.innerHTML = "";
        previewDiv.appendChild(img_preview);            
    } else if (previewDiv.filters) {                        
        previewDiv.innerHTML = 
            "<table id=\"tbl_img_preview\" border=\"0\" cellpaddig=\"0\" cellspacing=\"0\" " + 
                "style=\"filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + v + "'," + 
                "sizingMethod=image); \">" + 
            "<tr><td >" + 
                " " + 
            "</td></tr>" + 
            "</table>";
        var o = document.getElementById("tbl_img_preview");
        var imgW = o.offsetWidth;
        var imgH = o.offsetHeight;
                    
        if (imgW > width) {
            var rate = (width / imgW);
            o.style.width = imgW * rate;
            o.style.height = imgH * rate;
            o.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = "scale"
            o.removeAttribute("id");
        }
    } else {
        alert("您使用的瀏覽器不支援圖檔預覽。");
        return;
    } 
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

<!--webbot BOT="GeneratedScript" PREVIEW=" " startspan -->
<form method="POST" name="sell_form" id="sell_form" action="book_edit.php?book_no=<?php echo $_GET[book_no];?>" enctype="multipart/form-data">
	<!--webbot bot="FileUpload" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" S-Destination="book_images/" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><input TYPE="hidden" NAME="_charset_" VALUE="utf-8"><!--webbot bot="FileUpload" i-checksum="45034" endspan -->
	<table border="0" width="650" id="table1" cellspacing="5" height="358">
		<tr>
			<td colspan="3" height="28">
			<p class="line30T" align="center">編輯賣書</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">書名</td>
			<td width="288" height="23"><input type="text" name="b_name" size="20" value="<?php echo $bookdata['book_name'];?>"></td>
			<td rowspan="7" width="264" height="164">
			<p align="center">
			<div id="div_preview_1" style="width:200px; height:260px; border-style:solid; border-width:1px; " /><img border="0" id="bimg" src="<?php echo $bookdata[img];?>" name="bimg"  width="210" height="280"></div></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">作者</td>
			<td width="288" height="23"><input type="text" name="author" size="20" value="<?php echo $bookdata['author'];?>"></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">出版社</td>
			<td width="288" height="23"><input type="text" name="publisher" size="20" value="<?php echo $bookdata['publisher'];?>"></td>
		</tr>
		<tr>
			<td align="right" width="73" height="26">學院 - 課程</td>
			<td width="288" height="26"><select size="1" name="college">
			<?php 
						echo '<option value="0"';if($bookdata[college_no] == 0)echo 'selected';echo '>--請選擇學院--</option>';
						for($i=0; $i< $college_num; $i++){
							 echo '<option value="'.$college_list[$i][college_no].'"';if($bookdata[college_no] == $college_list[$i][college_no])echo 'selected';echo '>'.$college_list[$i][college_name].'</option>'; 
						}
			?>
			</select>&nbsp; <input type="text" name="course" size="20" value="<?php echo $bookdata[course];?>"></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">原價</td>
			<td width="288" height="23"><input type="text" name="old_price" size="20" value="<?php echo $bookdata['old_price'];?>">元</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">售價</td>
			<td width="288" height="23"><input type="text" name="new_price" size="20" value="<?php echo $bookdata['new_price'];?>">元</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">新舊程度</td>
			<td width="288" height="23"><select size="1" name="new_old">
			<?php 
						echo '<option value="0"';if($bookdata[new_old] == 0)echo 'selected';echo '>--請選擇--</option>';
						for($i=0; $i< $newold_num; $i++) {
							echo '<option value="'.$new_old_list[$i][new_old_no].'"';if($bookdata[new_old] == $new_old_list[$i][new_old_no])echo 'selected';echo '>'.$new_old_list[$i][new_old_name].'</option>'; 
						}
			?>
			</select></td>
		</tr>
		<tr>
			<td align="right" valign="top" width="73" height="118">敘述</td>
			<td width="288" height="118"><textarea rows="7" name="describe" cols="32" ><?php echo $bookdata['b_describe'];?></textarea></td>
			<td valign="top" width="264" height="118">
			<p align="center">圖片上傳：<input type="file" name="file" onchange="previewUploadPic(this, document.getElementById('div_preview_1'), 200); " size="20" /></td>
		</tr>
		<tr>
			<td width="644" colspan="3" height="44">
			<p align="center"><input type="button" onClick="blankCheck()" value="修改" name="B2"><input type="reset" value="復原" name="B3"></td>
		</tr>
	</table>
	<input type="hidden" value="1" name="control_edit">
	<input type="hidden" name="max_file_size" value="1024000">
	<p>&nbsp;</p>
</form>
&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>