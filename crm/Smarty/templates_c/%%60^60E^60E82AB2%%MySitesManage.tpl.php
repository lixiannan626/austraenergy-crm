<?php /* Smarty version 2.6.18, created on 2011-12-16 20:14:45
         compiled from MySitesManage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'MySitesManage.tpl', 18, false),)), $this); ?>
<!-- BEGIN: main -->
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td colwidth=90% align=left class=small>
		<table border=0 cellspacing=0 cellpadding=5>
		<tr>
			<td align=left><a href="#" onclick="fetchContents('data');"><img src="<?php echo vtiger_imageurl('webmail_settings.gif', $this->_tpl_vars['THEME']); ?>
" align="absmiddle" border=0 /></a></td>
			<td class=small align=left><a href="#" onclick="fetchContents('data');"><?php echo $this->_tpl_vars['MOD']['LBL_MY_SITES']; ?>
</a></td>
		</tr>
		</table>
			
	</td>
	<td align=right width=10%>
		<table border=0 cellspacing=0 cellpadding=0>
		<tr><td nowrap class="componentName"><?php echo $this->_tpl_vars['MOD']['LBL_MY_SITES']; ?>
</td></tr>
		</table>
	</td>
</tr>
</table>

<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
<td colspan="3" class="genHeaderSmall" align="left"><?php echo $this->_tpl_vars['MOD']['LBL_MY_BOOKMARKS']; ?>
 <hr></td>
</tr>
<tr>
<td colspan="3" align="left"><input name="bookmark" value=" <?php echo $this->_tpl_vars['MOD']['LBL_NEW_BOOKMARK']; ?>
 " class="crmbutton small create" onclick="fnvshobj(this,'editportal_cont');fetchAddSite('');" type="button"></td>
</tr>
</table>
<table border="0" cellpadding="5" cellspacing="0" width="100%" class="listTable bgwhite"> 
<tr>
<td class="colHeader small" align="left" width="5%"><b><?php echo $this->_tpl_vars['MOD']['LBL_SNO']; ?>
</b></td>
<td class="colHeader small" align="left" width="75%"><b><?php echo $this->_tpl_vars['MOD']['LBL_BOOKMARK_NAME_URL']; ?>
</b></td>

<td class="colHeader small" align="left" width="20%"><b><?php echo $this->_tpl_vars['MOD']['LBL_TOOLS']; ?>
</b></td>
</tr>

<?php $_from = $this->_tpl_vars['PORTALS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['portallists'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['portallists']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sno'] => $this->_tpl_vars['portaldetails']):
        $this->_foreach['portallists']['iteration']++;
?>
<tr><td class="listTableRow small" align="left"><?php echo $this->_foreach['portallists']['iteration']; ?>
</td>
<td class="listTableRow small" align="left">
<b><?php echo $this->_tpl_vars['portaldetails']['portalname']; ?>
</b><br>
<span class="big"><?php echo $this->_tpl_vars['portaldetails']['portaldisplayurl']; ?>
</span>
</td>
<td class="listTableRow small" align="left">
<a href="javascript:;" onclick="fnvshobj(this,'editportal_cont');fetchAddSite('<?php echo $this->_tpl_vars['portaldetails']['portalid']; ?>
');" class="webMnu"><?php echo $this->_tpl_vars['APP']['LBL_EDIT']; ?>
</a>&nbsp;|&nbsp;
<a href="javascript:;" onclick="DeleteSite('<?php echo $this->_tpl_vars['portaldetails']['portalid']; ?>
');"class="webMnu"><?php echo $this->_tpl_vars['APP']['LBL_MASS_DELETE']; ?>
</a>
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>