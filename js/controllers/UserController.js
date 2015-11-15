angular.module('UserController', []).controller('UserController',
    function($scope, $routeParams, UserService) {

        var userId = $routeParams.id;

        UserService.getUserReviews(userId)
            .success(function(data) {
                $scope.data = data.data;

                $scope.username = data.data.username.user_name;

                if (data.data.reviews.length === 0) {
                    $scope.noReviews = true;
                }
            })
            .error(function(data) {
                alert("There has been an error: " + data.data);
            });

    });