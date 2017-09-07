<?php

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	include_once("connect_db.php");
	//Basic Info
	$query = "SELECT contact_no,
									concat_ws(' ', firstname, lastname) as fullname,
									concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as address,
									cf_625 as 'installdate',
									cf_627 as 'installteam'
					FROM vtiger_crmentity, vtiger_contactscf, vtiger_contactdetails, vtiger_contactaddress
					WHERE crmid = vtiger_contactscf.contactid
					AND crmid = vtiger_contactdetails.contactid
					AND crmid = contactaddressid
					AND deleted = 0
					AND cf_612 LIKE 'Sale'
					AND crmid = $id
	
	";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1)
	{
		$basic = mysql_fetch_array($result, MYSQL_ASSOC);
		$bt = "<h3>Basic Info</h3>
					<table>
						<tr><td>CON #</td><td style='text-align: left;'>".$basic['contact_no']."</td></tr>
						<tr><td>Name</td><td style='text-align: left;'>".$basic['fullname']."</td></tr>
						<tr><td>Address</td><td style='text-align: left;'>".$basic['address']."</td></tr>
						<tr><td>Install Team</td><td style='text-align: left;'>".$basic['installteam']."</td></tr>
						<tr><td>Install Date</td><td style='text-align: left;'>".$basic['installdate']."</td></tr>
					</table>
					
				";
		//Comments
		$query = "SELECT commentcontent AS  'cc', 
						createdtime AS  'time', 
						vtiger_users.user_name AS  'name'
						FROM vtiger_modcomments, vtiger_crmentity, vtiger_users
						WHERE modcommentsid = crmid
						AND smownerid = vtiger_users.id
						AND vtiger_modcomments.related_to = $id";
						
		$result = mysql_query($query);
		$rows = array();
		while($row = mysql_fetch_array($result, MYSQL_NUM))
			array_push($rows, $row);
		if(count($rows) == 0)
			echo "<div class='commentdetail reveal-modal'>
						$bt
						Didn't find any comment.
				<a class='close-reveal-modal'>&#215;</a>
				</div>";
		else
		{
			$body = "";
			foreach($rows as $r)
				$body .= "<tr>
									<td>$r[2]</td>
									<td>$r[1]</td>
									<td style='text-align: left'>$r[0]</td>
								</tr>";
		echo "<div class='commentdetail reveal-modal'>
					$bt
					<h3>Comments</h3>
					<div style='max-height: 400px; overflow: scroll; '>
						<table>
							<tr><th>Author</th><th>Time</th><th>Content</th></tr>
							$body
						</table>
					</div>
				<a class='close-reveal-modal'>&#215;</a>
				</div>";
		}
	}
	else
	echo "<div class='commentdetail reveal-modal'>
			Error getting customer info.
			<a class='close-reveal-modal'>&#215;</a>
			</div>";
}
else
{
echo "<div class='commentdetail reveal-modal'>
Error getting customer id.
			<a class='close-reveal-modal'>&#215;</a>
			</div>";
}
?>