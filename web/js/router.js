angular.module('MyApp').

config(['$routeProvider', function($routeProvider) {
	$routeProvider.

	when('/login', {
		templateUrl:'modules/login/login.html',
		controller:'LoginCtrl'
	}).

	otherwise({ redirectTo: '#'});
}]);