<?php

/************************************
Detail view of an install team

************************************/

//Show
?>
<style>
#install_team_detailview_div{padding: 20px;}
#install_team_detailview_div h3{padding: 0; margin: 0;}
table.detail{border-collapse: collapse; font-size: 11px; font-family: "Arial"; width: 900px; border-top: none; }
.header_container h2{border-left: 1px solid #DEDEDE; border-right: 1px solid #DEDEDE; border-top: 2px solid #06c; display: inline-block; padding: 10px 20px; margin-bottom: 0;}
.table_container{border: 1px solid #DEDEDE; padding: 10px; width: 900px;}
.detail th, .detail td{border: 1px solid #DEDEDE;}
.label, .value{width: 20%; padding: 0 10px; height: 25px;}
.label{background: #F7F7F7; text-align: right;}
.div_header{padding: 10px; font-weight: 700; background: #b7b5b5;}
span.sedit{display: inline-block; float: right; position: relative; right: 2px; color: #06c;cursor: pointer;}
span.sedit:hover{text-decoration: underline; cursor: pointer;}
div.data{float: left;}
.edit{background: white; color: black;}
</style>
<div id='install_team_detailview_div'>
	<h3><a href="index.php?module=InstallTeam&action=index&parenttab=Support">&lt;&lt; Back to Install Team List</a></h3>
	<?php ShowTeamDetail(); ?>
</div>

<script type="text/javascript" src="modules/InstallTeam/jquery.min.js"></script>
<script>
$(document).ready(function() {
	//
	$('td.edit').append("<span class='sedit' style='display:none'>Edit</span>");
	$('td.edit').mouseover(function(){
		$(this).find("span.sedit").show();
	}).mouseleave(function(){
		$(this).find("span.sedit").hide();
	}); 
	
	$('span.sedit').click(function(){
		//Create a text input and 2 buttons
		//One hidden
		
		var root = $(this).parent();
		if(root.find('div.edit_area').length == 0)
		{
			var val = root.find('div.data').text();
			//var name = root.attr("name");
			var hid = "<input type='hidden' class='original_txt' value='"+val+"' />";
			var bts = "<br><button class='yes'>OK</button><button class='no'>Cancel</button>";
			root.append("<div class='edit_area'><input type='text' class='new_val' value='"+val+"'/>"+bts+hid+"</div>");
			root.find('div.data').remove();
			root.find("input.new_val").focus();
		}
	});
	
	$('button.no').live("click", function(){
		var root = $(this).parent().parent();
		var val = $(this).parent().find('input.original_txt').val();
		root.append("<div class='data'>"+val+"</div>");
		root.find('div.edit_area').remove();
		//alert(val);
	});
	
	$('button.yes').live("click", function(){
		var root = $(this).parent().parent();
		var tn = $("td#tn").text();
		var name = root.attr("name");
		var new_val = root.find('input.new_val').val();
		var val = root.find('input.original_txt').val();
		$.post(
						"modules/InstallTeam/detail_ajax.php", 
						{
							userid: cre,
							tn: tn,
							field: name,
							val: new_val
						},
						function(responseText)
						{
							if(responseText == "Er0rr")
							{
								root.append("<div class='data'>"+val+"</div>");
								root.find('div.edit_area').remove();
							}
							else
							{
								root.append("<div class='data'>"+responseText+"</div>");
								root.find('div.edit_area').remove();
							}
						},
						"html"
					);
		//var field = $
	});
});
</script>



<?php
//Functions
function ShowTeamDetail()
{
	$team_id = $_GET['team_id'];
	$ths = array("Team Name", "Installer Name", "Designer Name", "Electrician Name");
	//Comment out when uploading to the server
	//$link = mysql_connect("localhost", "root", "jackson");
	//mysql_select_db("test");
	$q = "SELECT *
				FROM custom_install_team
				WHERE team_id = $team_id";
	$result = mysql_query($q);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	echo "
		<div class='header_container'>
			<h2>".$row['team_name']."</h2>
		</div>
		<div class='table_container'>
		<table class='detail'>
			<tr>
				<td colspan='4' class='div_header'>Team Information</td>
			</tr>
			<tr>
				<td class='label'>Team Name</td><td class='no_edit value' id='tn'>".$row['team_name']."</td>
				<td class='label'>Team Username</td><td name='team_username' class='edit value'><div class='data'>".$row['team_username']."</div></td>
			</tr>
			<tr>
				<td class='label'>Team Password</td><td name='team_password' class='edit value'><div class='data'>".$row['team_password']."</div></td>
			</tr>
			
			<tr>
				<td colspan='4' class='div_header'>CEC Installer Information</td>
			</tr>
			<tr>
				<td class='label'>Installer First Name</td><td name='i_firstname' class='edit value'><div class='data'>".$row['i_firstname']."</div></td>
				<td class='label'>Installer Last Name</td><td name='i_lastname' class='edit value'><div class='data'>".$row['i_lastname']."</div></td>
			</tr>
			<tr>
				<td class='label'>Installer Company</td><td name='i_company' class='edit value'><div class='data'>".$row['i_company']."</div></td>
				<td class='label'>Installer Accreditation No.</td><td name='i_accre_no' class='edit value'><div class='data'>".$row['i_accre_no']."</div></td>
			</tr>
			<tr>
				<td class='label'>Installer Street</td><td name='i_street' class='edit value'><div class='data'>".$row['i_street']."</div></td>
				<td class='label'>Installer Suburb</td><td name='i_suburb' class='edit value'><div class='data'>".$row['i_suburb']."</div></td>
			</tr>
			<tr>
				<td class='label'>Installer State</td><td name='i_state' class='edit value'><div class='data'>".$row['i_state']."</div></td>
				<td class='label'>Installer Postcode</td><td name='i_postcode' class='edit value'><div class='data'>".$row['i_postcode']."</div></td>
			</tr>
			<tr>
				<td class='label'>Installer Telephone</td><td name='i_phone' class='edit value'><div class='data'>".$row['i_phone']."</div></td>
				<td class='label'>Installer Mobile</td><td name='i_mobile' class='edit value'><div class='data'>".$row['i_mobile']."</div></td>
			</tr>
			<tr>
				<td class='label'>Installer Email</td><td name='i_email' class='edit value'><div class='data'>".$row['i_email']."</div></td>
			</tr>
			
			<tr>
				<td colspan='4' class='div_header'>Designer Information (leave blank if the same as Installer)</td>
			</tr>
			<tr>
				<td class='label'>Designer First Name</td><td name='d_firstname' class='edit value'><div class='data'>".$row['d_firstname']."</div></td>
				<td class='label'>Designer Last Name</td><td name='d_lastname' class='edit value'><div class='data'>".$row['d_lastname']."</div></td>
			</tr>
			<tr>
				<td class='label'>Designer Company</td><td name='d_company' class='edit value'><div class='data'>".$row['d_company']."</div></td>
				<td class='label'>Designer Accreditation No.</td><td name='d_accre_no' class='edit value'><div class='data'>".$row['d_accre_no']."</div></td>
			</tr>
			<tr>
				<td class='label'>Designer Street</td><td name='d_street' class='edit value'><div class='data'>".$row['d_street']."</div></td>
				<td class='label'>Designer Suburb</td><td name='d_suburb' class='edit value'><div class='data'>".$row['d_suburb']."</div></td>
			</tr>
			<tr>
				<td class='label'>Designer State</td><td name='d_state' class='edit value'><div class='data'>".$row['d_state']."</div></td>
				<td class='label'>Designer Postcode</td><td name='d_postcode' class='edit value'><div class='data'>".$row['d_postcode']."</div></td>
			</tr>
			<tr>
				<td class='label'>Designer Telephone</td><td name='d_phone' class='edit value'><div class='data'>".$row['d_phone']."</div></td>
				<td class='label'>Designer Mobile</td><td name='d_mobile' class='edit value'><div class='data'>".$row['d_mobile']."</div></td>
			</tr>
			<tr>
				<td class='label'>Designer Email</td><td name='d_email' class='edit value'><div class='data'>".$row['d_email']."</div></td>
			</tr>
			
			<tr>
				<td colspan='4' class='div_header'>Electrician Information</td>
			</tr>
			<tr>
				<td class='label'>Electrician First Name</td><td name='e_firstname' class='edit value'><div class='data'>".$row['e_firstname']."</div></td>
				<td class='label'>Electrician Last Name</td><td name='e_lastname' class='edit value'><div class='data'>".$row['e_lastname']."</div></td>
			</tr>
			<tr>
				<td class='label'>Electrician Company</td><td name='e_company' class='edit value'><div class='data'>".$row['e_company']."</div></td>
				<td class='label'>Electrician No.</td><td name='e_elec_no' class='edit value'><div class='data'>".$row['e_elec_no']."</div></td>
			</tr>
			<tr>
				<td class='label'>Electrician Street</td><td name='e_street' class='edit value'><div class='data'>".$row['e_street']."</div></td>
				<td class='label'>Electrician Suburb</td><td name='e_suburb' class='edit value'><div class='data'>".$row['e_suburb']."</div></td>
			</tr>
			<tr>
				<td class='label'>Electrician State</td><td name='e_state' class='edit value'><div class='data'>".$row['e_state']."</div></td>
				<td class='label'>Electrician Postcode</td><td name='e_postcode' class='edit value'><div class='data'>".$row['e_postcode']."</div></td>
			</tr>
			<tr>
				<td class='label'>Electrician Telephone</td><td name='e_phone' class='edit value'><div class='data'>".$row['e_phone']."</div></td>
				<td class='label'>Electrician Mobile</td><td name='e_mobile' class='edit value'><div class='data'>".$row['e_mobile']."</div></td>
			</tr>
			<tr>
				<td class='label'>Electrician Email</td><td name='e_email' class='edit value'><div class='data'>".$row['e_email']."</div></td>
			</tr>
		
		</table>
	</div>";
}

?>