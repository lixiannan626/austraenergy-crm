<?php

if(isset($_GET['return_id']) == false)
	die("<p>No ID</p>");
else
{
	$link = mysql_connect("localhost", "root", "johnamy");
	mysql_select_db("goforsolar_crm");
	$id = $_GET['return_id'];
	$query = "SELECT crmid, contact_no, 
							concat_ws(' ', firstname, lastname) as 'fullname',
							concat_ws(', ', mailingstreet, mailingcity, concat(mailingstate, ' ', mailingzip)) as address,
							IF(concat_ws(' ',cf_620, cf_621) LIKE ' ', 'Unknown', concat_ws(' ',cf_620, cf_621)) as 'panelbrand',
							cf_621 as 'panelsize',
							cf_622 as 'panelnumber',
							IF(cf_623 LIKE '', 'Unknown', cf_623) as 'inverterbrand',
							IF(cf_650 LIKE '', 'Unknown', cf_650) as 'invertermodel1',
							cf_657 as 'invertermodel2',
							cf_658 as 'invertermodel3',
							IF(concat_ws(' ', cf_609, cf_608) LIKE ' ', 'Unknown', concat_ws(' ', cf_609, cf_608)) as 'mounting',
							cf_625 as 'installdate',
							cf_627 as 'installteam',
							IF(cf_659 IS NULL, 'waiting', 'gone') as 'f1',
							IF(cf_660 IS NULL, 'waiting', 'gone') as 'f2',
							IF(cf_661 IS NULL, 'waiting', 'gone') as 'f3',
							cf_662 as 'etc',
							mobile,
							homephone as 'phone'
						FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress, vtiger_contactsubdetails
							WHERE crmid = vtiger_contactdetails.contactid
							AND crmid = vtiger_contactaddress.contactaddressid
							AND crmid = vtiger_contactscf.contactid
							AND crmid = contactsubscriptionid
							AND deleted = 0
							AND cf_612 LIKE 'Sale'
							AND crmid = $id";
	$result = mysql_query($query);
	if(mysql_num_rows($result) != 1)
		die("<p>Wrong ID, or not a sale.</p>");
	else
	{
		$r = mysql_fetch_array($result, MYSQL_ASSOC);
		if($r['installteam'] == 'Unknown')
			die("<p>Install team unspecified.</p>");
			
		//Start Construct output
		/* foreach($r as $k=>$v)
			echo "$k=>$v<br>";  */
		
		//Determine Inverter(s)
		if(Dry($r['inverterbrand']) == 'aps micro')
			$invertertr = "<tr class='re'><td class='inout'>Out</td><td class='item'>".$r['inverterbrand']."</td><td class='qty'>".$r['panelnumber']."</td></tr>";
		else
			$invertertr = "<tr class='re'><td class='inout'>Out</td><td class='item'>".$r['inverterbrand']." ".$r['invertermodel1']."</td><td class='qty'>1</td></tr>";
		if($r['invertermodel2'] != "" && $r['invertermodel2'] != "None")
			$invertertr .= "<tr class='re'><td class='inout'>Out</td><td class='item'>".$r['inverterbrand']." ".$r['invertermodel2']."</td><td class='qty'>1</td></tr>";
		if($r['invertermodel3'] != "" && $r['invertermodel3'] != "None")
			$invertertr .= "<tr class='re'><td class='inout'>Out</td><td class='item'>".$r['inverterbrand']." ".$r['invertermodel3']."</td><td class='qty'>1</td></tr>";
		//Determine Racking
		$mountingsize = number_format(GetSize($r['panelsize'])*$r['panelnumber']/1000,2)."KW";
		
		//Output
		$table = "
		<div id='stock_form'>
			<img src='bwlogo.jpg' id='logo' height='54' width='140'/>
			<div id='flexible_part'>
			<h1> Go For Solar Stock Log</h1>
			<p class='right'>Contact No.: <span class='data strong'>".$r['contact_no']."</span></p>
			<p class='right'>Print Time: <span class='data'>".date("Y-m-d H:i")."</span></p>
			
			<h2>Install Information</h2>
			<table id='install_info'>
				<tr><td class='right'>Customer Name</td><td>".$r['fullname']."</td><tr>
				<tr><td class='right'>Install Address</td><td>".$r['address']."</td><tr>
				<tr><td class='right'>Phone/Mobile</td><td>".$r['mobile'].", ".$r['phone']."</td><tr>
				<tr><td class='right'>Install Team</td><td>".$r['installteam']."</td><tr>
				<tr><td class='right'>Install Date</td><td>".$r['installdate']."</td><tr>
			</table>
			
			<h2>Stock Movement Log</h2>
			<table id='stock_info'>
				<tr><th class='inout'>Stock In/Out</th><th class='item'>Item</th><th class='qty'>Qty</th></tr>
				<tr class='re'><td class='inout'>Out</td><td class='item'>".$r['panelbrand']."</td><td class='qty'>".$r['panelnumber']."</td></tr>
				$invertertr
				<tr class='re'><td class='inout'>Out</td><td class='item'>Mounting Kit ".$r['mounting']." $mountingsize</td><td  class='qty'>1</td></tr>
			</table>
			<a class='add hide'>Add item</a>
		</div>
		<div id='fixed_part'>
			<div id='signature'>
				<p><span class='label'>Release Date</span><span class='field'></span></p>
				<p><span class='label'>Release Person Signature</span><span class='field'></span></p>
				
				<p><span class='label'>Pickup Person/Company (Print)</span><span class='field'></span></p>
				<p><span class='label'>Pickup Person Signature</span><span class='field'></span></p>
				
			</div>
			<div id='company_info'>
				Go For Solar Pty Ltd <span class='large'>&#8729;</span> Phone: 1300 308 074 <span class='large'>&#8729;</span> ABN: 86 151 980 068
			</div>
		</div>
		</div>
		
		";
	}
	
}

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
?>










