<script type="text/javascript">
document.getElementById("p10").className += "selected";
</script>
<h3>Sale, but no invoice</h3>
<p class="exp">Suggestion: either change the sales progress or add invoice number.</p>
<?php
	include_once("queries.php");
	include_once("helpers.showtables.php");
	$records = getData($noinvoice);
	showTables($records, array("CON #", "Progress", "Invoice #"), array("sales_progress", "invoice_number"), true);
?>