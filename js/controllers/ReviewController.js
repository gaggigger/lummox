angular.module('ReviewController', []).controller('ReviewController', function($scope, $routeParams, $localStorage, $confirm, $window, ReviewService, UserService) {

    var id = $routeParams.id;

    ReviewService.getReview(id)
        .success(function(data) {
            $scope.review = data.data;
        })
        .error(function(data) {
            alert('There has been an error: ' + data.data);
        });

});