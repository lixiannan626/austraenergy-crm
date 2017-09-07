<?php	
$paath = "modules/StockManager/";	
include_once($paath."stock-functions.php");
?>
<script language="JavaScript" src="modules/StockManager/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/StockManager/jquery.reveal.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/StockManager/main.js" type="text/javascript"></script>
<link rel="stylesheet" href="modules/StockManager/reveal.css">
<link rel="stylesheet" href="modules/StockManager/style.css">

<?php
$today = date("Y-m-d");
$q1 = "SELECT crmid
							FROM vtiger_crmentity, vtiger_contactscf
							WHERE crmid = vtiger_contactscf.contactid
							AND deleted = 0
							AND cf_612 LIKE 'Sale'
							AND (cf_659 IS NULL OR cf_660 IS NULL OR cf_661 IS NULL)";
$c1 = mysql_num_rows(mysql_query($q1));
?>
<div id="main">
	<table class='custom_table'>
		<tr>
			<td id="left-td">
				<div id="left-panel">
					<ul>
						<li class='problem-li'><a id='i1' href='index.php?module=StockManager&action=index&parenttab=Support'>Overview</a></li>
						<li class='problem-li'><a id='i2' href='index.php?module=StockManager&action=index&parenttab=Support&i=i1'>Reserved stock <span class="numbercount"><?php echo $c1;?></span></a></li>
						<li class='problem-li'><a id='i3' href='index.php?module=StockManager&action=index&parenttab=Support&i=i2'>Upcoming installation <span class="numbercount"><?php echo $c1;?></span></a></li>
					<!--	<li class='problem-li'><a>More to come</a></li> -->
					</ul>
				</div>
			</td>
			<td id="main_content">
				<div id="right-panel">
					<?php 
						//include_once("modules/Standardization/test.php"); 
						if(isset($_GET['i']) == false)
						{
							include_once($paath."stock-overview.php");
						}
						else if($_GET['i'] == 'i1')
						{
							include_once($paath."stock-reserve.php");
						}
						else if($_GET['i'] == 'i2')
						{
							include_once($paath."stock-upcoming.php");
						}
					?>
				</div>
			</td>
		</tr>
	</table>
</div>