<script type="text/javascript">
document.getElementById("i2").className += "selected";
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
$qr = array();
$vr = array();
$result =mysql_query($query);
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	if($row['state'] == 'VIC')
		array_push($vr, $row);
	else
		array_push($qr, $row);
}
$vic_stat = GetStats($vr);
$qld_stat = GetStats($qr);

//Display
//Left Column: Summary
echo "<table><tr><td id='summary-td'>";
ShowSummary($vic_stat, "vic");
ShowSummary($qld_stat, "qld");

echo "</td><td>";
//Right Column: Detail


//Split into VIC/QLD
$ths = array("#", "CON", "Customer Detail", "Panel", "Inverter", "Mounting", "Install Date");
ShowTable($vr, "VIC Detail", $ths, "vic");
ShowTable($qr, "QLD Detail", $ths, "qld");


echo "</td></tr></table>";




function ShowSummary($stat, $class)
{
	echo "<div class='$class'>";
	echo "<h3>Summary</h3>";
	$panel = $stat[0];
	$inverter = $stat[1];
	$mounting = $stat[2];
	ksort($panel);
	ksort($inverter);
	ksort($mounting);
	if(count($panel) != 0)
	{
		echo "<table class='summary_table $class'>
						<tr><th>Panel</th><th>Qty</th></tr>";
		foreach($panel as $k=>$v)
			echo "<tr class='data' data-option='".GetClass($k)."'><td>$k</td><td class='center'>$v</td></tr>";
		echo "</table>";
	}
	if(count($inverter) != 0)
	{
		echo "<table class='summary_table $class'>
						<tr><th>Inverter</th><th>Qty</th></tr>";
		foreach($inverter as $k=>$v)
			echo "<tr class='data' data-option='".GetClass($k)."'><td>$k</td><td class='center'>$v</td></tr>";
		echo "</table>";
	}
	if(count($mounting) != 0)
	{
		echo "<table class='summary_table $class'>
						<tr><th>Mounting</th><th>Size (KW)</th></tr>";
		foreach($mounting as $k=>$v)
			echo "<tr class='data' data-option='".GetClass($k)."'><td>$k</td><td class='center'>".number_format($v/1000,2)."</td></tr>";
		echo "</table>";
	}
	echo "</div>";
}




//
//
//
function GetStats($raw)
{
	$out = array();
	$panel = array();
	$inverter = array();
	$mounting  = array();
	
	foreach($raw as $r)
	{
		//Panel Flag
		if($r['f1'] == 'waiting')
		{
			if(isset($panel[$r['panelbrand']]) == false)
				$panel[$r['panelbrand']] = 0;
			$panel[$r['panelbrand']] += $r['panelnumber'];
		}
		//Inverter Flag
		if($r['f2'] == 'waiting')
		{
			if(Dry($r['inverterbrand']) != "aps micro")
			{
				if($r['invertermodel1'] != '')
				{
					$bm = $r['inverterbrand'].' '.$r['invertermodel1'];
					if(isset($inverter[$bm]) == false)
						$inverter[$bm] = 0;
					$inverter[$bm] += 1;
				}
				if($r['invertermodel2'] != '' && $r['invertermodel2'] != 'None' )
				{
					$bm = $r['inverterbrand'].' '.$r['invertermodel2'];
					if(isset($inverter[$bm]) == false)
						$inverter[$bm] = 0;
					$inverter[$bm] += 1;
				}
				if($r['invertermodel3'] != '' && $r['invertermodel3'] != 'None' )
				{
					$bm = $r['inverterbrand'].' '.$r['invertermodel3'];
					if(isset($inverter[$bm]) == false)
						$inverter[$bm] = 0;
					$inverter[$bm] += 1;
				}			
			}
			else
			{
				if(isset($inverter[$r['inverterbrand']]) == false)
					$inverter[$r['inverterbrand']] = 0;
				$inverter[$r['inverterbrand']] += $r['panelnumber'];
			}
		}
		//Mounting Flag
		if($r['f3'] == 'waiting')
		{
			$ts = GetSize($r['panelsize'])*$r['panelnumber'];
			if(isset($mounting[$r['mounting']]) == false)
				$mounting[$r['mounting']] = 0;
			$mounting[$r['mounting']] += $ts;
		}
	}
	$out[0] = $panel;
	$out[1] = $inverter;
	$out[2] = $mounting;
	return $out;
}

?>