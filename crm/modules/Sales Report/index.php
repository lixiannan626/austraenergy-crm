<?php
/*
 *	
 *This script shows the sales their performance
 *Let Sales know by updating C:\xampp\htdocs\crm\modules\Users\Login.php
 *
 */
error_reporting(1);
include_once('language/en_us.lang.php');

$condition = "";
$condition = "AND smownerid = $current_user->id";
//1. Get all the stats
$query = "SELECT cf_612, count(cf_612)
				FROM vtiger_crmentity, vtiger_contactscf
				WHERE crmid = contactid
				$condition
				AND deleted = 0
				GROUP BY cf_612
				ORDER BY cf_612 DESC
				";
$result = mysql_query($query);
$stats = array();
if($result)
{
	while($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		if($row[0] != "")
			$stats[$row[0]] = $row[1];
		else
		{
			if(isset($stats["Lead"]))
				$stats["Lead"] += $row[1];
			else
				$stats["Lead"] = $row[1];
		}
	}
	
	ksort($stats);
	//stats_to_table($stats, array("Status", "Count"));
}

//2. Leads
$query = "SELECT crmid, contact_no, concat(firstname, ' ', lastname), concat(mailingstreet, ' ', mailingcity, ' ', mailingstate, ' ', mailingzip) as address, concat(cf_638, ' ', cf_639) as app, cf_612
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = contactaddressid
				AND deleted = 0
				$condition
				AND (cf_612 LIKE 'Lead' OR cf_612 LIKE '')
				ORDER BY cf_638 DESC
				";
$leads = array();
$leads = query_to_table($query);

//3. Sales
$query = "SELECT count(CASE WHEN cf_646 = 1 AND cf_619 LIKE 'Yes' THEN 1 ELSE NULL END) as installed_paid, count(CASE WHEN cf_646 = 1 AND cf_619 LIKE 'No' OR cf_619 LIKE '' THEN 1 ELSE NULL END) as installed_not_piad, count(CASE WHEN cf_646 = 0 THEN 1 ELSE NULL END) as not_installed
				FROM vtiger_contactscf, vtiger_crmentity
				WHERE contactid = crmid
				AND deleted = 0
				$condition
				AND cf_612 LIKE 'Sale'
				"; 
$sales_sum = query_to_row($query);

$query = "SELECT crmid, contact_no, concat(firstname, ' ', lastname), concat(mailingstreet, ' ', mailingcity, ' ', mailingstate, ' ', mailingzip) as address, concat(cf_638, ' ', cf_639) as app, cf_612, cf_625 as install_date, cf_646 as installed, cf_619 as fully_paid
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = contactaddressid
				AND deleted = 0
				$condition
				AND cf_612 LIKE 'Sale'
				ORDER BY cf_646 DESC, cf_619 DESC,  cf_625 DESC
				";
$sales = array();
$sales = query_to_table($query);

//4. Others
$query = "SELECT count(CASE WHEN cf_612 LIKE 'Thinking' THEN 1 END) as thinking, count(CASE WHEN cf_612 LIKE 'Not Interested' THEN 1 END) as na, count(CASE WHEN cf_612 LIKE 'Not Available' THEN 1 END) as na, count(CASE WHEN cf_612 LIKE 'Close Lose' THEN 1 END) as cl 
				FROM vtiger_contactscf, vtiger_crmentity
				WHERE contactid = crmid
				AND deleted = 0
				$condition
				";
$other_sum = query_to_row($query);

$query = "SELECT crmid, contact_no, concat(firstname, ' ', lastname), concat(mailingstreet, ' ', mailingcity, ' ', mailingstate, ' ', mailingzip) as address, concat(cf_638, ' ', cf_639) as app, cf_612
				FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf
				WHERE crmid = vtiger_contactdetails.contactid
				AND crmid = vtiger_contactscf.contactid
				AND crmid = contactaddressid
				AND deleted = 0
				$condition
				AND (cf_612 NOT LIKE 'Lead' AND cf_612 NOT LIKE '' AND cf_612 NOT LIKE 'Sale')
				ORDER BY cf_612 DESC, cf_638 DESC
				";
