    {* Login/Giriş Sayfası *}
    {literal}
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
    {/literal}