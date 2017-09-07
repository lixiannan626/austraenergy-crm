<?php 
include_once("nav.php");
show_nav("Balance required");
?>
<div id="report_wrapper" class="balance_chasing">
<div id="explanation">
				<p>To appear below, it must:</p><ul>
						<li>be a sale</li>
						<li>installation confirmed</li>
						<li>full payment NOT confirmed</li>
					</ul>
	</div>
<?php
	$rows = array();
	$rows_v = array();
	$rows_q = array();
	$today = date("Y-m-d");
	//Con ID, Name, Address, Install Date, From Today, Sales
	//----------------------------------------------VIC---------------------------------
	$state_option = "AND (mailingstate LIKE '%vic%' OR mailingstate LIKE '%victoria%') ";
	$query = "
					SELECT crmid, contact_no, concat_ws(' ', firstname, lastname) as 'name', concat_ws(' ', mailingstreet, mailingcity, mailingstate, mailingzip) as 'address', cf_625, DATEDIFF('$today', cf_625), user_name
					FROM vtiger_crmentity, vtiger_contactaddress, vtiger_contactdetails, vtiger_contactscf, vtiger_users
					WHERE crmid = vtiger_contactaddress.contactaddressid
					AND crmid = vtiger_contactdetails.contactid
					AND crmid = vtiger_contactscf.contactid
					AND smownerid = vtiger_users.id
					AND vtiger_crmentity.deleted = 0
					AND cf_612 LIKE 'Sale'
					AND cf_646 = 1
					AND cf_625 <= '$today'
					AND (cf_619 LIKE 'NO' OR cf_619 LIKE '')
					$state_option
					ORDER BY cf_625 ASC
					";
	//cf_619: Full Payment Confirmed
	//cf_646: INSTALLED
	//cf_612: Sales Progress
	//cf_625: Install Date
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($rows_v, $row);
		
		
		
	//----------------------------------------------QLD---------------------------------
	$state_option = "AND (mailingstate LIKE '%qld%' OR mailingstate LIKE '%queensland%') ";
	$query = "
					SELECT crmid, contact_no, concat_ws(' ', firstname, lastname) as 'name', concat_ws(' ', mailingstreet, mailingcity, mailingstate, mailingzip) as 'address', cf_625, DATEDIFF('$today', cf_625), user_name
					FROM vtiger_crmentity, vtiger_contactaddress, vtiger_contactdetails, vtiger_contactscf, vtiger_users
					WHERE crmid = vtiger_contactaddress.contactaddressid
					AND crmid = vtiger_contactdetails.contactid
					AND crmid = vtiger_contactscf.contactid
					AND smownerid = vtiger_users.id
					AND vtiger_crmentity.deleted = 0
					AND cf_612 LIKE 'Sale'
					AND cf_646 = 1
					AND cf_625 <= '$today'
					AND (cf_619 LIKE 'NO' OR cf_619 LIKE '')
					$state_option
					ORDER BY cf_625 ASC
					";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($rows_q, $row);
		
		
	//Display
	//----------------------VIC---------------
	$c = 0;
	$ths = array("#","Con No", "Name", "Address", "Install Date", "From Today (days)", "Sale");
	$output = "<table id='chasing_table' class='detail_table'><tr class='first_row'>";
	foreach($ths as $th)
		$output .= "<th>$th</th>";
	$output .= "</tr>";
	
	foreach($rows_v as $row)
	{
		$c++;
		$output .= "<tr>
						<td>$c</td>
						<td><a href='index.php?module=Contacts&parenttab=Sales&action=DetailView&record=$row[0]' target='_blank'>$row[1]</a></td>
						<td>$row[2]</td>
						<td>$row[3]</td>
						<td>$row[4]</td>
						<td>$row[5]</td>
						<td>$row[6]</td>
					</tr>";
	}
	
	$output .= "</table>";
	
	echo "<p>There are a total of ".count($rows_v)." customers in VIC haven't paid their balance.</p>";
	echo $output;
	//----------------------QLD---------------
	$c = 0;
	$ths = array("#","Con No", "Name", "Address", "Install Date", "From Today (days)", "Sale");
	$output = "<table id='chasing_table' class='detail_table'><tr class='first_row'>";
	foreach($ths as $th)
		$output .= "<th>$th</th>";
	$output .= "</tr>";
	
	foreach($rows_q as $row)
	{
		$c++;
		$output .= "<tr>
						<td>$c</td>
						<td><a href='index.php?module=Contacts&parenttab=Sales&action=DetailView&record=$row[0]' target='_blank'>$row[1]</a></td>
						<td>$row[2]</td>
						<td>$row[3]</td>
						<td>$row[4]</td>
						<td>$row[5]</td>
						<td>$row[6]</td>
					</tr>";
	}
	
	$output .= "</table>";
	
	echo "<p>There are a total of ".count($rows_q)." customers in QLD haven't paid their balance.</p>";
	echo $output;
?>



</div>