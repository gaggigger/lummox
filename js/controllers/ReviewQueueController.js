angular.module('ReviewQueueController', []).controller('ReviewQueueController', function($scope, ReviewService, $localStorage, $window, UserService) {

    if (typeof $localStorage.token !== 'undefined') {
        UserService.getUserRole($localStorage.token)
            .success(function(data) {
                if (data.data.user_role_id === 1 || data.data.user_role_id === 2) {
                    ReviewService.getAllPending($localStorage.token)
                        .success(function(data) {
                            $scope.reviews = data.data;
                        })
                        .error(function(data) {
                            alert('There has been an error: ' + data.data);
                        });
                } else {
                    $window.location = '#/error'
                }
            }).error(function(data) {
                alert('Error occurred: ' + data.data)
            });
    } else {
        $window.location = '#/error'
    }


});