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
        .when('/error', {
            templateUrl : 'views/error.html'
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
            controller : 'UsersController'
        })
        .when('/viewuser/:id/', {
            templateUrl : 'views/viewuser.html',
            controller : 'UserController'
        })
        .when('/profile', {
            templateUrl : 'views/profile.html',
            controller : 'ProfileController'
        })
        .when('/profile/editreview/:id', {
            templateUrl : 'views/editreview.html',
            controller : 'EditReviewController'
        })
        .when('/viewreview/:id', {
            templateUrl : 'views/viewreview.html',
            controller : 'ReviewController'
        })
        .when('/reviews', {
            templateUrl : 'views/reviews.html',
            controller : 'ReviewsController'
        })
        .when('/registration', {
            templateUrl : 'views/registration.html',
            controller : 'RegistrationController'
        })
        .when('/login', {
            templateUrl : 'views/login.html',
            controller : 'LoginController'
        })
        .when('/logout', {
            templateUrl : 'views/home.html',
            controller : 'LogoutController'
        })
        .when('/verification', {
            templateUrl : 'views/verification.html',
            controller : 'VerificationController'
        })
        .when('/tools/reviewqueue', {
            templateUrl : 'views/reviewqueue.html',
            controller : 'ReviewQueueController'
        })
        .when('/tools/pendingreview/:id', {
            templateUrl : 'views/viewreviewpending.html',
            controller : 'ReviewPendingController'
        })
    .otherwise({
        redirectTo: '/'
    })
	;

}]);