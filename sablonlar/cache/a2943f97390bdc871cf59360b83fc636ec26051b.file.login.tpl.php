<?php /* Smarty version Smarty-3.1.21-dev, created on 2014-11-15 23:48:46
         compiled from "sablonlar\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:232105467ca3e36ad23-24467081%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a2943f97390bdc871cf59360b83fc636ec26051b' => 
    array (
      0 => 'sablonlar\\login.tpl',
      1 => 1416088042,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '232105467ca3e36ad23-24467081',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_5467ca3e3e4b29_90263659',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5467ca3e3e4b29_90263659')) {function content_5467ca3e3e4b29_90263659($_smarty_tpl) {?>    
    
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
