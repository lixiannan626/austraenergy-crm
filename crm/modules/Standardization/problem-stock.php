<script type="text/javascript">
document.getElementById("p8").className += "selected";
</script>
<h3>Problem: Stock picked up but no date</h3>
<p class="exp">Suggestion: Fill the stock pickup date or change the sales progress to something other than "sale".</p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$today = date('Y-m-d');
$rows = array();
$query = "SELECT contact_no,
							crmid,
							cf_648 as 'pickupflag',
							mailingstate,
							cf_612 as 'progress',
							cf_627 as 'installteam',
							cf_659 as 'pickup1',
							cf_660 as 'pickup2',
							cf_661 as 'pickup3',
							cf_625 as 'install_date'
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				AND cf_648 LIKE  'Yes'
				AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)
				ORDER BY mailingstate, cf_627, cf_625 ASC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);
	
//Display Table
if(count($rows) != 0)
{
	$c = 0;
	$ths = array("#", "CON #", "Picked up?", "State","Progress", "Install Team","Panel<br> Pickup date","Inverter <br>Pickup date", "Mounting Kit <br>Pickup date", "Install Date", "Comments");
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
					<td class='center'>&#10004;</td>
					<td>$r[3]</td>
					<td>$r[4]</td>
					<td>$r[5]</td>
					<td>$r[6]</td>
					<td>$r[7]</td>
					<td>$r[8]</td>
					<td>$r[9]</td>
					<td class='center'><a class='comment_detail' data-id='$r[1]'>Detail</a></td>
				</tr>";
	}
	echo "</table>";
} 
else
	echo "<p class='exp'>No record found related to the problem.</p>";
?>