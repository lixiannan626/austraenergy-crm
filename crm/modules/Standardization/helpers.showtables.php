<?php
//Helpers

function showTables($data, $ths, $tds, $crmidguide)
{
	if(count($data) == 0)
		die("<p class='exp'>No record found.</p>");
	
	$o = "<table class='detail_table'>
			<tr><th>#</th>";
	foreach($ths as $th)
		$o .= "<th>$th</th>";
	$o .= "</tr>";
	
	for($i = 0; $i < count($data); $i++)
	{
		$da = $data[$i];
		$id = $da['crmid'];
		$contact_no = $da['contact_no'];
		$o .= "<tr>
				<td>".($i+1)."</td>
				<td><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=$id' target='_blank'>$contact_no</a></td>";
		foreach($tds as $td)
			$o .= "<td>$da[$td]</td>";
		$o .= "</tr>";
	}
	
	$o .= "</table>";
	echo $o;
}

function getData($sql)
{
	$arr = array();
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result))
		array_push($arr, $row);
	
	return $arr;
}
?>