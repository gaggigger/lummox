angular.module('FilmController', []).controller('FilmController', function($scope, $routeParams, FilmService) {
    var id = $routeParams.id;
    FilmService.getFilmData(id)
        .success(function(data) {
            $scope.film = data.data;
            $scope.releaseYear = data.data.film_release_date.substring(0,4);
        })
        .error(function(data) {
            $scope.error = data.data;
            alert("there has been an error : " + data.data);
        });
});