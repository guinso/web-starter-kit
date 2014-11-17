angular.module('MyApp').

service('$LoginService', ['$resource', function($resource) {
	this.getCurrentUser = function(successCallback, failCallback) {
		return $resource('api/current-user').get(
			successCallback, 
			failCallback);
	};
	
	this.login = function(usr, password, successCallback, failCallback) {
		return $resource('api/login').save(
			{username: usr, pwd: password}, 
			successCallback, 
			failCallback);
	};
	
	this.logout = function(successCallback, failCallback) {
		return $resource('api/logout').get(
			{}, 
			successCallback, 
			failCallback);
	};
	
	return this;
}]);