angular.module('MyApp').

controller('MiscCtrl', function($scope, $resource, $util) {
	$scope.misc = {
		item: {},
		acc: {},
	};
	
	$scope.misc.saveAcc = function() {
		$scope.loader.showLoader();
		
		$resource('api/update-account').save(
			$scope.misc.acc,
			function() {
				$scope.msc.acc = {};
				$scope.loader.hideLoader();
				$util.setMsg('Succesfully change admin account.', 'ok');
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.misc.saveMisc = function() {
		$scope.loader.showLoader();
		
		var x = $resource('api/misc-setting').save(
			$scope.misc.item,
			function() {
				$scope.loader.hideLoader();
				$util.setMsg('Successfully update site maintenance.', 'ok');
				
				$scope.misc.item = x;
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
/*	
	$scope.misc.saveMaintenance = function() {
		var x = $resource('api/misc-maintenance').save(
			$scope.misc.item,
			function() {
				
				$scope.loader.hideLoader();
				$util.setMsg('Successfully update site maintenance.', 'ok');
				
				$scope.misc.item = x;
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};

	$scope.misc.saveDeploy = function() {
		var x = $resource('api/misc-deploy').save(
			$scope.misc.item,
			function() {
				$scope.loader.hideLoader();
				$util.setMsg('Successfully update site optimization.', 'ok');
				
				$scope.misc.item = x;
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
*/	
	$scope.misc.reload = function() {
		$scope.loader.showLoader();
		
		var x = $resource('api/misc-setting').get(
			function() {
				$scope.misc.item = x;
				$scope.loader.hideLoader();
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.misc.reload();
});