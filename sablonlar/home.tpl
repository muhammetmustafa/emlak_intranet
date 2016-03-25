    {literal}
    <div class="navbar navbar-default navbar-static-top" role="navigation" ng-controller="MenuController">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Menü</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/emlak_intranet">
                    <img src="img/logo/ei_logo.png" alt="Logo" class="navbar-brand"/>
                </a>
            </div>
            <div class="navbar-collapse collapse" ng-controller="AramaController">
                <form class="navbar-form navbar-left">
                    <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   placeholder="İlan ya da emlakçı ara.."
                                   ng-keydown="sonuclara_gec($event)"
                                   ng-change="ara()"
                                   ng-click="ara()"
                                   ng-model="aranan"/>
                    </div>
                    <ul class="nav-sonuc list-group hide" ng-class="{'show' : sonuclar.length && !ilana_tiklandi_mi}">
                        <li ng-repeat="sonuc in sonuclar" class="list-group-item" ng-class="{'list-group-item-secili' : $index == sonuc_indis}">
                            <a ng-href="ilanlar/{{sonuc.kod}}" ng-bind-template="{{sonuc.ilan}}" ng-click="ilana_tiklandi()"></a>
                        </li>
                    </ul>
                </form>
                <ul class="nav navbar-nav navbar-right">
                    <li ui-sref-active="active">
                        <a ui-sref="yeni.adim1" href="">
                            <span class="glyphicon glyphicon-plus"></span>
                            <span class="saga_itekle">Yeni İlan<sup>beta</sup></span>
                        </a>
                    </li>
                    <li>
                        <a ui-sref="yeni_ilan">
                            <span class="glyphicon glyphicon-plus"></span>
                            <span class="saga_itekle">Yeni İlan</span>
                        </a>
                    </li>
                    <li>
                        <a ui-sref="ilanlarim">
                            <span class="glyphicon glyphicon-list"></span>
                            <span class="saga_itekle">İlanlarim</span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <span>İstekler</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="isteklerim/giden">
                                    <span class="glyphicon glyphicon-arrow-up" title=""></span>
                                    <span class="saga_itekle">Benim Yaptığım İstekler</span>
                                    <span class="badge" ng-bind="istekler.giden" ng-show="istekler.giden"></span>
                                </a>
                            </li>
                            <li>
                                <a href="isteklerim/gelen" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-arrow-down" title=""></span>
                                    <span class="saga_itekle">Bana Yapılan İstekler</span>
                                    <span class="badge" ng-bind="istekler.gelen" ng-show="istekler.gelen"></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-flag"></span>
                            <span class="" ng-bind="bildirimlerim.miktar"  ng-show="bildirimlerim.miktar"></span>
                        </a>
                        <ul class="dropdown-menu mesaj" role="menu">
                            <li class="dropdown-header" ng-hide="bildirimlerim.miktar">
                                <span>Hiç bildiriminiz yok!</span>
                            </li>
                            <li ng-repeat="bildirim in bildirimlerim.silinmis">
                                <span class="dropdown-header">
                                    Devir isteğinde bulunduğunuz <strong>"{{bildirim.ilan}}"</strong> başlıklı ilan silindiğinden <br/>
                                    <i style="text-decoration: underline;">{{bildirim.tarih}}</i> tarihinde yapmış olduğunuz<br/>
                                    devir isteği iptal edilmiştir.
                                </span>
                            </li>
                            <li ng-repeat="bildirim in bildirimlerim.incelenmis">
                                <span class="dropdown-header">
                                    Devir isteğinde bulunduğunuz <strong>"{{bildirim.ilan}}"</strong> başlıklı ilan için<br/>
                                    <i style="text-decoration: underline;">{{bildirim.tarih}}</i> tarihinde yapmış olduğunuz<br/>
                                    devir isteğini ilan sahibi incelemiştir.
                                </span>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-envelope"></span>
                            <span class="" ng-bind="mesajlarim.length"  ng-show="mesajlarim.length"></span>
                        </a>
                        <ul class="dropdown-menu mesaj" role="menu">
                            <li class="dropdown-header" ng-hide="mesajlarim.length">
                                <span>Hiç mesajınız yok!</span>
                            </li>
                            <li class="dropdown-header btn btn-link" ng-show="mesajlarim.length">
                                <a href="mesajlarim">Tümünü Göster</a>
                            </li>
                            <li class="mesaj-item" ng-repeat="mesaj in mesajlarim">
                                <a class="mesaj-gonderen"
                                   ng-href="emlakcilar/{{mesaj.mesaj_gonderen_kod}}">
                                    {{mesaj.mesaj_gonderen_ad}}
                                    <span class='mesaj-tarih'>{{mesaj.tarih}})</span>
                                </a>
                                <a class="mesaj-konu" ng-href="mesajlarim/{{mesaj.mesaj_kod}}" ng-bind="mesaj.konu"></a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="glyphicon glyphicon-wrench"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-text">
                                <a href="profilim"><strong>{$smarty.session.kullanici_adi}</strong></a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="ayarlar">Ayarlar</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="yardim">Yardım</a>
                            </li>
                            <li>
                                <a href="" ng-click="cikis()">Çıkış</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container asagi_itekle">
        <div class="container">
            <div class="container-fluid" ui-view>
            </div>
        </div>
    </div>
    <div class="footer hide">
        <div class="container text-center">
            <div class="">2014 &copy; Muhammet Mustafa Çalışkan</div>
        </div>
    </div>
    {/literal}