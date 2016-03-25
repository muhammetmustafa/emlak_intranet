/**
 * Created by MMC on 26.10.2014.
 */

angular.module('EmlakNET')

    .filter("fiyatOkunusuBas", function(){
        var okunusuBas = function(data){

            var okunus = "";
            var sayi = data.toString();
            var yuzler = ["", "Bin", "Milyon", "Milyar", "Trilyon", "Katrilyon"];
            var onlar = ["On", "Yirmi", "Otuz", "Kırk", "Elli", "Altmış", "Yetmiş", "Seksen", "Doksan", "Yüz"];
            var birler = ["Bir", "İki", "Üç", "Dört", "Beş", "Altı", "Yedi", "Sekiz", "Dokuz", "On" ];

            var yuzler_takip = 0;

            var yuzler_basamagi = "";
            var onlar_basamagi = "";
            var birler_basamagi = "";

            for (var i = sayi.length - 1; i >= 0;)
            {
                if (angular.isDefined(sayi[i-2]) && sayi[i-2] != '0')
                {
                    if (sayi[i-2] == 1)
                        yuzler_basamagi = "Yüz";
                    else
                        yuzler_basamagi = birler[sayi[i-2]-1] + "Yüz";
                }
                else
                {
                    yuzler_basamagi = "";
                }

                if (angular.isDefined(sayi[i-1]) && sayi[i-1] != '0')
                {
                    onlar_basamagi = onlar[sayi[i-1]-1];
                }
                else
                {
                    onlar_basamagi = "";
                }

                if (sayi[i] != '0')
                {
                    birler_basamagi = birler[sayi[i]-1];
                }
                else
                {
                    birler_basamagi = "";
                }

                okunus =  yuzler_basamagi +
                          onlar_basamagi +
                          birler_basamagi + " " +
                          ((yuzler_basamagi == "" & onlar_basamagi == "" & birler_basamagi == "") ? "" : yuzler[yuzler_takip]) + " " +
                          okunus + " ";

                i -= 3;
                yuzler_takip++;
            }

            return okunus;
        };

        return okunusuBas;
    })

    .filter("ilkHarflerBuyuk", function(){
        var ilkHarflerBuyult = function(data)
        {
            if (angular.isString(data))
            {
                var kelimeler = data.trim().split(' ');
                var sonuc = "";
                var kelime = "";

                angular.forEach(kelimeler, function (value, key) {
                    kelime = value.toLocaleLowerCase();
                    kelime[0].toLocaleUpperCase();
                    sonuc += value[0].toLocaleUpperCase() + value.toLocaleLowerCase().slice(1) + " ";
                });

                return sonuc;
            }
            else
            {
                return data;
            }
        }

        return ilkHarflerBuyult;
    })

    .filter("belirginlestir", function(){
        var belirginlestir = function(veri, aranan)
        {
            if (angular.isString(veri))
            {
                return veri.replace(aranan, '<b>' + aranan + '</b>');
            }
            else
            {
                return data;
            }
        }

        return belirginlestir;
    })

    ;