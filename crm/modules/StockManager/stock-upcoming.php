<script type="text/javascript">
document.getElementById("i3").className += "selected";
</script>
<div id="vicqld">
					<a id="vicjob" class="selected">VIC</a>
					<a id="qldjob">QLD</a>
				</div>
<p><span class='gone'>Stock</span>: means that particular stock (panel, inverter, mounting kit) has been picked up.</p>
<?php
$today = date("Y-m-d");
$query = "SELECT crmid, contact_no, 
							concat_ws(' ', firstname, lastname) as 'fullname',
							concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as address,
							IF(concat_ws(' ',cf_620, cf_621) LIKE ' ', 'Unknown', concat_ws(' ',cf_620, cf_621)) as 'panelbrand',
							cf_621 as 'panelsize',
							cf_622 as 'panelnumber',
							IF(cf_623 LIKE '', 'Unknown', cf_623) as 'inverterbrand',
							IF(cf_650 LIKE '', 'Unknown', cf_650) as 'invertermodel1',
							cf_657 as 'invertermodel2',
							cf_658 as 'invertermodel3',
							IF(concat_ws(' ', cf_609, cf_608) LIKE ' ', 'Unknown', concat_ws(' ', cf_609, cf_608)) as 'mounting',
							cf_625 as 'installdate',
							cf_627 as 'installteam',
							IF(cf_659 IS NULL, 'waiting', 'gone') as 'f1',
							IF(cf_660 IS NULL, 'waiting', 'gone') as 'f2',
							IF(cf_661 IS NULL, 'waiting', 'gone') as 'f3',
							TRIM(UPPER(mailingstate)) as 'state'
							FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
							WHERE crmid = vtiger_contactdetails.contactid
							AND crmid = vtiger_contactaddress.contactaddressid
							AND crmid = vtiger_contactscf.contactid
							AND deleted = 0
							AND cf_612 LIKE 'Sale'
							AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)
							ORDER BY cf_625 DESC";
$vic = array();
$qld = array();
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	if($row['state'] == "VIC")
		array_push($vic, $row);
	else
		array_push($qld, $row);
}

$ths = array("#", "CON", "Customer Detail", "Panel", "Inverter", "Mounting", "Install Team", "Install Date", "Comment");
ShowTable2($vic, "VIC Detail", $ths, "vic");
ShowTable2($qld, "QLD Detail", $ths, "qld");
		 				
							
							
							
function ShowTable2($array, $h2, $ths, $class)
{
	echo "<div class='job_div $class'>
				<h3>$h2 (".count($array)." records)</h3>
				";
	if(count($array) !=0)
	{
		$c = 0;
		echo "<table>
					<tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($array as $a)
		{
			$c++;
			echo "<tr class='data'><td>$c</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."<br>".ucwords(strtolower($a['address']))."</td>
						<td><span class='".$a['f1']."'>".$a['panelnumber']." &times; ".$a['panelbrand']."</span></td>
						<td><span class='".$a['f2']."'>".$a['inverterbrand'].' '.$a['invertermodel1']."$c2 $c3</span></td>
						<td><span class='".$a['f3']."'>".$a['mounting']."</span></td>
						<td>".$a['installteam']."</td>
						<td>".$a['installdate']."</td>
						<td class='center'><a class='comment_detail' data-id='".$a['crmid']."'>Detail</a></td>
			</tr>";
		}
		echo "</table></div>";
	}
	else
	{
		//echo "<p class='no_record'>No record found.</p></div>";
		echo "</div>";
	}
}

?>