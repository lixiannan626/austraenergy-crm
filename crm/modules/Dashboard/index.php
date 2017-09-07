<?php 
include_once("nav.php");
show_nav("Progress Report");
	
	//SOME GLOBAL VIARIABLES
	//Installed?
	$i1 = "AND cf_646 = 1 ";
	$i2 = "AND cf_646 != 1";	
	
	//Paperwork
	$p2 = " AND (cf_651 IS NULL OR cf_654 IS NULL OR cf_655 IS NULL OR cf_656 IS NULL) ";
	$p1 = " AND (cf_651 IS NOT NULL AND cf_654 IS NOT NULL AND cf_655 IS NOT NULL AND cf_656 IS NOT NULL) ";
	
	//Applying for STCs
	$sp = "  ";
	$s1 = " AND cf_632 IS NOT NULL AND cf_632 NOT LIKE '' ";
	$s2 = " AND (cf_632 IS NULL OR cf_632 LIKE '') ";
	
	
	//STC Grant
	$g1 = " AND cf_633 IS NOT NULL";
	$g2 = " AND cf_633 IS NULL ";
	
	//Send Docs to Retailers
	$r1 = " AND cf_652 IS NOT NULL ";
	$r2 = " AND cf_652 IS NULL ";
	
	//Pre-approval 
	$pa1 = "";
	$pa2 = "";

	//2013-07-03 Update, new condition for paperwork
	//If distributor requires pre-approval, 2 dates must be entered
	$pre_approval_field = ", concat_ws('','submit:', cf_669, '<br>approve:', cf_670) AS 'pre_approval', cf_668 AS distributor";
	$pre_approval_fail_condition = " ";
	$pre_approval_pass_condition = " ";
	$distributors = get_distributors();
	if(count($distributors) != 0)
	{
		$ds = implode("','", $distributors);
		$pre_approval_fail_condition = " AND ((cf_668 IN ('$ds') AND cf_669 IS NULL) OR (cf_668 IN ('$ds') AND cf_670 IS NULL)) ";
		$pre_approval_pass_condition = " AND (cf_668 NOT IN ('$ds') OR (cf_668 IN ('$ds') AND cf_669 IS NOT NULL AND cf_670 IS NOT NULL)) ";
	}
	$pa1 = $pre_approval_pass_condition;
	$pa2 = $pre_approval_fail_condition;
		
function get_distributors()
{
	$dis = array();
	$select = "SELECT distributor FROM distributor_approval WHERE need_approval=1";
	$result = mysql_query($select);
	if($result)
		while($row = mysql_fetch_assoc($result))
			array_push($dis, $row['distributor']);
	return $dis;
}
?>
<script>
$(document).ready(function(){
	//
	$("div#qld_jobs").hide();
	
	$("a#vicjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		
		$("div#vic_jobs").show();
		$("div#qld_jobs").hide();
	});
	
	$("a#qldjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		$("div#vic_jobs").hide();
		$("div#qld_jobs").show();
	});
	

});
</script>

<div id="report_wrapper" class="progress_report">

<table>
	<tr>
		<td id="left_nav">
		<p style='font-size: 10px; '>Click fields below to view detail.</p>
		<?php Nav(); ?>
		<p><span style='display: inline-block; height: 14px; width:14px; background: green;'></span>means completed.</span></p>
		<p><span style='display: inline-block; height: 14px; width:14px; background: orange;'></span>means not completed.</span></p>
		</td>
		<td id="main_content">
		<?php MainC(); ?>
		</td>
	</tr>
</table>
</div>

