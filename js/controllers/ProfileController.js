angular.module('ProfileController', []).controller('ProfileController',
    function($scope, $routeParams, $localStorage, UserService) {

        UserService.getUserRole($localStorage.token)
            .success(function(data) {

                var userId = data.data.user_id;
                UserService.getUserReviews(userId)
                    .success(function(data) {
                        $scope.data = data.data;

                        $scope.username = data.data.username.user_name;

                        if (data.data.reviews.length === 0) {
                            $scope.noReviews = true;
                        }
                    })
                    .error(function(data) {
                        alert('There has been an error: ' + data.data);
                    });
            })
            .error(function(data) {
                alert('An error occurred: ' + data.data);
            });

    });
