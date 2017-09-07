<link rel="stylesheet" type="text/css" href="modules/QuotePrice/qp/stylesheets/screen.css">
<link rel="stylesheet" type="text/css" href="modules/QuotePrice/qp/stylesheets/jquery-ui-1.10.1.custom.min.css">
<link rel="stylesheet" type="text/css" href="modules/QuotePrice/qp/stylesheets/jquery.jscrollpane.css">
    <script src="modules/QuotePrice/js/jquery-1.8.3.min.js"></script>
    <script src="modules/QuotePrice/js/jquery-ui-1.10.1.custom.min.js"></script>
<div id='QuoteWrapper'>


<?php
    include_once('quote_config.php');
	//Variable
	$page_name = "quote_main";
	if(isset($_GET['page']))
		$page_name = $_GET['page'];
		
	//Nav
	include_once('quote_nav.php');
	
	//Body
	if($page_name != "" && file_exists("modules/QuotePrice/$page_name.php"))
		include_once("$page_name.php");

?>


</div>