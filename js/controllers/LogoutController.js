angular.module('LogoutController', []).controller('LogoutController', function($confirm, $scope, $window, $route, $localStorage) {
    $confirm({
        text: 'Are you sure you want to log out?',
        title: 'Log out',
        ok: 'OK'
    }).then(function() {
        delete $localStorage.token;
        $scope.loggedIn = false;
        $window.location = '#/';
        $window.location.reload();
    });
});