<?php
function MainC()
{
	global $pre_approval_field, $pre_approval_fail_condition, $pre_approval_pass_condition;
	global $i1, $i2, $s1, $s2, $sp, $g1, $g2, $r1, $r2, $p1, $p2, $pa1, $pa2;
	//Define queries
	$qb = "Select contact_no,
							crmid,
							concat_ws(' ', firstname, lastname) as 'fullname',
							concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as 'address',
							cf_625 as 'installdate',
							cf_627 as 'installteam',
							IF (cf_646 = 1, '&#10004;', '&#10008;') as 'installed',
							cf_654 as 'p_stc',
							cf_655 as 'p_connect',
							cf_656 as 'p_ewr',
							cf_651 as 'p_ces',
							cf_652 as 'docs_to_retailer',
							cf_632 as 'pvd',
							cf_633 as 'rec_grant' $pre_approval_field
				FROM vtiger_crmentity, vtiger_contactaddress, vtiger_contactscf, vtiger_contactdetails
				WHERE crmid = vtiger_contactaddress.contactaddressid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = vtiger_contactdetails.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				";
	$sv = $qb."AND mailingstate LIKE '%vic%' ";
	$sq = $qb."AND mailingstate NOT LIKE '%vic%' ";
	
	
	
	$order = " ORDER BY cf_625";
	
	//Assign values
	$q1 = $q2 = $q3 = $q4 = $h = $h2 = $h3 =  "";
	if($_GET['t'] == 'pa')
	{
		$q1 = $sv.$pa1.$order;
		$q2 = $sv.$pa2.$order;
		$q3 = $sq.$pa1.$order;
		$q4 = $sq.$pa2.$order;
		$h = "Approved or not";
		$h2 = "Yet Approved";
		$h3 = "Approved";
	
	}
	if($_GET['t'] == 'i')
	{	
		$q1 = $sv.$i1.$order;
		$q2 = $sv.$i2.$order;
		$q3 = $sq.$i1.$order;
		$q4 = $sq.$i2.$order;
		$h = "Installed or not";
		$h2 = "Yet Installed";
		$h3 = "Installed";
	}	
	if($_GET['t'] == 'p')
	{	
		$q1 = $sv.$i1.$p1.$order;
		$q2 = $sv.$i1.$p2.$order;
		$q3 = $sq.$i1.$p1.$order;
		$q4 = $sq.$i1.$p2.$order;
		$h = "Paperwork complete or not";
		$h2 = "Paperwork not complete";
		$h3 = "Paperwork complete";
	}	
	if($_GET['t'] == 's')
	{	
		$q1 = $sv.$i1.$s1.$sp.$order;
		$q2 = $sv.$i1.$s2.$sp.$order;
		$q3 = $sq.$i1.$s1.$sp.$order;
		$q4 = $sq.$i1.$s2.$sp.$order;
		$h = "Got PVD number or not";
		$h2 = "No PVD number";
		$h3 = "Got PVD number";
	}	
	if($_GET['t'] == 'g')
	{	
		$q1 = $sv.$g1.$s1.$order;
		$q2 = $sv.$g2.$s1.$order;
		$q3 = $sq.$g1.$s1.$order;
		$q4 = $sq.$g2.$s1.$order;
		$h = "STC Granted or not";
		$h2 = "No STC grant date";
		$h3 = "STC granted";
	}	
	if($_GET['t'] == 'r')
	{	
		$q1 = $sv.$r1.$i1.$order;
		$q2 = $sv.$r2.$i1.$order;
		$q3 = $sq.$r1.$i1.$order;
		$q4 = $sq.$r2.$i1.$order;
		$h = "Docs sent to retailer or not";
		$h2 = "Docs yet sent to retailer";
		$h3 = "Docs sent to retailer";
	}	
	ShowTables($q1, $q2, $q3, $q4,  $h, $h2, $h3);
}

