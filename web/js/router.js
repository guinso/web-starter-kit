angular.module('MyApp').

config(['$routeProvider', function($routeProvider) {
	$routeProvider.

	when('/login', {
		templateUrl:'modules/login/login.html',
		controller:'LoginCtrl'
	}).
	
	when('/sample/a', {
		templateUrl:'modules/sample/a/a.html',
		controller:'SampleACtrl'
	}).
	
	when('/sample/b', {
		templateUrl:'modules/sample/b/b.html',
		controller:'SampleBCtrl'
	}).

	otherwise({ redirectTo: '/sample/a'});
}]);