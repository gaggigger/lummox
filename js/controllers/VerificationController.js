angular.module('VerificationController', []).controller('VerificationController',
    function($scope, $window, $localStorage) {

    $scope.logout = function() {
        delete $localStorage.token;
        $scope.unverified = false;
        $scope.loggedIn = false;
        $window.location = '#/';
    }
});