<?php	
$paath = "modules/InstallArrangement/";	
include_once($paath."install-functions.php");
?>
<script language="JavaScript" src="modules/InstallArrangement/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/InstallArrangement/jquery.reveal.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/InstallArrangement/jquery.sort.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/InstallArrangement/sortth.js" type="text/javascript"></script>
<link rel="stylesheet" href="modules/InstallArrangement/reveal.css">
<link rel="stylesheet" href="modules/InstallArrangement/style.css">
<script>
$(document).ready(function(){
	//
	$("div.qld").hide();
	
	$("a#vicjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		$("div.commentdetail").remove();
		$("div.reveal-modal-bg").remove();
		
		$("div.vic").show();
		$("div.qld").hide();
	});
	
	$("a#qldjob").click(function()
	{
		$("#vicqld a").attr("class", "");
		$(this).attr("class", "selected");
		$("div.commentdetail").remove();
		$("div.reveal-modal-bg").remove();
		$("div.qld").show();
		$("div.vic").hide();
	});
	
	//
	$("a.comment_detail").click(function()
	{
		var self = $(this);
		var id = $(this).attr("data-id");
		var root = $(this).parent();
		
		if(root.find('div.commentdetail').length == 0)
		{
		$.post(
			"modules/InstallArrangement/comment_detail.php",
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
	
});
</script>
<?php
$today = date("Y-m-d");
$q1 = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf
				WHERE  crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND (cf_625 LIKE '' OR cf_625 IS NULL OR cf_625 > '$today')";

$q2 = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf
				WHERE  crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND cf_625 <= '$today'
				AND cf_646 = 0";

$q3 = "SELECT crmid
				FROM vtiger_crmentity, vtiger_contactscf
				WHERE  crmid = vtiger_contactscf.contactid
				AND deleted = 0
				AND cf_612 LIKE 'Sale'
				AND cf_625 <= '$today'
				AND cf_646 = 1";
$c1 = mysql_num_rows(mysql_query($q1));
$c2 = mysql_num_rows(mysql_query($q2));
$c3 = mysql_num_rows(mysql_query($q3));
?>
<div id="main">
	<table class='custom_table'>
		<tr>
			<td>
				<div id="left-panel">
					<ul>
						<li class='problem-li'><a id='i1' href='index.php?module=InstallArrangement&action=index&parenttab=Support'>Overview</a></li>
						<li class='problem-li'><a id='i2' href='index.php?module=InstallArrangement&action=index&parenttab=Support&i=i1'>Assign Jobs <span class="numbercount"><?php echo $c1; ?></span></a></li>
						<li class='problem-li'><a id='i3' href='index.php?module=InstallArrangement&action=index&parenttab=Support&i=i2'>Confirm Jobs <span class="numbercount"><?php echo $c2; ?></span></a></li>
						<li class='problem-li'><a id='i4' href='index.php?module=InstallArrangement&action=index&parenttab=Support&i=i3'>History Jobs <span class="numbercount"><?php echo $c3; ?></span></a></li>
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
							include_once($paath."install-overview.php");
						}
						else if($_GET['i'] == 'i1')
						{
							include_once($paath."install-assign.php");
						}
						else if($_GET['i'] == 'i2')
						{
							include_once($paath."install-confirm.php");
						}
						else if($_GET['i'] == 'i3')
						{
							include_once($paath."install-complete.php");
						}
					?>
				</div>
			</td>
		</tr>
	</table>
</div>