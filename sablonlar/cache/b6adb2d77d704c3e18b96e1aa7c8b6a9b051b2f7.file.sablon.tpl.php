<?php /* Smarty version Smarty-3.1.21-dev, created on 2014-11-15 23:45:51
         compiled from "sablonlar\sablon.tpl" */ ?>
<?php /*%%SmartyHeaderCode:151965467c98f6a9116-27205797%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6adb2d77d704c3e18b96e1aa7c8b6a9b051b2f7' => 
    array (
      0 => 'sablonlar\\sablon.tpl',
      1 => 1416086901,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '151965467c98f6a9116-27205797',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'uygulama_adi' => 0,
    'baslik' => 0,
    'css' => 0,
    'href' => 0,
    'js' => 0,
    'src' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5467c98f9b2749_75410307',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5467c98f9b2749_75410307')) {function content_5467c98f9b2749_75410307($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="tr" xmlns="http://www.w3.org/1999/xhtml" ng-app="<?php echo $_smarty_tpl->tpl_vars['uygulama_adi']->value;?>
">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <base href="http://localhost/emlak_intranet/"/>
        <title><?php echo $_smarty_tpl->tpl_vars['baslik']->value;?>
</title>
        
        <?php  $_smarty_tpl->tpl_vars['href'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['href']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['css']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['href']->key => $_smarty_tpl->tpl_vars['href']->value) {
$_smarty_tpl->tpl_vars['href']->_loop = true;
?>
            <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['href']->value;?>
"/>
        <?php } ?>
    </head>
    <body>

        
        <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['sayfa']->value).".tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


        
        <?php  $_smarty_tpl->tpl_vars['src'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['src']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['js']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['src']->key => $_smarty_tpl->tpl_vars['src']->value) {
$_smarty_tpl->tpl_vars['src']->_loop = true;
?>
            <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['src']->value;?>
"><?php echo '</script'; ?>
>
        <?php } ?>
    </body>
</html><?php }} ?>
