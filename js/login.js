/**
 * Created by MMC on 01.11.2014.
 */

    angular.module('Login', [])

    .controller('LoginController', function($scope, $http){
        $scope.login_mesaj = "";
        $scope.kimlik_dogrula = function(){
            $http
                .post('api/rest/login', {id:$scope.login.id, sifre:$scope.login.sifre})
                .success(function(data){
                    if (data.cevap.LOGIN_DURUM == 1){
                        document.location.href="http://localhost/emlak_intranet/"
                    }
                    else
                    {
                        $scope.login_mesaj = "Girdiğiniz değerlerle uyuşan kullanıcı bulunamadı.";
                    }
                });
        }
    });