<?php
// Check if session is not registered, redirect back to main page. 
// Put this code in first line of web page. 
session_start();
if(!session_is_registered(myusername))
{
	header("location:main_login.php");
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Go For Solar Installer Portal</title>
<script language="JavaScript" type="text/javascript" src="jquery.min.js"></script>
<script language="JavaScript" type="text/javascript" src="jquery.reveal.js"></script>
<script language="JavaScript" type="text/javascript" src="jquery.ui.js"></script>
<script language="JavaScript" type="text/javascript" src="installer.js"></script>
<link rel="stylesheet" href="reveal.css">
<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="jquery-ui.css">
<script type="text/javascript">

</script>
</head>
<body>
	<div id="wrap_up">
		<div id="header">
		
		</div>
		<div id="nav">
			<a class="nav_option selected" href="index.php">Job Overview</a>
			<a class="nav_option" href="paperwork.php">Paperwork</a>
		</div>
		<div id="main">
		<?php Main(); ?>
		</div>
		<div id="footer">
		
		</div>
	</div>
	<div id="myModal" class="reveal-modal">
		<h1>Modal Title</h1>
		<p>Any content could go in here.</p>
     <a class="close-reveal-modal">&#215;</a>
	</div>
</body>
</html>

<?php
function Main()
{
	//User Info
	$name = $_COOKIE['ck_login_name'];
	echo "<p class='right'>Hi, $name (<a href='logoff.php'>Log out</a>)</p>";
	
	//Page Info
	echo "
	<div id='page-intro'>
	<p>On this page, you are able to:</p>
	<p> - View all the jobs assigned to you.</p>
	<p> - View detail of a single job.</p>
	<p> - Modify future job's install date.</p>
	<p> - Download STC assignment form, PV Solar connection form, EWR.</p>
	</div>
	";
	
	//Show Install Job List
	include_once("connect_db.php");
	$rows = array();
	$id = $_COOKIE['ck_login_id'];
	$query = "SELECT concat_ws(' ', firstname, lastname) as 'fullname',
										mailingstreet as 'street',
										mailingcity as 'suburb',
										mailingstate as 'state',
										mailingzip as 'postcode',
										cf_625 as 'installdate',
										crmid,
										contact_no,
										cf_646 as 'installflag'
					FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf, custom_install_team
					WHERE vtiger_crmentity.crmid = vtiger_contactdetails.contactid
					AND vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
					AND vtiger_crmentity.crmid = vtiger_contactscf.contactid
					AND vtiger_crmentity.deleted = 0
					AND vtiger_contactscf.cf_627 = custom_install_team.team_name
					AND custom_install_team.team_id = $id
					AND cf_612 LIKE 'Sale'
					ORDER BY cf_625 DESC
										";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		array_push($rows, $row);
	
	$future = array();
	$completed = array();
	$confirm = array();
	
	//Put jobs into 3 categories
	//1. Yet installed jobs
	//2. Installed but need confirmation
	//3. Installed and confirmed
	if(count($rows) != 0)
	{
		foreach($rows as $r)
		{
			$d = $r['installdate'];
			$dt = strtotime($d);
			$today = strtotime(date("Y-m-d"));
			$installflag = $r['installflag'];
			//Future means the install date > today
			if($dt > $today || $d == "")
				array_push($future, $r);
			//0 means past job need confirmation
			else if($installflag == 0)
				array_push($confirm, $r);
			else
				array_push($completed, $r);
		}
	}
	
	//Display 3 categories
	$f_explain = "Following jobs have been assigned to you, waiting to be installed.";
	$c_explain = "Following jobs' installation dates are prior to today, but haven't been confirmed whether installation actually happened.";
	$p_explain = "Following installations have been comfirmed.";
	ShowJobTable($future, "Future Jobs", "future_div", $f_explain, "changable", $id);
	ShowJobTable($confirm, "Jobs Waiting For Confirmation", "confirm_div", $c_explain, "", $id);
	ShowJobTable($completed, "Completed Jobs", "past_div", $p_explain, "", $id);
}

function ShowJobTable($array, $h2, $div_id, $explain, $dateclass, $installerid)
{
	$ths = array("#", "CON #", "Client Name/Address", "Install Date", "STC form", "Connect. form", "EWR", "Detail");
	echo "<div id='$div_id' class='job_div'>
				<h2>$h2 (".count($array).")</h2>
				";
	if(count($array) != 0)
	{
		echo "<p class='normal'>$explain</p>";
		$c = 0;
		echo "<table>
					<tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($array as $a)
		{
			//The arranged install date is 3 weeks after today, that will be suspicious
			/* if(strtotime("+3 weeks") < strtotime($a['installdate']))
				$dateclass = ''; */
			echo "<tr>
						<td class='center'>".++$c."</td>
						<td class='center'>".$a['contact_no']."</td>
						<td>".ucwords(strtolower($a['fullname']))."<br>
								".ucwords(strtolower($a['street'])).", 
								".ucwords(strtolower($a['suburb'])).", 
								".strtoupper($a['state'])." 
								".$a['postcode']."</td>
						<td class='center $dateclass'><div class='origin'>".$a['installdate']."</div>
									<div class='data-storage'>
										<input type='hidden' class='crmid' value='".$a['crmid']."'/>
										<input type='hidden' class='con_no' value='".$a['contact_no']."'/>
										<input type='hidden' class='installerid' value='$installerid'/>
									</div></td>
						<td class='center'><a href='../crm/custom_merge/stc.php?return_id=".$a['crmid']."' target='_blank'><img src='File.png'></a></td>
						<td class='center'><a href='../crm/custom_merge/connectionform.php?return_id=".$a['crmid']."' target='_blank'><img src='File.png'></a></td>
						<td class='center'><a href='../crm/custom_merge/EWR.pdf' target='_blank'><img src='File.png'></a></td>
						<td class='center'><a class='view_detail' data-con='".$a['contact_no']."' data-id='".$a['crmid']."'>View</a></td>
					</tr>";			
		}
		echo "</table></div>";
	}
	else
	{
		echo "<p class='no_record'>No record found.</p></div>";
	}
}
?>