<?php
//echo "Ok";
error_reporting(0);

if(isset($_POST['crmid']) && isset($_POST['con_no']) && isset($_POST['newdate']) && isset($_POST['installerid']))
{
	$crmid = $_POST['crmid'];
	$con_no = $_POST['con_no'];
	$newdate = $_POST['newdate'];
	$installerid = $_POST['installerid'];
	//Check Date's validity
	$dateflag = 0;
	
	if($newdate != "")
	{
		$nd = strtotime($newdate);
		$td = strtotime(date("Y-m-d"));
		if($nd < $td)
			$dateflag = 1;
	}
	
	//Invalid Date
	if($dateflag == 1)
	{
		echo "Err0r";
	}
	//Valid Date
	else
	{
		//Reformat
		if($newdate != "")
			$newdate = date("Y-m-d", strtotime($newdate));
		
		//echo $newdate;
		include_once("connect_db.php");
		$q = "SELECT crmid FROM vtiger_contactscf, vtiger_crmentity, vtiger_contactdetails, custom_install_team
					WHERE crmid = vtiger_contactscf.contactid
					AND crmid = vtiger_contactdetails.contactid
					AND cf_627 = team_name
					AND deleted = 0
					AND crmid = $crmid
					AND contact_no = '$con_no'
					AND team_id = $installerid
					";
		//Pass the anti-injection test
		if(mysql_num_rows(mysql_query($q)) == 1)
		{
			//Update the db
			if($newdate != "")
				$q = "UPDATE vtiger_contactscf SET cf_625 = '$newdate' WHERE contactid = $crmid";
			else
				$q = "UPDATE vtiger_contactscf SET cf_625 = NULL WHERE contactid = $crmid";
			mysql_query($q);
			//Successfully update the db
			if(mysql_errno == 0)
			{
				//Log the update
				$time = date("Y-m-d H:i:s");
				$q = "INSERT custom_installer_datelog (d_time, d_crmid, d_installerid, d_newdate) VALUES ('$time', $crmid, $installerid, '$newdate')";
				mysql_query($q);
				
				//Return new date
				echo $newdate;
			}
			else
			{
				echo "Err0r";
			}
			
		}
		else
		{
			echo "Err0r";
		}
	}
}
?>