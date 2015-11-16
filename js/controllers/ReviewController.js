angular.module('ReviewController', []).controller('ReviewController', function($scope, $routeParams, $localStorage, $confirm, ReviewService) {

    var id = $routeParams.id;
    ReviewService.getReview(id)
        .success(function(data) {
            $scope.review = data.data;
        })
        .error(function(data) {
            alert('There has been an error: ' + data.data);
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
            $localStorage.review = {
                user : $scope.user,
                review : $scope.review,
                film : $scope.film
            };
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
                    reviewFilmId : $scope.film.film_id,
                    token : $localStorage.token
                };
                ReviewService.save(review)
                    .success(function(data) {
                        clearFields();
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
            $scope.user = $localStorage.review.user;
            $scope.review = $localStorage.review.review;
            $scope.film = $localStorage.review.film;
        }
    };

    var clearFields = function() {
        delete $localStorage.review;
        $scope.user = {};
        $scope.review = {};
        $scope.film = {};
    };

});