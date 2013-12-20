<?php
// 建立資料庫連線
$link = mysql_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD);

// 選擇資料庫
mysql_select_db($DB_NAME, $link);
?>