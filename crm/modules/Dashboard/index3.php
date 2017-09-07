<?php 
include_once("nav.php");
show_nav("Leads Summary");
?>
<div id="report_wrapper">


<!--<del style="color: #F46B25; font-weight: bold;">Statistics below does not include the contacts without appointment date.</del>-->
<?php
//This is a customised Dashboard by Yi
//2011-12-18
require_once('modules/Dashboard/custom_files/GoogleChart.php');
require_once('modules/Dashboard/custom_files/methods.php');

showcontent();

function showcontent()
{
	//Variables
	$sales_perf = array();
	$sales_perf2 = array();
	$sales_perf3 = array();
	
	//Read from database 	
	$link = mysql_connect('localhost:3306','root','johnamy');
	if(!$link)
	{
		die('Could not connect:'.mysql_error());
	}
	else
	{
		mysql_select_db("goforsolar_crm");
		mysql_query("set names utf8") ; 
		
		
		//------------------------------------------Read Sales Progress Array------------------------------------------------//
		$statusrows = array();
		$whereclause = "SELECT cf_612 FROM vtiger_cf_612";
		$result = mysql_query($whereclause);
		if($result)
		{
			while($row = mysql_fetch_array($result, MYSQL_NUM))
			{
				array_push($statusrows, $row[0]);
				//echo "<p>".$row[0]."</p>";
			}
		} 
		
		//------------------------------------------Read status and process----------------------------------------------------//
		
		//1.1. This month
		$rows = array();
		//Deleted = 0: Not deleted
		$month_start = date("Y-m-")."01";
		//$month_end = date("Y-m-d");
		$month_end = date("Y-m-",strtotime("next month"))."01";
		$whereclause0 = "SELECT crmid, vtiger_contactsubdetails.leadsource, cf_612, leadsourceid FROM vtiger_leadsource, vtiger_contactsubdetails, vtiger_contactscf, vtiger_crmentity WHERE vtiger_contactsubdetails.leadsource = vtiger_leadsource.leadsource AND vtiger_crmentity.crmid = vtiger_contactscf.contactid AND vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid AND vtiger_crmentity.deleted = 0 AND cf_638 >= '$month_start 00:00:00' AND cf_638 <'$month_end 00:00:00'";
		
		//echo $whereclause0;
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
 			
			//Deal with no lead sour problem
			/* if($row[1] == "")
			{
				if(isset($sales_perf["--None--"]))
					$sales_perf["--None--"]->add($row);
				else
				{
					$sales_perf["--None--"] = new leadsource2();
					$sales_perf["--None--"]->initialise($statusrows);
					$sales_perf["--None--"]->add($row);
				}
			} */
			
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
		
		
		
		//1.2 Last month
		$rows = array();
		//Deleted = 0: Not deleted
		//$month_start = date("Y-m-", strtotime('last month'))."01";
		$month_start = date("Y-m-", strtotime('last month', strtotime(date("Y-m-")."01")))."01";
		$month_end = date("Y-m")."-01";
		$whereclause0 = "SELECT crmid, vtiger_contactsubdetails.leadsource, cf_612, leadsourceid FROM vtiger_leadsource, vtiger_contactsubdetails, vtiger_contactscf, vtiger_crmentity WHERE vtiger_contactsubdetails.leadsource = vtiger_leadsource.leadsource AND vtiger_crmentity.crmid = vtiger_contactscf.contactid AND vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid AND vtiger_crmentity.deleted = 0 AND cf_638 >= '$month_start 00:00:00' AND cf_638 <'$month_end 00:00:00'";
		
		//echo $whereclause0;
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
 			
			//Deal with no lead sour problem
			/* if($row[1] == "")
			{
				if(isset($sales_perf2["--None--"]))
					$sales_perf2["--None--"]->add($row);
				else
				{
					$sales_perf2["--None--"] = new leadsource2();
					$sales_perf2["--None--"]->initialise($statusrows);
					$sales_perf2["--None--"]->add($row);
				}
			} */
			
			if(isset($sales_perf2[$row[1]]))
			{
				$sales_perf2[$row[1]]->add($row);
			}
			else
			{
				$sales_perf2[$row[1]] = new leadsource2();
				$sales_perf2[$row[1]]->initialise($statusrows);
				$sales_perf2[$row[1]]->add($row);
			} 
		} 
		
		//1.3 Overall
		$rows = array();
		//Deleted = 0: Not deleted
		$whereclause0 = "SELECT crmid, vtiger_contactsubdetails.leadsource, cf_612, leadsourceid FROM vtiger_leadsource, vtiger_contactsubdetails, vtiger_contactscf, vtiger_crmentity WHERE vtiger_contactsubdetails.leadsource = vtiger_leadsource.leadsource AND vtiger_crmentity.crmid = vtiger_contactscf.contactid AND vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid AND vtiger_crmentity.deleted = 0";
		
		//echo $whereclause0;
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
 			
			//Deal with no lead sour problem
			/* if($row[1] == "")
			{
				if(isset($sales_perf3["--None--"]))
					$sales_perf3["--None--"]->add($row);
				else
				{
					$sales_perf3["--None--"] = new leadsource2();
					$sales_perf3["--None--"]->initialise($statusrows);
					$sales_perf3["--None--"]->add($row);
				}
			} */
			
			if(isset($sales_perf3[$row[1]]))
			{
				$sales_perf3[$row[1]]->add($row);
			}
			else
			{
				$sales_perf3[$row[1]] = new leadsource2();
				$sales_perf3[$row[1]]->initialise($statusrows);
				$sales_perf3[$row[1]]->add($row);
			} 
		} 
		
		//1.4 Errors to be corrected
		$error_leads = array();
		$query = "SELECT crmid, contact_no, firstname, lastname, cf_638, cf_612, user_name
						FROM vtiger_crmentity, vtiger_contactsubdetails, vtiger_contactdetails, vtiger_contactscf, vtiger_users
						WHERE vtiger_crmentity.crmid = vtiger_contactdetails.contactid
						AND vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
						AND vtiger_crmentity.crmid = vtiger_contactscf.contactid
						AND vtiger_crmentity.smownerid = vtiger_users.id
						AND vtiger_crmentity.deleted = 0
						AND vtiger_contactsubdetails.leadsource LIKE ''
						ORDER BY user_name, cf_612 ASC
						";
		$result = mysql_query($query);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			array_push($error_leads, $row);
		}
		
	} 
	
	//------------------------------------------------Display the table-----------------------------------------------------//
	//1.1 This Month
	echo "<h3>".date('F')." Leads Summary</h3>";
	display_summary_table($statusrows, $sales_perf);
	if(count($sales_perf) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads=all_sources&date=this_month">Download Data of Current Month</a></p>';
	//1.2 Last Month
	echo "<h3>".$month_start = date("F", strtotime('last month', strtotime(date("Y-m-")."01")))." Leads Summary</h3>";
	display_summary_table($statusrows, $sales_perf2);
	if(count($sales_perf2) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads=all_sources&date=last_month">Download Data of Last Month</a></p>';
	//1.3 Overall	
	echo "<h3>Overall Leads Summary</h3>";
	display_summary_table($statusrows, $sales_perf3);
	if(count($sales_perf3) != 0)
		echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads=all_sources&date=all">Download All Data</a></p>';
	//1.4 Errors
	echo "<h3>Contacts without lead source</h3>";
	if(count($error_leads) > 0)
		echo "<p>Empty lead source causes the inconsistency between the tables on this page and the tables on <a href=\"index.php?module=Dashboard&action=index&parenttab=Analytics\">Sales Summary page</a></p>
		<p>They also make the downloaded data slightly different the statistics above.</p><p>Currently there are <strong>".count($error_leads)."</strong> contacts with no lead source, listed below.</p>";
	else
		echo "<p>Congratulations! All the contacts have lead source.</p>";
	display_error_table($error_leads);
}

?>


<p>Note: Contacts without appointment dates will not appear in the statistics of recent months, but will be in overall report.</p>
</div>












<?php
function display_error_table($errors)
{
	echo "<div><table class='toggle summary_table'><tr class='first_row'><th>CON ID</th><th>First Name</th><th>Last Name</th><th>Appointment Date</th><th>Sales Progress</th><th>Assigned to</th></tr>";
	foreach($errors as $rec)
	{
				echo "<tr>
							<td><a href='index.php?module=Contacts&parenttab=Sales&action=DetailView&record=$rec[0]' target='_blank'>$rec[1]</a></td>
							<td>$rec[2]</td>
							<td>$rec[3]</td>
							<td>$rec[4]</td>
							<td>$rec[5]</td>
							<td>$rec[6]</td>
						</tr>";
	}
	echo "</table></div>";
}
function display_summary_table($statusrows, $sales_perf)
{
	echo "<table class=summary_table><tr class='first_row'><th>Lead Source / Progress</th>";
	$temp = "";
	foreach($statusrows as $status)
		$temp .= "<th>".$status."</th>";
	echo $temp;	
	echo "<th>Total</th><th>Win Rate</th><th>View Detail</th></tr>";
	
	foreach($sales_perf as $lead_source)
	{
		$lead_source->calculate_display();
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
	
	//echo "<tr class='first_row'><th>Lead Source / Progress</th>$temp<th>Total</th><th>Win Rate</th><th>View Detail</th></tr>";
	echo "</table>";
	
}
class leadsource2
{
	var $name;
	var $statuslist = null;
	var $total = 0;
	var $win_percentage = 0;
	var $win = 0;
	var $source_id;
	
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
		$this->source_id = $lead[3];
		
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
	
	function calculate_display()
	{
		//Calculate
		if($this->total != 0)
			$this->win_percentage = number_format(($this->win*100/$this->total), 2);
		
		//Display
		$display_str = "<tr><td>$this->name</td>";
		foreach($this->statuslist as $key=>$status_count)
		{
			$display_str .= "<td>".$status_count."</td>";
		}
		$display_str .= "<td>".$this->total."</td>";
		$display_str .= "<td>".$this->win_percentage."%</td>";
		$display_str .= '<td><a href="index.php?module=Dashboard&action=index4&parenttab=Analytics&source_id='.$this->source_id.'">Detail</a></td></tr>';
		echo $display_str;
	}
}
?>