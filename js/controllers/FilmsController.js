angular.module('FilmsController', []).controller('FilmsController',
    function($scope, FilmService) {
    FilmService.getFilms()
        .success(function(data) {
            $scope.films = data.data;
        })
        .error(function(data) {
            alert(data.data);
        });
});