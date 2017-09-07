<script type="text/javascript">
document.getElementById("p11").className += "selected";
</script>
<h3>Invoiced, but not sale</h3>
<p class="exp">Suggestion: either change the sales progress or add "cancel" to invoice number , e.g. 00000248(cancel).</p>
<?php
	include_once("queries.php");
	include_once("helpers.showtables.php");
	$records = getData($invoicebutwrongprogress);
	showTables($records, array("CON #", "Progress", "Invoice #"), array("sales_progress", "invoice_number"), true);
?>