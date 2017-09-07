<script type="text/javascript">
document.getElementById("p9").className += "selected";
</script>
<h3>Problem: Installed but no stock picked date</h3>
<p class="exp">Suggestion: untick "INSTALLED?", remove install date, or fill in stock pickup date.</p>
<?php
/*
	This Script displays the sales which record suburb, state, zip in street
*/
$today = date('Y-m-d');
$rows = array();
$query = "SELECT contact_no,
							crmid,
							cf_648 as 'pickupflag',
							IF(cf_646 = 1, 'Yes', 'No') as 'installflag',
							cf_612 as 'progress',
							cf_659 as 'pickup1',
							cf_660 as 'pickup2',
							cf_661 as 'pickup3',
							cf_625 as 'install_date'
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_646 = 1
				AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)
				ORDER BY cf_625 ASC";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_NUM))
	array_push($rows, $row);
	
//Display Table
if(count($rows) != 0)
{
	$c = 0;
	$ths = array("#", "CON #", "Stock <br>Collected?", "Installed?", "Progress", "Panel<br> Pickup date","Inverter <br>Pickup date", "Mounting Kit<br> Pickup date", "Install Date", "Comments");
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
					<td class='center'>$r[2]</td>
					<td>$r[3]</td>
					<td>$r[4]</td>
					<td>$r[5]</td>
					<td>$r[6]</td>
					<td>$r[7]</td>
					<td>$r[8]</td>
					<td class='center'><a class='comment_detail' data-id='$r[1]'>Detail</a></td>
				</tr>";
	}
	echo "</table>";
} 
else
	echo "<p class='exp'>No record found related to the problem.</p>";
?>