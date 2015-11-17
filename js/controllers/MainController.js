angular.module('MainController', [])
.controller('MainController', function($scope, $localStorage, $window, $location, UserService) {

        // active nav element
        $scope.navIsActive = function(viewLocation) {
            return viewLocation === $location.path();
        };

        // look up localStorage for token
        if (typeof $localStorage.token !== 'undefined') {
            // user is logged in
            // check if user is on an unverified account
            UserService.getUserRole($localStorage.token)
                .success(function(data) {
                    if (data.data.role_name === 'Unverified') {
                        $scope.unverified = true;
                        $window.location = '#/verification';
                        // redirect to verification page forever
                    } else {
                        if (data.data.role_name === 'Admin' || data.data.role_name === 'Moderator') {
                            $scope.showTools = true;
                        }
                        $scope.userId = data.data.user_id;
                        $scope.username = data.data.user_name;
                        $scope.loggedIn = true;
                    }
                })
                .error(function(data) {
                    alert('An error occurred: ' + data.data);
                });
        }


});