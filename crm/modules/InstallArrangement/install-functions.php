<?php


/*
	$a: array of data
	$divid: id of the div container
	$h2: the header
	$exp: the explanation

*/
function ShowTable($array, $divid, $h2, $exp, $divclass)
{
	$ths = array("#", "CON #", "Client Name", "Address", "Install Team", "Install Date", "Comments");
	echo "<div id='$divid' class='job_div $divclass'>
				<h2>$h2 (".count($array).")</h2>
				";
	if(count($array) !=0)
	{
		//echo "<p class='normal'>$exp</p>";
		$c = 0;
		echo "<table>
					<tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($array as $a)
		{
			echo "<tr>
						<td class='center'>".++$c."</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."</td>
						<td>".ucwords(strtolower($a['address']))."</td>
						<td class='center'>".$a['installteam']."</td>
						<td class='center'>".$a['installdate']."</td>
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
?>