<?php
/*Two variables:
 *1. leads: -None-, PR, etc. OR all_sources
 *2. date: this_month, last_month, all
 *
 */

//------------------------------------Get variables, determine the query-------------------------------------------------------------------------------------------------------------------------------------------------//
$leads = $_GET['leads'];
$date = $_GET['date'];

$leads_clause = " AND vtiger_contactsubdetails.leadsource = '$leads' ";
$date_clause = " AND vtiger_contactscf.cf_638 IS NOT NULL ";
//It seems that for overall summary, app date is not important.
$date_clause = "";
$file_name_date = "all";

if($leads == "all_sources")
{
	$leads_clause = " ";
}

if($date == "this_month")
{
	$start = date("Y-m-")."01";
	$end = date("Y-m-",strtotime("next month"))."01";
	$date_clause = "AND vtiger_contactscf.cf_638 >= '$start 00:00:00' AND vtiger_contactscf.cf_638 < '$end 00:00:00' ";
	$file_name_date = date("Y-m");
}

if($date == "last_month")
{
	//$start = date("Y-m-", strtotime("last month"))."01";
	$start = date("Y-m-", strtotime('last month', strtotime(date("Y-m-")."01")))."01";
	$end = date("Y-m-")."01";
	$date_clause = "AND vtiger_contactscf.cf_638 >= '$start 00:00:00' AND vtiger_contactscf.cf_638 < '$end 00:00:00' ";
	$file_name_date = date("Y-m", strtotime("last month"));
}


//----------------------------------------------------------Generate the query-------------------------------------------------------------------------------------------------------------------------------------------//
//cf_607 as storey, cf_608 as material, cf_609 as slope, cf_610 as switchbox, cf_611 as sys_req cf_612 as progress, cf_613 as deposit_date, cf_614 as deposit_amount, cf_615 as inv_no, cf_616 as payment, cf_617 as total_amount, cf_618 as payment_date, cf_619 as pay_confirmed, cf_620 as panel_bra, cf_621 as panel_size, cf_622 as panel_no, cf_623 as inveter_bra, cf_636 as inverter_size, cf_637 as 
$whereclause0 = "SELECT user_name, vtiger_contactsubdetails.leadsource, contact_no, firstname, lastname, email, phone, mobile, cf_638 as app_date, cf_639 as app_time, cf_649 as unqualified, mailingstreet, mailingcity, mailingzip, mailingstate, cf_607 as storey, cf_608 as material, cf_609 as slope, cf_610 as switchbox, cf_611 as sys_req, cf_612 as progress, cf_613 as deposit_date, cf_614 as deposit_amount, cf_615 as inv_no, cf_616 as payment, cf_617 as total_amount, cf_618 as payment_date, cf_619 as pay_confirmed, cf_620 as panel_bra, cf_621 as panel_size, cf_622 as panel_no, cf_623 as inveter_bra, cf_636 as inverter_size, cf_625 as install_date, cf_626 as install_time, cf_627 as install_team, cf_628 as install_instru, smownerid, all_comments, vtiger_crmentity.description
							FROM vtiger_crmentity
							INNER JOIN vtiger_contactdetails
							ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
							INNER JOIN vtiger_contactaddress
							ON vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
							INNER JOIN vtiger_contactscf
							ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
							INNER JOIN vtiger_contactsubdetails
							ON vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
							$leads_clause
							INNER JOIN vtiger_users
							ON vtiger_crmentity.smownerid = vtiger_users.id 
							AND vtiger_crmentity.deleted = 0 
							$date_clause
							LEFT JOIN
							(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'), '\n', commentcontent, '\n')) AS all_comments
							FROM vtiger_crmentity, vtiger_modcomments 
							WHERE modcommentsid = crmid
							GROUP BY related_to) AS temp_table
							ON temp_table.related_to = vtiger_crmentity.crmid
							ORDER BY vtiger_contactsubdetails.leadsource, cf_638 ASC";
									
$whereclause1 = "SELECT groupname, vtiger_contactsubdetails.leadsource, contact_no, firstname, lastname, email, phone, mobile, cf_638 as app_date, cf_639 as app_time, cf_649 as unqualified, mailingstreet, mailingcity, mailingzip, mailingstate, cf_607 as storey, cf_608 as material, cf_609 as slope, cf_610 as switchbox, cf_611 as sys_req, cf_612 as progress, cf_613 as deposit_date, cf_614 as deposit_amount, cf_615 as inv_no, cf_616 as payment, cf_617 as total_amount, cf_618 as payment_date, cf_619 as pay_confirmed, cf_620 as panel_bra, cf_621 as panel_size, cf_622 as panel_no, cf_623 as inveter_bra, cf_636 as inverter_size, cf_625 as install_date, cf_626 as install_time, cf_627 as install_team, cf_628 as install_instru, smownerid, all_comments, vtiger_crmentity.description
							FROM vtiger_crmentity
							INNER JOIN vtiger_contactdetails
							ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
							INNER JOIN vtiger_contactaddress
							ON vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
							INNER JOIN vtiger_contactscf
							ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
							INNER JOIN vtiger_contactsubdetails
							ON vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
							$leads_clause
							INNER JOIN vtiger_groups
							ON vtiger_crmentity.smownerid = vtiger_groups.groupid 
							AND vtiger_crmentity.deleted = 0 
							$date_clause
							LEFT JOIN
							(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'), '\n', commentcontent, '\n')) AS all_comments
							FROM vtiger_crmentity, vtiger_modcomments 
							WHERE modcommentsid = crmid
							GROUP BY related_to) AS temp_table
							ON temp_table.related_to = vtiger_crmentity.crmid
							ORDER BY vtiger_contactsubdetails.leadsource, cf_638 ASC";
		
//-------------if it's an individual selling---------------------//
$time = date("YmdHis");
$first_row = array("assigned_to","lead source","contact no","first name","last name","email","phone","mobile","appoint date","appoint time","unqualified", "street","suburb","postcode","state","storey","material","slope","switchbox","system req","progress","deposit date","deposite amount","invoice","payment method","total amount","payment date","payment confirm","panel brand","panel size","panel no","inverter brand","inverter size","install date","install time","install team","install instruction","assigned_to","all comments","description");
$output_path = "modules/Dashboard/downloadFiles/leads_".$leads."_".$file_name_date."_".$time.".csv";
$fh = fopen($output_path, "w");
fputcsv($fh, $first_row);
$result = mysql_query($whereclause0);
if (!$result) 
{
	die('Invalid query: ' . mysql_error());
}
while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
{
	fputcsv($fh, $row);
} 


//------------if it's a tem selling-----------------------------//
$result = mysql_query($whereclause1);
if (!$result) 
{
	die('Invalid query: ' . mysql_error());
}
while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
{
	fputcsv($fh, $row);
} 
fclose($fh);

header("Content-Type: application/csv");
header("Content-Length: ".filesize($output_path));
header('Content-Disposition: attachment; filename="' . basename($output_path) . '"');
$fp = fopen($output_path,"r");
fpassthru($fp);
fclose($fp);
exit; 
?>