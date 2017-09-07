<?php /* Smarty version 2.6.18, created on 2012-01-04 14:53:03
         compiled from modules/Vtiger/OperationNotPermitted.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'vtiger_imageurl', 'modules/Vtiger/OperationNotPermitted.tpl', 19, false),)), $this); ?>

<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'>
<tr><td align='center'>
	<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 100000020;'>

	<table border='0' cellpadding='5' cellspacing='0' width='98%'>
	<tr>
		<td rowspan='2' width='11%'><img src="<?php echo vtiger_imageurl('denied.gif', $this->_tpl_vars['THEME']); ?>
" ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'><?php echo $this->_tpl_vars['APP']['LBL_PERMISSION']; ?>
</span></td>
	</tr>
	<tr>
		<td class='small' align='right' nowrap='nowrap'>			   	
			<a href='javascript:window.history.back();'><?php echo $this->_tpl_vars['APP']['LBL_GO_BACK']; ?>
</a><br>
		</td>
	</tr>
	</table> 
	</div>
</td></tr>
</table>