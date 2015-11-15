angular.module('UserController', []).controller('UserController',
    function($scope, $routeParams, UserService) {

        var userId = $routeParams.id;

        UserService.getUserReviews(userId)
            .success(function(data) {

            })
            .error(function(data) {

            });

    });