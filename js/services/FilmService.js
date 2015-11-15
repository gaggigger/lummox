angular.module('FilmService', []).factory('FilmService', function($http) {

    return {
        getFilms : function() {
            return $http.get('api/films');
        },
        getFilmData : function(filmId) {
            return $http.post('api/filmdata', filmId);
        }
    }
});