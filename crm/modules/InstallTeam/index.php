<?php

/************************************
List view of install teams

1. Insert new install teams if necessary
2. Update install team names if necessary
3. Show the lis of install team
************************************/

//Comment out when uploading to the server
//$link = mysql_connect("localhost", "root", "jackson");
//mysql_select_db("test");
//1. Insert
$q = "INSERT INTO custom_install_team (team_c_id, team_name)
			SELECT e.cf_627id, e.cf_627
			FROM (SELECT cf_627id, cf_627, team_c_id, team_name
						FROM vtiger_cf_627 LEFT  JOIN custom_install_team
						ON team_c_id = cf_627id) AS e
			WHERE  e.team_c_id IS NULL";
mysql_query($q);
//2. Update
$q = "UPDATE custom_install_team, vtiger_cf_627 SET team_name = cf_627 WHERE cf_627id = team_c_id AND cf_627 != team_name";
mysql_query($q);
//3. Show
?>
<style>
#install_team_listview_div{font-family: "Arial"; padding: 0 20px;}
#install_team_listview_div table{border-collapse: collapse; 1width: 100%;}
#install_team_listview_div th, #install_team_listview_div td{border: 1px solid gray; padding: 5px 10px;}
#install_team_listview_div tr:hover td{1background: #f2f2f2;}
#install_team_listview_div tr:nth-child(even){background: #f2f2f2;}
#install_team_listview_div th{background: green; color: white; font-weight: normal;}
#install_team_listview_div a{color: green; text-decoration: none;}
</style>
<div id='install_team_listview_div'>
	<h2>Install Teams</h2>
	<p>Please click team name to view the detail and make modificiations.</p>
	<?php ShowTeamTable(); ?>
</div>




<?php
//Functions
function ShowTeamTable()
{
	$rows = array();
	$ths = array("Team Name", "Installer Name", "Accreditation #","Electrician Name", "Electrician #", "Mobile", "Username", "Password", "Paperwork");
	//Comment out when uploading to the server
	//$link = mysql_connect("localhost", "root", "jackson");
	//mysql_select_db("test");
	$q = "SELECT team_id, team_name, 
				concat_ws(' ', i_firstname, i_lastname) as i_name, 
				i_accre_no,
				concat_ws(' ', e_firstname, e_lastname) as e_name,
				e_elec_no,
				i_mobile,
				team_username,
				team_password
			FROM custom_install_team
			ORDER BY team_name ASC";
	$result = mysql_query($q);
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
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result, MYSQL_NUM))
		array_push($teams, $row[0]);
	
	$c = count($rows);
	$p1 = $p2 = "";
	if($c != 0)
	{
		echo "<table>
					<tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		
		foreach($rows as $r)
		{
			$temp = "<tr>
						<td><a href='index.php?module=InstallTeam&action=detail&parenttab=Support&team_id=".$r[0]."'>$r[1]</a></td>
						<td>$r[2]</td>
						<td>$r[3]</td>
						<td>$r[4]</td>
						<td>$r[5]</td>
						<td>$r[6]</td>
						<td>$r[7]</td>
						<td>$r[8]</td>
						<td><a href='index.php?module=InstallPaperwork&action=index&parenttab=Support&team_id=$r[0]' target='_blank'>View</a></td>
					</tr>";
			if(in_array($r[1], $teams))
				$p1 .= $temp;
			else
				$p2 .= $temp;
		}
		if($p1 != '')
			echo "<tr><td colspan=".count($ths).">Owing paperwork team</td></tr>$p1";
		if($p2 != '')
			echo "<tr><td colspan=".count($ths).">Not owing paperwork team</td></tr>$p2";
		
		echo "</table>";
	}
}
?>