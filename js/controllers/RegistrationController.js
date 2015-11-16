angular.module('RegistrationController', []).controller('RegistrationController',
    function($scope, $localStorage, $window, UserService) {

        $scope.registration = function(isValid) {
            if (isValid) {
                UserService.registration($scope.formData)
                    .success(function(data) {
                        if(data.success === true) {
                            $localStorage.token = data.data.token;
                            $window.location = '#/';
                        }
                    })
                    .error(function(data) {
                        alert('An error occurred: ' + data.data);
                    });
            }
        }

});