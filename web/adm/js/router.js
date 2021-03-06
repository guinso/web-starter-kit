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
	
	when('/config/access', {
		templateUrl:'modules/access/access.html',
		controller:'AccessCtrl'
	}).
	
	when('/login', {
		templateUrl:'modules/login/login.html',
		controller:'LoginCtrl'
	}).

	when('/config/update', {
		templateUrl:'modules/ig-update/ig-update.html',
		controller:'IgUpdateCtrl'
	}).
	
	when('/config/misc', {
		templateUrl:'modules/misc/misc.html',
		controller:'MiscCtrl'
	}).
	
	when('/config/sp', {
		templateUrl:'modules/sp-profile/sp-profile.html',
		controller:'SpCtrl'
	}).
	
	when('/com/cp', {
		templateUrl:'modules/comProfile/comProfile.html',
		controller:'CpCtrl'
	}).
	
	otherwise({ redirectTo: '/config/update'});
	
	//otherwise({ redirectTo: '#'});
}]);