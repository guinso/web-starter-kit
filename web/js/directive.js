'use strict';

angular.module('MyApp').run(["$templateCache", function($templateCache) {
	$templateCache.put("myapp-filepicker.html",
		"<div ng-show='!filePath'>" +
		"	<a class='btn btn-default' href='javascript:;'>Choose File" +
		"	<input 	type='file' file-upload='fileModel' " +
		"		style=\"" +
		"			width:100%;" +
		"			height:34px;" +
		"			position:absolute;" +
		"			z-index:2;top:0;left:0;" +
		"			filter: alpha(opacity=0);" +
		"			-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';" +
		"			opacity:0;" +
		"			background-color:transparent;" +
		"			color:transparent;\" " +
		"		name='file_source' " +
		"		size='40'  " +
		"		onchange='angular.element(this).scope().fileChange($(this).val())'>" +
		"	</a>&nbsp;" +
		"	<span ng-bind='targetFileName'></span>" +
		"</div>" +

		"<div ng-show='filePath'>" +
		"	<a 	ng-href='{{filePath}}' " +
		"		ng-bind='fileName' " +
		"		style='padding-left:15px;padding-top:7px;'>" +
		"	</a>" +
		"	<button type='button' class='btn btn-default' " +
		"		ng-click='removeFile()'>Remove File" +
		"	</button>" +
		"</div>"
	);
	
	$templateCache.put("modulez/myapp-filepicker2.html",
		"<div ng-show='!fileName'>" +
		"	<a class='btn btn-default' href='javascript:;'>Choose File" +
		"	<input 	type='file' file-upload='fileModel' " +
		"		style=\"" +
		"			width:112px;" +
		"			height:34px;" +
		"			position:absolute;" +
		"			z-index:2;top:0;left:0;" +
		"			filter: alpha(opacity=0);" +
		"			-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';" +
		"			opacity:0;" +
		"			background-color:transparent;" +
		"			color:transparent;\" " +
		"		name='file_source' " +
		"		size='40'  " +
		"		onchange='angular.element(this).scope().fileChange($(this).val())'>" +
		"	</a>&nbsp;" +
		"	<div><span ng-bind='targetFileName'></span></div>" +
		"</div>" +

		"<div ng-show='fileName'>" +
		"	<button type='button' class='btn btn-default' " +
		"		ng-hide='hideRemove'" +
		"		ng-click='removeFile()'>Remove File" +
		"	</button>" +
		"	<a 	ng-href='{{fileUrl + fileGuid}}' " +
		"		ng-bind='fileName' " +
		"		style='padding-left:15px;padding-top:7px;'>" +
		"	</a>" +
		"</div>"
	);
	
	$templateCache.put("modulez/myapp-filepicker-img.html",
			"<img ng-src=\"{{fileUrl + fileGuid}}\" class='img-responsive'>" +
			"<div ng-show='!fileName' >" +
			"	<a class='btn btn-default' style='position:absolute;' href='javascript:;'>Choose File" +
			"	<input 	type='file' file-upload='fileModel' " +
			"		style=\" " +
			"			width:100%;" +
			"			height:34px;" +
			"			position:absolute;" +
			"			z-index:2;top:0;left:0;" +
			"			filter: alpha(opacity=0);" +
			"			-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=0)';" +
			"			opacity:0;" +
			"			background-color:transparent;" +
			"			color:transparent;\" " +
			"			name='file_source' " +
			"			size='40'  " +
			"			onchange='angular.element(this).scope().fileChange($(this).val())'>" +
			"	</a>&nbsp;	" +	
			"	<span ng-bind='targetFileName'></span>" +
			"</div>" +

			"<div ng-show='fileName'>" +
			"	<a 	ng-href='{{fileUrl + fileGuid}}' " +
			"		ng-bind='fileName' " +
			"		style='padding-left:15px;padding-top:7px;'>" +
			"	</a>" +
			"	<button type='button' class='btn btn-default' " +
			"		ng-click='removeFile()'>Remove File " +
			"	</button>" +
			"</div>"
	);
	
	$templateCache.put("modulez/myapp-filepicker-arr.html",
		"<div class='form-group'>" +
		"	<button class='btn btn-default' ng-click='addAttachment($event)'>Add attachment</button>" +
		"</div>" +
		"<div ng-repeat='r in attachment' class='form-group'>" +
		"<div class='col-md-1'>" +
		"	<button type='button' " +
		"		class='btn btn-default' " +
		"		ng-click='removeAttachment(r)'>" +
		"		<span class='red glyphicon glyphicon-remove'></span>" +
		"	</button>" +
		"</div>" +
		"<div class='col-md-11'>" +
		"	<div file-picker-2 " +
		"		hide-remove='true'" +
		"		file-model='r.fileModel'" +
		"		file-name='r.attachment.filename'" +
		"		file-guid='r.attachment.guid'" +
		"		file-id='r.attachmentId'" +
		"		file-url='fileUrl'></div>" +
		"	</div>" +
		"</div>"
	);
}]).

directive('fileUpload', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileUpload);
            var modelSetter = model.assign;
            
            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]).

directive('filePicker', function() {
	function f(scope, element, attrs) {
		scope.removeFile = function() {
			scope.fileName = '';
			scope.filePath = '';
		}
		
		scope.fileChange = function(filee) {
			scope.targetFileName = filee;
		}
		
		scope.targetFileName = '';
	}
	
	return {
		restrict: 'A',
		scope: {
			fileModel: '=',
			fileName: '=',
			filePath: '=',
		},
		link: f,
		templateUrl: 'myapp-filepicker.html'
	};
}).

directive('filePicker2', function() {
	function f(scope, element, attrs) {
		scope.removeFile = function() {
			scope.fileName = '';
			scope.fileGuid = '';
			scope.fileId = null;
		}
		
		scope.fileChange = function(file) {
			scope.targetFileName = file;
		}
		
		scope.targetFileName = '';
	}
	
	return {
		restrict: 'A',
		scope: {
			fileModel: '=',
			fileName: '=',
			fileGuid: '=',
			fileUrl: '=',
			fileId: '=',
			hideRemove: '='
		},
		link: f,
		templateUrl: 'modulez/myapp-filepicker2.html'
	};
}).

directive('filePickerImg', function() {
	function f(scope, element, attrs) {
		scope.removeFile = function() {
			scope.fileName = '';
			scope.fileGuid = '';
			scope.fileId = null;
		}
		
		scope.fileChange = function(file) {
			scope.targetFileName = file;
		}
		
		scope.targetFileName = '';
	}
	
	return {
		restrict: 'A',
		scope: {
			fileModel: '=',
			fileName: '=',
			fileGuid: '=',
			fileUrl: '=',
			fileId: '='
		},
		link: f,
		templateUrl: 'modulez/myapp-filepicker-img.html'
	};
}).

directive('filePickerArr', function() {
	function f(scope, element, attrs) {

		scope.removeAttachment = function(item) {
			
			var cnt = scope.attachment.indexOf(item);
			
			scope.attachment.splice(cnt, 1);
		};
		
		scope.addAttachment = function(e) {
			e.preventDefault();
			
			scope.attachment.push({});
		};
	}
	
	return {
		restrict: 'A',
		scope: {
			attachment: '=',
			fileUrl: '=',
		},
		link: f,
		templateUrl: 'modulez/myapp-filepicker-arr.html'
	};
});