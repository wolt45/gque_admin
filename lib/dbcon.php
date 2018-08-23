<?php
$mxHost = "localhost";
$mxUser = "root";
$mxPwd  = "";
$mxDB 	= "ipadrbg";
$db_ipadrbg = mysqli_connect($mxHost, 
							 $mxUser,
							 $mxPwd ,
							 $mxDB) 
	OR DIE ("Connection error!");
?>