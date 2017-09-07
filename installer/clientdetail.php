<?php
if(isset($_POST['id']) && isset($_POST['conno']))
{
	$id = $_POST['id'];
	$conno = $_POST['conno'];
	include_once("connect_db.php");
	$query = "SELECT concat_ws(' ', firstname, lastname) as 'fullname',
										mailingstreet as 'street',
										mailingcity as 'suburb',
										mailingzip as 'postcode',
										cf_625 as 'installdate',
										contact_no,
										mobile,
										homephone,
										concat_ws(' ', cf_620, cf_621) as 'panel',
										cf_622 as 'panelqty',
										concat_ws(' ', cf_623, cf_650) as 'inverter',
										concat_ws(' ', cf_609, cf_608) as 'roof',
										cf_610 as 'switchbox',
										cf_654 as 'form1',
										cf_655 as 'form2',
										cf_656 as 'form3',
										cf_651 as 'form4'
					FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf, vtiger_contactsubdetails
					WHERE vtiger_crmentity.crmid = vtiger_contactdetails.contactid
					AND vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
					AND vtiger_crmentity.crmid = vtiger_contactscf.contactid
					AND vtiger_crmentity.crmid = vtiger_contactsubdetails.contactsubscriptionid
					AND vtiger_crmentity.deleted = 0
					AND cf_612 LIKE 'Sale'
					AND contact_no = '$conno'
					AND crmid = $id
	
	";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1)
	{
		$r = mysql_fetch_array($result, MYSQL_ASSOC);
		
		//Basic Table
		$basic = array("Con #"=>"contact_no",
									"Name"=>"fullname",
									"Street"=>"street",
									"Suburb"=>"suburb",
									"Postcode"=>"postcode",
									"Phone"=>"homephone",
									"Mobile"=>"mobile");
		$b = '';
		foreach($basic as $k=>$v)
			$b .= "<tr><td class='d_label'>$k</td><td class='d_value'>".$r[$v]."</td></tr>";
		
		//System Table
		$system = array("Panel"=>"panel",
									"Panel Qty"=>"panelqty",
									"Inverter"=>"inverter",
									"Roof"=>"roof",
									"Switchbox"=>"switchbox");
		$s = "";
		foreach($system as $k=>$v)
			$s .= "<tr><td class='d_label'>$k</td><td class='d_value'>".$r[$v]."</td></tr>";
		
		//Paperwork Table
		$f1 = $f2 = $f3 = $f4 = "error";
		if($r['form1'] != "")
			$f1 = "success";
		if($r['form2'] != "")
			$f2 = "success";
		if($r['form3'] != "")
			$f3 = "success";
		if($r['form4'] != "")
			$f4 = "success";
		
		echo "<div class='clientdetail reveal-modal'>
						<h3>Basic</h3>
							<table>
								$b
							</table>
						<h3>System</h3>
							<table>
								$s
							</table>
						<h3>Paperwork</h3>
							<table>
								<tr><td class='d_label'>STC Assignment Form</td><td class='d_value'><span class='$f1'></span></td></tr>
								<tr><td class='d_label'>Solar Connection Form</td><td class='d_value'><span class='$f2'></span></td></tr>
								<tr><td class='d_label'>EWR</td><td class='d_value'><span class='$f3'></span></td></tr>
								<tr><td class='d_label'>CES</td><td class='d_value'><span class='$f4'></span></td></tr>
							</table>
					<a class='close-reveal-modal'>&#215;</a>
				</div>";
	}
	else
	echo "<div class='clientdetail reveal-modal'>There is a problem, please notify go for solar about the issue, $conno can not be retrieved.
     <a class='close-reveal-modal'>&#215;</a></div>";
	
}
else
	echo "<div class='clientdetail reveal-modal'>Incorrect input
     <a class='close-reveal-modal'>&#215;</a></div>";
?>