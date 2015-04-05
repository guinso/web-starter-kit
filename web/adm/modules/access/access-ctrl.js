angular.module('MyApp').

controller('AccessCtrl', function($scope, $resource, $util) {
	$scope.access = {
		items: [],
		roles: []
	};
	
	$scope.access.updateAuth = function(group) {
		$scope.loader.showLoader();
		
		$resource('api/access-matrix/' + group.id, {}, {update: {method:'PUT'}}).update(
			group,
			function(response) {
				$util.setMsg('Update access success.', 'ok');
				$scope.accMain.reload();
			},
			function(response) {
				$util.setMsg('Update access fail.', 'error');
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.access.rebuildAuth = function() {
		$scope.loader.showLoader();
		
		$resource('api/access-matrix-rebuild').get(
			function() { 
				$scope.accMain.reload();
			},
			function(response) { 
				$util.handleErrorMsg(response); 
				$scope.loader.hideLoader();
			} 
		);
	};
	
	$scope.access.reload = function() {
		$scope.loader.showLoader();
		
		$scope.access.roles = $resource('api/role').query();
		
		var x = $resource('api/access-matrix').query(
			function(response) { 
				$scope.access.items = x;
				$scope.loader.hideLoader(true);
			},
			function(response) {
				$util.handleErrorMsg(response); 
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.access.reload();
});