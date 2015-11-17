angular.module('ReviewService', []).factory('ReviewService', function($http) {
   return {
       publish : function(review) {
           return $http.post('api/review/publish', review);
       },
       getReview : function(reviewId) {
          return $http.post('api/review/get', reviewId);
       },
       getAll : function() {
           return $http.get('api/reviews');
       },
       getAllPending : function(token) {
           return $http.get('api/admin/reviews/pending', {
               headers : {'Authorization' : 'Bearer ' + token}
           })
       },
       save : function(review) {
           return $http.post('api/review/save', review);
       },
       delete : function(review) {
           return $http.post('api/review/delete', review, {
               headers : {'Authorization' : 'Bearer ' + token}
           });
       },
       reject : function(review, token) {
           return $http.post('api/admin/review/reject', review, {
               headers : {'Authorization' : 'Bearer ' + token}
           });
       },
       accept : function(review, token) {
           return $http.post('api/admin/review/accept', review, {
               headers : {'Authorization' : 'Bearer ' + token}
           });
       }
   }
});