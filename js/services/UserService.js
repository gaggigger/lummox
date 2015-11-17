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
        },
        login : function(formData) {
            return $http.post('api/users/authenticate', formData);
        },
        getUserRole : function(token) {
            return $http.get('api/users/role', {
                headers : {'Authorization' : 'Bearer ' + token}
            });
        },
        getUser : function(token, userId) {
            return $http.post('api/admin/getuser', userId, {
                headers : {'Authorization' : 'Bearer ' + token}
            });
        },
        verifyUser : function(token, user) {
            return $http.post('api/admin/users/verify', user, {
                headers : {'Authorization' : 'Bearer ' + token}
            });
        }
    }
});

