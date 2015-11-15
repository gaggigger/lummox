angular.module('UserController', []).controller('UserController',
    function($scope, $routeParams, UserService) {

        var userId = $routeParams.id;

        UserService.getUserReviews(userId)
            .success(function(data) {
                $scope.data = data.data;

                $scope.username = data.data.username.user_name;
            })
            .error(function(data) {
                alert("There has been an error: " + data.data);
            });

    });