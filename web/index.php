<?php 
include_once 'maintenance.php';
?>

<!DOCTYPE html>
<html lang="en" ng-app="MyApp">
	 <head>
	 	<meta charset='utf-8' name="Starter" content="Starter Kit" />
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
  	<body>
  		<div id='loading-msg'>
  			Please wait while loading
  		</div>
  		<div id='bodyy' ng-controller='AppCtrl'>
  		</div>
  	</body>
  	
  	<script src="lib/jquery/jquery-2.1.1.min.js"></script>
  	<script type="text/javascript">
		$('#bodyy').hide();
		$.ajaxSetup({async:false});
		$('#bodyy').load('page/mainFrame.html');
		
		//$.getScript("../lib/moment/moment-2.6.0.min.js");
		$.ajaxSetup({async:true});
	</script>
	
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
include_once 'loadModules.php';
?>

<script type="text/javascript">
	$('#loading-msg').hide();
	$('#bodyy').show();
</script>

</html>
