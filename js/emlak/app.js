/**
 * Created by MMC on 23.10.2014.
 */

    angular.module('EmlakNET', ["ui.router", "ui.router.router", "ngResource"])

    .config(function($stateProvider, $urlRouterProvider){
        $stateProvider
            .state("menu", {
                url:"/",
                templateUrl : "suretler/karsilama",
                controller : "MenuController"
            })
            .state("yeni_ilan", {
                url:"/yeni_ilan",
                templateUrl : "suretler/ilanformu",
                controller : "IlanOlusturController"
            })
            .state("ilanlarim", {
                url:"/ilanlarim",
                templateUrl : "suretler/ilanlar",
                controller : "IlanlariListeleController",
                resolve : {
                    ilanlarim : function(ilanServisi) {return ilanServisi.ilanlarim();}
                }
            })
            .state("yeni", {
                url:"/yeni",
                abstract: true,
                notify: true,
                templateUrl : "suretler/yeni",
                controller : "YeniIlanController"
            })
            .state("yeni.adim1", {
                url:"/adim1",
                templateUrl : "suretler/yeni.adim1"
            })
            .state("yeni.adim2", {
                url:"/adim2",
                templateUrl : "suretler/yeni.adim2"
            })
            .state("yeni.adim3", {
                url:"/adim3",
                templateUrl : "suretler/yeni.adim3"
            })
            .state("yeni.adim4", {
                url:"/adim4",
                templateUrl : "suretler/yeni.adim4"
            })
            .state("yeni.adim5", {
                url:"/adim5",
                templateUrl : "suretler/yeni.adim5"
            })
            .state("ilanlar", {
                url:"/ilanlar/:ilan_id",
                templateUrl : "suretler/ilan",
                controller: "IlanGosterController",
                resolve : {
                    iller: function (geoServisi) {return geoServisi.iller();},
                    ilan: function(ilanServisi, $stateParams){return ilanServisi.al('g', $stateParams.ilan_id);}
                }
            })
            .state("ilanlar.duzenle", {
                url:"/duzenle",
                templateUrl : "suretler/ilanform",
                controller : "IlanDuzenleController",
                resolve : {
                    iller: function (geoServisi) {return geoServisi.iller();},
                    ilan: function(ilanServisi, $stateParams){return ilanServisi.al('d', $stateParams.ilan_id);}
                }
            })
            .state("isteklerim", {
                url:"/isteklerim/:tur",
                templateUrl : "suretler/istekler",
                controller : "IsteklerimController",
                resolve : {
                    isteklerim : function (istekServisi, $stateParams) {return istekServisi.isteklerim($stateParams.tur);}
                }
            })
            .state("ilanlar.istekler", {
                url:"/istekler/:istek_id",
                templateUrl : "suretler/istek",
                controller : "IstekController",
                resolve : {
                    istek : function(istekServisi, $stateParams){return istekServisi.istek($stateParams.istek_id);}
                }
            })
            .state("mesajlarim", {
                url:"/mesajlarim",
                templateUrl : "suretler/mesajlar",
                controller: "MesajlariListeleController"
            })
            .state("mesajlarim.mesaj", {
                url:"/:mesaj_id",
                templateUrl : "suretler/mesaj",
                controller: "MesajController",
                resolve: {
                    mesaj : function(mesajServisi, $stateParams){return mesajServisi.mesaj($stateParams.mesaj_id);}
                }
            })
            .state("hata", {
                url:"/hata",
                templateUrl : "suretler/hata"
            })
            ;
    })

    .config(function($locationProvider){
        if (window.history && history.pushState){
            $locationProvider.html5Mode(true);
        }
    });
