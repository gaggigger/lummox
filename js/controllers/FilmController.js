angular.module('FilmController', []).controller('FilmController',
    function($scope, $routeParams, $modal, $confirm, FilmService, ReviewService) {
    var id = $routeParams.id;
    FilmService.getFilmData(id)
        .success(function(data) {
            $scope.film = data.data;
        })
        .error(function(data) {
            $scope.error = data.data;
            alert("there has been an error : " + data.data);
        });

    $scope.preview = function() {
        $scope.previewPosted = true;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() +1;
        var yyyy = today.getFullYear();
        $scope.previewReviewDatetime = dd + "." + mm + "." + yyyy;
    };

    $scope.publish = function(isValid) {
        if (isValid) {
            $confirm({
                text: 'Are you sure you want to publish this review?',
                title: 'Publish review',
                ok: 'Publish',
                cancel: 'Cancel'
            }).then(function(result) {
                var review = {
                    reviewAuthor : $scope.user.user_name,
                    reviewTitle : $scope.review.review_title,
                    reviewScore : $scope.review.review_score,
                    reviewText : $scope.review.review_text,
                    reviewFilmId : $scope.film.film_id
                };
               ReviewService.publish(review);
            });
        }
    };

});