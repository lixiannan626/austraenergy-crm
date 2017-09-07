<?php
 /*
  *	Scripts and CSS
  */
?>
<script src="modules/Dashboard/custom_files/jquery.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/show.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/jquery.sort.js" type="text/javascript"></script>
<script src="modules/Dashboard/custom_files/sortth.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="modules/Dashboard/custom_files/default.css" />
<?php
/**
 *	Navigation files
 */
 function show_nav($current = '')
 {
	//All options
	$navs = array(
		"index"=>"Progress Report", 
		"index5"=>"Sales Summary", 
		"index2"=>"Sales Detail", 
		"index3"=>"Leads Summary", 
		"index4"=>"Leads Detail", 
		"index9"=>"Balance required");
	
	$output = "<div id='nav_wrapper'><ul class='dashboard_nav'>";
	foreach($navs as $key=>$val)
		$output .= "<li ".($val == $current ? "class='current'" : '')."><a href='index.php?module=Dashboard&parenttab=Analytics&action=$key'>$val</a></li>";
	$output .= "</ul></div>";
	
	echo $output;
 }
?>