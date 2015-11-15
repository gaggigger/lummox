angular.module('UsersController', []).controller('UsersController',
    function($scope, UserService) {

        UserService.getUsers()
            .success(function(data) {
                $scope.users = data.data;
            })
            .error(function(data) {
                $scope.error = data.data;
                alert(data.data);
            });

});