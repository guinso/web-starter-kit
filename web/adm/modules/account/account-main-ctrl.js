angular.module('MyApp').

controller('AccMainCtrl', function($scope, $resource, $util) {
	$scope.accMain = {
		acc: {
			items: [],
			item: {},
			newItem: {},
			myFile: null
		},
		accLog: {
			items: [],
			item: {}
		},
		role: {
			items: [],
			item: {name:''}
		},
		auth: {
			items: []
		},
		loginLog: { 
			items:[]
		},
		roleStatus: [{id:1, name:'active'},{id:2, name:'disabled'}],
		accStatus: [{id:1, name:'active'},{id:2, name:'disabled'}],
		accQuery: { pgIndex:1, pgSize:15},
		accLogQuery: { pgIndex:1, pgSize:15},
		loginLogQuery: {pgIndex: 1, pgSize:15}
	};
	
	//------------------------- Account -------------------------------
	$scope.accMain.selectAcc = function(selectItem) {
		$scope.accMain.acc.item = angular.copy(selectItem);
	};
	
	$scope.accMain.clearAccItem = function() {
		$scope.accMain.acc.item = null;
	};
	
	//Change Pasword
	$scope.accMain.callChangeAccPwd = function(modalId, item) {
		$scope.accMain.acc.item = angular.copy(item);
		
		$(modalId).modal('show');
	};
	
	$scope.accMain.changeAccPwd = function(modalId) {
		if($scope.accMain.acc.newItem.pwd != $scope.accMain.acc.newItem.confirmPwd) {
			$window.alert('Password not match, please check your newly entered password.');
		} else {
			$resource('api/change-pwd').save(
				{userId: $scope.accMain.acc.item.id, pwd: $scope.accMain.acc.item.pwd},
				function() {
					$util.setMsg('Successfully change ' + $scope.accMain.acc.newItem.username + ' password.', 'ok');
				},
				function(response) {
					$util.handleErrorMsg(response);
				}
			);
			
			$(modalId).modal('hide');
		}
	};
	
	//Update User Details
	$scope.accMain.callUpdateUserDetails = function(modalId, item) {
		$scope.accMain.acc.myFile = null;
		
		$scope.accMain.acc.item = angular.copy(item);
		
		$(modalId).modal('show');
	};
	
	$scope.accMain.uploadAndUpdateAcc = function(modalId) {
		if (!$scope.accMain.acc.myFile) {
			$scope.accMain.updateAcc();
		}
		else {
			$util.uploadFile($scope.accMain.acc.myFile, "api/file-upload",
				function(response, filename, filepath) {
					$scope.accMain.acc.item.attachmentId = response.id;
					$scope.accMain.updateAcc();
				},
				function(response) {
					$util.handleErrorMsg(response);
				}
			);
		}
		
		$(modalId).modal('hide');
	};
		
	$scope.accMain.updateAcc = function() {
		$resource('api/user/' + $scope.accMain.acc.item.id, 
			{}, {update:{method:'PUT'}}).update(
				
			$scope.accMain.acc.item,
			function() {
				$util.setMsg('Update account success.','ok');
				$scope.accMain.acc.items = $resource('api/user').query();
				$scope.accMain.acc.item = {};
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.accMain.acc.items = $resource('api/user').query();
			}
		);
	};
	
	//Create User
	$scope.accMain.callCreateUser = function(modalId) {
		$scope.accMain.acc.myFile = null;
		
		$scope.accMain.acc.item = {};
		
		$(modalId).modal('show');
	};
	
	$scope.accMain.uploadAndCreateAcc = function(modalId) {
		if(!$scope.accMain.acc.myFile) {
			$scope.accMain.createAcc();
		} else {
			$util.uploadFile($scope.accMain.acc.myFile, 'api/file-upload', 
				function(response, filename, filepath) {
					$scope.accMain.acc.item.attachmentId = response.id;
					$scope.accMain.createAcc();
				},
				function(response) { $util.handleErrorMsg(response); }
			);
		}
		
		$(modalId).modal('hide');
	}
	
	$scope.accMain.createAcc = function() {
		$resource('api/user').save(
			$scope.accMain.acc.item,
			function() {
				$util.setMsg('New account created successfully.', 'ok');
				$scope.accMain.reload();
			},
			function(response) {
				$util.handleErrorMsg(response);
			}
		);
	}
	
	$scope.accMain.deleteAcc = function() {
		$resource('api/user/' + $scope.accMain.acc.item.id).remove(
			{userId: $scope.accMain.acc.item.id},
			function() {
				$util.setMsg('Delete account success.','ok');
				$scope.accMain.acc.items = $resource('api/user').query();
				$scope.accMain.acc.item = {};
			},
			function() {
				$util.setMsg('Delete account fail.','error');
				$scope.accMain.acc.items = $resource('api/user').query();
			}
		);
	};
	
	$scope.accMain.openNewAccount = function(modalId) {
		$scope.accMain.acc.newItem = {};
		$(modalId).modal('show');
	}
	
	//-------------------- Role ----------------------------------
	$scope.accMain.addRole = function() {
		$scope.accMain.role.items.push({});
	};
	
	$scope.accMain.removeRole = function(item) {
		var cnt = $scope.accMain.role.items.indexOf(item);
		$scope.accMain.role.items.splice(cnt, 1);
	};
	
	$scope.accMain.updateRole = function() {
		$resource('api/role-bulk').save($scope.accMain.role.items,
			function() {
				$util.setMsg("Role had been update sucessfully", 'ok');
				
				$scope.accMain.reload();
			},
			function(response) { $util.handleErrorMsg(response); }
		);
	}
	
	//---------------------- Authorization ------------------------
	$scope.accMain.updateAuth = function(group) {
		$scope.loader.showLoader();
		
		$resource('api/access-matrix/' + group.id, {}, {update: {method:'PUT'}}).update(
			group,
			function(response) {
				$util.setMsg('Update access success.', 'ok');
				$scope.loader.hideLoader();
				$scope.accMain.reload();
			},
			function(response) {
				$util.setMsg('Update access fail.', 'error');
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.accMain.rebuildAuth = function() {
		$resource('api/access-matrix-rebuild').get(
			function() { $scope.accMain.reload(); },
			function(response) { $util.handleErrorMsg(response); } 
		);
	}

	//-------------------- General ---------------------------------
	$scope.accMain.reload = function() {
		var cnt = 0;
		$scope.accMain.manageUser = false;
		
		$scope.loader.showLoader();
		
		$util.countAndQuery('api/user-cnt', 'api/user', 
			$scope.accMain.accQuery,
			function(x) { 
				++cnt;
				if(cnt >= 5)
					$scope.loader.hideLoader();
				$scope.accMain.acc.items = x; 
			},
			function(response) { 
				$util.handleErrorMsg(response); 
				$scope.loader.hideLoader();
			} 
		);
		
		$util.countAndQuery('api/user-log-cnt', 'api/user-log', 
				$scope.accMain.accLogQuery,
				function(x) { 
					++cnt;
					if(cnt >= 5)
						$scope.loader.hideLoader();
					$scope.accMain.accLog.items = x; 
				},
				function(response) { 
					$util.handleErrorMsg(response); 
					$scope.loader.hideLoader();
				} 
			);
		
		$scope.accMain.role.items = $resource('api/role').query();
		
		$scope.accMain.auth.items = $resource('api/access-matrix').query(
			function() {
				++cnt;
				if(cnt >= 5)
					$scope.loader.hideLoader();
			},
			function(response) { 
				$util.handleErrorMsg(response); 
				$scope.loader.hideLoader();
			} 
		);
		
		$util.countAndQuery('api/login-log-count', 'api/login-log', 
			$scope.accMain.loginLogQuery,
			function(x) {
				$scope.accMain.loginLog.items = x;
				
				++cnt;
				if(cnt >= 5)
					$scope.loader.hideLoader();
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
			
		);
		
		var x = $resource('api/access-right').get(
			{name: 'manage user'},
			function() {
				$scope.accMain.manageUser = x.access;
				
				++cnt;
				if(cnt >= 5)
					$scope.loader.hideLoader();
			},
			function(response) {
				$util.handleErrorMsg(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.accMain.reload();
});