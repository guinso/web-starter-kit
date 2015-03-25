angular.module('MyApp').

controller('LoginCtrl', function($scope, $resource, $location, $window, $util) {
	
	$scope.usr = {username: '', password: '', errorMsg: '', rememberMe:false};
	
	$scope.usr.checkKey = function(event) {
		if(event.which == 13) { // <ENTER> key detected
			$scope.usr.login();
		}
	}
	
	$scope.usr.login = function() {
		$scope.usr.errorMsg = '';
		
		$resource('api/login').save(
			{username: $scope.usr.username, pwd: $scope.usr.password, rememberMe: $scope.usr.rememberMe},
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
				$scope.usr.errorMsg = 'Login failed, please check your username and password.';
			}
		);
	};
	
	$scope.navigation.plainPage = true;
});