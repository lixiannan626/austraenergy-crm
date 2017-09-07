<script language="JavaScript" src="modules/Standardization/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="modules/Standardization/jquery.reveal.js" type="text/javascript"></script>
<link rel="stylesheet" href="modules/Standardization/reveal.css">
<link rel="stylesheet" href="modules/Standardization/style.css">

<script>
$(document).ready(function(){
	//
	$("a.comment_detail").click(function()
	{
		var self = $(this);
		var id = $(this).attr("data-id");
		var root = $(this).parent();
		
		if(root.find('div.commentdetail').length == 0)
		{
		$.post(
			"modules/Standardization/comment_detail.php",
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
$today = date('Y-m-d');
include_once("queries.php");

$navs = array();
array_push($navs, array(mysql_num_rows(mysql_query($q1)), 0, "Suburb, State, Zip included in Street"));
array_push($navs, array(mysql_num_rows(mysql_query($q2)), 1, "State not VIC/QLD/NSW"));
array_push($navs, array(mysql_num_rows(mysql_query($q3)), 2, "Suburb is Melbourne / Brisbane"));
array_push($navs, array(mysql_num_rows(mysql_query($q4)), 3, "Postal Address in Install Address"));
array_push($navs, array(mysql_num_rows(mysql_query($q5)), 4, "Address contains useless comma"));
//array_push($navs, array(mysql_num_rows(mysql_query($q6)), 5, "New and old inverter size different"));
array_push($navs, array(mysql_num_rows(mysql_query($q7)), 6, "Fully paid but not sales"));
//array_push($navs, array(mysql_num_rows(mysql_query($q8)), 7, "Stock picked up but no date"));
array_push($navs, array(mysql_num_rows(mysql_query($q9)), 8, "Installed but no stock pickup date"));
array_push($navs, array(mysql_num_rows(mysql_query($noinvoice)), 9, "Sale but no invoice"));
array_push($navs, array(mysql_num_rows(mysql_query($invoicebutwrongprogress)), 10, "Invoiced but not sale"));
array_push($navs, array(mysql_num_rows(mysql_query($teamedbutnoinvoice)), 11, "Teamed but no invoice"));
array_push($navs, array(mysql_num_rows(mysql_query($invoicedbutnoteam)), 12, "Invoiced but no team"));

?>
<div id="main">
	<table class='custom_table'>
		<tr>
			<td>
				<div id="left-panel">
					<ul>
						<li class='problem-li'><a id='p0' href='index.php?module=Standardization&action=index&parenttab=Tools'>Overview</a></li>
					<?php
					foreach($navs as $nav)
						if($nav[0] != 0)
							echo "<li class='problem-li'><a id='p".(1+$nav[1])."' href='index.php?module=Standardization&action=index&parenttab=Tools&p=$nav[1]'>$nav[2]<span class='numbercount'>$nav[0]</span></a></li>";
					?>
					</ul>
				</div>
			</td>
			<td id="main_content">
				<div id="right-panel">
					<?php 
						//include_once("modules/Standardization/test.php"); 
					
						if(isset($_GET['p']) == false)
							$page_index = 100;
						else
							$page_index = $_GET['p'];
						$pages = array(
							100=>"overview",
							0=>"address",
							1=>"state",
							2=>"suburb",
							3=>"postal",
							4=>"comma",
							5=>"inverter",
							6=>"sales",
							7=>"stock",
							8=>"installed",
							9=>"noinvoice",
							10=>"invoicednotsale",
							11=>"teamedbutnoinvoice",
							12=>"invoicedbutnoteam"
							
						);
						
						include_once("modules/Standardization/problem-".$pages[$page_index].".php");
					?>
				</div>
			</td>
		</tr>
	</table>
</div>