angular.module('RegistrationController', []).controller('RegistrationController',
    function($scope, $localStorage, $window, $confirm, UserService) {

        $scope.registration = function(isValid) {
            if (isValid) {
                UserService.registration($scope.formData)
                    .success(function(data) {
                        if(data.success === true) {
                            $confirm({
                                text: data.data,
                                title: 'Registration',
                                ok: 'OK'
                            });
                            $window.location = '#/';
                        }
                    })
                    .error(function(data) {
                        $confirm({
                            text: 'Registration failed! ' + data.data,
                            title: 'Registration',
                            ok: 'OK'
                        });
                    });
            }
        }

});