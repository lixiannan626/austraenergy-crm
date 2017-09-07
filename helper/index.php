<?php

$link = mysql_connect("localhost", "root", "johnamy");
mysql_select_db("goforsolar_crm");
$query1 = "SELECT tablename, count(tablename) FROM vtiger_field GROUP BY tablename ";

//Show the links to tables
echo "<div id='tn_div'><h3>Tables</h3>";
$result = mysql_query($query1);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	if($row[1] >2)
		echo "<a href='?table=$row[0]'>$row[0] ($row[1])</a>";
echo "</div>";


//Show detail if table is selected
echo "<div id='tb_div'>";
if(isset($_GET['table']))
{
	$rows = array();
	$table = $_GET['table'];
	$query2 = "SELECT fieldname, fieldlabel FROM vtiger_field WHERE tablename LIKE '$table'";
	$result = mysql_query($query2);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($rows, $row);
	
	//Display
	$ths = array("Label", "SQL field name");
	
	echo "
		<h4>TABLE $table's fields</h4>
		<table id='labeltable'>
					<tr>";
	foreach($ths as $th)
		echo "<th>$th</th>";
	echo "</tr>";
	
	foreach($rows as $r)
		echo "<tr><td>$r[1]</td><td>$r[0]</td></tr>";
	
	echo "</table>";
}

echo "</div>";


?>


<style>
	*{margin: 0; padding: 0; font-family: "Arial";}
	
	#tb_div{float: left; padding: 10px;}
	table{border-collapse: collapse;}
	th, td{border: 1px solid gray; padding: 5px 10px;}
	th{background: gray; color: #fff; font-weight: heavy;}
	tr:hover{background: #f2f2f2;}
	
	#tn_div a:link, #tn_div a:visited{display: block; margin: 5px; padding: 5px 10px; border: 2px solid #204363; color: #204363; border-radius: 3px; text-decoration: none;}
	#tn_div a:hover{background: #204363; color: #fff;}
	#tn_div{border-right: 2px solid #204363; float: left; padding: 5px 0;}
	
	h3, h4{color: #204363;}
</style>