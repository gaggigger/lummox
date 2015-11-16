angular.module('LoginController', []).controller('LoginController',
    function($scope, $rootScope, $localStorage, $confirm, $window, $location, $route, UserService) {

        $scope.login = function(isValid) {
            if (isValid) {
                UserService.login($scope.formData)
                    .success(function(data) {
                        if (data.success) {
                            $confirm({
                                text: data.data,
                                title: 'Login',
                                ok: 'OK'
                            }).then(function() {
                                $localStorage.token = data.token;
                                $scope.loggedIn = true;
                                $window.location = '#/';
                                $window.location.reload();
                            });


                        } else {
                            $confirm({
                                text: data.data,
                                title: 'Login',
                                ok: 'OK'
                            });
                        }
                    })
                    .error(function(data) {
                        $confirm({
                            text: data.data,
                            title: 'Login',
                            ok: 'OK'
                        });
                    });
            }
        }

});