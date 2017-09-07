
<script src="modules/Dashboard/custom_files/jquery.min.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/show.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/jquery.sort.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/sortth.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="modules/Dashboard/custom_files/default.css" />
<style type="text/css">
		*{font-family: Arial;}
		1th,td {border: 1px solid gray; text-align:center;padding: 3px 1px;}
		table{border-collapse:collapse;}
		a.togglelink{margin-top: 5px;display:inline-block; vertical-align:middle;color:#248FB2;text-decoration:none;}
		a img{border:none;}
		span{text-align: center; padding: 10px 0; vertical-align: middle; display: inline-block;margin: 0;}
		span span{margin:0;float:left;}
		.color1{background-color: #568F53;}
		.color2{background-color: #F46B25;}
		.color3{background-color: gray;}
		.color4{background-color: #06c;}
		.square{width: 10px; height: 10px; margin: 0; padding: 0; margin: 0 3px 3px;}
		.charts{1margin: 0 10px; color: white; font-weight: 700; 1width: 400px;}
		table.toggle th{background-color: gray; color: white; padding: 3px; border-right: 1px solid white;}
		table.toggle td{padding: 3px; border: 1px solid gray;}
		.general_col{max-width: 150px;}
		.width0{visibility: hidden; width: 0;}
		.small_charts {width: 340px; font-size:12px; }
		.small_charts span {padding: 7px 0; color: white;margin: 0;}
		.small_charts{margin: 0; padding: 0;float: left;1width:300px;}
		.chart_cell{1width:301px;}
		.chart_cell span{float: left;}
		.doc_table th, .doc_table td{border: none; text-align: center;}
		.doc_table a{color: #06c;}
		.number{color: brown;}
		#sum1 a{color: #06c;}
		#sum1 table th{padding: 5px 7px;background: gray; color: white;border-right: 1px solid white;}
		#sum1 table td{padding: 5px;border-bottom: 1px dotted gray; border-right: 1px dotted gray; }
		#sum1 td:last-child{border-right: none;}
		p.Download span.explanation {display: inline-block;padding: 0;margin: 0;color: gray;font-size: 0.8em;}
		
		.doc_table{font-size: 1.2em;}
		#report_wrapper{font-size: 1.2em;}
		h1,h2,h3,h4,h5,h6{margin: 0; padding: 5px 0;}
		h3{margin-top: 20px; border-bottom: none; padding: 0;}
</style>
<div id="nav_wrapper">
<ul class="dashboard_nav">
	<li><a href="index.php?module=Dashboard&action=index&parenttab=Analytics">Sales Summary</a></li>
	<li><a href="index.php?module=Dashboard&action=index2&parenttab=Analytics">Sales Detail</a></li>
	<li><a href="index.php?module=Dashboard&action=index3&parenttab=Analytics">Leads Summary</a></li>
	<li><a href="index.php?module=Dashboard&action=index4&parenttab=Analytics">Leads Detail</a></li>
	<li><a href="index.php?module=Dashboard&action=index5&parenttab=Analytics">Progress Report</a></li>
	<li><a href="index.php?module=Dashboard&action=index6&parenttab=Analytics">Stock required</a></li>
	<li class="current"><a href="index.php?module=Dashboard&action=index7&parenttab=Analytics">Documents required</a></li>
</ul>
</div>
<div id="report_wrapper">

<?php
//Today's Date
	$today = date("Y-m-d");
	$excelName = date("y_m_d_h_i_s").".xls";


	//Variables
	$rows = null;
	$teams = null;
	$qldrows = null;
	$nonqldrows = null;

		//Get all installation team names
		$result = mysql_query("SELECT cf_627 FROM vtiger_cf_627");
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($rown = mysql_fetch_array($result, MYSQL_BOTH)) 
		{
			$team = new teamwork();
			$team->teamname = $rown[0];
			$teams[$team->teamname] = $team;
		}
		
		
		$whereclause0 = "SELECT firstname, lastname, mailingstreet, mailingcity, mailingstate, cf_615, cf_625, contactaddressid, cf_616, cf_630, cf_629, cf_627, cf_635, cf_628 
										FROM vtiger_contactdetails,vtiger_contactaddress,vtiger_contactscf, vtiger_crmentity 
										WHERE vtiger_contactdetails.contactid = vtiger_contactscf.contactid 
										AND vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid 
										AND vtiger_contactdetails.contactid = vtiger_crmentity.crmid 
										AND vtiger_crmentity.deleted = 0 
										AND cf_625 < '$today'
										AND cf_612 LIKE 'Sale'
										AND cf_646=1
										ORDER BY cf_625 ASC";
	
		//
		//row[0-9]		
		//row[11],cf_630: Document From Installer Arrived		0: No 1: Yes	Updated
		//row[13],cf_627: Installation Team														Updated
		//row[15],cf_640: REC granted date						NULL or 2011-05-22
		
		//cf_625 Installation Date
		//cf_615 Invoice Number
		//
		
		$th = '<th>First Name</th><th>Last Name</th><th>Mailing Street</th><th>Mailing City</th>'
		.'<th>Mailing State</th><th>Inv Number</th><th>installation date</th><th>Action</th>';
				
		$result = mysql_query($whereclause0);
		if (!$result) 
		{
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_array($result,  MYSQL_NUM)) 
		{
			$row[7] = '<a href="http://110.142.207.187/crm/index.php?module=Contacts&action=DetailView&record='.$row[7].'" target="_blank">View Details</a>';
			$rows[count($rows)] = $row;
		}
		
		//Variables Declaration
		$installed = null;
		$paper_arrived = 0;
		$paper_missing = null;
		
		
		//Generate Groups
		for($i = 0; $i < count($rows); $i++)
		{
			$temp = $rows[$i];
			//9 is the index for cf_630 0 indicates not arrived
			if($temp[9] == 0)
			{
				$paper_missing[count($paper_missing)] = $temp;
				$teams[$temp[11]]->add_missing($temp);
			}
			else
			{
				$paper_arrived ++;
				$teams[$temp[11]]->add_done($temp);
			}
		}
		
		foreach($teams as &$team)
		{
			$team->calculate();
		}
		//Output
		echo "<div id='explanation'>
			<p>To appear below, it must:
					<ul>
						<li>be a sale</li>
						<li>have the INSTALLED box checked</li>
						<li>have installation date earlier than today.</li>
					</ul>
			</p>
		</div>";
		//-----------------------------------------SUMMARY--------------------------------------------//
		$teamsum = sorteam($teams);
		$teamsum2 = sorteam2($teams);
		echo '<div id="sum1">';
			//echo '<div class="DownloadWrapper"><p class="Download"><a href="modules/Dashboard/'.$excelName.'">Download list<br><span class="explanation">xls file, open in Excel</span></a></p></div>';
			echo '<h2>Docs expected from installers (Before '.$today.')</h2>';
			echo '<table class="doc_table">';
			echo '<thead><tr><th>Team Name</th><th>Status</th><th>Docs Needed</th><th>Docs Arrived</th></tr></thead>';
			foreach($teamsum as &$team)
			{
				if($team->paper2 != 0)
				{
					echo '<tr class="doc_desc">';
					echo '<td><a href="#'.$team->teamname.'">'.$team->teamname.'</a></td>';
					echo '<td class="chart_cell">';
					echo '<span class="small_charts">';
					if($team->paper1_percentage != 0)
						echo '<span class="color1 width'.$team->paper1_percentage.'" style="width: '.($team->paper1_percentage*3).'px;">'.$team->paper1_percentage.'%</span>';
					if($team->paper2_percentage != 0)
						echo '<span class="color2 width'.$team->paper2_percentage.'" style="width: '.($team->paper2_percentage*3).'px;">'.$team->paper2_percentage.'%</span>';
					echo '</span></td>';
					echo '<td class="number">'.$team->paper2.'</td>';
					echo '<td class="number">'.$team->paper1.'</td>';
					echo '</tr>';
				}
			}
			
			
			echo '<tr><th></th><th></th><th>'.count($paper_missing).'</th><th>'.$paper_arrived.'</th></tr>';
			
			echo '</table>';
		echo '</div>';

		
		//----------------------------------------------Detail------------------------------------------------------//
		//Do not display the all done teams
		foreach($teamsum as &$team)
		{
			if($team->paper2 != 0)
			{
				echo '<div id="'.$team->teamname.'">';
					echo '<h3>'.$team->teamname.' ('.($team->paper1+$team->paper2).' jobs)</h3>';
					echo '<div>';
						echo '<span class="charts"><span class="color1 width'.$team->paper1_percentage.'" style="width: '.($team->paper1_percentage*4).'px;">'.$team->paper1_percentage.'%</span>
						<span class="color2 width'.$team->paper2_percentage.'" style="width: '.($team->paper2_percentage*4).'px;">'.$team->paper2_percentage.'%</span></span>';
					echo '</div>';
					echo '<div>';
						echo '<span class="color1 square"></span>Paperwork Arrived ('.$team->paper1.'</strong> jobs)<br>';
						echo '<span class="color2 square"></span>Paperwork Not Arrived (<strong>'.$team->paper2.'</strong> jobs)<br>';
						sqltotable($team->paper_missing,$th,8);
					echo '</div>';
				echo '</div>';
			}
		}
		
		//---------------------------------------------Write to File---------------------------------------------------//
		/** PHPExcel */
	/*  	require_once 'modules/Dashboard/custom_files/Classes/PHPExcel.php';


		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		
		// Add some data
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"First Name");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"Last Name");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"Mailing Street");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Mailing Suburb");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,"Mailing State");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,"Invoice Number");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1,"Installation Date");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,"Panel Number");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,1,"Inverter Size");
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,1,"Action");
		for($i = 0; $i < count($paper_missing); $i++)
		{
			$missing = $paper_missing[$i];
			for($j = 0; $j < 9; $j++)
			{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, ($i+2), $missing[$j]);
			}
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,($i+2),"Detail");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9,($i+2))->getHyperlink()->setUrl(getlink($missing[9]));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,($i+2),str_replace("&#039;","'",$missing[13]));
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(16);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(23);
		
		// Write to a local file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save(str_replace('index.php', $excelName, __FILE__));	 */


		
		
//$table: the table extracted from database
//$th: header of the table to be displayed
//$column_number: the number of columns to be displayed
function sqltotable($table, $th, $column_number)
{
	if(count($table) > 0)
	{
		//echo '<a href="#" class="toggleLink">Show Detail</a>';
		echo "<h5>Details of jobs lacking paperwork</h5>";
		echo '<table class="toggle">';
		echo '<tr><th>#</th>'.$th.'</tr>';
		for($i = 0; $i < count($table); $i++)
		{
			$row = $table[$i];
			echo '<tr><td>'.($i+1).'</td>';
			for($j = 0; $j < min($column_number,count($row)); $j++)
			{
				echo '<td class="general_col general_col'.($j+1).'">'.$row[$j].'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}
function sorteam($teams)
{
	$teamss = null;
	foreach($teams as &$team)
	{
		$teamss[count($teamss)] = $team;
	}
	for($i = 0; $i < count($teamss); $i++)
	{
		for($j = $i+1; $j < count($teamss); $j++)
		{
			if($teamss[$i]->paper2 < $teamss[$j]->paper2)
			{
				$temp = $teamss[$i];
				$teamss[$i] = $teamss[$j];
				$teamss[$j] = $temp;
			}
			if($teamss[$i]->paper2 == $teamss[$j]->paper2 && $teamss[$i]->paper2_percentage < $teamss[$j]->paper2_percentage)
			{
				$temp = $teamss[$i];
				$teamss[$i] = $teamss[$j];
				$teamss[$j] = $temp;
			}
		}
	}
	return $teamss;
}
function sorteam2($teams)
{
	$teamss = null;
	foreach($teams as &$team)
	{
		$teamss[count($teamss)] = $team;
	}
	for($i = 0; $i < count($teamss); $i++)
	{
		for($j = $i+1; $j < count($teamss); $j++)
		{
			if($teamss[$i]->paper2_percentage < $teamss[$j]->paper2_percentage)
			{
				$temp = $teamss[$i];
				$teamss[$i] = $teamss[$j];
				$teamss[$j] = $temp;
			}
			if($teamss[$i]->paper2_percentage == $teamss[$j]->paper2_percentage && $teamss[$i]->paper2 < $teamss[$j]->paper2)
			{
				$temp = $teamss[$i];
				$teamss[$i] = $teamss[$j];
				$teamss[$j] = $temp;
			}
		}
	}
	return $teamss;
}
function getlink($slink)
{
	return str_replace('<a href="','',str_replace('" target="_blank">View Details</a>','',$slink));
}
class teamwork
{
	var $teamname;
	var $teamjob = null;
	var $paper_done = null;
	var $paper_missing = null;
	var $paper1 = 0;
	var $paper2 = 0;
	var $paper1_percentage = 0;
	var $paper2_percentage = 0;
	function add($job)
	{
		$this->teamjob[count($this->teamjob)] = $job;
	}
	function add_done($job)
	{
		$this->paper_done[count($this->paper_done)] = $job;
	}
	function add_missing($job)
	{
		$this->paper_missing[count($this->paper_missing)] = $job;
	}
	function calculate()
	{
		$this->paper1 = count($this->paper_done);
		$this->paper2 = count($this->paper_missing);
		
		if(($this->paper1+$this->paper2) > 0)
		{
			$this->paper1_percentage = round($this->paper1*100/($this->paper1+$this->paper2),0);
			$this->paper2_percentage = round($this->paper2*100/($this->paper1+$this->paper2),0);
			if($this->paper1_percentage + $this->papger2_percentage != 100)
				$this->paper2_percentage = 100 - $this->paper1_percentage;
		}
	}
}

?>

</div>