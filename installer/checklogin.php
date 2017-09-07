<?php
error_reporting(0);
ob_start();
include_once("connect_db.php");

// Define $myusername and $mypassword 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);

//Must not be empty
if(trim($myusername) == "" || trim($mypassword) == "")
{
	header("location:main_login.php?ec=1");
}
else
{
	//Regardless of the success login or failed login, the action must be recorded
	//Log in custom_installer_login_log
	$time = date("Y-m-d H:i:s");
	$ip = $_SERVER['REMOTE_ADDR'];
	mysql_query("INSERT INTO custom_installer_login_log (LoginTime, LoginIp, LoginUsername, LoginPassword) VALUES ('$time', '$ip', '$myusername', '$mypassword')");
	
	
	//Verify the identity
	$sql="SELECT team_id, team_name FROM $tbl_name WHERE team_username='$myusername' and team_password='$mypassword'";
	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1)
	{
		$r = mysql_fetch_array($result, MYSQL_NUM);
		// Register $myusername, $mypassword and redirect to file "login_success.php"
		session_register("myusername");
		session_register("mypassword"); 
		header("location:index.php");
		 setcookie('ck_login_id', $r[0]);
		 setcookie('ck_login_name', $r[1]);
		
	}
	else 
	{
		header("location:main_login.php?ec=1");
	}
}
ob_end_flush();
?>
