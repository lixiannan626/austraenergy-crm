<?php 
include_once("nav.php");
show_nav("Leads Detail");
?>
<div id="report_wrapper">
	<p style="color: #F46B25; font-weight: bold;">Contacts without appointment dates will not appear in the statistics of recent months, but will be in overall report.</p>
	<div class="admin-input">
		<form action="index.php" method="get">
			<input type="hidden" name="module" value="Dashboard" />
			<input type="hidden" name="action" value="index4" />
			<input type="hidden" name="parenttab" value="Analytics" />

			<label>Choose a lead source: </label>
			<select name="source_id">
			<?php
			/*
			 *Lead Source Report
			 *This report evaluates each lead source's performance
			 *
			 */

			 //GENERATE LEAD SOURCE LIST
			 $result = mysql_query("SELECT leadsourceid, leadsource FROM vtiger_leadsource");
			if($result)
			{
				while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
					echo "<option value='$row[0]'>$row[1]</option>\n";
			}

			?>
			</select>
			<input type="submit" value="Get the report" />
		</form>
	</div>
<?php
if(isset($_GET['source_id']))
{
		//1.1 This Month
		$rows = array();
		$source_id = $_GET['source_id'];
		
		$lead_source_name = "";
		$clause = "SELECT leadsource FROM vtiger_leadsource WHERE leadsourceid = $source_id";
		$result = mysql_query($clause);
		$result_row = mysql_fetch_array($result, MYSQL_NUM);
		$lead_source_name = $result_row[0];
		
		$month_start = date("Y-m-")."01";
		//$month_end = date("Y-m-d");
		$month_end = date("Y-m-",strtotime("next month"))."01";
		$query = "SELECT crmid, vtiger_leadsource.leadsource, contact_no, firstname, lastname, cf_612, smownerid, vtiger_crmentity.description, all_comments
									FROM vtiger_crmentity
									INNER JOIN vtiger_contactdetails
									ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
									INNER JOIN vtiger_contactscf
									ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
									INNER JOIN vtiger_contactsubdetails
									ON vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
									INNER JOIN vtiger_leadsource
									ON vtiger_contactsubdetails.leadsource = vtiger_leadsource.leadsource
									AND vtiger_leadsource.leadsourceid = $source_id
									AND vtiger_crmentity.deleted = 0 
									AND vtiger_contactscf.cf_638 >= '$month_start 00:00:00' AND vtiger_contactscf.cf_638 <'$month_end 00:00:00'
									LEFT JOIN
									(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'),  ' [', user_name, '] ', commentcontent, '<br><br>') SEPARATOR '') AS all_comments
									FROM vtiger_crmentity, vtiger_modcomments, vtiger_users
									WHERE modcommentsid = crmid
									AND smownerid = vtiger_users.id
									GROUP BY related_to) AS temp_table
									ON temp_table.related_to = vtiger_crmentity.crmid";
	$result = mysql_query($query);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
			$lead_source_name = $row[1];
		} 
		
		//1.2 Last Month
		$rows2 = array();
		$source_id = $_GET['source_id'];

		//$month_start = date("Y-m-", strtotime('last month'))."01";
		$month_start = date("Y-m-", strtotime('last month', strtotime(date("Y-m-")."01")))."01";
		$month_end = date("Y-m-")."01";
		$query = "SELECT crmid, vtiger_leadsource.leadsource, contact_no, firstname, lastname, cf_612, smownerid, vtiger_crmentity.description, all_comments
									FROM vtiger_crmentity
									INNER JOIN vtiger_contactdetails
									ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
									INNER JOIN vtiger_contactscf
									ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
									INNER JOIN vtiger_contactsubdetails
									ON vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
									INNER JOIN vtiger_leadsource
									ON vtiger_contactsubdetails.leadsource = vtiger_leadsource.leadsource
									AND vtiger_leadsource.leadsourceid = $source_id
									AND vtiger_crmentity.deleted = 0 
									AND vtiger_contactscf.cf_638 >= '$month_start 00:00:00' AND vtiger_contactscf.cf_638 <'$month_end 00:00:00'
									LEFT JOIN
									(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'), ' [', user_name, '] ', commentcontent, '<br><br>') SEPARATOR '') AS all_comments
									FROM vtiger_crmentity, vtiger_modcomments, vtiger_users
									WHERE modcommentsid = crmid
									AND smownerid = vtiger_users.id
									GROUP BY related_to) AS temp_table
									ON temp_table.related_to = vtiger_crmentity.crmid";
	$result = mysql_query($query);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows2[count($rows2)] = $row;
			$lead_source_name = $row[1];
		} 
		
		
		//1.3 Overall
		
		
		//-------------Display Tables-------------------//
		//1.1 Current Month
		if(count($rows) > 0)
		{
			echo "<h3>$lead_source_name ".date('F')." Report</h3>";
			echo "<table class='detail_table'>
						<tr class='first_row'><th>Con No</th><th>First Name</th><th>Last Name</th><th>Status</th><th>Description</th><th>All Comments</th></tr>";
			foreach($rows as $rec)
			{
				echo "<tr>
							<td><a href='index.php?module=Contacts&parenttab=Sales&action=DetailView&record=$rec[0]' target='_blank'>$rec[2]</a></td>
							<td>$rec[3]</td>
							<td>$rec[4]</td>
							<td>$rec[5]</td>
							<td class='desc'>$rec[7]</td>
							<td class='all_comments'>$rec[8]</td>
						</tr>";
			}
			echo "</table>";
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads='.$lead_source_name.'&date=this_month">Download Data of Current Month</a></p>';
		}
		else
		{
			echo '<p>No data for '.$lead_source_name.' of current month</p>';
		}
		//1.2 Last Month
		if(count($rows2) > 0)
		{
			echo "<h3>$lead_source_name ".$month_start = date("F", strtotime('last month', strtotime(date("Y-m-")."01")))." Report</h3>";
			echo "<table class='detail_table'>
						<tr class='first_row'><th>Con No</th><th>First Name</th><th>Last Name</th><th>Status</th><th>Description</th><th>All Comments</th></tr>";
			foreach($rows2 as $rec)
			{
				echo "<tr>
							<td><a href='index.php?module=Contacts&parenttab=Sales&action=DetailView&record=$rec[0]' target='_blank'>$rec[2]</a></td>
							<td>$rec[3]</td>
							<td>$rec[4]</td>
							<td>$rec[5]</td>
							<td class='desc'>$rec[7]</td>
							<td class='all_comments'>$rec[8]</td>
						</tr>";
			}
			echo "</table>";
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads='.$lead_source_name.'&date=last_month">Download Data of Last Month</a></p>';
		}
		else
		{
			echo '<p>No data for '.$lead_source_name.' of last month</p>';
		}
		//1.3 Overall
		if(true)
		{
			echo "<h3>$lead_source_name Overall Report</h3>";
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadLeadsReport&leads='.$lead_source_name.'&date=all">Download all Data</a></p>';
		}
}


?>

</div>












<?php
//FUNCTIONS, HELPERS

function displayArrayn($array)
{
	echo '##### Array Start #########<br>';
	foreach($array as $elements)
	{
		foreach($elements as $element)
		{
			echo $element.'    ';
		}
		echo '<br>';
	}
	echo '##### Array End #########<br>';
}
?>