<?php
include_once("../../config.inc.php");
mysql_connect($dbconfig['db_server'], $dbconfig['db_username'], $dbconfig['db_password']);
mysql_select_db($dbconfig['db_name']);

//CHECK USER ID

//VARIABLES
$id = $_POST['id'];

//UPDATE DB
$update = "UPDATE distributor_approval SET need_approval = MOD((1+need_approval),2) WHERE da_id=$id";
mysql_query($update);
if(mysql_errno() == 0)
	echo "OK";
else
	echo "$update";
?>