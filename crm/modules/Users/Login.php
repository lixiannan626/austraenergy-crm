<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Login.php,v 1.6 2005/01/08 13:15:03 jack Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
$image_path="include/images/";

global $app_language;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

 define("IN_LOGIN", true);

include_once('vtlib/Vtiger/Language.php');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_user_name"]))
{
	if (isset($_REQUEST['default_user_name']))
		$login_user_name = trim(vtlib_purify($_REQUEST['default_user_name']), '"\'');
	else
		$login_user_name =  trim(vtlib_purify($_REQUEST['login_user_name']), '"\'');
}
else
{
	if (isset($_REQUEST['default_user_name']))
	{
		$login_user_name = trim(vtlib_purify($_REQUEST['default_user_name']), '"\'');
	}
	elseif (isset($_REQUEST['ck_login_id_vtiger'])) {
		$login_user_name = get_assigned_user_name($_REQUEST['ck_login_id_vtiger']);
	}
	else
	{
		$login_user_name = $default_user_name;
	}
	$_session['login_user_name'] = $login_user_name;
}

$current_module_strings['VLD_ERROR'] = base64_decode('UGxlYXNlIHJlcGxhY2UgdGhlIFN1Z2FyQ1JNIGxvZ29zLg==');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_password"]))
{
	$login_password = trim(vtlib_purify($_REQUEST['login_password']), '"\'');
}
else
{
	$login_password = $default_password;
	$_session['login_password'] = $login_password;
}

if(isset($_SESSION["login_error"]))
{
	$login_error = $_SESSION['login_error'];
}

?>
<!--Added to display the footer in the login page by Dina-->
<style type="text/css">@import url("themes/<?php echo $theme; ?>/style.css");</style>
<script type="text/javascript" language="JavaScript">
<!-- Begin -->
function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}
<!-- End -->
</script>
<div id="wrap">
	<div id="container">

		<div id="rightpanel">
			<div id="form-wrapper">
				<div id="rightheader">
					<img src="custom_yi/login_4.png" width="280" height="44" />
				</div>
						<!-- Sign in form -->
				<form action="index.php" method="post" name="DetailView" id="form">
					<input type="hidden" name="module" value="Users">
					<input type="hidden" name="action" value="Authenticate">
					<input type="hidden" name="return_module" value="Users">
					<input type="hidden" name="return_action" value="Login">
								
					<p class="label">Username</p>
					<p><input class="logindetail" type="text" name="user_name" tabindex="1" autocomplete="on" placeholder="username"></p>
					<p class="label">Password</p>
					<p><input class="logindetail" type="password" name="user_password" tabindex="2" autocomplete="on" placeholder="password"></p>
												
					<?php
					if(isset($login_error) && $login_error != "")
						echo "<p><font color='Brown'>$login_error</font></p>";
					?>
							
					<input type="submit" id="SubmitButton" name="Login" value="Sign in"  tabindex="3">
				</form>
			</div>
			<div id="news">
				<p><span class="h4">News</span></p>
				<div id="newscontent">
					<!--<p><span class="newsdate">[2012.08.07]</span><span class="newsgroup">[Admin]</span>Stock Log Generator (Contact Detail) available!</p>-->
					<p><span class="newsdate">[2012.08.07]</span><span class="newsgroup">[Admin]</span>Stock Management module available!</p>
					<p><span class="newsdate">[2012.08.06]</span><span class="newsgroup">[Admin]</span>Install Paperwork module available!</p>
					<p><span class="newsdate">[2012.08.05]</span><span class="newsgroup">[Admin]</span>Install Arrangement module available!</p>
				<!--	<p><span class="newsdate">[2012.08.05]</span><span class="newsgroup">[Everyone]</span>New Login Page!</p> -->
				</div>
			</div>
		</div>
		
		<div id="leftpanel">
			<div>
				<img src="custom_yi/login_1.jpg" width="475" height="275" />
			</div>
			<div id="text">
				<h2>Customer Relationship Management</h2>
				<p><span class="newsgroup">[Admin]</span> Use Install Paperwork module to check paperwork status.</p>
				<p><span class="newsgroup">[Admin]</span> Use Install Arrangement module to assist job distribution.</p>
				<p><span class="newsgroup">[Sales]</span> Use Sales Report to view your sales summary.</p>
			</div>
		</div>
		
	</div>
	<div id="login_footer">
		<p>&copy; 2012 Go For Solar</p>
	</div>
</div>
