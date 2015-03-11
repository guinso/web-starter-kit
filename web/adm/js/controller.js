angular.module('MyApp').

controller('LoaderCtrl', function($scope, $timeout) {
	$scope.loader.show = false;
	$scope.loader.cnt = 0;
	
	$scope.loader.reset = function(){
		$scope.loader.show = false;
		$scope.loader.cnt = 0;
	};
	
	$scope.loader.showLoader = function(){
		$scope.loader.cnt+=1;
		$scope.loader.show = true;
		
	};
	
	$scope.loader.hideLoader = function(forceHide){
		$scope.loader.cnt-=1;
		
		if($scope.loader.cnt < 1 || forceHide){
			$scope.loader.show = false;
			$scope.loader.cnt = 0;
		}

	};
}).

controller('AppCtrl', function($scope, $resource, $location) {
	window.scope = {
		main: $scope
	};

	$scope.navigation = {
		paths: [],
		args: [],
		plainPage: false,
		stackUrl: ''
	};

	//loader
	$scope.loader = {
		show: false
	};
	
	//login data
	$scope.user = {
		username: '',
		role: '',
		roleId: '',
		login: false,
	}
	
	//misc
	$scope.actives = [{name:'active', value:true}, {name:'inactive', value:false}];
	
	$scope.checkUser = function() {
		$resource('api/current-user').get(
			function(response) {
				var isLogin = response.login;
				if(!isLogin) {
					
					//temporary keep previous requested URL path
					if($location.url() != '/login') {
						$scope.navigation.stackUrl = $location.url();
					}
				
					$location.path('/login');
				} else {
					$scope.user = response;
				}
			},
			function(response) {
				$util.handleErrorMsg(response);
			}
		);
	};
	
	$scope.logout = function() {
		$resource('api/logout').get(
			function() {
				$location.path('/login');
			},
			function() {
				$util.setMsg('logout fail', 'error');
			}
		);
	}
	
	//x $scope.dateRegex = "/^(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$/";
	
	$scope.$on('$locationChangeSuccess', function(event){
		$scope.navigation.plainPage = false;
		$scope.navigation.paths = $location.path().split('/');
		$scope.navigation.args = $location.search();
		
		$scope.checkUser();
		
		//unregister onbeforeunload event
		window.onbeforeunload = null;
		
		//unregister $locationChangeStart event
		$scope.$on('$locationChangeStart', function(event) {
		    return;
		});
	});
});