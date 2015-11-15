angular.module('ReviewService', []).factory('ReviewService', function($http) {
   return {
       publish : function(review) {
           return $http.post('api/review/publish', review);
       }
   }
});