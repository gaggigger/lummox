angular.module('appRoutes', []).config(['$routeProvider', '$locationProvider',
                                        function($routeProvider) {
	
	// map correct view and controller to url request
	
	$routeProvider
	
	// home page
	.when('/', {
		templateUrl : 'views/home.html',
		controller : 'MainController'
	})
	.when('/about', {
		templateUrl : 'views/about.html',
		controller : 'MainController'
	})
        .when('/films', {
            templateUrl : 'views/films.html',
            controller : 'FilmsController'
        })
        .when('/viewfilm/:id', {
            templateUrl : 'views/viewfilm.html',
            controller : 'FilmController'
        })
        .when('/viewfilm/:id/review/write', {
            templateUrl : 'views/writereview.html',
            controller : 'FilmController'
        })
        .when('/users', {
            templateUrl : 'views/users.html',
            controller : 'UserController'
        })
        .when('/viewuser/:id/', {
            templateUrl : 'views/viewuser.html',
            controller : 'UserController'
        })
    .otherwise({
        redirectTo: '/'
    })
	;

}]);