angular.module('MyApp').

controller('LoginCtrl', function($scope, $resource, $LoginService, $location, $window) {
	
	$scope.usr = {username: '', password: '', errorMsg: ''};
	
	$scope.usr.checkKey = function(event) {
		if(event.which == 13) { // <ENTER> key detected
			$scope.usr.login();
		}
	}
	
	$scope.usr.login = function() {
		$LoginService.login($scope.usr.username, $scope.usr.password,
			function() {
				if($scope.navigation.stackUrl) {
					var urll = $scope.navigation.stackUrl;
					$scope.navigation.stackUrl = '';
					
					//restore back previous attempt visit URL
					$location.path(urll);
				} else {
					$location.path('#');
				}
				
				//x $window.location.href = 'asd/qwe';
			},
			function() {
				$scope.usr.errorMsg = 'Login failed, please check username and password.';
			}
		);
	};
	
	$scope.navigation.plainPage = true;
});