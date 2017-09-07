<?php
error_reporting(0);
$tn = $_POST['tn'];
$field = $_POST['field'];
$val = trim($_POST['val']);
$userid = $_POST['userid'];

$tn = str_replace("'", "&#39;", $tn);
$field = str_replace("'", "&#39;", $field);
$val = str_replace("'", "&#39;", $val);

//echo 'Er0rr';

//Update this when uploading to the server
$link = mysql_connect("localhost", "root", "johnamy");
mysql_select_db("goforsolar_crm");
$q = "UPDATE custom_install_team SET $field ='$val' WHERE team_name='$tn'";
//echo $q;
mysql_query($q);
//Update the field successfully
if(mysql_errno() == 0)
{
	//Log the update
	$time = date("Y-m-d H:i:s");
	$ip = $_SERVER['REMOTE_ADDR'];
	
	mysql_query("INSERT INTO custom_install_team_changelog VALUES ('$time', '$ip', $userid, '$tn', '$field', '$val')");
	//Return new value
	echo $val;
}
else
	echo "Er0rr";
?>