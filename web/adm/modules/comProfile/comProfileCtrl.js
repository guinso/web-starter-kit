angular.module('MyApp').

controller('CpCtrl', function($scope, $resource, $util) {
	$scope.cp = {
		item: {}
	};

	$scope.cp.save = function() {
		$scope.loader.showLoader();
		
		$resource('api/com-profile').save(
			$scope.cp.item,
			function() {
				$scope.msg.setMsg('Successfully update company profile.', 'ok');
				$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.cp.reload = function() {
		$scope.loader.showLoader();
		
		var x = $resource('api/com-profile').get(
			function(response) {
				$scope.cp.item = x;
				$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.cp.reload();
});