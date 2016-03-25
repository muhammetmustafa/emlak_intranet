<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-16 22:42:14
         compiled from "sablonlar\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:164695557aba6d64676-20794004%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63acdb674693935b7b6c41184cf27a6132f9f82a' => 
    array (
      0 => 'sablonlar\\login.tpl',
      1 => 1416088042,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '164695557aba6d64676-20794004',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5557aba6e46fa5_67886911',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5557aba6e46fa5_67886911')) {function content_5557aba6e46fa5_67886911($_smarty_tpl) {?>    
    
    <div class="container" ng-controller="LoginController">
        <form class="login form-horizontal" ng-submit="kimlik_dogrula()" name="login">
            <div class="row">
                <div class="form-group">
                    <a href="index.php"><img src="img/logo/ei_logo2.png"/></a>
                    <label class="control-label">ID veya Email</label>
                    <input type="text"
                           class="form-control text-center"
                           placeholder="ID veya Email"
                           ng-model="login.id"
                           required
                           autofocus/>
                </div>
                <div class="form-group">
                    <label class="control-label">Şifre</label>
                    <input type="password"
                           class="form-control text-center"
                           placeholder="Şifre"
                           ng-model="login.sifre"
                           required/>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-lg btn-primary btn-block" value="Giriş Yap"/>
                </div>
                <div class="alert alert-danger" ng-show="login_mesaj != ''" ng-bind="login_mesaj">
                </div>
            </div>
        </form>
    </div>
    <div class="footer container">
        <div class=" text-center">
            <div class="">2014 &copy; Muhammet Mustafa Çalışkan</div>
        </div>
    </div>
    <?php }} ?>
