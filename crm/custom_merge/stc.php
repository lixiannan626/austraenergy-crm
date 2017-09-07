<?php

//echo $_GET['return_id'];
//echo $_SERVER['REMOTE_ADDR'];
//echo "Under Development";

$link = mysql_connect("localhost", "root", "johnamy");
if($link && isset($_GET['return_id']))
{
	mysql_select_db("goforsolar_crm");
	//Check whether id belongs to an active contact
	$id = $_GET['return_id'];
	$q = "SELECT firstname as 'SSS',
						lastname as 'D_LastName',
						mailingstreet as 'D_Street',
						mailingcity as 'D_Suburb',
						mailingstate as 'D_State',
						mailingzip as 'D_Zip',
						homephone as 'D_HomePhone',
						mobile as 'D_Mobile',
						email as 'D_Email',
						cf_620 as 'D_PanBra',
						UPPER(cf_621) as 'D_PanSiz',
						cf_622 as 'D_PanQty',
						cf_623 as 'D_InvBra',
						cf_650 as 'D_InvSiz',
						cf_625 as 'D_InsDat',
						cf_611 as 'D_PowOut',
						cf_617 as 'D_Cost',
						contact_no as 'N_CONN',
						i_firstname as 'I_FirstName',
						i_lastname as 'I_LastName',
						i_company as 'I_Company',
						i_accre_no as 'I_ACCRE_NO',
						i_street as 'I_Street',
						i_suburb as 'I_Suburb',
						i_state as 'I_State',
						i_postcode as 'I_Zip',
						i_accre_no as 'I_ACCRE_NO',
						i_phone as 'I_Phone',
						i_mobile as 'I_Mobile',
						i_email as 'I_Email',
						e_firstname as 'E_FirstName',
						e_lastname as 'E_LastName',
						e_elec_no as 'E_ELEC_NO',
						e_company as 'E_Company',
						e_street as 'E_Street',
						e_suburb as 'E_Suburb',
						e_state as 'E_State',
						e_postcode as 'E_Zip',
						e_phone as 'E_Phone',
						e_mobile as 'E_Mobile',
						e_email as 'E_Email',
						concat_ws(',', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as 'A_Address'
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

		//Change Date format
		$row['D_InsDat'] = date("d/m/Y", strtotime($row['D_InsDat']));
		
		//Open RTF file
		$content= file_get_contents('STC_Assignment_Template.rtf'); 

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
		$output = "download/SAF_".$conno."_$dt.rtf";
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