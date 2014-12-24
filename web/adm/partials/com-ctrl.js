angular.module('MyApp').

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