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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/DetailView.php,v 1.38 2005/04/25 05:04:46 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('include/CustomFieldUtil.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

global $log;
global $mod_strings;
global $app_strings;
global $currentModule, $singlepane_view;

$focus = CRMEntity::getInstance($currentModule);

if(isset($_REQUEST['record']) && $_REQUEST['record']!='') {
        //Display the error message
        if($_SESSION['image_type_error'] != '')
        {
                echo '<font color="red">'.$_SESSION['image_type_error'].'</font>';
                session_unregister('image_type_error');
        }

        $focus->id=$_REQUEST['record'];
        $focus->retrieve_entity_info($_REQUEST['record'],'Contacts');
	 $log->info("Entity info successfully retrieved for Contact DetailView.");
	$focus->firstname=$focus->column_fields['firstname'];
        $focus->lastname=$focus->column_fields['lastname'];
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

global $theme, $current_user;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$log->info("Contact detail view");

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("UPDATEINFO",updateInfo($focus->id));

if(useInternalMailer() == 1) 
	$smarty->assign("INT_MAILER","true");

$contact_name = $focus->lastname;
if (getFieldVisibilityPermission($currentModule, $current_user->id,'firstname') == '0') {
	$contact_name .= ' '.$focus->firstname;
}
$smarty->assign("NAME",$contact_name);

$log->info("Detail Block Informations successfully retrieved.");
$smarty->assign("BLOCKS", getBlocks($currentModule,"detail_view",'',$focus->column_fields));
$smarty->assign("CUSTOMFIELD", $cust_fld);
$smarty->assign("SINGLE_MOD", 'Contact');

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if ($mod_seq_field != null) {
	$mod_seq_id = $focus->column_fields[$mod_seq_field['name']];
} else {
	$mod_seq_id = $focus->id;
}
$smarty->assign('MOD_SEQ_ID', $mod_seq_id);
// END

$smarty->assign("ID", $_REQUEST['record']);

/*
if(isPermitted("Contacts","EditView",$_REQUEST['record']) == 'yes')
	$smarty->assign("EDIT_DUPLICATE","permitted");
*/
if(isPermitted("Contacts","Delete",$_REQUEST['record']) == 'yes')
	$smarty->assign("DELETE","permitted");
if(isPermitted("Emails","EditView",'') == 'yes')
{
	//Added to pass the parents list as hidden for Emails -- 09-11-2005
	$parent_email = getEmailParentsList('Contacts',$_REQUEST['record'], $focus);
	$smarty->assign("HIDDEN_PARENTS_LIST",$parent_email);
	$smarty->assign("SENDMAILBUTTON","permitted");
	$smarty->assign("EMAIL1",$focus->column_fields['email']);
	$smarty->assign("EMAIL2",$focus->column_fields['yahooid']);

}

if(isPermitted("Contacts","Merge",'') == 'yes')
{
	global $current_user;
        require("user_privileges/user_privileges_".$current_user->id.".php");

	require_once('include/utils/UserInfoUtil.php');
	$wordTemplateResult = fetchWordTemplateList("Contacts");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString[$tempVal["templateid"]]=$tempVal["filename"];
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
        if($is_admin)
                $smarty->assign("MERGEBUTTON","permitted");
	elseif($tempCount >0)
		$smarty->assign("MERGEBUTTON","permitted");
	 $smarty->assign("TEMPLATECOUNT",$tempCount);
	$smarty->assign("WORDTEMPLATEOPTIONS",$app_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']);
        $smarty->assign("TOPTIONS",$optionString);
}

//Security check for related list
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$check_button = Button_Check($module);
$smarty->assign("CHECK", $check_button);

$tabid = getTabid("Contacts");
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);

$smarty->assign("MODULE",$currentModule);
$smarty->assign("EDIT_PERMISSION",isPermitted($currentModule,'EditView',$_REQUEST['record']));
$smarty->assign("IS_REL_LIST",isPresentRelatedLists($currentModule));
$smarty->assign("USE_ASTERISK", get_use_asterisk($current_user->id));

$sql = $adb->pquery('select accountid from vtiger_contactdetails where contactid=?', array($focus->id));
$accountid = $adb->query_result($sql,0,'accountid');
if($accountid == 0) $accountid='';
$smarty->assign("accountid",$accountid);
if($singlepane_view == 'true')
{
	$related_array = getRelatedLists($currentModule,$focus);
	$smarty->assign("RELATEDLISTS", $related_array);
	require_once('include/ListView/RelatedListViewSession.php');
	if(!empty($_REQUEST['selected_header']) && !empty($_REQUEST['relation_id'])) {
		RelatedListViewSession::addRelatedModuleToSession(vtlib_purify($_REQUEST['relation_id']),
				vtlib_purify($_REQUEST['selected_header']));
	}
	$open_related_modules = RelatedListViewSession::getRelatedModulesFromSession();
	$smarty->assign("SELECTEDHEADERS", $open_related_modules);
}
$smarty->assign("TODO_PERMISSION",CheckFieldPermission('parent_id','Calendar'));
$smarty->assign("CONTACT_PERMISSION",CheckFieldPermission('contact_id','Calendar'));
$smarty->assign("EVENT_PERMISSION",CheckFieldPermission('parent_id','Events'));
$smarty->assign("SinglePane_View", $singlepane_view);

if(PerformancePrefs::getBoolean('DETAILVIEW_RECORD_NAVIGATION', true) && isset($_SESSION[$currentModule.'_listquery'])){
	$recordNavigationInfo = ListViewSession::getListViewNavigation($focus->id);
	VT_detailViewNavigation($smarty,$recordNavigationInfo,$focus->id);
}

// Record Change Notification
$focus->markAsViewed($current_user->id);
// END

include_once('vtlib/Vtiger/Link.php');
$customlink_params = Array('MODULE'=>$currentModule, 'RECORD'=>$focus->id, 'ACTION'=>vtlib_purify($_REQUEST['action']));
$smarty->assign('CUSTOM_LINKS', Vtiger_Link::getAllByType(getTabid($currentModule), Array('DETAILVIEWBASIC','DETAILVIEW','DETAILVIEWWIDGET'), $customlink_params));

$smarty->assign('DETAILVIEW_AJAX_EDIT', PerformancePrefs::getBoolean('DETAILVIEW_AJAX_EDIT', true));

/* Custom for GFS Quote Module Start */
	//1. Read db
	$sql = "SELECT ss_id, ss_sales_id, ss_sales_name, ss_time FROM quote_snapshot WHERE ss_customer_id='".$focus->id."' ORDER BY ss_id DESC";
	$rows = array();
	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result))
		array_push($rows, $row);
	if(count($rows) == 0)
		$quote_block = "So far no quote has been made.";
	else
	{
		$quote_block = "<table class='quote-list'>
						    <tr>
						        <th>Quote#</th>
						        <th>Who</th>
						        <th>When</th>
						    </tr>";
        foreach($rows as $q)
            $quote_block .= "<tr>
                             <td><a href='modules/QuotePrice/quote_view.php?qid=".$q['ss_id']."&sid=".$q['ss_sales_id']."' target='_blank'>Q".str_pad((int)$q['ss_id'],4,"0",STR_PAD_LEFT)."</a></td>
                             <td>".$q['ss_sales_name']."</td>
                             <td>".date("Y-m-d H:i", strtotime($q['ss_time']))."</td>
                             </tr>";
        $quote_block .= "</table>";
	}
	

$smarty->assign('QUOTES', $quote_block);
/* Custom for GFS Quote Module End */



$smarty->display("DetailView.tpl");


require_once('custom_yi/detailViewChanges.php');
?>