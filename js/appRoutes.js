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
    .otherwise({
        redirectTo: '/'
    })
	;

}]);