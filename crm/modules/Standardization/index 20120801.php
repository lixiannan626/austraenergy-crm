<?php
//error_reporting(-1);
include_once('language/en_us.lang.php');
echo "Hello World!1";

$rows = array();
$query = "SELECT crmid, contact_no, cf_623, cf_636, cf_650
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND cf_612 LIKE 'Sale'
				AND cf_650 LIKE ''
				AND deleted = 0";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);

DisplayInverterTable($rows);
	
//
function DisplayInverterTable($rows)
{
	$ths = array("CON #", "Inverter Brand", "Old Size", "New Size", "Same", "Action");
	if(count($rows) == 0)
		return false;
	else
	{
		echo "<table class='detail_table' style='border: 1px solid black; border-collapse: collapse;'><tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($rows as $row)
		{
			$same = "";
			
			echo "<tr>
						<td>$row[1]</td>
						<td>$row[2]</td>
						<td>$row[3]</td>
						<td>$row[4]</td>
						<td>$same</td>
						<td>Not Now</td>
					</tr>";
		}
		echo "</table>";
	}
}
?>

<style>
.detail_table th, .detail_table td{border: 1px solid black;}
</style>