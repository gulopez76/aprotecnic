<?php /* Smarty version Smarty-3.1.7, created on 2017-02-15 06:43:24
         compiled from "/www/vhost/at/includes/runtime/../../layouts/vlayout/modules/Vtiger/ModuleSummaryView.tpl" */ ?>
<?php /*%%SmartyHeaderCode:128939927758a3f88c282429-96978995%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad7515be8d3fcc788bf1381f436064e7826d0d85' => 
    array (
      0 => '/www/vhost/at/includes/runtime/../../layouts/vlayout/modules/Vtiger/ModuleSummaryView.tpl',
      1 => 1468488064,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '128939927758a3f88c282429-96978995',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MODULE_NAME' => 0,
    'SUMMARY_RECORD_STRUCTURE' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_58a3f88c28e7e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a3f88c28e7e')) {function content_58a3f88c28e7e($_smarty_tpl) {?>
<div class="recordDetails"><?php echo $_smarty_tpl->getSubTemplate (vtemplate_path('DetailViewBlockView.tpl',$_smarty_tpl->tpl_vars['MODULE_NAME']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('RECORD_STRUCTURE'=>$_smarty_tpl->tpl_vars['SUMMARY_RECORD_STRUCTURE']->value,'MODULE_NAME'=>$_smarty_tpl->tpl_vars['MODULE_NAME']->value), 0);?>
</div>
<?php }} ?>