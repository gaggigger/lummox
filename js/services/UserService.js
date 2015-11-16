angular.module('UserService', []).factory('UserService', function($http) {
    return {
        getUsers : function() {
            return $http.get('api/users');
        },
        getUserReviews : function(userId) {
            return $http.post('api/users/reviews', userId);
        },
        registration : function(formData) {
            return $http.post('api/users/registration', formData);
        }
    }
});