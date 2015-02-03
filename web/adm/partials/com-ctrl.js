angular.module('MyApp').

//circle arc
directive('circleArc', function() {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
        	var canvas = $(element)[0];
	      	var context = canvas.getContext('2d');
	      	var x = canvas.width / 2;
	      	var y = canvas.height / 2;
	      	var radius = x * 0.8;
	      	var startAngle = 1.2 * Math.PI;
	      	var endAngle = 1.0 * Math.PI;
	      	var counterClockwise = false;

	      	context.beginPath();
	      	context.arc(x, y, radius, startAngle, endAngle, counterClockwise);
	      	context.lineWidth = 5;

	      	// line color
	     	context.strokeStyle = 'black';
	      	context.stroke();
        }
	}
}).

//Loader Directive
directive('mdlLoader', function() {
    return {
        restrict: 'A',
        scope: {
        	show: '=',
        },
        templateUrl: 'partials/modal-loader.html'
    }
}).

//GUI generator Search Directive
directive('accountSearch', function() {
    return {
        restrict: 'A',
        scope: {
        	guig: '=',
        	title: '@'
        },
        templateUrl: 'partials/account-search.html'
    }
}).

factory('$coms', ['$resource', '$util', '$timeout', function($resource, $util) {

	//User account Search
	this.accountSearch = function(id, selectCallback, failCallback, modalClosedCallback) {
		var x = {
			items: [],
			modalId: id,
			selectedItem: null,
			query: {
				pgIndex: 1,
				pgSize: 10,
			},
		};

		x.reload = function() {
			$util.countAndQuery('api/account-cnt', 'api/account', x.query, 
				function(z) {
					x.items = z;
				}, 
				function(response) {
					if(failCallback)
						failCallback(response);
				});
		};
		
		x.callSearch = function() {
			x.selectedItem = null;
			$('#' + x.modalId).modal('show');
			x.reload();
		};
		
		x.search = function() {

			$('#' + x.modalId).on('hidden.bs.modal', function(e) {	
				if(modalClosedCallback) {
					modalClosedCallback(x.selectedItem);
				}
			});
			
			if(selectCallback) {
				selectCallback(x.selectedItem);
			}
			
			$('#' + x.modalId).modal('hide');
		}
		
		return x;
	};

	return this;
}]);