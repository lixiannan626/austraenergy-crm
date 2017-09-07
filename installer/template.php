<?php
// Check if session is not registered, redirect back to main page. 
// Put this code in first line of web page. 
session_start();
if(!session_is_registered(myusername))
{
	header("location:main_login.php");
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Go For Solar Installer Portal</title>
<style>
	#wrap_up{width: 1000px; margin: 0 auto; 
					-moz-box-shadow: 0 0 5px #888;
					-webkit-box-shadow: 0 0 5px#888;
					box-shadow: 0 0 5px #888;}
</style>
</head>
<body>
	<div id="wrap_up">
		<div id="header">
		
		</div>
		<div id="nav">
		
		</div>
		<div id="main">
		<?php Main(); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
</body>
</html>

<?php
function Main()
{
	//Main Task
	include_once("connect_db.php");
	$id = $_COOKIE['ck_login_id'];
	$query = "SELECT * FROM custom_install_team WHERE team_id= $id";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1)
	{	
		$r = mysql_fetch_array($result, MYSQL_ASSOC);
		foreach($r as $key=>$value)
			echo "$key=>$value</br>";
	}
}
?>