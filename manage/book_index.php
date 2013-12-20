<?php
//檢查是否登入
include("conponent/loginCheck.php");
?>
<html>	
<script type="text/javascript">	
	function pageGO( pagenum )
	{	
		document.ann_form.page.value = pagenum;
		document.ann_form.submit();
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
<div id="left"><?php include("left_book.php"); ?></div>
<div id="main" class="main">
	<p class="line30T">二手書管理</p>
	<?php if($_GET[NotFound] == 1) echo '<p style="text-align: center" class="font11"><font color="#FF0000"><b>找不到此人!</b></font>'; ?>
	<p style="text-align: center" class="font11"><b>請在左側輸入搜尋條件</b>
</div></div>
<?php include("footer.php"); ?>
</body>

</html>