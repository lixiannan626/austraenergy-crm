<?php 
include_once("nav.php");
show_nav("Sales Detail");
?>
<div id="report_wrapper">
	<p style="color: #F46B25; font-weight: bold;">Contacts without appointment dates will not appear in the statistics of recent months, but will be in overall report.</p>
	<div class="admin-input">
		<form action="index.php" method="get">
			<input type="hidden" name="module" value="Dashboard" />
			<input type="hidden" name="action" value="index2" />
			<input type="hidden" name="parenttab" value="Analytics" />
			<label>Choose a sales: </label>
			<select name="sales_id">
			<?php
			//GENERATE SALES LIST
			$result = mysql_query("SELECT id, user_name FROM vtiger_users");
			if($result)
				while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
					echo "<option value='$row[0]'>$row[1]</option>\n";
			?>
			</select>
			<input type="submit" value="Get the report" />
		</form>
	</div>
<?php
//Query
if(isset($_GET['sales_id']))
{
		$rows = array();
		$rows2 = array();
		
		$sales_id = $_GET['sales_id'];
		$sales_name = "";
		$clause0 = "SELECT user_name FROM vtiger_users WHERE id = $sales_id";
		if(mysql_num_rows(mysql_query($clause0)) == 1)
			$result = mysql_fetch_array(mysql_query($clause0), MYSQL_NUM);
		else
			$result = mysql_fetch_array(mysql_query("SELECT groupname FROM vtiger_groups WHERE groupid = $sales_id"), MYSQL_NUM);
		$sales_name = $result[0];
		
		
		//1.1 This Month's report
		$month_start = date("Y-m-")."01";
		$month_end = date("Y-m-",strtotime("next month"))."01";
		$whereclause0 = "SELECT crmid, user_name, contact_no, firstname, lastname, cf_612, smownerid, vtiger_crmentity.description, all_comments
									FROM vtiger_crmentity
									INNER JOIN vtiger_contactdetails
									ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
									INNER JOIN vtiger_contactscf
									ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
									INNER JOIN vtiger_users
									ON vtiger_crmentity.smownerid = vtiger_users.id 
									AND vtiger_users.id = $sales_id
									AND vtiger_crmentity.deleted = 0 
									AND vtiger_contactscf.cf_638 >= '$month_start 00:00:00' AND vtiger_contactscf.cf_638 < '$month_end 00:00:00'
									LEFT JOIN
									(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'),  ' [', user_name, '] ', commentcontent, '<br><br>') SEPARATOR '') AS all_comments
									FROM vtiger_crmentity, vtiger_modcomments, vtiger_users
									WHERE modcommentsid = crmid
									AND smownerid = vtiger_users.id
									GROUP BY related_to) AS temp_table
									ON temp_table.related_to = vtiger_crmentity.crmid";
									
		
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows[count($rows)] = $row;
			$sales_name = $row[1];
		} 
		
		
		//1.2 Last Month's report
		//$month_start = date("Y-m-", strtotime('last month'))."01";
		$month_start = date("Y-m-", strtotime('last month', strtotime(date("Y-m-")."01")))."01";
		$month_end = date("Y-m")."-01";
		$whereclause0 = "SELECT crmid, user_name, contact_no, firstname, lastname, cf_612, smownerid, vtiger_crmentity.description, all_comments
									FROM vtiger_crmentity
									INNER JOIN vtiger_contactdetails
									ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
									INNER JOIN vtiger_contactscf
									ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
									INNER JOIN vtiger_users
									ON vtiger_crmentity.smownerid = vtiger_users.id 
									AND vtiger_users.id = $sales_id
									AND vtiger_crmentity.deleted = 0 
									AND vtiger_contactscf.cf_638 >= '$month_start 00:00:00' AND vtiger_contactscf.cf_638 < '$month_end 00:00:00'
									LEFT JOIN
									(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'), ' [', user_name, '] ', commentcontent, '<br><br>') SEPARATOR '') AS all_comments
									FROM vtiger_crmentity, vtiger_modcomments, vtiger_users
									WHERE modcommentsid = crmid
									AND smownerid = vtiger_users.id
									GROUP BY related_to) AS temp_table
									ON temp_table.related_to = vtiger_crmentity.crmid";
									
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$rows2[count($rows2)] = $row;
			$sales_name = $row[1];
		} 
		
		//----------------------Display Tables-------------------------------//
		//2.1 This Month's table
		if(count($rows) > 0)
		{
			echo "<h3>$sales_name ".date('F')." Report</h3>";
			echo "<table class='detail_table'>
						<tr class='first_row'><th>Con No</th><th>first name</th><th>last name</th><th>status</th><th>description</th><th>All Comments</th></tr>";
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
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales='.$sales_name.'&date=this_month">Download Data of Current Month</a></p>';
		}
		else
		{
			echo '<p>No data for '.$sales_name.' of current month.</p>';
		}
		//2.2 Last Month's table
		if(count($rows2) > 0)
		{
			echo "<h3>$sales_name ".date("F", strtotime('last month', strtotime(date("Y-m-")."01")))." Report</h3>";
			echo "<table class='detail_table'>
						<tr class='first_row'><th>Con No</th><th>first name</th><th>last name</th><th>status</th><th>description</th><th>All Comments</th></tr>";
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
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales='.$sales_name.'&date=last_month">Download Data of Last Month</a></p>';
		}
		else
		{
			echo '<p>No data for '.$sales_name.' of last month.</p>';
		}
		
		
		//2.3 Overall 
		if(true)
		{
			echo "<h3>$sales_name Overall Report</h3>";
			echo "<p>No detailed table is provided,  please download and view data in Excel";
			echo '<p class="download"><a href="index.php?module=Dashboard&action=downloadSalesReport&sales='.$sales_name.'&date=all">Download all Data</a></p>';
		}
		
		
}


?>

</div>
