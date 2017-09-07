<?php

$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password="johnamy"; // Mysql password 
$db_name="goforsolar_crm"; // Database name 
$tbl_name="custom_install_team"; // Table name 

// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

?>