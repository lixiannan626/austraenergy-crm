<style>
.setting-wrap {width: 97%; padding: 5px 1%; margin: 5px auto; background: #f2f2f2;}
.distributor-list {border-collapse: collapse; }
.distributor-list th, .distributor-list td {border: 1px solid gray; padding: 3px 5px;}
</style>
<script src="modules/SimpleSettings/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('.change_approval').click(function(event){
		var thi = $(this);
		$.post(
			"modules/SimpleSettings/ajax.php",
			{action: "change-approval", id: $(this).attr("data-id"), preCache: Math.random()},
			function(res) {
				if(res === "OK") {
					alert("updated");
				} else {
					alert("failed");
				}
			}
		);
	});
})
</script>
<div class='setting-wrap'>
<h3 class='setting-header'>Distributors that need pre-approval</h3>
<div class='setting-content'>
<?php echo approval_list(); ?>
</div>
</div>





<?php
/*  */

//Database connected

function approval_list()
{
	//INSERT
	$insert = "INSERT INTO distributor_approval (distributor) 
	SELECT e.cf_668
	FROM (SELECT da_id, cf_668 FROM vtiger_cf_668 LEFT JOIN distributor_approval ON distributor = cf_668) AS e
	WHERE e.da_id IS NULL AND e.cf_668 NOT LIKE '--'";
	mysql_query($insert);

	//DELETE
	$delete = "DELETE FROM distributor_approval WHERE distributor NOT IN (SELECT cf_668 FROM vtiger_cf_668)";
	mysql_query($delete);

	//SELECT
	$select = "SELECT da_id, distributor, IF(need_approval=0, '', 'checked') AS flag FROM distributor_approval";
	$result = mysql_query($select);
	if(!$result)
		die("No distributor found");
	$rows = array();
	while($row = mysql_fetch_assoc($result))
		array_push($rows, $row);

	//SHOW
	$table = "<table class='distributor-list'><tr><th>Distributor</th><th>Need Approval</th></tr>";
	foreach($rows as $row)
		$table .= "<tr>
		<td>".$row['distributor']."</td>
		<td><input type='checkbox' class='change_approval' ".$row['flag']." data-id='".$row['da_id']."'/></td>
		</tr>";
	$table .= "</table>";
	return $table;
}
?>

