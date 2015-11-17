angular.module('ReviewPendingController', []).controller('ReviewPendingController', function($scope, $routeParams, $localStorage, $confirm, $window, ReviewService, UserService) {

    var id = $routeParams.id;

    ReviewService.getReview(id)
        .success(function(data) {
            $scope.review = data.data;
        })
        .error(function(data) {
            alert('There has been an error: ' + data.data);
        });

    $scope.reject = function() {
        $confirm({
            text : 'Are you sure you want to reject this review?',
            title : 'Reject',
            ok : 'Yes',
            cancel : 'Cancel'
        }).then(function() {
            ReviewService.reject($scope.review, $localStorage.token)
                .success(function(data) {
                    $confirm({
                        text : 'Review has been rejected.',
                        title : 'Reject',
                        ok : 'OK'
                    }).then(function() {
                        $window.location = '#/tools/reviewqueue';
                    });
                })
                .error(function(data) {
                    $confirm({
                        text : 'Error occurred. ' + data.data,
                        title : 'API error',
                        ok : 'OK'
                    }).then(function() {
                        $window.location = '#/tools/reviewqueue';
                    });
                });
        });

    };

    $scope.accept = function() {
        $confirm({
            text : 'Are you sure you want to accept this review?',
            title : 'Accept',
            ok : 'Yes',
            cancel : 'Cancel'
        }).then(function() {
            ReviewService.accept($scope.review, $localStorage.token)
                .success(function(data) {
                    $confirm({
                        text : data.data,
                        title : 'Accept',
                        ok : 'OK'
                    }).then(function() {
                        $window.location = '#/tools/reviewqueue';
                    });
                })
                .error(function(data) {
                    $confirm({
                        text : 'Error occurred. ' + data.data,
                        title : 'API error',
                        ok : 'OK'
                    }).then(function() {
                        $window.location = '#/tools/reviewqueue';
                    });
                });
        });
    };
});