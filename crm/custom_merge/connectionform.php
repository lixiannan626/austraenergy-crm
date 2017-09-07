<?php

//echo $_GET['return_id'];
//echo $_SERVER['REMOTE_ADDR'];
//echo "Under Development";
error_reporting(0);
$link = mysql_connect("localhost", "root", "johnamy");
if($link && isset($_GET['return_id']))
{
	mysql_select_db("goforsolar_crm");
	//Check whether id belongs to an active contact
	$id = $_GET['return_id'];
	$q = "SELECT concat_ws(' ', firstname, lastname) as 'C_Name',
						mobile as 'C_Phone',
						email as 'EmAd',
						cf_641 as 'NNMI',
						contact_no as 'N_CONN',
						concat_ws(' ', i_firstname, i_lastname) as 'FullName',
						i_company as 'I_Company',
						i_accre_no as 'I_Accre',
						concat_ws(', ', i_street, i_suburb, concat(i_state, ' ', i_postcode)) as 'I_Address',
						i_mobile as 'I_Phone',
						concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as 'SupplyAddress',
						concat_ws(', ', otherstreet, othercity, concat(otherstate, ' ', otherzip)) as 'MailingAddress',
						UPPER(cf_621) as 'D_PanSiz',
						cf_622 as 'D_PanQty'
			FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf,vtiger_contactaddress, vtiger_contactsubdetails, custom_install_team
			WHERE crmid = $id 
			AND crmid = vtiger_contactdetails.contactid
			AND crmid = vtiger_contactscf.contactid
			AND crmid = vtiger_contactaddress.contactaddressid
			AND crmid = vtiger_contactsubdetails.contactsubscriptionid
			AND setype = 'Contacts' 
			AND deleted = 0
			AND team_name = cf_627
			";
	$result = mysql_query($q);
	if(mysql_num_rows($result) == 1)
	{
		//Read fields from database
		$row = mysql_fetch_array($result, MYSQL_ASSOC);

		//Open RTF file
		$content= file_get_contents('Solar_Connection_Form_Template.rtf'); 
		
		
		//Create 2 keys on the fly
		$row['Cap1'] = '';
		$row['Cap2'] = '';
		$total = trim(str_replace('W', '',$row['D_PanSiz']))*$row['D_PanQty'];
		$total = number_format($total/1000, 2, '.', '');
		$t = explode('.', $total);
		if(isset($t[0]))
			$row['Cap1'] = $t[0];
		if(isset($t[1]))
			$row['Cap2'] = $t[1];
		
		//Upper First Letter
		$row['C_Name'] = ucwords(strtolower($row['C_Name']));
		$row['SupplyAddress'] = ucwords(strtolower($row['SupplyAddress']));
		if(strlen($row['MailingAddress']) < 10)
			$row['MailingAddress'] = $row['SupplyAddress'];
		
		//Replace keys
		foreach($row as $key=>$val)
		{
			//echo "$key=>$val<br>";
			$content = str_replace($key, $val, $content);
		}
		//Prompt for download
		//$content = str_replace("CRM", "Test", $content);
		$conno = $row['N_CONN'];
		$dt = date("YmdHis");
		$output = "download/Connection_Form_".$conno."_$dt.rtf";
		$fh = fopen($output, "w");
		fwrite($fh, $content);
		fclose($fh);
		header("Content-Type: application/rtf");
		header("Content-Length: ".filesize($output));
		header('Content-Disposition: attachment; filename="' . basename($output) . '"');
		$fp = fopen($output,"r");
		fpassthru($fp);
		fclose($fp);

		exit; 
	}
	else
	{
		echo "Install team info not in place, please go to Support->Install Team to update.";
	}
}
/**************************** Example
$output_path = "downloadFiles/contacts_".$time.".csv";
	$fh = fopen($output_path, "w");
	fputcsv($fh, $first_row);
	$result = mysql_query($query);
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
	***************************************************/
?>