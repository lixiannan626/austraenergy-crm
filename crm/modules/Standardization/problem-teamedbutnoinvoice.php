<script type="text/javascript">
document.getElementById("p12").className += "selected";
</script>
<h3>Teamed, but no invoice</h3>
<p class="exp">Suggestion: either remove the team (change to "Unknown") or input invoice.</p>
<?php
	include_once("queries.php");
	include_once("helpers.showtables.php");
	$records = getData($teamedbutnoinvoice);
	showTables($records, array("CON #", "Progress", "Invoice #", "Team"), array("sales_progress", "invoice_number", "install_team"), true);
?>