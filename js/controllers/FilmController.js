angular.module('FilmController', []).controller('FilmController',
    function($scope, $routeParams, $modal, $window, $confirm, $localStorage,
             FilmService, ReviewService) {
    var id = $routeParams.id;
    FilmService.getFilmData(id)
        .success(function(data) {
            $scope.film = data.data;
            getDataFromStorage();
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
            $localStorage.review = {
                user : $scope.user,
                review : $scope.review,
                film : $scope.film
            };
            $confirm({
                text: 'Are you sure you want to publish this review?',
                title: 'Publish review',
                ok: 'Publish',
                cancel: 'Cancel'
            }).then(function() {
                var review = {
                    reviewAuthor : $scope.user.user_name,
                    reviewTitle : $scope.review.review_title,
                    reviewScore : $scope.review.review_score,
                    reviewText : $scope.review.review_text,
                    reviewFilmId : $scope.film.film_id,
                    email : $scope.review.email
                };
               ReviewService.publish(review)
                   .success(function(data) {
                       clearFields();
                       if(data.autopublished) {
                           $confirm({
                               text: 'Thanks for your submission! Your review has been published.',
                               title: 'Review published',
                               ok: 'OK'
                           });
                       }
                       else if (!data.autopublished) {
                           $confirm({
                               text: 'Thanks for your submission! Your review is pending for moderator approval.',
                               title: 'Review added to queue',
                               ok: 'OK'
                           });
                       }
                       $window.location = '#/films';
                   })
                   .error(function(data) {
                       alert('An error occurred: ' + data.data);
                       getDataFromStorage();
                   });

            });
        } else if (!isValid) {
            alert('Invalid form data.');
        }
    };

        $scope.startOver = function() {
            $confirm({
                text : 'Clear all fields? Your review will be lost if you proceed.',
                title : 'Start over',
                ok : 'Go',
                cancel : 'Cancel'
            }).then(function(result) {
                clearFields();
            });
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