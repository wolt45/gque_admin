<?php
$mxHost = "localhost";
$mxUser = "softmo_admin";
$mxPwd  = "MedixMySqlServerBox1";
$mxDB 	= "ipadrbg";

$db_connect = mysqli_connect($mxHost, 
							 $mxUser,
							 $mxPwd ,
							 $mxDB) 
	OR DIE ("Connect failed: prod level");

?>
