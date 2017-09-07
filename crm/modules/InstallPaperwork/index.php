<?php
	//2013-07-03 Update, new condition for paperwork
	//If distributor requires pre-approval, 2 dates must be entered
	$pre_approval_field = ", concat_ws('','sumbit:', cf_669, '<br>approve:', cf_670) AS 'pre_approval', cf_668 AS distributor";
	$pre_approval_fail_condition = " ";
	$distributors = get_distributors();
	if(count($distributors) != 0)
		$pre_approval_fail_condition = " OR (cf_668 IN ('".implode("','", $distributors)."') AND cf_669 IS NULL) OR (cf_668 IN ('".implode("','", $distributors)."') AND cf_670 IS NULL) ";
?>

<link rel="stylesheet" href="modules/InstallPaperwork/reveal.css">
<link rel="stylesheet" href="modules/InstallPaperwork/main.css">
<script language="JavaScript" type="text/javascript" src="modules/InstallPaperwork/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/InstallPaperwork/jquery.reveal.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/InstallPaperwork/jquery.sort.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/InstallPaperwork/sort.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/InstallPaperwork/sortth.js"></script>
<script src="jslib/jquery.waypoints.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	 
	 //Comment Detail
	 $("a.comment_detail").click(function()
	{
		var self = $(this);
		var id = $(this).attr("data-id");
		var root = $(this).parent();
		
		if(root.find('div.commentdetail').length == 0)
		{
		$.post(
			"modules/InstallPaperwork/comment_detail.php",
			{id:id},
			function(responseText)
			{
				//self.hide();
				root.append(responseText);
				root.find('div.commentdetail').reveal();
				//self.show();
			},
			"html"
		);
		}
		else
		{
			root.find('div.commentdetail').reveal();
		}
	});
	
	//Show Future Jobs
	$("a#showfuture").click(function()
	{
		$('div#future_div').toggle();
		var c = $(this).text();
		$(this).text($(this).attr('data-alt'));
		$(this).attr('data-alt', c);
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
					<td id="main_content">
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
/**
 *
 */

function get_distributors()
{
	$dis = array();
	$select = "SELECT distributor FROM distributor_approval WHERE need_approval=1";
	$result = mysql_query($select);
	if($result)
		while($row = mysql_fetch_assoc($result))
			array_push($dis, $row['distributor']);
	return $dis;
}
function Main()
{
	global $pre_approval_field, $pre_approval_fail_condition;
	
	
	//Show Install Job List
	$rows = array();
	
	//Case 1: No particular installer is selected, show all
	if(isset($_GET['team_id']) == false)
	{
		$today = date("Y-m-d");
		echo "<script type='text/javascript'>
					document.getElementById('over').className += 'selected';
			</script>
		";
		$query = "SELECT concat_ws(' ', firstname, lastname) as 'fullname',
											mailingstreet as 'street',
											mailingcity as 'suburb',
											mailingzip as 'postcode',
											mailingstate as 'state',
											cf_625 as 'installdate',
											crmid,
											contact_no,
											cf_646 as 'installflag',
											IF (cf_646 = 1, '<span class=green>&#10004;</span>', '<span class=red>&#10008;</span>') as 'installed',
											cf_654 as 'stc_form',
											cf_655 as 'connection_form',
											cf_656 as 'ewr_form',
											cf_651 as 'ces' $pre_approval_field
						FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactaddress, vtiger_contactscf, custom_install_team
						WHERE vtiger_crmentity.crmid = vtiger_contactdetails.contactid
						AND vtiger_crmentity.crmid = vtiger_contactaddress.contactaddressid
						AND vtiger_crmentity.crmid = vtiger_contactscf.contactid
						AND vtiger_crmentity.deleted = 0
						AND vtiger_contactscf.cf_627 = custom_install_team.team_name
						AND cf_612 LIKE 'Sale'
						AND (cf_654 IS NULL OR cf_655 IS NULL OR cf_656 IS NULL OR cf_651 IS NULL $pre_approval_fail_condition)
						AND cf_625 <= '$today'
						ORDER BY mailingstate, cf_625 DESC";
						//echo $query;
		$rows = array();
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($rows, $row);
		ShowJobTable($rows, "Summary table of contacts waiting for paperwork", "past_div","past_header");
	}
	else
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
											mailingstate as 'state',
											cf_625 as 'installdate',
											crmid,
											contact_no,
											cf_646 as 'installflag',
											IF (cf_646 = 1, '<span class=green>&#10004;</span>', '<span class=red>&#10008;</span>') as 'installed',
											cf_654 as 'stc_form',
											cf_655 as 'connection_form',
											cf_656 as 'ewr_form',
											cf_651 as 'ces' $pre_approval_field
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
		if(count($future) != 0)
			echo "<p><a id='showfuture' style='cursor:pointer;display: inline-block; padding: 5px; background: green; color: white;' data-alt='Hide future jobs'>Show future jobs (".count($future).")</a></p>";
		ShowJobTable($future, "Future Jobs", "future_div", "future_header");
		if(count($confirm) != 0)
			ShowJobTable($confirm, "Jobs Waiting For Confirmation", "confirm_div", "confirm_header");
		ShowJobTable($completed, "Completed Jobs", "past_div","past_header");
	}
}

function ShowJobTable($array, $h2, $div_id, $th_id)
{
	$ths = array("#", "CON #", "Client Name/Address",  "Install Date", "Installed?", "Distributor", "Pre-approval", "STC Form", "Connec. Form", " EWR (Form A/2)", "CES (COC)", "Comment");
	global $distributors;
	echo "<div id='$div_id' class='job_div'>
				<h2>$h2 (".count($array).")</h2>
				";
	if(count($array) != 0)
	{
		//echo "<p class='normal'>$explain</p>";
		$c = 0;
		echo "<table>
					<tr id='$th_id'>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($array as $a)
		{
			if($a['ces'] == "" || $a['stc_form'] == "" || $a['ewr_form'] == "" || $a['connection_form'] == "" || (in_array($a['distributor'], $distributors) && strlen($a['pre_approval']) < 20))
				$clas = "incomplete";
			else
				$clas = "";
			echo "<tr class='$clas'>
						<td class='center'>".++$c."</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."<br>
								".ucwords(strtolower($a['street'])).", 
								".ucwords(strtolower($a['suburb']))." 
								".$a['state'].' '.$a['postcode']."</td>
						<td class='center'>".$a['installdate']."</td>
						<td class='center'>".$a['installed']."</td>
						<td class='center'>".$a['distributor']."</td>
						<td class=''>".$a['pre_approval']."</td>
						<td class='center'>".$a['stc_form']."</td>
						<td class='center'>".$a['connection_form']."</td>
						<td class='center'>".$a['ewr_form']."</td>
						<td class='center'>".$a['ces']."</td>
						<td class='center'><a class='comment_detail' data-id='".$a['crmid']."'>Detail</a></td>
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
	global $pre_approval_fail_condition;
	echo "<ul><li><a href='index.php?module=InstallPaperwork&action=index&parenttab=Support' id='over'>Overview</a></li></ul>";
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
	$today = date("Y-m-d");
	$query = "SELECT team_name, count(crmid)
					FROM custom_install_team, vtiger_crmentity, vtiger_contactscf
					WHERE crmid = contactid
					AND cf_627 = team_name
					AND cf_612 LIKE 'Sale'
					AND deleted = 0
					AND (cf_654 IS NULL OR cf_655 IS NULL OR cf_656 IS NULL OR cf_651 IS NULL $pre_approval_fail_condition)
					AND cf_625 <= '$today'
					GROUP BY team_name";
	$teams =array();
	$teams2 =array();
	$pcount = array();
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
	{
		array_push($teams, $row[0]);
		$pcount[$row[0]] = $row[1];
	}
	if(count($rows) != 0)
	{
		echo "<h3>Bad boys</h3><ul>";
		foreach($rows as $r)
		{
			if(in_array($r[0], $teams))
				echo "<li><a href='index.php?module=InstallPaperwork&action=index&parenttab=Support&team_id=$r[1]' id='t$r[1]'>$r[0] <span class='papercount'>".$pcount[$r[0]]."</span></a></li>";
			else
				array_push($teams2, $r);
		}
		echo "</ul>";
	}
	
	
	if(count($teams2) != 0)
	{
		echo "<h3>Good boys</h3><ul>";
		foreach($teams2 as $r)
		{
			echo "<li><a href='index.php?module=InstallPaperwork&action=index&parenttab=Support&team_id=$r[1]' id='t$r[1]'>$r[0] <span class='papercount'>0</span></a></li>";
		}
		echo "</ul>";
	}
	
	
	if(isset($id))
		echo "<script type='text/javascript'>
					document.getElementById('t$id').className += 'selected';
			</script>";
}
?>