<script type="text/javascript">
document.getElementById("p4").className += "selected";
</script>
<h3>Problem: Postal address and install address mixed</h3>
<p class="exp">Following contacts' postal address and install address is mixed.</p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$rows = array();
$query = "SELECT contact_no,
							crmid,
							mailingstreet,
							mailingcity,
							mailingstate,
							mailingzip
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND (
							mailingstreet LIKE '%Post%' 
							OR mailingstreet LIKE '%box%'
							OR mailingstreet LIKE '%install%'
							)
				AND mailingstreet NOT LIKE '%postle%'
				ORDER BY mailingstate DESC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);
	
//Display Table
if(count($rows) != 0)
{
	$c = 0;
	$ths = array("#", "CON No", "Street", "Suburb", "State", "Postcode");
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
				</tr>";
	}
	echo "</table>";
} 
else
	echo "<p class='exp'>No record found related to the problem.</p>";
?>