<script type="text/javascript">
document.getElementById("p13").className += "selected";
</script>
<h3>Invoiced, but no team</h3>
<p class="exp">Suggestion: either add "cancel" to invoice number or assign team.</p>
<?php
	include_once("queries.php");
	include_once("helpers.showtables.php");
	$records = getData($invoicedbutnoteam);
	showTables($records, array("CON #", "Progress", "Invoice #", "Team"), array("sales_progress", "invoice_number", "install_team"), true);
?>