/**
 * Created by MMC on 25.10.2014.
 */

angular.module('EmlakNET')

    .factory('geoServisi', function($http)
    {
        return {
            il: function(il_kodu)
            {
                var _il = $http.get('ajax/geo.php?c=d&t=il&id='+il_kodu).success(function(data){
                    return data.cevap;
                });

                return _il;
            },
            iller: function()
            {
                var _iller = $http.get('ajax/geo.php?c=l&t=il&id=0').success(function(data, status, headers, config){
                    return data;
                });

                return _iller;
            },
            ilce: function(ilce_kodu)
            {
                return $http.get('ajax/geo.php?c=d&t=ic&id='+ilce_kodu);
            },
            ilceler: function(il_kodu)
            {
                return $http.get('ajax/geo.php?c=l&t=ic&id='+il_kodu);
            },
            semt: function(ilce_kodu)
            {
                return $http.get('ajax/geo.php?c=d&t=sm&id='+semt_kodu);
            },
            semtler: function(ilce_kodu)
            {
                return $http.get('ajax/geo.php?c=l&t=sm&id='+ilce_kodu);
            },
            mahalle: function(mahalle_kodu)
            {
                return $http.get('ajax/geo.php?c=d&t=mh&id='+mahalle_kodu);
            },
            mahalleler: function(semt_kodu)
            {
                return $http.get('ajax/geo.php?c=l&t=mh&id='+semt_kodu);
            }
        };
    })

    .factory('hataServisi', function()
    {
        return {
            mesaj: function(hata)
            {
                if (hata.tur.required){
                    return "Gerekli alan.";
                }
                if (hata.tur.number){
                    return "Numara girmelisiniz.";
                }
                if (hata.tur.minlength){
                    return "Girdiğiniz değer " + hata.uzunluk.min + " karakterden az olamaz.";
                }
                if (hata.tur.maxlength){
                    return "Girdiğiniz değer " + hata.uzunluk.max + " karakterden fazla olamaz.";
                }
                if (hata.tur.min){
                    return "Girdiğiniz değer " + hata.sinir.min + "'den küçük olamaz.";
                }
                if (hata.tur.max){
                    return "Girdiğiniz değer " + hata.sinir.max + "'den büyük olamaz.";
                }
            }
        };
    })

    .factory('ilanServisi', function($http)
    {
        return {
            al : function(amac, ilan_id)
            {
                return $http
                    .get('ajax/ilan_al.php?a=' + amac + '&id=' + ilan_id)
                    .success(function(data)
                    {
                        return data;
                    });
            },
            ilanlarim : function()
            {
                return $http
                    .get('ajax/ilanlarim.php')
                    .success(function(data)
                    {
                        return data;
                    });
            },
            sil : function(silinecek_ilan_id)
            {
                return $http.post('ajax/ilan_sil.php', silinecek_ilan_id);
            }
        };
    })

    .factory('menuServisi', function($http)
    {
        return {
            cikis:  function()
            {
                return  $http
                    .get('api/rest/cikis')
                    .success(function(data){
                        if (data.cevap.CIKIS == 1)
                        {
                            document.location.href = "index.php";
                        }
                    });
            }
        }
    })

    .factory('istekServisi', function($http, $timeout, $location, $state)
    {
        return {
            istek: function(istek_id)
            {
                return $http
                    .get('ajax/istek.php?istek_kod=' + istek_id)
                    .success(function(data)
                    {
                        return data;
                    });
            },
            istekCevap : function($scope, cevap)
            {
                return $http
                    .post('ajax/istek_cevap.php?', {cevap: cevap, istekKod: $route.current.params.istek_id})
                    .success(function(data)
                    {
                        if (data.hatamiktari == 0)
                        {
                            $scope.istekCevapDurum = data.cevap.durum;

                            $timeout(function(){
                                $location.path("benim/isteklerim/gelen");
                            }, 8000);
                        }
                    });
            },
            istekler: function($scope)
            {
                return $http
                    .get('ajax/ilan_istekler.php?ilan_id=' + $route.current.params.ilan_id)
                    .success(function(data)
                    {
                        if (data.cevap.isteklerim.length > 0)
                        {
                            $scope.istekler = data.cevap.isteklerim;
                        }
                    });
            },
            isteklerim: function(tur)
            {
                return $http
                    .get('ajax/isteklerim.php?t=' + (tur === 'gelen' ? 'gl' : 'gd').toString())
                    .success(function(data)
                    {
                        return data;
                    });
            },
            istekler_istatistik: function($scope)
            {
                var simdi = new Date();
                simdi = simdi.getFullYear() + "-" +
                        simdi.getMonth() + "-" +
                        simdi.getDay() + " " +
                        simdi.getHours() + ":" +
                        simdi.getMinutes() + ":" +
                        simdi.getSeconds();

                return $http
                    .get('ajax/istekler_istatistik.php?t='+ simdi)
                    .success(function(data)
                    {
                        $scope.istekler = data.cevap.istekler;
                    });
            },
            gonder: function($scope)
            {
                return $http
                    .post('ajax/istek_gonder.php', { ilan: $route.current.params.ilan_id, ek : $scope.istek.ek})
                    .success(function(data)
                    {
                        $scope.ilan.istek = data.cevap.DURUM;
                        $scope.istek.ek = data.cevap.DURUM == 1 ? "" : $scope.istek.ek;
                    })
            },
            vazgec: function()
            {
                return $http
                    .post('ajax/istekten_vazgec.php', $route.current.params.ilan_id);
            }
        };
    })

    .factory('aramaServisi', function($http){
        return {
            ara : function($scope, ifade){
                return $http
                    .get('api/rest/ara/' + ifade.toString())
                    .success(function(data){
                        if (data.hatamiktari <= 0)
                        {
                            $scope.sonuclar = data.cevap.sonuclar;
                        }
                    })
            }
        };
    })

    .factory('mesajServisi', function($http)
    {
        return {
            gonder : function($scope)
            {
                return $http
                    .post('ajax/mesaj_gonder.php', {icerik: $scope.mesaj, ilan_id: $route.current.params.ilan_id})
                    .success(function(data){

                    })
            },
            mesajlarim : function($scope)
            {
                return $http
                    .get('ajax/mesajlarim.php')
                    .success(function(data){
                        $scope.mesajlarim = data.cevap.mesajlarim;
                    })
            },
            mesaj: function(mesaj_id)
            {
                return $http
                    .get('ajax/mesaj.php?id=' + mesaj_id)
                    .success(function(data){
                        return data;
                    })
            }
        };
    })

    .factory('bildirimServisi', function($http)
    {
        return {
            bildirimlerim : function($scope)
            {
                return $http
                    .get('api/rest/bildirimlerim')
                    .success(function(data){
                        $scope.bildirimlerim = data.cevap.bildirimlerim;
                    })
            }
        };
    })
    ;