<?php 
include_once("nav.php");
show_nav("Sales Summary");
?>

<div id="report_wrapper">

<?php
//This is a customised Dashboard by Yi
//2011-12-18

//2012-06-17
//Vishal needs to display month and more month is expected.

require_once('modules/Dashboard/custom_files/GoogleChart.php');
require_once('modules/Dashboard/custom_files/methods.php');


if(isset($_GET['month']) == false)
	showcontent();
else
{
	$mm = $_GET['month'];
	
	$statusrows = array();
		$whereclause = "SELECT cf_612 FROM vtiger_cf_612";
		$result = mysql_query($whereclause);
		if($result)
		{
			while($row = mysql_fetch_array($result, MYSQL_NUM))
				array_push($statusrows, $row[0]);
		} 
		
	$sales_perf = GetStat($mm, $statusrows);
	
	ShowDropdownMonths();
	
	$mf = date("F", strtotime($mm."-01"));
	echo "<h3>$mm Sales Summary</h3>";
	summary_table($statusrows, $sales_perf, $mf);
	
	
}



function showcontent()
{
	//Variables
	$sales_perf = array();
	$sales_perf2 = array();
	$sales_perf3= array();
	
	//Read from database 	
		
		//------------------------------------------Read Sales Progress Array------------------------------------------------//
		$statusrows = array();
		$whereclause = "SELECT cf_612 FROM vtiger_cf_612";
		$result = mysql_query($whereclause);
		if($result)
		{
			while($row = mysql_fetch_array($result, MYSQL_NUM))
				array_push($statusrows, $row[0]);
		} 
		
		//------------------------------------------Read status and process----------------------------------------------------//
		$this_month = date("Y-m-")."01";
		//1.1 This Month's 
		$sales_perf = GetStat(date("Y-m"), $statusrows);
		//1.2 Last Month's 
		$sales_perf2 = GetStat(date("Y-m", strtotime("last month", strtotime($this_month))), $statusrows);
		
		//1.3 Overall
		$sales_perf3 = GetStat("all", $statusrows);
	
	ShowDropdownMonths();
	
	
	$m1 = date('F');
	$m2 = date("F", strtotime('last month', strtotime(date("Y-m-")."01")));
	//------------------------------------------------2.1 Display the table-----------------------------------------------------//
	echo "<h3>".$m1." Sales Summary</h3>";
	summary_table($statusrows, $sales_perf, $m1);
	if(count($sales_perf) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales=all_sales&date=this_month">Download Data of Current Month</a></p>';
	//------------------------------------------------2.2 Display the table-----------------------------------------------------//
	echo "<h3>".$m2." Sales Summary</h3>";
	summary_table($statusrows, $sales_perf2, $m2);
	if(count($sales_perf2) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales=all_sales&date=last_month">Download Data of Last Month</a></p>';
	//------------------------------------------------2.3 Display the table-----------------------------------------------------//
	echo "<h3>Overall Sales Summary</h3>";
	summary_table($statusrows, $sales_perf3, "All");
	if(count($sales_perf3) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales=all_sales&date=all">Download All Data</a></p>';
}

?>
<p>Note: Contacts without appointment dates will not appear in the statistics of recent months, but will be in overall report. </p>
<p>Contacts with empty lead status is considered "Lead".</p>
</div>


















<?php



function ShowDropdownMonths()
{

	//-----------------------------------------------Show Dropdown List Of Other Months-----------------------------------------//
	$ms = array();
	$q = "SELECT concat_ws('-', YEAR(cf_638), MONTH(cf_638)) as 'ym'
				FROM vtiger_contactscf 
				WHERE cf_638 IS NOT NULL 
				AND cf_638 NOT LIKE '' 
				AND cf_638 NOT LIKE '0000-00-00'
				GROUP BY concat_ws('-', YEAR(cf_638), MONTH(cf_638))
				ORDER BY cf_638 DESC";
	$result = mysql_query($q);
	while($r = mysql_fetch_array($result, MYSQL_NUM))
		array_push($ms, date("Y-m", strtotime($r[0]."-01")));
	
	echo "
		<form action='index.php?module=Dashboard&action=index&parenttab=Analytics' method='get' id='month_select'>
		<p>Select a month: 
			<select name='month'>
	";
	foreach($ms as $m)
		echo "<option value='$m'>$m</option>";
	echo "
			</select>
			<input type='submit' name='month_submit' />
			
			<input type='hidden' name='module' value='Dashboard' />
			<input type='hidden' name='action' value='index5' />
			<input type='hidden' name='parenttab' value='Analytics' />
		</p>
		</form>";
	 
}





function GetStat($month, $statusrows)
{
	$this_month = date("Y-m");
	
	//Is it overall
	if($month == "all")
	{
		$term = "";
	}
	//Generic
	else
	{
		$term = "AND cf_638 LIKE '$month%'";
	}
		$sales_perf = array();
		$rows = array();
		$query0 = "SELECT crmid, user_name, cf_612, smownerid FROM vtiger_crmentity, vtiger_contactscf, vtiger_users WHERE vtiger_crmentity.crmid = vtiger_contactscf.contactid AND vtiger_crmentity.smownerid = vtiger_users.id AND vtiger_crmentity.deleted = 0 $term";
		$query1 = "SELECT crmid, groupname, cf_612, smownerid FROM vtiger_crmentity, vtiger_contactscf, vtiger_groups WHERE vtiger_crmentity.crmid = vtiger_contactscf.contactid AND vtiger_crmentity.smownerid = vtiger_groups.groupid AND vtiger_crmentity.deleted = 0 $term";
		
		$result = mysql_query($query0);
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
 			
			if(isset($sales_perf[$row[1]]))
			{
				$sales_perf[$row[1]]->add($row);
			}
			else
			{
				$sales_perf[$row[1]] = new leadsource2();
				$sales_perf[$row[1]]->initialise($statusrows);
				$sales_perf[$row[1]]->add($row);
			} 
		} 
		$result = mysql_query($query1);
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
 			
			if(isset($sales_perf[$row[1]]))
			{
				$sales_perf[$row[1]]->add($row);
			}
			else
			{
				$sales_perf[$row[1]] = new leadsource2();
				$sales_perf[$row[1]]->initialise($statusrows);
				$sales_perf[$row[1]]->add($row);
			} 
		} 
		
	return $sales_perf;
}
















//--------------------------------Methods & Classes-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//Display the table
function summary_table($statusrows, $sales_perf, $month)
{
	echo "<table class=summary_table><tr class='first_row'><th>Month</th><th>Name</th>";
	$temp = "";
	foreach($statusrows as $status)
		$temp .= "<th>".$status."</th>";
	echo $temp;	
	echo "<th>Total</th><th>Win Rate</th><th>View</th></tr>";
	
	foreach($sales_perf as $lead_source)
	{
		$lead_source->calculate_display($month);
	}
	
	echo "<tr class='last_row'><th></th><th>Total</th>";
	foreach($statusrows as $status)
	{
		$no = 0;
		foreach($sales_perf as $lead_source)
		{
			$no += $lead_source->statuslist[$status];
		}
		echo "<th>$no</th>";
	}
	
	$no1 = 0;
	$no2 = 0;
	foreach($sales_perf as $lead_source)
	{
		$no1 += $lead_source->total;
		$no2 += $lead_source->win;
	}
	echo "<th>$no1</th>";
	$win_rate = 0;
	if($no1 != 0)
		$win_rate = number_format($no2*100/$no1, 2);
	echo "<th>".$win_rate."%</th><th></th></tr>";
	
	//echo "<tr class='first_row'><th>Name / Progress</th>$temp<th>Total</th><th>Win Rate</th><th>View Detail</th></tr>";
	echo "</table>";
}

//Display the table DEBUGGING
function summary_table2($statusrows, $sales_perf)
{
	echo "<table class=summary_table><tr class='first_row'><th>Name / Progress</th>";
	foreach($statusrows as $status)
	{
		echo "<th>".$status."</th>";
	}
	echo "<th>Total</th><th>Win Rate</th><th>View Detail</th></tr>";
	
	foreach($sales_perf as $lead_source)
	{
		$lead_source->calculate_display2();
	}
	
	echo "<tr class='last_row'><th>Total</th>";
	foreach($statusrows as $status)
	{
		$no = 0;
		foreach($sales_perf as $lead_source)
		{
			$no += $lead_source->statuslist[$status];
		}
		echo "<th>$no</th>";
	}
	
	$no1 = 0;
	$no2 = 0;
	foreach($sales_perf as $lead_source)
	{
		$no1 += $lead_source->total;
		$no2 += $lead_source->win;
	}
	echo "<th>$no1</th>";
	$win_rate = 0;
	if($no1 != 0)
		$win_rate = number_format($no2*100/$no1, 2);
	echo "<th>".$win_rate."%</th><th></th></tr>";
	
	echo "</table>";
}

class leadsource2
{
	var $name;
	var $statuslist = null;
	var $total = 0;
	var $win_percentage = 0;
	var $win = 0;
	var $sales_id;
	
	function initialise($leadstatus)
	{
		for($i = 0; $i < count($leadstatus); $i++)
		{
			$this->statuslist[$leadstatus[$i]] = 0;
		}
	}
	function add($lead)
	{
		//$lead[1]: name
		//$lead[2]: status
		$this->name = $lead[1];
		$this->sales_id = $lead[3];
		
		/*******   To tackle the no-lead-source issue       *********/
		if($lead[2] == '')
			$lead[2] = 'Lead';
		/*******   To tackle the no-lead-source issue       *********/	
			
		$this->statuslist[$lead[2]]++;
		$this->total++;
		if(strpos("|".$lead[2],"Sale") > 0)
		{
			//echo $lead[2]."<br>";
			$this->win++;
		}
	}
	
	function calculate_display($month)
	{
		//Calculate
		if($this->total != 0)
			$this->win_percentage = number_format(($this->win*100/$this->total), 2);
		
		//Display
		$display_str = "<tr><td>$month</td><td>$this->name</td>";
		foreach($this->statuslist as $key=>$status_count)
		{
			$display_str .= "<td>".$status_count."</td>";
		}
		$display_str .= "<td>".$this->total."</td>";
		$display_str .= "<td>".$this->win_percentage."%</td>";
		$display_str .= '<td><a href="index.php?module=Dashboard&action=index2&parenttab=Analytics&sales_id='.$this->sales_id."\">Detail</a></td></tr>";
		echo $display_str;
	}
	
	function calculate_display2()
	{
		//Calculate
		if($this->total != 0)
			$this->win_percentage = number_format(($this->win*100/$this->total), 1);
		
		//Display
		$display_str = "<tr><td>$this->name</td>";
		foreach($this->statuslist as $key=>$status_count)
		{
			$display_str .= "<td>".$status_count."</td>";
		}
		$display_str .= "<td>".$this->total."</td>";
		$display_str .= "<td>".$this->win_percentage."%</td>";
		$display_str .= '<td><a href="index.php?module=Dashboard&action=index2&parenttab=Analytics&sales_id='.$this->sales_id."\">Detail</a></td></tr>";
		echo $display_str;
	}
}
?>