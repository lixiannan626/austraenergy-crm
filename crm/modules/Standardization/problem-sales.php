<script type="text/javascript">
document.getElementById("p7").className += "selected";
</script>
<h3>Problem: Fully paid but not sales</h3>
<p class="exp">Suggestion: update sales progress.</p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$today = date('Y-m-d');
$rows = array();
$query = "SELECT contact_no,
							crmid,
							cf_612 as 'sales_progress',
							cf_646 as 'install_flag',
							cf_619 as 'payment_flag',
							cf_625 as 'install_date'
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_646 = 1
				AND cf_619 LIKE 'Yes'
				AND cf_612 NOT LIKE 'Sale'
				ORDER BY cf_625 ASC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);
	
//Display Table
if(count($rows) != 0)
{
	$c = 0;
	$ths = array("#", "CON #", "Sales Progress", "Installed?", "Fully paid?", "Install Date");
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
					<td class='center'>&#10004;</td>
					<td class='center'>$r[4]</td>
					<td>$r[5]</td>
				</tr>";
	}
	echo "</table>";
} 
else
	echo "<p class='exp'>No record found related to the problem.</p>";
?>