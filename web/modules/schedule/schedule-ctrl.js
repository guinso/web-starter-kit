angular.module('MyApp').

controller('ScheduleCtrl', function($scope, $resource, $location, $util) {
	$scope.sch = {
		tasks: [],
		logs: [],
		taskStatus: [{id:1, name:'active'}, {id:2, name:'disabled'}],
		logQuery: {pgIndex: 1, pgSize:15},
		recordOpt: [{id:1, name:'Record fail only'}, 
		            {id:2, name:'Record all'}, 
		            {id:3, name:'Disable record'}]
	};
	
	$scope.sch.updateTask = function() {
		$scope.loader.showLoader();
		
		$resource('api/sch-bulk').save(
			$scope.sch.tasks,
			function() { 
				$scope.msg.setMsg("Successfully update task.", 'ok');
				$scope.sch.reload();
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader();
			}
		);
	};
	
	$scope.sch.removeTask = function(item) {
		var cnt = $scope.sch.tasks.indexOf(item);
		$scope.sch.tasks.splice(cnt, 1);
	};
	
	$scope.sch.addTask = function() {
		$scope.sch.tasks.push({});
	}
	
	$scope.sch.reload = function() {
		var cnt = 0;
		$scope.loader.showLoader();
		
		$scope.sch.tasks = $resource('api/sch').query(
			function(x) {
				++cnt;
				if(cnt >= 2)
					$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}	
		);
		
		$util.countAndQuery('api/sch-log-cnt', 'api/sch-log', $scope.sch.logQuery,
			function(x) {
				$scope.sch.logs = x;
				++cnt;
				if(cnt >= 2)
					$scope.loader.hideLoader(true);
			},
			function(response) {
				$scope.msg.handleError(response);
				$scope.loader.hideLoader(true);
			}
		);
	};
	
	$scope.sch.reload();
});