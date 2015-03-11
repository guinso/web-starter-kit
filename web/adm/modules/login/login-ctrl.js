angular.module('MyApp').

controller('LoginCtrl', function($scope, $resource, $location, $window, $util) {
	
	$scope.usr = {username: '', password: '', errorMsg: ''};
	
	$scope.usr.checkKey = function(event) {
		if(event.which == 13) { // <ENTER> key detected
			$scope.usr.login();
		}
	}
	
	$scope.usr.login = function() {
		$resource('api/login').save(
			{username: $scope.usr.username, pwd: $scope.usr.password},
			function() {
				if($scope.navigation.stackUrl) {
					var url = $scope.navigation.stackUrl;
					$scope.navigation.stackUrl = '';
					
					//restore back previous attempt visit URL
					$location.path(url);
				} else {
					$location.path('#');
				}
			},
			function() {
				$scope.usr.errorMsg = 'Login failed, please check username and password.';
			}
		);
	};
	
	$scope.navigation.plainPage = true;
});