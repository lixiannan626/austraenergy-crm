<?php
echo "<!-- $current_user->user_name -->";
//Determine the users to enjoy the ban of edit function.
//id >10, 13, 15, 14, 35, 36 are either admin or support
/* 10. yi
 *13. test.admin
 *15. Vishal
 *14. test.support
 *35.

*/
if($current_user->id >10 && $current_user->id !=13 && $current_user->id !=15 && $current_user->id !=14 && $current_user->id !=35  && $current_user->id !=36)
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
//2012-03-06 John requested that Payment Method can only be modified by Amy/John/Vishal/Yi/admin
//else if($current_user->id != 1 && $current_user->id != 5 && $current_user->id != 6)
//else if($current_user->id != 1 && $current_user->id != 6)
else
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