<?php


//Variables
$source = "http://jemena.com.au/outages/find-my-distributor.aspx";

//Connect to db
include_once("../../config.inc.php");
mysql_connect($dbconfig['db_server'], $dbconfig['db_username'], $dbconfig['db_password']);
mysql_select_db($dbconfig['db_name']);

//Query the db for results
$ps = $_POST['id'];
$sql = "SELECT * FROM postcode_distributor WHERE postcode='$ps'";
$result = mysql_query($sql);
if($result)
{
	//Fetch result
	$rows = array();
	while($row = mysql_fetch_assoc($result))
		array_push($rows, $row);
	
	//No record
	if(count($rows) == 0)
	{
		echo "Did not find record in database.";
	}
	else
	{
		$output = "<table class='quote-list'><tr><th>postcode</th><th>suburb</th><th>distributor</th></tr>";
		foreach($rows as $row)
			$output .= "<tr><td>".$row['postcode']."</td><td>".ucwords(strtolower($row['suburb']))."</td><td>".$row['distributor']."</td></tr>";
		$output .= "</table>
		<p>The database was updated on 2013-7-2 10:30PM, the source is <a href='$source' target='_blank'>Jemena's webpage</a>.</p>
		";
		echo $output;
	}
}

?>