<script type="text/javascript">
document.getElementById("p1").className += "selected";
</script>
<h3>Problem: Street contains either Suburb, State or Zip</h3>
<p class="exp"></p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$rows = array();
$query = "SELECT contact_no,
							crmid,
							LOWER(mailingstreet),
							LOWER(mailingcity),
							LOWER(mailingstate),
							mailingzip
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND (mailingstreet LIKE concat('% ', mailingcity, ' %')
				OR mailingstreet LIKE concat('% ', mailingstate, ' %')
				OR mailingstreet LIKE concat('% ', mailingzip, ' %'))
				AND mailingstreet NOT REGEXP '[0-9a-zA-Z]* [a-zA-Z]* [a-zA-Z]*'
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
		$s = "<span class='red'>";
		$s1 = "<span class='orange'>";
		$s2 = "<span class='purple'>";
		$e = "</span>";
		$r[2] = ucwords($r[2]);
		$r[3] = ucwords($r[3]);
		$r[4] = ucwords($r[4]);
		$r[2] = str_replace($r[3], $s.$r[3].$e, $r[2]);
		$r[2] = str_replace($r[4], $s1.$r[4].$e, $r[2]);
		$r[2] = str_replace($r[5], $s2.$r[5].$e, $r[2]);
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