<!DOCTYPE HTML>

<html>
<head>
<title><?php echo $r['contact_no'];?> Stock Log
</title>

<style>
@media screen
{
div#stock_form{border: 1px solid black;margin: 0 auto;}
}
@media screen, print{
*{font-family: "Arial"; margin: 0; padding: 0;}
#logo {position: absolute; top: 6mm;}
div#stock_form{width: 200mm; 1background:#f2f2f2; height: 290mm; padding: 10px;}
div#flexible_part{height: 232mm; 1background: url('bwlogo.jpg') no-repeat top right;}
h1{text-align: center; padding-top: 7mm; margin-bottom: 10mm;}
h2{padding: 4px;}
.right{text-align: right;}
span.data{display: inline-block; padding-left: 10px; width: 140px; border-bottom: 1px solid black; text-align: left;}
p.right{padding: 5px 0;}

#install_info,#stock_info{border-collapse:collapse; width:100%; margin-bottom: 40px;}
#install_info td, #stock_info td, #stock_info th{border: 1px solid black; padding: 3px 3mm;}
td.right{width: 35mm; 1text-align: right; padding: 1.3mm 3mm; font-weight: 700;}
td.qty{text-align: center;}
#stock_info .item{width: 80%; text-align: left; height: 22px;}
#stock_info .inout{width: 10%; text-align: center;}

#signature{margin-bottom: 5mm;}
#signature p{padding: 3mm 0;}
#signature .label{display: inline-block; width: 70mm; text-align: right; padding-right:4mm;}
#signature .field{display: inline-block; width: 80mm; border-bottom: 1px solid black;}
.strong{font-weight: 700;}

#company_info{padding: 1mm; text-align: center; border-top: 1px solid black;}
}

div.intro{display: block; width: 200mm; margin:20px auto; border: 1px solid black; padding: 10px;}
td.hide{cursor: pointer; color: white; background: red; font-weight: 700;}
a.add{color: white; cursor:pointer; display:inline-block; background: #06c; padding: 7px;}
div.add_div{padding: 10px; border: none; background:#f2f2f2; z-index: 1000;}
div.add_div p{padding: 3px 0;}
div.add_div p label{display: inline-block; width:10mm; padding-right: 3mm; text-align:right;}
div.add_div p a{cursor:pointer; display: inline-block; margin-right: 5px; background: #06c; padding: 5px; color: white;}
@media print
{
	div.hide{display: none;}
	td.hide{display: none;}
	a.hide{display: none;}
}
</style>
<script language="JavaScript" src="jquery-1.7.1.min.js" type="text/javascript"></script>
<script>

$(document).ready(function(){
	$("#stock_info tr.re").append("<td class='hide remove' title='remove this item'>x</td>");
	
	$('td.remove').live("click", function(){
		$(this).parent().remove();
	});
	
	$('a.add').click(function(){
		if($('div.add_div').length == 0)
			$(this).after("<div class='hide add_div'><p><label>Stock In/Out: </label><input type='text' class='item_inout' value='Out'/></p><p><label>Item: </label><input type='text' class='item_name' /></p><p><label>Qty: </label><input type='text' class='item_qty'></p><p><a class='confirm'>Add</a><a class='cancel'>Cancel</a></p></div>");
	});
	$('a.confirm').live("click", function(){
		var root = $(this).parent().parent();
		var item = root.find('input.item_name').val();
		var qty = root.find('input.item_qty').val();
		var inout = root.find('input.item_inout').val();
		$('table#stock_info').append("<tr class='re'><td class='inout'>"+inout+"</td><td class='item'>"+item+"</td><td  class='qty'>"+qty+"</td><td class='hide remove' title='remove this item'>x</td></tr>");
		root.remove();
	});
	$('a.cancel').live("click", function(){
		$(this).parent().parent().remove();
	});
});
</script>
</head>
<body>
<?php echo $table; ?>

<div class="hide intro">
	<p>This stock pickup form has been tested in Chrome, Firefox and IE9. Content in this block will not be printed.</p>
	<p>Use "<span style=' color: white; background: red; display: inline-block; padding: 3px;'>x</span>" to remove item.</p>
	<p>Use "<span style='background: #06c; color: white;display: inline-block; padding: 3px;'>Add item</span>" to add item on the fly, e.g. Splice, mid clamps.</p>
	<p>Print out 2 copies, sign both, give one copy to pickup person, one as own reference.</p>
	<p>If using Chrome, uncheck "Headers and footers" option while printing.</p>
</div>
</body>
</html>