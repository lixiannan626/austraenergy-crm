<?php
echo "<!-- $current_user->user_name -->";
//Determine the users to enjoy the ban of edit function.
//Change the method of considering a role is eligible to edit or not
$query = "SELECT roleid FROM vtiger_user2role WHERE userid = $current_user->id";
$row = mysql_fetch_array(mysql_query($query));
$role = $row[0];
if($role != "H2" && $role != "H10")
{
	?>
	<script type="text/javascript">
	//Forbid editing
		var elems = document.getElementsByTagName('td');
		var a = 0;
		var b = 0;
    for (var i in elems)
    {
        if((" "+elems[i].className+" ").indexOf("dvtCellInfo") > -1)
        {
			var idd = elems[i].getAttribute("id");
			//Determine the fields to be editable by Sales
			if(idd != "mouseArea_Sales Progress" && idd !="mouseArea_ModCommentsDetailViewBlockCommentWidget" && idd != "mouseArea_Sales Follow Up Date")
			{	
				elems[i].setAttribute("onmouseover","");
			}
		}
    }
		
	//alert("Test");
	//alert("a="+a+",b="+b);
	
	//Enable certain fields
	
	</script>
	<?php
}
//2012-03-06 John requested that Payment Method can only be modified by Amy
//2012-10-01 John requested that Payment Method can also be modified by Jenny
else if($current_user->id != 1 && $current_user->id != 6 && $current_user->id != 8)

{
	?>
	<script type="text/javascript">
		var elems = document.getElementsByTagName('td');
		var a = 0;
		var b = 0;
    for (var i in elems)
    {
        if((" "+elems[i].className+" ").indexOf("dvtCellInfo") > -1)
        {
			var idd = elems[i].getAttribute("id");
			var count = 0;
			//Determine the fields to be editable by Sales
			if(idd == "mouseArea_Installation Charge" || idd == "mouseArea_Installer Invoice Number" || idd == "mouseArea_Deposit Date" || idd == "mouseArea_Deposit Amount" || idd == "mouseArea_Invoice Number" || idd == "mouseArea_Full Payment Option" || idd == "mouseArea_Total Amount" || idd == "mouseArea_Full Payment Date" || idd == "mouseArea_Full Payment Confirmed" || idd == "mouseArea_STCs Claimed by")
			{	
				//alert(idd);
				//count = count+1;
				elems[i].setAttribute("onmouseover","");
			}
		}
    }
	//alert(count);
	</script>
	<?php
}
?>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>