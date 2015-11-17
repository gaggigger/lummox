angular.module('ManageUsersController', []).controller('ManageUsersController',
    function($scope, $localStorage, $window, UserService) {

        if (typeof $localStorage.token !== 'undefined') {
            UserService.getUserRole($localStorage.token)
                .success(function(data) {
                    if (data.data.user_role_id === 1) {
                        UserService.getUsers()
                            .success(function(data) {
                                $scope.users = data.data;
                            })
                            .error(function(data) {
                                $scope.error = data.data;
                                alert(data.data);
                            });
                    } else {
                        $window.location = '#/error';
                    }
                }).error(function(data) {
                    alert('Error occurred: ' + data.data);
                });
        } else {
            $window.location = '#/error';
        }

    });