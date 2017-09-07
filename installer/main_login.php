<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Go For Solar Installer Login</title>
		<style>
			*{font-family: "Arial";}
			#login_table{border: 2px solid #06c; margin-top: 50px; padding-top: 100px; background: white url('gfs-logo.jpg') no-repeat; width: 320px; margin: 50px auto 0 auto;}
			.login_div_header{text-align: right; }
			.login_div_header h2{padding: 5px; margin-bottom: 0; color: #06c;}
			.login_label{text-align: right; color: #06c;}
			.login_detail input{border: 1px solid #06c; border-radius: 2px; padding: 6px; margin-left: 10px;}
			#login_table input#submit {padding: 5px 20px; background: #06c; color: white; border-radius: 5px; border: none; }
			body {background: url('installer-bg.jpg') repeat;}
		</style>
	</head>
	<body>
		<div id="login_table">
		<table width="320" align="center">
			<tr>
				<form name="form1" method="post" action="checklogin.php">
					<td>
						<table width="100%">
							<tr class='login_div_header'>
								<td colspan="2"><h2>Installer Login </h2></td>
							</tr>
							<tr>
								<td class='login_label'>Username </td>
								<td class='login_detail'><input name="myusername" id="un" type="text" id="myusername"></td>
							</tr>
							<tr>
								<td class='login_label'>Password </td>
								<td class='login_detail'><input name="mypassword" type="password" id="mypassword"></td>
							</tr>
							<?php
							if(isset($_GET['ec']) && $_GET['ec'] == 1)
								echo "<tr>
											<td colspan='2' style='color:red; padding-left: 20px;'>Invalid username/password.</td>
										</tr>";
							?>
							<tr>
								<td>&nbsp;</td>
								<td><input id="submit" type="submit" name="Submit" value="Login"></td>
							</tr>
						</table>
					</td>
				</form>
			</tr>
		</table>
		</div>
          <script
               type="text/javascript"
               language="javascript">
               document.getElementById("un").focus();
          </script>
	</body>