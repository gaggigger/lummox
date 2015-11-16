angular.module('ReviewController', []).controller('ReviewController', function($scope, $routeParams, $localStorage, $confirm, $window, ReviewService, UserService) {

    // do not allow to load review by any other user
    // look up current user
    var userId;
    UserService.getUserRole($localStorage.token)
        .success(function(data) {
            userId = data.data.user_id;

            // now get the review
            var id = $routeParams.id;
            ReviewService.getReview(id)
                .success(function(data) {
                    // if review is by the same user, save it to scope
                    if (data.data.review_author === userId) {
                        $scope.review = data.data;
                    } else {
                        $window.location = '#/error';
                    }
                })
                .error(function(data) {
                    alert('There has been an error: ' + data.data);
                });
        })
        .error(function(data) {
            alert('An error occurred: ' + data.data);
        });



    $scope.preview = function() {
        $scope.previewPosted = true;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() +1;
        var yyyy = today.getFullYear();
        $scope.previewReviewDatetime = dd + "." + mm + "." + yyyy;
    };

    $scope.save = function(isValid) {
        if (isValid) {
            $localStorage.review = $scope.review;
            $confirm({
                text: 'Are you sure you want to save these changes to this review?',
                title: 'Save changes to review',
                ok: 'Save',
                cancel: 'Cancel'
            }).then(function() {
                var review = {
                    reviewTitle : $scope.review.review_title,
                    reviewScore : $scope.review.review_score,
                    reviewText : $scope.review.review_text,
                    reviewFilmId : $scope.review.review_film_id,
                    token : $localStorage.token
                };
                ReviewService.save(review)
                    .success(function(data) {
                        clearFields();
                        delete $localStorage.review;
                        $confirm({
                            text: 'Thanks for your submission! Your review has been published.',
                            title: 'Review published',
                            ok: 'OK'
                        }).then(function(){
                            $window.location = '#/profile';
                        });
                    }).error(function(data) {
                        $confirm({
                            text: 'Error saving the review: ' + data.data,
                            title: 'Error',
                            ok: 'OK'
                        });
                        getDataFromStorage();
                    });
            });
        }
    };

    var getDataFromStorage = function() {
        if (typeof $localStorage.review !== 'undefined') {
            $scope.review = $localStorage.review.review;
        }
    };

    var clearFields = function() {
        delete $localStorage.review;
        $scope.user = {};
        $scope.review = {};
        $scope.film = {};
    };

});