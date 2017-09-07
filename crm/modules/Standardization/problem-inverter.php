<script type="text/javascript">
document.getElementById("p6").className += "selected";
</script>
<h3>Problem: New and old inverter sizes are different</h3>
<p class="exp">New inverter size (model) was introduced on June, most contacts prior to that didn't migrate to the new size.</p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$today = date('Y-m-d');
$rows = array();
$query = "SELECT contact_no,
							crmid,
							cf_623 as 'brand',
							cf_636 as 'old',
							cf_650 as 'new1',
							cf_657 as 'new2',
							cf_658 as 'new3',
							cf_625 as 'installdate'
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND (cf_636 IS NOT NULL OR cf_636 != '')
				AND (cf_650 IS NULL OR cf_650 = '')
				AND cf_625 <= '$today'
				ORDER BY cf_625 ASC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);
	
//Display Table
if(count($rows) != 0)
{
	$c = 0;
	$ths = array("#", "CON #", "Inverter Brand", "Old size", "New size 1","New size 2","New size 3", "Install Date");
	echo "<table class='detail_table'>
				<tr>";
	foreach($ths as $th)
		echo "<th>$th</th>";
	echo "</tr>";
	foreach($rows as $r)
	{
		echo "<tr>
					<td>".++$c."</td>
					<td><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=$r[1]' target='_blank'>$r[0]</a></td>
					<td>$r[2]</td>
					<td>$r[3]</td>
					<td>$r[4]</td>
					<td>$r[5]</td>
					<td>$r[6]</td>
					<td>$r[7]</td>
				</tr>";
	}
	echo "</table>";
} 
else
	echo "<p class='exp'>No record found related to the problem.</p>";
?>