<?php

if($_GET['ids'] != "")
{
	$ids = $_GET['ids'];
	$ids = substr($ids, 0, strlen($ids)-1);
	$ids = str_replace("|",",",$ids);
	$ids = str_replace("row_","",$ids);

	//----------------------------------------------------------Generate the query-------------------------------------------------------------------------------------------------------------------------------------------//
	//cf_607 as storey, cf_608 as material, cf_609 as slope, cf_610 as switchbox, cf_611 as sys_req cf_612 as progress, cf_613 as deposit_date, cf_614 as deposit_amount, cf_615 as inv_no, cf_616 as payment, cf_617 as total_amount, cf_618 as payment_date, cf_619 as pay_confirmed, cf_620 as panel_bra, cf_621 as panel_size, cf_622 as panel_no, cf_623 as inveter_bra, cf_636 as inverter_size, cf_637 as 
	$link = mysql_connect('localhost','root','johnamy');
	mysql_select_db("goforsolar_crm");
	$whereclause0 = "SELECT user_name, vtiger_contactsubdetails.leadsource, contact_no, firstname, lastname, email, phone, mobile, cf_638 as app_date, cf_639 as app_time, mailingstreet, mailingcity, mailingzip, mailingstate, cf_607 as storey, cf_608 as material, cf_609 as slope, cf_610 as switchbox, cf_611 as sys_req, cf_612 as progress, cf_613 as deposit_date, cf_614 as deposit_amount, cf_615 as inv_no, cf_616 as payment, cf_617 as total_amount, cf_618 as payment_date, cf_619 as pay_confirmed, cf_620 as panel_bra, cf_621 as panel_size, cf_622 as panel_no, cf_623 as inveter_bra, cf_636 as inverter_size, cf_625 as install_date, cf_626 as install_time, cf_627 as install_team, cf_628 as install_instru, smownerid, all_comments, vtiger_crmentity.description
								FROM vtiger_crmentity
								INNER JOIN vtiger_contactdetails
								ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
								AND crmid IN ($ids)
								INNER JOIN vtiger_contactaddress
								ON vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
								INNER JOIN vtiger_contactscf
								ON vtiger_crmentity.crmid = vtiger_contactscf.contactid
								INNER JOIN vtiger_contactsubdetails
								ON vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
								INNER JOIN vtiger_users
								ON vtiger_crmentity.smownerid = vtiger_users.id 
								AND vtiger_crmentity.deleted = 0 
								LEFT JOIN
								(SELECT related_to, GROUP_CONCAT(CONCAT(DATE_FORMAT(createdtime,'%Y-%m-%d %H:%i:%s'), '\n', commentcontent, '\n')) AS all_comments
								FROM vtiger_crmentity, vtiger_modcomments 
								WHERE modcommentsid = crmid
								GROUP BY related_to) AS temp_table
								ON temp_table.related_to = vtiger_crmentity.crmid
								ORDER BY cf_638 ASC";
										
//echo "<pre>".$whereclause0."</pre>";
	//-------------if it's an individual selling---------------------//
 	$time = date("YmdHis");
	$first_row = array("assigned_to","lead source","contact no","first name","last name","email","phone","mobile","appoint date","appoint time","street","suburb","postcode","state","storey","material","slope","switchbox","system req","progress","deposit date","deposite amount","invoice","payment method","total amount","payment date","payment confirm","panel brand","panel size","panel no","inverter brand","inverter size","install date","install time","install team","install instruction","assigned_to","all comments","description");
	$output_path = "modules/Dashboard/downloadFiles/contacts_".$time.".csv";
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

	fclose($fh);

	header("Content-Type: application/csv");
	header("Content-Length: ".filesize($output_path));
	header('Content-Disposition: attachment; filename="' . basename($output_path) . '"');
	$fp = fopen($output_path,"r");
	fpassthru($fp);
	fclose($fp);
	exit; 
}
?>
