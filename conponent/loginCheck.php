<?php
session_start();
if( !session_is_registered('user') ){
	//$direct = explode('/', $_SERVER["HTTP_REFERER"]);
	header("location:login.php");
}
?>