$others = array();
$others = query_to_table($query);
?>
<div id="sales_report">
<style>
	div.report_div{margin: 10px 5%; border: 1px solid #046A3A; font-size: 1.2em;}
	div.report_div h2{background: #046A3A; padding: 5px; color: white; margin:0; }
	div.report_div table{border-collapse: collapse; margin: 10px;}
	div.report_div table th, div.report_div td{border: 1px solid #046A3A; padding: 4px 5px;}
	.c1{background: #c2d69a;} /*Olive*/
	.c2{background: #b2a1c7;} /*Purple*/
	.c3{background: #93cddd;} /*Sea Blue*/
	.c4{background: #fac090;} /*Orange*/
	.c5{background: #95b3d7;} /*Blue*/
	.c6{background: #948b54;} /*Brown*/
	.c7{background: #d99795;} /*Red*/
	.explanation{margin: 5px 10px;}
</style>
<div class="report_div">
	<h2>Summary</h2>
	<?php stats_to_table($stats, array("status", "count", "%"));	?>
</div>

<div class="report_div">
	<h2>Leads</h2>
	<p class="explanation">This section shows all the contacts marked as "Lead", please update them if necessary.</p>
	<?php sqltotable($leads, array("#", "contact id", "name", "address", "appointment", "status"));	?>
</div>

<div class="report_div">
	<h2>Sales</h2>
	<p class="explanation">This section shows the all the sold systems, their installation status (1 means installation confirmed, 0 means not) and payment.</p>
	<?php 
		row_to_table($sales_sum, array("status", "count"), array("installed & fully paid", "installed & not fully paid", "not installed yet"));
		sqltotable_sale($sales, array("#", "contact id", "name", "address", "appointment", "status", "install date", "installed", "fully paid"));
	?>
</div>

<div class="report_div">
	<h2>Other (Not interested, etc.)</h2>
	<p class="explanation">This section shows all the contacts marked other than "Lead" and "Sale", please update them if necessary.</p>
	<?php 
		row_to_table($other_sum, array("status", "count"), array("Thinking", "Not Interested", "Not Available", "Close Lose"));
		sqltotable_other($others, array("#", "contact id", "name", "address", "appointment", "status"));	 	
	?>
</div>

</div>

<?php
//------------------------------------------------------------------------------------------------------Methods----------------------------------------------------------------------------
function query_to_table($query)
{
	$result = mysql_query($query);
	$arr = array();
	if($result)
		while($row = mysql_fetch_array($result, MYSQL_NUM))
			array_push($arr, $row);
	return $arr;
}

function query_to_row($query)
{
	$row = "";
	$result = mysql_query($query);
	if($result)
		$row = mysql_fetch_array($result, MYSQL_NUM);
	return $row;
}
function sqltotable($arr, $ths)
{
	$out = "<table><tr>";
	$count = 1;
	foreach($ths as $th)
		$out .= "<th>$th</th>";
	$out .= "<th>Action</th></tr>";
	foreach($arr as $row)
	{
		$out .= "<tr><td>$count</td>";
		for($i = 1; $i < count($row); $i++)
			$out .= "<td>$row[$i]</td>";
		$out .= "<td><a target='_blank' href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$row[0]."'>View</a></td>";
		$out .= "</tr>";
		$count++;
	}
	$out .= "</table>";
	echo $out;
}
function sqltotable_sale($arr, $ths)
{
	$out = "<table><tr>";
	$count = 1;
	foreach($ths as $th)
		$out .= "<th>$th</th>";
	$out .= "<th>Action</th></tr>";
	foreach($arr as $row)
	{
		$style = " class='c3'";
		if($row[7] == 1 && $row[8] == "Yes")
			$style = " class='c1'";
		else if($row[7] == 1)
			$style = " class='c2'";
		$out .= "<tr $style><td>$count</td>";
		for($i = 1; $i < count($row); $i++)
			$out .= "<td>$row[$i]</td>";
		$out .= "<td><a target='_blank' href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$row[0]."'>View</a></td>";
		$out .= "</tr>";
		$count++;
	}
	$out .= "</table>";
	echo $out;
}
function sqltotable_other($arr, $ths)
{
	$out = "<table><tr>";
	$count = 1;
	foreach($ths as $th)
		$out .= "<th>$th</th>";
	$out .= "<th>Action</th></tr>";
	foreach($arr as $row)
	{
		$style = " class='c4'";
		if($row[5] == "Thinking")
			$style = " class='c1'";
		else if($row[5] == "Not Interested")
			$style = " class='c2'";
		else if($row[5] == "Not Available")
			$style = " class='c3'";
			
		$out .= "<tr $style><td>$count</td>";
		for($i = 1; $i < count($row); $i++)
			$out .= "<td>$row[$i]</td>";
		$out .= "<td><a target='_blank' href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$row[0]."'>View</a></td>";
		$out .= "</tr>";
		$count++;
	}
	$out .= "</table>";
	echo $out;
}
function stats_to_table($arr, $ths)
{
	$sum = array_sum($arr);
	$out = "<table id='stats_table'><tr>";
	foreach($ths as $th)
		$out .= "<th>$th</th>";
	$out .= "</tr>";
	foreach($arr as $key=>$val)
	{
		$width = number_format($val/$sum*600);
			$out .= "<tr>
							<td>$key</td>
							<td>$val</td>
							<td>".number_format($val*100/$sum,1)."%</td>
							<td style='border:none;'><span style='background: #06c;display: inline-block; height: 18px; width: ".$width."px'></span></td>
						</tr>";
	}
	$out .= "<tr><th>Total</th><th>$sum</th></tr></table>";
	echo $out;
}
function row_to_table($arr, $ths, $row_name)
{
	//sort($arr);
	$sum = array_sum($arr);
	$out = "<table id='sales_detail_table'><tr>";
 	foreach($ths as $th)
		$out .= "<th>$th</th>";
	$out .= "</tr>";
	for($i = 0; $i < count($arr); $i++)
	{
		if($arr[$i] != 0)
		{
			$width = number_format($arr[$i]/$sum*400);
			$out .= "<tr>
							<td>".$row_name[$i]."</td>
							<td>".$arr[$i]."</td>
							<td style='border:none;'><span style='background: #06c; display: inline-block; height:18px; width: ".$width."px'></span></td>
						</tr>
			";
		}
	}
	$out .= "<tr><th>Total</th><th>$sum</th></tr></table>";
	echo $out; 
}
?>