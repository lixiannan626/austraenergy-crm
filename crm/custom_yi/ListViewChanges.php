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
	//Forbit mass editing
	document.getElementById("mass_edit_button").setAttribute("onclick","");

	</script>
	<?php
}
?>