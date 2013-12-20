<?php
session_start();
if(session_is_registered('power') AND $_SESSION[power] != 1){
	die('<script>alert("Your usage right has been suspended!");location.href="index.php"</script>');
}
if($_POST[control_sell] == 1){
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認

mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");
putenv("TZ=Asia/Taipei");
// 處理FORM資料並INSERT到資料庫
$b_name = $_POST[b_name];
$author = $_POST[author];
$publisher = $_POST[publisher];
$college = $_POST[college];
$course = $_POST[course];
$teacher = $_POST[teacher];
$old_price = $_POST[old_price];
$new_price = $_POST[new_price];
$new_old = $_POST[new_old];
$describe = $_POST[describe];
$reg_time = date("Y-m-d H:i:s");

if($_FILES['file']['error'] > 0){
switch($_FILES['myfile']['error']){
case 1 : die("檔案大小超出 php.ini:upload_max_filesize 限制");
case 2 : die("檔案大小超出 MAX_FILE_SIZE 限制");
case 3 : die("檔案僅被部分上傳");
case 4 : die("檔案未被上傳");
}
}
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
$sql = "INSERT INTO book (  reg_time , on_time , buy_time , finish_time , unsale_time , book_name , publisher , author ,
                             college_no , course , seller , new_old , book_state_no ,trade_state ,
														b_describe , old_price , new_price , fee, b_storage, img)
               VALUES ( '$reg_time', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00','0000-00-00 00:00:00', '$b_name', '$publisher',
               '$author', '$college', '$course', '$_SESSION[user]' , '$new_old', '1', 'n', '$describe', '$old_price', '$new_price', '0', '0','$pic')";
mysql_query($sql, $link);
$rel_time = date("Y-m-d H:i:s");

$sql = "INSERT INTO remind (title, type, release_time, member_id)VALUES('您已成功販賣$_POST[b_name]，請至會員中心查看，並在期限內到聯合中心繳書!','2','$rel_time','$_SESSION[user]')";
mysql_query($sql, $link);

header("location:remind.php");


}
else{
// 取得系統組態
include ("connectDB/configure.php");
// 連結資料庫
include ("connectDB/connect_db.php");
//處理文字編碼
include("conponent/loginCheck.php");
//登入確認
mysql_query("SET NAMES UTF8");
mysql_query("set character_set_results=UTF8");

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
}
?>
<html>
<script type="text/javascript">
function blankCheck()
{
	if(document.sell_form.b_name.value == '')
	{
		alert("請輸入書名!");
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
		if(document.sell_form.old_price.value < document.sell_form.new_price.value){
			alert("售價不可大於原價，請重新輸入!!");
			document.sell_form.new_price.focus();
		}
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
                "sizingMethod=image; \">" +
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
            o.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = v;
            o.removeAttribute("id");
        }else
         {
            o.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = v;
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
<form method="POST" name="sell_form" id="sell_form" action="sell.php" enctype="multipart/form-data">
	<!--webbot bot="FileUpload" U-File="_private/form_results.csv" S-Format="TEXT/CSV" S-Label-Fields="TRUE" S-Destination="book_images/" startspan --><input TYPE="hidden" NAME="VTI-GROUP" VALUE="0"><input TYPE="hidden" NAME="_charset_" VALUE="utf-8"><!--webbot bot="FileUpload" i-checksum="45034" endspan -->
	<table border="0" width="650" id="table1" cellspacing="5" height="358">
		<tr>
			<td colspan="3" height="28">
			<p class="line30T" align="center">新增賣書</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">書名</td>
			<td width="288" height="23"><input type="text" name="b_name" size="20"></td>
			<td rowspan="7" width="264" height="164">
			<p align="center"><div id="div_preview_1" style="width:200px; height:260px; border-style:solid; border-width:1px; " /></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">作者</td>
			<td width="288" height="23"><input type="text" name="author" size="20"></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">出版社</td>
			<td width="288" height="23"><input type="text" name="publisher" size="20"></td>
		</tr>
		<tr>
			<td align="right" width="73" height="26">學院 - 課程</td>
			<td width="288" height="26"><select size="1" name="college">

			<?php
						echo '<option value="0">--請選擇學院--</option>';
						for($i=0; $i< $college_num; $i++) echo '<option value="'.$college_list[$i][college_no].'">'.$college_list[$i][college_name].'</option>';
			?>
			</select>&nbsp; <input type="text" name="course" size="20" ></td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">原價</td>
			<td width="288" height="23"><input type="text" name="old_price" size="20">元</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">售價</td>
			<td width="288" height="23"><input type="text" name="new_price" size="20">元</td>
		</tr>
		<tr>
			<td align="right" width="73" height="23">新舊程度</td>
			<td width="288" height="23"><select size="1" name="new_old">
			<?php
						echo '<option value="0">--請選擇--</option>';
						for($i=0; $i< $newold_num; $i++) echo '<option value="'.$new_old_list[$i][new_old_no].'">'.$new_old_list[$i][new_old_name].'</option>';
			?>
			</select></td>
		</tr>
		<tr>
			<td align="right" valign="top" width="73" height="118">敘述</td>
			<td width="288" height="118"><textarea rows="7" name="describe" cols="32"></textarea></td>
			<td valign="top" width="264" height="118">
			<p align="center">圖片上傳：<input type="file" name="file" onchange="previewUploadPic(this, document.getElementById('div_preview_1'), 200); " size="20" /></td>
		</tr>
		<tr>
			<td width="644" colspan="3" height="44">
			<p align="center"><input type="button" onClick="blankCheck()" value="送出" name="B2"><input type="reset" value="重新設定" name="B3"></td>
		</tr>
	</table>
	<input type="hidden" value="1" name="control_sell">
	<input type="hidden" name="max_file_size" value="1024000">
	<p>&nbsp;</p>
</form>
&nbsp;</div>
<?php include("footer.php");?>
</body>

</html>