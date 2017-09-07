<?php

function GetSize($s)
{
	$o = strtolower($s);
	$o = str_replace("w", "", $o);
	$o = trim($o);
	return intval($o);
}
function Dry($s)
{
	$o = trim($s);
	return strtolower($o);
}
function Contains($needle, $stack)
{
	$needle = Dry($needle);
	$stack = Dry($stack);
}
function GetClass($s)
{
	$o = trim($s);
	$o = str_replace(".","_",$o);
	$o = str_replace(" ","_",$o);
	return $o;
}

/*
	$a: array of data
	$divid: id of the div container
	$h2: the header
	$exp: the explanation

*/
function ShowTable($array, $h2, $ths, $class)
{
	echo "<div class='job_div $class'>
				<h3>$h2 (".count($array)." records)</h3>
				";
	if(count($array) !=0)
	{
		$c = 0;
		echo "<table>
					<tr>";
		foreach($ths as $th)
			echo "<th>$th</th>";
		echo "</tr>";
		foreach($array as $a)
		{
			$pc = "";
			$ic = "";
			$mc = "";
			$c2 = "";
			$c3 = "";
			if($a['f1'] == 'waiting')
				$pc = GetClass($a['panelbrand']);
			if($a['f2'] == 'waiting')
			{
				if(Dry($a['inverterbrand']) == 'aps micro')
					$ic = GetClass($a['inverterbrand']);
				else
				{
					$ic = GetClass($a['inverterbrand'].' '.$a['invertermodel1']);
					if($a['invertermodel2'] != "" && $a['invertermodel2'] != "None")
					{
						$ic = $ic." ".GetClass($a['inverterbrand'].' '.$a['invertermodel2']);
						$c2 = "<br>".$a['inverterbrand'].' '.$a['invertermodel2'];
					}
					if($a['invertermodel3'] != "" && $a['invertermodel3'] != "None")
					{
						$ic = $ic." ".GetClass($a['inverterbrand'].' '.$a['invertermodel3']);
						$c3 = "<br>".$a['inverterbrand'].' '.$a['invertermodel3'];
					}
				}
			}
			if($a['f3'] == 'waiting')
				$mc = GetClass($a['mounting']);
			$c++;
			echo "<tr class='$pc $ic $mc data'><td>$c</td>
						<td class='center'><a href='index.php?module=Contacts&parenttab=Support&action=DetailView&record=".$a['crmid']."' target='_blank'>".$a['contact_no']."</a></td>
						<td>".ucwords(strtolower($a['fullname']))."<br>".ucwords(strtolower($a['address']))."</td>
						<td><span class='".$a['f1']."'>".$a['panelnumber']." &times; ".$a['panelbrand']."</span></td>
						<td><span class='".$a['f2']."'>".$a['inverterbrand'].' '.$a['invertermodel1']."$c2 $c3</span></td>
						<td><span class='".$a['f3']."'>".$a['mounting']."</span></td>
						<td>".$a['installdate']."</td>
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