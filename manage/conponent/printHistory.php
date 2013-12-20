<?php
session_start();
if( !session_is_registered('admin') ){
	header("location:login.php");
}
?>