angular.module('ReviewsController', []).controller('ReviewsController', function($scope, ReviewService) {

    ReviewService.getAll()
        .success(function(data) {
            $scope.reviews = data.data;
        })
        .error(function(data) {
            alert('There has been an error: ' + data.data);
        });


});