angular.module('MyApp').

controller('SpCtrl', function($scope, $resource, $util) {
	$scope.sp = {
		item: {},
		selectedItem: null,
		selectedName: ''
	};
	
	$scope.sp.callAddProfile = function() {
		$('#addProfileMdl').modal('show');
	};
	
	$scope.sp.callDeleteProfile = function() {
		$('#deleteProfileMdl').modal('show');
	};
	
	$scope.sp.selectProfile = function(item) {
		$scope.sp.selectedItem = item;
		
		var name = null;
		angular.forEach($scope.sp.item.items, function(value, key){
			if(value == $scope.sp.selectedItem)
				name = key;
		});
		
		$scope.sp.selectedName = name;
	};
	
	$scope.sp.addProfile = function() {
		$scope.sp.item.items[$scope.sp.addSpName] = {};
		$('#addProfileMdl').modal('hide');
	};
	
	$scope.sp.deleteProfile = function() {
		if($scope.sp.item.items[$scope.sp.selectedName]) {
			delete $scope.sp.item.items[$scope.sp.selectedName];
			
			$scope.sp.selectedItem = null;
			$scope.sp.selectedName = null;
			
			$('#deleteProfileMdl').modal('hide');
		}
		else {
			$('#deleteProfileMdl').modal('hide');
			alert("There is no such profile '" + name + "' found in record.");
		}
			
	};
	
	$scope.sp.save = function() {
		$scope.loader.showLoader();
		
		$resource('api/sys-profile').save(
			$scope.sp.item,
			function() {
				$scope.msg.setMsg('Update profile success.', 'ok');
				$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.sp.reload = function() {
		$scope.loader.showLoader();
		
		var x = $resource('api/sys-profile').get(
			function(response) {
				$scope.sp.item = x;
				$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.sp.reload();
});