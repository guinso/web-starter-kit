angular.module('MyApp').

config(['$routeProvider', function($routeProvider) {
	$routeProvider.
	
	when('/config/account-main', {
		templateUrl:'modules/account/account-main.html',
		controller:'AccMainCtrl'
	}).
	
	when('/config/schedule', {
		templateUrl:'modules/schedule/main.html',
		controller:'ScheduleCtrl'
	}).
	
	when('/login', {
		templateUrl:'modules/login/login.html',
		controller:'LoginCtrl'
	}).

	otherwise({ redirectTo: '#'});
}]);