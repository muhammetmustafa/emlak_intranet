/**
 * Created by MMC on 20.10.2014.
 */

angular.module('EmlakNET')

    .controller('IlanOlusturController', function($scope, $http, $location, hataServisi)
    {
        $scope.form_baslik = "Yeni İlan";

        $scope.hataGoster = function(hata)
        {
            return hataServisi.mesaj(hata);
        };

        $scope.formu_gonder = function()
        {
            $http
                .post('ajax/ilan_olustur.php', $scope.ilan)
                .success(function(data)
                {
                    if (data.cevap.DURUM === 1)
                    {
                        $location.path("/benim/ilanlarim");
                    }
                });
        };
    })

    .controller('GeoController', function($scope, geoServisi)
    {
        $scope.$watch('ilan.il', function(yeni_il)
        {
            if (yeni_il !== 0 && yeni_il !== 'undefined')
            {
                geoServisi.ilceler(yeni_il).success(function(data)
                {
                    $scope.ilceler = data.cevap.c;
                });
            }
        });

        $scope.$watch('ilan.ilce', function(yeni_ilce)
        {
            if (yeni_ilce !== 0 && yeni_ilce !== 'undefined')
            {
                geoServisi.semtler(yeni_ilce).success(function(data)
                {
                    $scope.semtler = data.cevap.c;
                });
            }
        });

        $scope.$watch('ilan.semt', function(yeni_semt)
        {
            if (yeni_semt !== 0 && yeni_semt !== 'undefined')
            {
                geoServisi.mahalleler(yeni_semt).success(function(data)
                {
                    $scope.mahalleler = data.cevap.c;
                });
            }
        });
    })

    .controller('IlanDuzenleController', function($scope, $http, $location, $routeParams, geoServisi, hataServisi, iller, ilan)
    {
        $scope.form_baslik = "İlan Düzenle";

        $scope.iller = iller.data.cevap.c;

        if (ilan.data.hatamiktari > 0)
        {
            $location.path('/hata');
        }
        else
        {
            $scope.ilan = ilan.data.cevap.ilan.veri;
        }

        $scope.formu_gonder = function()
        {
            $http
                .post('ajax/ilan_guncelle.php', {id: $routeParams.ilan_id, ilan: $scope.ilan})
                .success(function(data)
                {
                    if (data.cevap.DURUM == 1)
                    {
                        $location.path("/benim/ilanlarim");
                    }
                });
        };

        $scope.hataGoster = function(hata)
        {
            return hataServisi.mesaj(hata);
        };

        $scope.vazgec = function()
        {
            $location.path('benim/ilanlarim');
        };
    })

    .controller('IlanlariListeleController', function($scope, $http, ilanServisi, ilanlarim)
    {
        $scope.silinecek_ilan_id = -1;
        $scope.ilanlar = ilanlarim.data.cevap.ilanlarim;

        $scope.silinecek_ilan_id_ata = function(ilan_id)
        {
            $scope.silinecek_ilan_id = ilan_id;
        };

        $scope.sil = function()
        {
            ilanServisi.sil($scope.silinecek_ilan_id)
                .success(function(data)
                {
                    if (data.cevap.DURUM == 1)
                    {
                        var indis = 0;

                        for (var i = 0; i < $scope.ilanlar.length; i++)
                        {
                            if ($scope.ilanlar[i].id == $scope.silinecek_ilan_id)
                            {
                                indis = i;
                            }
                        }

                        $scope.ilanlar.splice(indis, 1);
                    }
                });
        }
    })

    .controller('MenuController', function($scope, $http, $location, istekServisi, mesajServisi, menuServisi, bildirimServisi)
    {
        mesajServisi.mesajlarim($scope);
        istekServisi.istekler_istatistik($scope);
        bildirimServisi.bildirimlerim($scope);

        $scope.cikis = function()
        {
            menuServisi.cikis();
        };

    })

    .controller('IlanGosterController', function($scope, $http, $routeParams, $location, ilan, istekServisi, mesajServisi, ilanServisi)
    {
        $scope.istek = {};
        $scope.ilan_id = $routeParams.ilan_id;

        if (ilan.data.hatamiktari > 0)
        {
            $location.path('/hata');
        }
        else
        {
            $scope.ilan = ilan.data.cevap.ilan;
            istekServisi.istekler($scope);
        }

        $scope.istek_gonder = function()
        {
            istekServisi.gonder($scope);
        };

        $scope.mesaj_gonder = function()
        {
            mesajServisi.gonder($scope);
        };

        $scope.istekten_vazgec = function()
        {
            istekServisi.vazgec()
                .success(function(data)
                {
                    $scope.ilan.istek = !data.cevap.DURUM;
                    $scope.istek.ek = !data.cevap.DURUM == 1 ? "" : $scope.istek.ek;
                });
        };

        $scope.ilani_sil = function()
        {
            ilanServisi.sil($routeParams["ilan_id"])
                .success(function(data)
                {
                    if (data.hatamiktari == 0 && data.cevap.DURUM == 1)
                    {
                        $location.path("benim/ilanlarim");
                    }
                });
        };
    })

    .controller('YeniIlanController', function($scope, $state)
    {
        $scope.ilan = {};
        $scope.adimlar = [false, false, false, false, false];
        $scope._state = $state;
        $scope.siniflar = ["tamamlanmamis-adim", "acik-adim", "simdiki-adim"];

        $scope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams)
        {
            var simdiki_adim = adim_no(toState.url);

            if (simdiki_adim != 1 && $scope.adimlar[simdiki_adim - 2] == false)
            {
                //eğer şimdiki form geçerli değilse state değiştirme
                event.preventDefault();
            }
        });

        $scope.$on('$stateChangeSuccess', function()
        {
            $scope._state = $state;

            //tüm adımların formları geçerli mi kontrol edelim.
            var tum_adimlar = false;
            angular.forEach($scope.adimlar, function(value, key)
            {
                tum_adimlar |= value;
            })

            //eğer hiç bir adım tamamlanmamışsa adım 1 e git.
            if (!tum_adimlar)
            {
                $state.go('yeni.adim1');
            }
        });

        $scope.$watch('fadim.$valid', function(yeni_deger)
        {
            var simdiki_adim = adim_no($state.current.url);
            $scope.adimlar[simdiki_adim - 1] = yeni_deger;
        });

        $scope.ilerle = function()
        {
            var simdiki_adim = adim_no($state.current.url);
            var sonraki_adim = simdiki_adim + 1;

            if ($scope.fadim.$valid)
            {
                $scope.adimlar[simdiki_adim - 1] = $scope.fadim.$valid;
                $state.go("yeni.adim" + sonraki_adim);
            }
        };

        function adim_no(adim)
        {
            return parseInt(adim.slice(1).match(/(\d+)/)[0]);
        };
    })

    .controller('AramaController', function($scope, $location, aramaServisi)
    {
        $scope.sonuclar = [];
        $scope.ilana_tiklandi_mi = false;
        $scope.sonuc_indis = 0;

        $scope.ara = function()
        {
            if ($scope.aranan != "")
            {
                $scope.ilana_tiklandi_mi = false;
                $scope.sonuc_indis = 0;
                aramaServisi.ara($scope, $scope.aranan);
            }
            else
            {
                $scope.sonuclar = [];
            }
        };

        $scope.ilana_tiklandi = function()
        {
            $scope.ilana_tiklandi_mi = true;
        };

        $scope.sonuclara_gec = function(olay)
        {
            if (olay.keyCode == 40) //aşağı tuşu
            {
                $scope.ilana_tiklandi_mi = false;
                $scope.sonuc_indis = ($scope.sonuc_indis + 1) % $scope.sonuclar.length;
            }
            if (olay.keyCode == 38) //yukarı tuşu
            {
                $scope.ilana_tiklandi_mi = false;
                $scope.sonuc_indis = ($scope.sonuc_indis + $scope.sonuclar.length - 1) % $scope.sonuclar.length;
            }
            if (olay.keyCode == 13) //enter tuşu
            {
                $location.path('ilanlar/' + $scope.sonuclar[$scope.sonuc_indis].kod);
                $scope.ilana_tiklandi_mi = true;
            }
        };
    })

    .controller('IsteklerimController', function($scope, $routeParams, isteklerim)
    {
        $scope.isteklerim = isteklerim.data.cevap.isteklerim;

        if ($routeParams["tur"] == "gelen")
        {
            $scope.baslik = "İlanlarıma Yapılan Devir İstekleri";
            $scope.istek_yapan_tur = "İstek Yapan";
        }
        else
        {
            $scope.baslik = "Yaptığım Devir İstekleri";
            $scope.istek_yapan_tur = "İlan Sahibi";
        }
    })

    .controller('IstekController', function($scope, $routeParams, istek, istekServisi, $location)
    {
        $scope.istek_kod = $routeParams.istek_id;

        if (istek.data.hatamiktari == 0)
        {
            $scope.istek = istek.data.cevap.istek;
        }
        else
        {
            $location.path('/hata');
        }

        $scope.cevap = function(cevap)
        {
            istekServisi.istekCevap($scope, cevap);
        };

        $scope.istekten_vazgec = function($scope)
        {
            istekServisi.vazgec()
                .success(function(data)
                {
                    if (data.hatamiktari == 0)
                    {
                        if (data.cevap.DURUM == 1)
                        {
                            $scope.istek.istekCevapDurum = "Vazgeçme başarılı.";
                        }
                    }
                });
        };
    })

    .controller('MesajlariListeleController', function($scope, mesajServisi)
    {
        mesajServisi.mesajlarim($scope);
    })

    .controller('MesajController', function($scope, mesaj)
    {
        $scope.mesaj = mesaj.data.cevap.mesaj;
    })
;