<!DOCTYPE html>
<html lang="en" ng-app="MyApp">
	 <head>
	 	<meta charset='utf-8' name="Top Brilliant" content="Top Brilliant" />
	 	<meta name="viewport" content="width=device-width, initial-scale=1">	
		<title>Starter Kit</title>
	 	
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	
		<link href="lib/bootstrap/css/bootstrap-3.1.1.css" rel="stylesheet">
		<link href="lib/bootstrap/css/bootstrap-theme-3.1.1.css" rel="stylesheet">
		<link href="lib/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
		<link href="lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

		<link href="css/override.css" rel="stylesheet">
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		   <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		   <!--[if lt IE 9]>
		     <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		     <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		   <![endif]-->
	 </head>
  	<body ng-clock ng-controller='AppCtrl'>
  		<!-- div class='orange-red'><b>Username</b>: test &nbsp;&nbsp;&nbsp;&nbsp;<b>Password</b>: 1q2w3e</div -->
  		<div class='lightBlue'><b>Username</b>: admin &nbsp;&nbsp;&nbsp;&nbsp;<b>Password</b>: 1q2w3e</div>
  		<nav class='navbar navbar-default' role='navigation' ng-show='!navigation.plainPage'>
  			<!-- Brand and toggle get grouped for better mobile display -->
  			<div class='container-fluid'>
  				
  				<div class='navbar-header'>
  					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        	<span class="sr-only">Toggle navigation</span>
			        	<span class="icon-bar"></span>
			        	<span class="icon-bar"></span>
			        	<span class="icon-bar"></span>
			      	</button>
			      	<a class="navbar-brand" href="#">Brand</a>
  				</div>
  				
  				<!-- Collect the nav links, forms, and other content for toggling -->
	  			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	  			
	  				<!-- Menu Item -->
	  				<ul class='nav navbar-nav'>
						<li class='dropdown' ng-class="{active: navigation.paths[1]=='config'}">
						  	<a class='dropdown-toggle hover-cursor' data-toggle='dropdown' >
						  	Setting<span class='caret'></span></a>
						  	<ul class='dropdown-menu'>
						  		<li><a href='#/config/account-main'>User</a></li>
						  		<li><a href='#/config/schedule'>Schedule</a></li>
					  		</ul>
						</li>	
	  				</ul>

	  				<!-- Login Menu -->
	  				<ul class='nav navbar-nav navbar-right'>
	  					<li ng-show='user.login'><a href=''>{{'Welcome, ' + user.username}}</a></li>
	  					<li ng-show='user.login'><a href='' ng-click='logout()'>Logout</a></li>
			  		</ul>
	  			</div>
  			</div>
  		</nav>

  		<div class='container-fluid' id='mainMsg' ng-controller='MsgCtrl' ng-class="{'avoid-clicks': !msg.show}">
		  	<!-- Message Container -->
		  	<div class='row'>
		  		<div class='alert alert-danger my-show-hide drop-shadow'
		  			ng-show='msg.show'
		  			ng-class="{	'alert-danger': msg.status=='error', 
		  						'alert-warning': msg.status=='warning',
		  						'alert-success': msg.status=='ok'}">
		  			<button type="button" class="close" 
		  					ng-click='msg.hideMsg()' aria-hidden="true">
		  				&times;
		  			</button>
		  			
		  			<span ng-show='!msg.lock' class='glyphicon glyphicon-pushpin' ng-click='msg.cancelHide()'></span>
		  			<span ng-show='msg.lock' class='glyphicon glyphicon-lock' ng-click='msg.hideMsg()'></span>
		  			<span ng-bind='msg.message'></span>
		  		</div>
		  	</div>
	  	</div>
	  	
	  	<div class='container-fluid'  ng-controller='LoaderCtrl' >
		  	<!-- Loader Container -->
		  	<div class='row' id="loader" ng-if="loader.show">
		  		<div class="loader-big">
		  			<img src="img/loader.gif">
		  		</div>
		  	</div>
	  	</div>
	  	
	  	<!-- Main Container -->
  		<div ng-view></div>
  	</body>
  	
  	<script src="lib/jquery/jquery-2.1.1.min.js"></script>
  	<script src="lib/moment/moment-2.6.0.min.js"></script>
  	
	<script src="lib/bootstrap/js/bootstrap-3.1.1.js"></script>
	<script src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker-3.0.0-min.js"></script>
	
	<script src="lib/angular/angular-1.2.13.js"></script>
	<script src="lib/angular/angular-resource-1.2.13.js"></script>
	<script src="lib/angular/angular-route-1.2.13.js"></script>
	<script src="lib/angular/angular-animate-1.2.13.js"></script>
	
	<script src="lib/ui-bootstrap/ui-bootstrap-0.10.0.js"></script>
	
	<script src="lib/ig/ig-core-0.1.js"></script>
	
	<script src='js/app.js'></script>
	<script src='js/directive.js'></script>
	<script src='js/filter.js'></script>
	<script src='js/service.js'></script>
	<script src='js/controller.js'></script>
	<script src="js/router.js"></script>
	
	
<?php 
//automatic load all JS module files
define('WEB_DIR', dirname(__FILE__));

function rerJS($dir) {
	$dirHandle = opendir($dir);
	
	while(false !== ($file = readdir($dirHandle)) ) {
		
		if($file != '.svn' && $file != '.' && $file != '..') {
			$tmpDir = $dir . '/' . $file;
				
			if(is_dir($tmpDir . '/')) {
				rerJS($tmpDir);
			} else {
				//check file is JS
				if(preg_match('/.+\.js$/', $tmpDir)) {
					$subStr = substr($tmpDir, strlen(WEB_DIR) + 1);
					echo "<script src=\"$subStr\"></script>";
				}
			}
		}
	}
}

rerJS(WEB_DIR . '/partials');
rerJS(WEB_DIR . '/modules');
?>

</html>
