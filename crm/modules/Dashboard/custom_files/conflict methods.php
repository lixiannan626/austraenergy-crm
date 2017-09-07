<?php



//Return an array of lead sources
function getAllLeadSource()
{
	$source = array();
	$link = mysql_connect('10.0.0.200:3306','remoteaccess','remote3073');
	if(!$link)
	{
		die('Could not connect:'.mysql_error());
	}
	else
	{
		mysql_select_db("vtigercrm521");
		mysql_query("set names utf8") ; 
		
		//Get all lead sources' names
		$result = mysql_query("SELECT leadsource FROM vtiger_leadsource");
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($rown = mysql_fetch_array($result, MYSQL_NUM)) 
		{
			$source[count($source)] = $rown[0];
		}
	}
	return $source;	
}
//Return an array of arrays
//Each element is an array, containing 2 elements: User_id, User_name
function getAllUsersIdName()
{
	$users = array();
	$link = mysql_connect('10.0.0.200:3306','remoteaccess','remote3073');
	if(!$link)
	{
		die('Could not connect:'.mysql_error());
	}
	else
	{
		mysql_select_db("vtigercrm521");
		mysql_query("set names utf8") ; 
		
		//Get all users' names
		$result = mysql_query("SELECT id,user_name FROM vtiger_users");
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($rown = mysql_fetch_array($result, MYSQL_NUM)) 
		{
			$users[count($users)] = $rown;
		}
	}
	return $users;
}
//Return an array of User_name only
function getAllUserName($idname)
{
	$username = array();
	foreach($idname as $element)
	{
		$username[count($username)] = $element[1];
	}
	return $username;
}
//Return an array of User_Id only
function getAllUserId($idname)
{
	$userid = array();
	foreach($idname as $element)
	{
		$userid[count($userid)] = $element[0];
	}
	return $userid;
}
//Debug Array
function displayArray1($array)
{
	echo '##### Array Start #########<br>';
	foreach($array as $element)
	{
		echo $element.'<br>';
	}
	echo '##### Array End #########<br>';
}
function displayArrayn($array)
{
	echo '##### Array Start #########<br>';
	foreach($array as $elements)
	{
		foreach($elements as $element)
		{
			echo $element.'    ';
		}
		echo '<br>';
	}
	echo '##### Array End #########<br>';
}

?>