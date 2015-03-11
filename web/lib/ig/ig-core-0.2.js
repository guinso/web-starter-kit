angular.module('ig.core', ['ngResource']).

//http://plnkr.co/edit/NBZXJ9BP76kPwpSddFMa?p=preview
//works well with $scope
directive('bindUnsafeHtml', ['$compile', function($compile) {
 return function(scope, element, attrs) {
   scope.$watch(
     function(scope) {
       // watch the 'bindUnsafeHtml' expression for changes
       return scope.$eval(attrs.bindUnsafeHtml);
     },
     function(value) {
       // when the 'bindUnsafeHtml' expression changes
       // assign it into the current DOM
       element.html(value);

       // compile the new DOM and link it to the current
       // scope.
       // NOTE: we only compile .childNodes so that
       // we don't get into infinite loop compiling ourselves
           $compile(element.contents())(scope);
         }
       );
     };
   }
 ]).
 
directive('mydatepicker', function() {
	return function(scope, element, attrs) {
		//read when ngModel change
		scope.$watch(attrs.ngModel, function(value) {
			element.text(value);
		});
		
		element.datepicker({
			calendarWeeks: true,
			autoclose: true,
			todayHighlight: true,
			format: 'yyyy-mm-dd'
			
			//set changes to ngModel
			
		}).on('changeDate', function(e) {
			if(attrs.ngModel) {
				var arr = attrs.ngModel.split('.');
				var s = scope;
				
				if(arr.length > 0) {
					for(var i=0; i<arr.length - 1; i++) {
						s = s[arr[i]];
					}
					
					s[arr[arr.length - 1]] = e.format('yyyy-mm-dd');

					scope.$apply();
				}
			}
		});
	}
}).

///Require momentJS library
directive('mydatetimepicker', function() {

	return function(scope, element, attrs) {
		var dateFormat = 'YYYY-MM-DD HH:mm:ss';
		if(attrs.myDateFormat) {
			dateFormat = attrs.myDateFormat;
		}
		
		//read when ngModel change
		scope.$watch(attrs.ngModel, function(value) {
			var m = moment(new Date(value));
			var v = m.format(dateFormat);
			
			if(value) {
				if(!m.isValid()) {
					m = moment(new Date());
				}
				
				v = m.format(dateFormat);
				
				if(attrs.ngModel) {
					var arr = attrs.ngModel.split('.');
					var s = scope;
					
					if(arr.length > 0) {
						for(var i=0; i<arr.length - 1; i++) {
							s = s[arr[i]];
						}
						
						s[arr[arr.length - 1]] = v;
					}
				}
			}

			value = v;
			
			element.text(value);
		});
		
		element.datetimepicker({
			useSeconds: true,
			showToday: true,
			useCurrent: false,
			dateFormat: dateFormat
		}).on('dp.change', function(e) {
			if(attrs.ngModel) {
				var arr = attrs.ngModel.split('.');
				var s = scope;
				
				if(arr.length > 0) {
					for(var i=0; i<arr.length - 1; i++) {
						s = s[arr[i]];
					}
					
					s[arr[arr.length - 1]] = e.date.format(dateFormat);

					scope.$apply();
				}
			}
		});
	}
}).

filter('yesno', function(){
		return function(result){
			return result ? 'Yes' : 'No';
		};
}).

filter('passfail', function(){
	return function(result){
		return result ? 'Pass' : 'Fail';
	}
}).

filter('active', function(){
	return function(result){
		return result ? 'Active' : 'Inactive';
	}
}).

filter('exactFilter', function(){
	return function(inputArray, search) {
		return inputArray.filter(function(element) {
			var result = true;
			angular.forEach(search, function(value, key){
				if(!angular.equals(element[key], value))
					result = false;
			});
			
			return result;
		});
	};
}).

