angular.module('UserService', []).factory('UserService', function($http) {
    return {
        getUsers : function() {
            return $http.get('/users');
        },
        getUserReviews : function(userId) {
            return $http.post('/users/reviews');
        }
    }
});