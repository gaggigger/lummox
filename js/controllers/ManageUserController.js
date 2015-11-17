angular.module('ManageUserController', []).controller('ManageUserController',
    function($scope, $routeParams, $localStorage, $confirm, $window, UserService) {

        if (typeof $localStorage.token !== 'undefined') {
            UserService.getUserRole($localStorage.token)
                .success(function(data) {
                    if (data.data.user_role_id === 1) {

                        var id = $routeParams.id;
                        UserService.getUser($localStorage.token, id)
                            .success(function(data) {
                               $scope.user = data.data;
                               if (data.data.user_role_id === 4) {
                                   $scope.unverified = true;

                                   // verify user
                                   $scope.verify = function() {
                                       $confirm({
                                           text : 'Are you sure you want to accept this user to the site?',
                                           title : 'Accept',
                                           ok : 'Yes',
                                           cancel : 'Cancel'
                                       }).then(function() {
                                           UserService.verifyUser($localStorage.token, $scope.user)
                                               .success(function(data) {
                                                   $confirm({
                                                       text : data.data,
                                                       title : 'Accept',
                                                       ok : 'OK'
                                                   }).then(function() {
                                                       $window.location = '#/tools/users';
                                                   });
                                               })
                                               .error(function(data) {
                                                   $confirm({
                                                       text : 'Error occurred. ' + data.data,
                                                       title : 'API error',
                                                       ok : 'OK'
                                                   }).then(function() {
                                                       $window.location = '#/tools/reviewqueue';
                                                   });
                                               });
                                       });
                                   };

                               }
                            })
                            .error(function(data) {
                                alert('An error occurred: ' + data.data);
                            });

                    } else {
                        $window.location = '#/error'
                    }
                }).error(function(data) {
                    alert('Error occurred: ' + data.data)
                });
        } else {
            $window.location = '#/error'
        }




    });