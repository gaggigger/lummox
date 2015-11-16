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
       save : function(review) {
           return $http.post('api/review/save', review);
       },
       delete : function(review) {
           return $http.post('api/review/delete', review);
       }
   }
});