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

controller('MsgCtrl', function($scope, $timeout) {
	$scope.msg.timer = null;
	$scope.msg.lock = false;
	$scope.msg.show = false;
	
	$scope.msg.setMsg = function(msg, status) {
		//$scope.msg.show = true;
		
		$scope.msg.lock = false;
		$scope.msg.status = status;
		$scope.msg.message = msg;
		
		//POOR-MAN-SOLUTION: ensure run at main thread
		$scope.msg.timer = $timeout($scope.msg.showMsg, 0);
	};
	
	$scope.msg.showMsg = function() {
		$scope.msg.show = true;
		
		$scope.msg.lock = false;
		
		$scope.msg.timer = $timeout($scope.msg.hideMsg, 1500);
	};
	
	$scope.msg.hideMsg = function() {
		$scope.msg.show = false;
		
		$scope.msg.lock = false;
	};
	
	$scope.msg.cancelHide = function() {
		if($scope.msg.timer) {
			$scope.msg.lock = true;
			$timeout.cancel($scope.msg.timer);
		}
	};
	
	$scope.msg.handleError = function(response) {
		var code = response.status;
		var err = response.data;
		var internalCode = err.code;
		var internalMsg = err.msg;
		
		if(err.attachment) {
			console.info(err.attachment);
		}
		
		if(code == 500) {
			$scope.msg.setMsg('error code 500: internal server error, please contact system administration.', 'error');
		} else {
			$scope.msg.setMsg('error code ' + internalCode + ': ' + internalMsg, 'error');
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
	
	//notfication
	$scope.msg = {
		message: '',
		show: false,
		status: 'error' //error, ok, warning
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
		var x = $resource('api/access-right-list').get(
			function() {
				$scope.accessList = x;
				//x console.log($scope.accessList.ApproveLeave);
			},
			function(response) {
				$scope.msg.handleError(response);
			}
		);
		
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
				$scope.msg.handleError(response);
			}
		);
	};
	
	$scope.logout = function() {
		$resource('api/logout').get(
			function() {
				$location.path('/login');
			},
			function() {
				$scope.msg.setMsg('logout fail', 'error');
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