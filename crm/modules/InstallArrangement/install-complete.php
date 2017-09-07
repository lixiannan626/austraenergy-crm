<script type="text/javascript">
document.getElementById("i4").className += "selected";
</script>

<?php
//No Install Date
$today = date("Y-m-d");
$rows = array();
$query = "SELECT contact_no,
							crmid,
							concat_ws(' ', firstname, lastname) as 'fullname',
							concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as 'address',
							cf_625 as 'installdate',
							cf_627 as 'installteam',
							TRIM(LOWER(mailingstate)) as 'state'
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactaddress.contactaddressid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND cf_625 <= '$today'
				AND cf_646 = 1
				ORDER BY cf_625 ASC
				";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	array_push($rows, $row);

if(count($rows) != 0)
{
	$v = $q = array();
	foreach($rows as $r)
		if($r['state'] == 'vic' || $r['state'] == 'victoria')
			array_push($v, $r);
		else
			array_push($q, $r);
	
	$c1 = count($v);
	$c2 = count($q);
	echo "
			<div id='vicqld'>
				<a id='vicjob' class='selected'>VIC ($c1)</a>
				<a id='qldjob'>QLD ($c2)</a>
			</div>
	";
				
	ShowTable($v, "confirm_vic_div", "Job status confirmed", "", "vic");
	
	ShowTable($q, "confirm_qld_div", "Job status confirmed", "", "qld");

}
else
{
	echo "<p>No job found.</p>";
}





?>