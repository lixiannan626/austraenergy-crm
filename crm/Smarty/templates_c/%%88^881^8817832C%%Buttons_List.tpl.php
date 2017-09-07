<?php /* Smarty version 2.6.18, created on 2012-09-30 01:17:31
         compiled from Buttons_List.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'Buttons_List.tpl', 18, false),array('modifier', 'vtiger_imageurl', 'Buttons_List.tpl', 34, false),)), $this); ?>
<script type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<?php $this->assign('action', 'ListView'); ?>
	<?php $this->assign('MODULELABEL', getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE'])); ?>	
	<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['CATEGORY']]; ?>
 > <a class="hdrLink" href="index.php?action=<?php echo $this->_tpl_vars['action']; ?>
&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['MODULELABEL']; ?>
</a></td>
	<td width=100% nowrap>
	
		<table border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td class="sep1" style="width:1px;"></td>
		<td class=small >
			<!-- Add and Search -->
			<table border=0 cellspacing=0 cellpadding=0>
			<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails'): ?>
			        		<?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
		                      	        	<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('btnL3Add-Faded.png', $this->_tpl_vars['THEME']); ?>
" border=0></td>
                	   			 <?php else: ?>
	                        		       	<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Add.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." title="<?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['SINGLE_MOD'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." border=0></a></td>
			                       	<?php endif; ?>
					<?php else: ?>
						<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('btnL3Add-Faded.png', $this->_tpl_vars['THEME']); ?>
" border=0></td>	
					<?php endif; ?>
									
					<?php if ($this->_tpl_vars['CHECK']['index'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails'): ?>
						 <td style="padding-right:10px"><a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')" ><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Search.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_SEARCH_ALT']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." title="<?php echo $this->_tpl_vars['APP']['LBL_SEARCH_TITLE']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
..." border=0></a></a></td>
					<?php else: ?>
						<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('btnL3Search-Faded.png', $this->_tpl_vars['THEME']); ?>
" border=0></td>
					<?php endif; ?>
					
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Calendar Clock Calculator and Chat -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
						<?php if ($this->_tpl_vars['CALENDAR_DISPLAY'] == 'true'): ?> 
										 		                                                <?php if ($this->_tpl_vars['CATEGORY'] == 'Settings' || $this->_tpl_vars['CATEGORY'] == 'Tools' || $this->_tpl_vars['CATEGORY'] == 'Analytics'): ?> 
 		                                                        <?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?> 
 		                                                               		                                                        <?php else: ?> 
 		                                                                 		                                                        <?php endif; ?> 
						<?php else: ?>
						<?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?> 
 		                                                             		                                                        <?php else: ?> 
 		                                                                 		                                                        <?php endif; ?> 
						<?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['WORLD_CLOCK_DISPLAY'] == 'true'): ?> 
 		                                                <td style="padding-right:0px"><a href="javascript:;"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Clock.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> 
 		                                        <?php endif; ?> 
 		                                        <?php if ($this->_tpl_vars['CALCULATOR_DISPLAY'] == 'true'): ?> 
 		                                                <td style="padding-right:0px"><a href="#"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calc.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td> 
 		                                        <?php endif; ?> 
												 		                                        <?php if ($this->_tpl_vars['CHAT_DISPLAY'] == 'true'): ?> 
 		                                                		                                        <?php endif; ?> 
                    </td>	
					<td style="padding-right:10px"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Tracker.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" border=0 onClick="fnvshobj(this,'tracker');">
                    			</td>	
				</tr>
				</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Import / Export -->
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>

			
			<?php if ($this->_tpl_vars['MODULE'] == 'Vendors' || $this->_tpl_vars['MODULE'] == 'HelpDesk' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Potentials' || $this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'Documents' || $this->_tpl_vars['CUSTOM_MODULE'] == 'true' || $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
		   		<?php if ($this->_tpl_vars['CHECK']['Import'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Calendar'): ?>	
					<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Import&step=1&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=index&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarImport.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>	
				<?php elseif ($this->_tpl_vars['CHECK']['Import'] == 'yes' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                    <td style="padding-right:10px"><a name='export_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalImport');" ><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarImport.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>
				<?php else: ?>	
					<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('tbarImport-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>	
				<?php endif; ?>	
				<?php if ($this->_tpl_vars['CHECK']['Export'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Calendar'): ?>	
                    <td style="padding-right:10px"><a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarExport.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>
				<?php elseif ($this->_tpl_vars['CHECK']['Export'] == 'yes' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                    <td style="padding-right:10px"><a name='export_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalExport');" ><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarExport.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
" border="0"></a></td>
				<?php else: ?>	
					<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('tbarExport-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
                	<?php endif; ?>
			<?php else: ?>
				<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo vtiger_imageurl('tbarImport-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
                		<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('tbarExport-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'Potentials' || $this->_tpl_vars['MODULE'] == 'HelpDesk' || $this->_tpl_vars['MODULE'] == 'Vendors' || $this->_tpl_vars['CUSTOM_MODULE'] == 'true'): ?> 
				<?php if ($this->_tpl_vars['CHECK']['DuplicatesHandling'] == 'yes'): ?>	
					<!--<td style="padding-right:10px"><a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=FindDuplicateRecords&button_view=true&list_view=true&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
findduplicates.png" alt="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPICATES']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPLICATES']; ?>
" border="0"></a></td> -->
					<td style="padding-right:10px"><a href="javascript:;" onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');"><img src="<?php echo vtiger_imageurl('findduplicates.png', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPICATES']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPLICATES']; ?>
" border="0"></a></td>
				<?php else: ?>
					<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('FindDuplicates-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>	
				<?php endif; ?>
			<?php else: ?>
				<td style="padding-right:10px"><img src="<?php echo vtiger_imageurl('FindDuplicates-Faded.png', $this->_tpl_vars['THEME']); ?>
" border="0"></td>
			<?php endif; ?>
			</tr>
			</table>	
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
				<!-- All Menu -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-left:10px;" id="before_download_td"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3AllMenu.png" id="before_download" alt="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_TITLE']; ?>
" border="0"></a></td>
				<?php if ($this->_tpl_vars['CHECK']['moduleSettings'] == 'yes'): ?>
	        		<td style="padding-left:10px;"><a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=Settings'><img src="<?php echo vtiger_imageurl('settingsBox.png', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_SETTINGS']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['MODULE'])) ? $this->_run_mod_handler('getTranslatedString', true, $_tmp, $this->_tpl_vars['MODULE']) : getTranslatedString($_tmp, $this->_tpl_vars['MODULE'])); ?>
 <?php echo $this->_tpl_vars['APP']['LBL_SETTINGS']; ?>
" border="0"></a></td>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
					<td><form id="download_form" name="download_form" action="custom_yi/exportCSV.php" target="_blank" method="get"><a id="export_button" style="display: inline-block;" href="javascript:getContacts()" title="Download records, select contacts using checkboxes first"><img src="<?php echo vtiger_imageurl('custom_download.png', $this->_tpl_vars['THEME']); ?>
" alt="Download Records" border="0" /></a><input type="hidden" id="ids" name="ids" value=""><input type="hidden" id="cre" name="cre" value="<?php echo $this->_tpl_vars['CURRENT_USER_ID']; ?>
"></form></td>
				<?php endif; ?>
				</tr>
				</table>
		</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td style="height:2px"></td></tr>
</TABLE>