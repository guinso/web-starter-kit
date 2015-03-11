angular.module('MyApp').

controller('IgUpdateCtrl', function($scope, $resource, $util) {
	$scope.igUpdate = {
		items: []
	};
	
	$scope.igUpdate.submit = function() {
		$scope.loader.showLoader();
		
		$resource('api/update-run').save(
			function() {
				$util.setMsg('System had been update successfully.', 'ok');
				$scope.loader.hideLoader();
				
				$scope.igUpdate.reload();
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.igUpdate.reload = function() {
		$scope.loader.showLoader();
		
		$scope.igUpdate.items = $resource('api/update-available').query(
			function() { $scope.loader.hideLoader(); },
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.igUpdate.reload();
});