//Utility
service('$util', ['$resource', '$http', function($resource, $http) {
	// generate rfc4122 version 4 compliant guid
	// SRC: http://byronsalau.com/blog/how-to-create-a-guid-uuid-in-javascript/
	this.guid = function()
	{
	    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
	        var r = Math.random()*16|0, v = c === 'x' ? r : (r&0x3|0x8);
	        return v.toString(16);
	    });
	};
	
	this.today = function() {
		var d=new Date();
	    var year=d.getFullYear();
	    var month=d.getMonth()+1;
		    if (month<10){
		      month="0" + month;
		    }
	    var day=d.getDate();
		    if (day<10){
		    	day="0" + day;
		    }	
	    return year + "-" + month + "-" + day;	
	};
	
	//obsoleted
	this.currentDate = function(){
		var d=new Date();
	    var year=d.getFullYear();
	    var month=d.getMonth()+1;
		    if (month<10){
		      month="0" + month;
		    }
	    var day=d.getDate();
		    if (day<10){
		    	day="0" + day;
		    }	
	    return year + "-" + month + "-" + day;	
	};
	
	this.round = function(number, precision) {
		precision = Math.abs(parseInt(precision)) || 0;
		var multiplier = Math.pow(10, precision);
		return (Math.round(number * multiplier) / multiplier);
	};
	
	var uf = function(file, uploadUrl, sccessCallback, failCallback){

		var fd = new FormData();
		fd.append('file', file);
		$http.post(uploadUrl, fd, {
			transformRequest: angular.identity,
			headers: {'Content-Type': undefined}
		})
		.success(function(response) {
			if(sccessCallback)
				sccessCallback(response, response.fileName, response.filePath);
		})
		.error(function(response) {
			if(failCallback)
				failCallback(response);
		});
    };
	this.uploadFile = uf;
    
	this.countAndQuery = function(cntUrl, url, query, successCallback, errorCallback) {

		var q = angular.copy(query);
		q.pgIndex -= 1;
		var x = $resource(cntUrl).get(
			q,
			function() {
				q.pgCnt = x.count;
				query.pgCnt = x.count;
				
				if(q.pgIndex * q.pgSize >= q.pgCnt) {
					q.pgIndex = 0;
					query.pgIndex = 1;
				}
				
				var y = $resource(url).query(
					q,
					function() {
						successCallback(y);
					},
					function(response) {
						errorCallback(response);
					}
				);
			},
			function(response) {
				errorCallback(response);
			}
		);
	};
	
	this.uploadAndPost = function(uploadUrl, url, file, data, 
    	successUploadCallback, successCallback, failCallback) {
    	
		if(file) {
	    	uf(file, uploadUrl, 
	    		function(response, filename, filepath) {
	    			successUploadCallback(response);
	    			
	    			var x = $resource(url).save(
	    				data,
	    				function() { successCallback(x); },
	    				function(response) { failCallback(response); }
	    			);
	    		},
	    		function(response) { failCallback(response); }
	    	);
		} else {
			var x = $resource(url).save(
				data,
				function() { successCallback(x); },
				function(response) { failCallback(response); }
			);
		}
    };
    
    this.uploadAndPut = function(uploadUrl, url, file, data, 
        	successUploadCallback, successCallback, failCallback) {
        	
		if(file) {
	    	uf(file, uploadUrl, 
	    		function(response, filename, filepath) {
	    			successUploadCallback(response);
	    			
	    			var x = $resource(url, {}, {update:{method:'PUT'}}).update(
	    				data,
	    				function() { successCallback(x); },
	    				function(response) { failCallback(response); }
	    			);
	    		},
	    		function(response) { failCallback(response); }
	    	);
		} else {
			var x = $resource(url, {}, {update:{method:'PUT'}}).update(
				data,
				function() { successCallback(x); },
				function(response) { failCallback(response); }
			);
		}
    };
    
    this.setMsg = function(msg, status) {
		
		var statusType = 'info';
		switch(status) {
			case 'ok': statusType = 'success'; break;
			case 'error': statusType = 'danger'; break;
			case 'warning': statusType = 'warning'; break;
			case 'info': statusType = 'info'; break;
			default: statusType = 'info'; break;
		}
		
		$.notify({
			// options
			message: msg
		},{
			// settings
			type: statusType,
			mouse_over: 'pause',
			allow_dismiss: true
		});
	};
	
	this.handleErrorMsg = function(response) {
		var code = response.status;
		var err = response.data;
		var internalCode = err.code;
		var internalMsg = err.msg;
		
		if(err.attachment) {
			console.info(err.attachment);
		}
		
		if(code == 500) {
			$util.setMsg('error code 500: internal server error, please contact system administration.', 'error');
		} else {
			$util.setMsg('error code ' + internalCode + ': ' + internalMsg, 'error');
		}
	};
}]);