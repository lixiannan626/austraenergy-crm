<script language="JavaScript" type="text/javascript" src="modules/InstallTeam/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/InstallTeam/jquery.reveal.js"></script>
<link rel="stylesheet" href="modules/InstallTeam/reveal.css">
<link rel="stylesheet" href="modules/InstallTeam/main.css">
<script type="text/javascript">
$(document).ready(function() {


     $('a.view_detail').click(function(e) {
          e.preventDefault();
		  var self = $(this);
		  var root = $(this).parent();
		  var id=$(this).attr("data-id");
		  var conno = $(this).attr("data-con");
		  
		  //if
		  if(root.find('div.clientdetail').length == 0)
		  {
			  $.post(
				"modules/InstallTeam/clientdetail.php",
				{
					id: id,
					conno: conno
				},
				function(responseText)
				{
					self.hide();
					//If data fetched
					root.append(responseText);
					root.find('div.clientdetail').reveal();
					self.show();
				},
				"html"
			  );
		  }
		  else
		  {
			root.find('div.clientdetail').reveal();
		  }
     });
});
</script>

	<div id="wrap_up">
		<table class='custom_table'>
				<tr>
					<td class='nav_td'>
						<div id="nav">
						<?php ShowNav($_GET['team_id']); ?>
						</div>
					</td>
					<td>
						<div id="main">
						<?php Main(); ?>
						</div>
					</td>
				</tr>
		</table>
		<div id="footer">
		
		</div>
	</div>

<?php
function Main()
{
	//Show Install Job List
	$rows = array();
	
	if(isset($_GET['team_id']))
	{
		$id = $_GET['team_id'];
		
		//Team Name
		$q = "SELECT team_name FROM custom_install_team WHERE team_id = $id";
		$name = mysql_fetch_array(mysql_query($q), MYSQL_NUM);
		$name = $name[0];
		echo "<h1>Team: $name</h1>";
		
		//Team Detail
		$query = "SELECT concat_ws(' ', firstname, lastname) as 'fullname',
											mailingstreet as 'street',
											mailingcity as 'suburb',
											mailingzip as 'postcode',
											cf_625 as 'installdate',
											crmid,
											contact_no,
											cf_646 as 'installflag',
											cf_654 as 'stc_form',
											cf_655 as 'connection_form',
											cf_656 as 'ewr_form',
											cf_651 as 'ces'
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
		//ShowJobTable($future, "Future Jobs", "future_div", "");
		if(count($confirm) != 0)
			ShowJobTable($confirm, "Jobs Waiting For Confirmation", "confirm_div", "");
		ShowJobTable($completed, "Completed Jobs", "past_div","");
	}
}

function ShowJobTable($array, $h2, $div_id, $explain)
{
	$ths = array("#", "CON #", "Client Name/Address",  "Install Date","STC Form", "Connec. Form", " EWR", "CES");
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
			$f1 = 'error';
			$f2 = 'error';
			$f3 = 'error';
			$f4 = 'error';
			if($a['stc_form'] != "")
				$f1 = 'success';
			if($a['connection_form'] != "")
				$f2 = 'success';
			if($a['ewr_form'] != "")
				$f3 = 'success';
			if($a['ces'] != "")
				$f4 = 'success';
			echo "<tr>
						<td class='center'>".++$c."</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."<br>
								".ucwords(strtolower($a['street'])).", 
								".ucwords(strtolower($a['suburb']))." 
								".$a['postcode']."</td>
						<td class='center'>".$a['installdate']."</td>
						<td class='center'><span class='$f1'></span></td>
						<td class='center'><span class='$f2'></span></td>
						<td class='center'><span class='$f3'></span></td>
						<td class='center'><span class='$f4'></span></td>
					</tr>";			
		}
		echo "</table></div>";
	}
	else
	{
		//echo "<p class='no_record'>No record found.</p></div>";
		echo "</div>";
	}
}
function ShowNav($id)
{
	//Find all teams and their statistics
	$today = date("Y-m-d");
	$query = "SELECT team_name, team_id, count(crmid)
					FROM custom_install_team, vtiger_crmentity, vtiger_contactscf
					WHERE crmid = contactid
					AND cf_627 = team_name
					AND cf_612 LIKE 'Sale'
					AND deleted = 0
					AND cf_625 <= '$today'
					GROUP BY team_name";
	$result = mysql_query($query);
	$rows = array();
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($rows, $row);
	
	//Find all teams with paperwork not submitted yet
	$query = "SELECT team_name
					FROM custom_install_team, vtiger_crmentity, vtiger_contactscf
					WHERE crmid = contactid
					AND cf_627 = team_name
					AND cf_612 LIKE 'Sale'
					AND deleted = 0
					AND (cf_654 IS NULL OR cf_655 IS NULL OR cf_656 IS NULL OR cf_651 IS NULL)
					GROUP BY team_name";
	$teams =array();
	$teams2 =array();
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($teams, $row[0]);
	
	if(count($rows) != 0)
	{
		echo "<h3>Owing paperwork team</h3><ul>";
		foreach($rows as $r)
		{
			if(in_array($r[0], $teams))
				echo "<li><a href='index.php?module=InstallTeam&action=jobdetail&parenttab=Support&team_id=$r[1]' id='t$r[1]'>$r[0] ($r[2])</a></li>";
			else
				array_push($teams2, $r);
		}
		echo "</ul>";
	}
	
	
	if(count($teams2) != 0)
	{
		echo "<h3>Not owing paperwork teams</h3><ul>";
		foreach($teams2 as $r)
		{
			echo "<li><a href='index.php?module=InstallTeam&action=jobdetail&parenttab=Support&team_id=$r[1]' id='t$r[1]'>$r[0] ($r[2])</a></li>";
		}
		echo "</ul>";
	}
	
	
	
		echo "<script type='text/javascript'>
					document.getElementById('t$id').className += 'selected';
			</script>";
}
?>