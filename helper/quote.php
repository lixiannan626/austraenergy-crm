<html>
<head>
<link href="../crm/modules/QuotePrice/qp/stylesheets/single_view.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<?php

$link = mysql_connect("localhost", "root", "johnamy");
mysql_select_db("goforsolar_crm");

$query = "SELECT * FROM quote_request, vtiger_users WHERE req_user=id ORDER BY req_id DESC LIMIT 0, 10";

$result = mysql_query($query);

$rows = array();

while($row = mysql_fetch_assoc($result))
	array_push($rows, $row);
	
foreach($rows as $request)
{
	echo "
	<div class='request' style='border-bottom: 1px solid black; width: 500px; padding: 10px;'>
		On ".$request['req_time']." By ".$request['user_name']."
		".stripslashes($request['req_detail'])."
	</div>
	
	
	";
}


?>
</body>
</html>