function ShowTables($q1, $q2, $q3, $q4, $h, $h2, $h3)
{
	$ths = array("#", "CON #", "Client Info", "Team", "Install Date", "Installed?", "Distributor", "Pre-approval", "STC Form", "Conn. Form", "EWR (Form A/2)", "CES (COC)", "To Retailer", "PVD", "STC Grant");
	if($q1 == "")
		die("");
	echo "<h1>$h</h1>";
	echo '<div id="vicqld">
				<a id="vicjob" class="selected">VIC</a>
				<a id="qldjob">QLD</a>
			</div>';
	//VIC DIV
	echo "<div id='vic_jobs' class='job_div'>";
	echo "<h2>$h2</h2>";
	ShowTable($q2, $ths);
	echo "<h2>$h3</h2>";
	ShowTable($q1, $ths);
	echo "</div>";
	//QLD DIV
	echo "<div id='qld_jobs' class='job_div'>";
	echo "<h2>$h2</h2>";
	ShowTable($q4, $ths);
	echo "<h2>$h3</h2>";
	ShowTable($q3, $ths); 
	echo "</div>";
}
function ShowTable($q, $ths)
{
	$result = mysql_query($q);
	$rows = array();
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		array_push($rows, $row);
	if(count($rows)!= 0)
	{
		$c = 0;
		echo "<div class='waypoints-wrapper'><table class=''><tr class='waypoints-header'>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($rows as $a)
			echo "<tr>
						<td class='center'>".++$c."</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."<br>
						".ucwords(strtolower($a['address']))."</td>
						<td class='center'>".$a['installteam']."</td>
						<td class='center'>".$a['installdate']."</td>
						<td class='center'>".$a['installed']."</td>
						<td class='center'>".$a['distributor']."</td>
						<td class='center'>".$a['pre_approval']."</td>
						<td class='center'>".$a['p_stc']."</td>
						<td class='center'>".$a['p_connect']."</td>
						<td class='center'>".$a['p_ewr']."</td>
						<td class='center'>".$a['p_ces']."</td>
						<td class='center'>".$a['docs_to_retailer']."</td>
						<td class='center'>".$a['pvd']."</td>
						<td class='center'>".$a['rec_grant']."</td>
			</tr>";
		echo "</table></div>";
	}
}
function Nav()
{
	global $pre_approval_field, $pre_approval_fail_condition, $pre_approval_pass_condition;
	global $i1, $i2, $s1, $s2, $sp, $g1, $g2, $r1, $r2, $p1, $p2, $pa1, $pa2;
	//Define queries
	$qb = "Select crmid
				FROM vtiger_crmentity, vtiger_contactaddress, vtiger_contactscf
				WHERE crmid = vtiger_contactaddress.contactaddressid
				AND crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				";
	$sv = $qb."AND mailingstate LIKE '%vic%' ";
	$sq = $qb."AND mailingstate NOT LIKE '%vic%' ";
	
	//Approved?
	LI(RR($sv.$pa1), RR($sv.$pa2), RR($sq.$pa1), RR($sq.$pa2), "Pre-approval", "pa");
	
	//Installed?
	LI(RR($sv.$i1), RR($sv.$i2), RR($sq.$i1), RR($sq.$i2), "Installation", 'i');
	
	
	//Paperwork
	LI(RR($sv.$i1.$p1), RR($sv.$i1.$p2), RR($sq.$i1.$p1), RR($sq.$i1.$p2), "Paperwork", 'p');
	
	//Applying for STCs	
	LI(RR($sv.$sp.$i1.$s1), RR($sv.$sp.$i1.$s2), RR($sq.$sp.$i1.$s1), RR($sq.$sp.$i1.$s2), "Applying for STCs", 's');
	
	//STC Grant
	LI(RR($sv.$s1.$g1), RR($sv.$s1.$g2),RR($sq.$s1.$g1), RR($sq.$s1.$g2), "STCs Grant", 'g');
	
	//Send Docs to Retailers
	LI(RR($sv.$i1.$r1), RR($sv.$i1.$r2), RR($sq.$i1.$r1), RR($sq.$i1.$r2), "Docs to Retailers", 'r');
}

function LI($n1, $n2, $n3, $n4, $h, $t)
{
	$w = 100;
	$l1 = floor($w*$n1/($n1+$n2));
	$l2 = floor($w*$n2/($n1+$n2));
	$l3 = floor($w*$n3/($n3+$n4));
	$l4 = floor($w*$n4/($n3+$n4));
	echo "
	<a class='nav_a' href='index.php?module=Dashboard&action=index&parenttab=Analytics&t=$t'>
	<div class='nav_li_div'>
		<h6>$h</h6>
		<table>
			<tr>
				<td>VIC</td>
				<td>
					<p><span style='display: inline-block; width:".$l1."px' class='green'></span><span class='number'>$n1</span></p>
					<p><span style='display: inline-block; width:".$l2."px' class='orange'></span><span class='number'>$n2</span></p>
				</td>
			</tr
			<tr>
				<td>QLD</td>
				<td>
					<p><span style='display: inline-block; width:".$l3."px' class='green'></span><span class='number'>$n3</span></p>
					<p><span style='display: inline-block; width:".$l4."px' class='orange'></span><span class='number'>$n4</span></p>
				</td>
			</tr>
		</table>
	</div>
	</a>
	";
}
function RR($query)
{
	return mysql_num_rows(mysql_query($query));
}

?>