<script type="text/javascript">
document.getElementById("i2").className += "selected";
</script>

<!-- Seperated in dates/installer-->
<div id="date_or_team">
<p><a href="index.php?module=InstallArrangement&action=index&parenttab=Support&i=i1" id='d1'>Sort By Install Date</a>  
	<a href="index.php?module=InstallArrangement&action=index&parenttab=Support&i=i1&byteam=1" id='d2'>Sort By Install Team</a><p>
</div>
<script type="text/javascript">
<?php
if(isset($_GET['byteam']))
	echo "var did = 'd2';";
else
	echo "var did = 'd1';";
?>
document.getElementById(did).className += "selected";
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
				AND (cf_625 LIKE '' OR cf_625 IS NULL OR cf_625 > '$today')
				ORDER BY cf_627 DESC, cf_625 ASC
				";
$result = mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	if($row['installteam'] == '')
		$row['installteam'] = "Unknown";
	array_push($rows, $row);
}

//By Install Date
if(isset($_GET['byteam']) == false)
{
	if(count($rows) != 0)
	{
		$ev = $eq = $dv = $dq = array();
		foreach($rows as $r)
			if($r['installdate'] == '')
				if($r['state'] == 'vic' || $r['state'] == 'victoria')
					array_push($ev, $r);
				else
					array_push($eq, $r);
			else
				if($r['state'] == 'vic' || $r['state'] == 'victoria')
					array_push($dv, $r);
				else
					array_push($dq, $r);

		$c1 = count($ev)+count($dv);
		$c2 = count($eq)+count($dq);
		echo "
				<div id='vicqld'>
					<a id='vicjob' class='selected'>VIC ($c1)</a>
					<a id='qldjob'>QLD ($c2)</a>
				</div>
		";
					
		ShowTable($dv, "withdate_vic_div", "Jobs with install dates assigned", "", "vic");
		ShowTable($ev, "empty_vic_div", "Jobs with no install date", "", "vic");
		
		ShowTable($dq, "withdate_qld_div", "Jobs with install dates assigned", "", "qld");
		ShowTable($eq, "empty_qld_div", "Jobs with no install date", "", "qld");

	}
	else
	{
		echo "<p>No job found.</p>";
	}
}

//By Install Team
else
{
	if(count($rows) != 0)
	{
		$vt = $qt = array();
		$c1 = $c2 = 0;
		foreach($rows as $r)
		{
			if($r['state'] == 'vic')
			{
				if(isset($vt[$r['installteam']]) == false)
					$vt[$r['installteam']] = array();
				array_push($vt[$r['installteam']], $r);
				$c1++;
			}
			else
			{
				if(isset($qt[$r['installteam']]) == false)
					$qt[$r['installteam']] = array();
				array_push($qt[$r['installteam']], $r);
				$c2++;
			}
		}
		echo "
				<div id='vicqld'>
					<a id='vicjob' class='selected'>VIC ($c1)</a>
					<a id='qldjob'>QLD ($c2)</a>
				</div>
		";
		foreach($vt as $key=>$vtt)
			ShowTable($vtt, "team_$key_div", "$key","", "vic");
		foreach($qt as $key=>$qtt)
			ShowTable($qtt, "team_$key_div", "$key","", "qld");
		
	}
}

?>