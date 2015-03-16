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
	
		<link href="lib/animate-css/animate.min.css" rel="stylesheet">
		<link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		
		<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="lib/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		<link href="lib/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet">
		<link href="lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="lib/metis-menu/metisMenu.min.css" rel="stylesheet">
		
		<link href="css/loader.css" rel="stylesheet">
		<link href="css/msg.css" rel="stylesheet">
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
  			<div align="center" class="center-msg">
				<div class="contener_general">
					<div class="contener_mixte"><div class="ballcolor ball_1">&nbsp;</div></div>
					<div class="contener_mixte"><div class="ballcolor ball_2">&nbsp;</div></div>
					<div class="contener_mixte"><div class="ballcolor ball_3">&nbsp;</div></div>
					<div class="contener_mixte"><div class="ballcolor ball_4">&nbsp;</div></div>
				</div>
				<h2 class='loader-title'>Web Starter Kit</h2>
				<p class='loader-desc' id='loading-desc'>Please wait while loading</p>
			</div>
  		</div>
  		<div id='bodyy' ng-controller='AppCtrl'>
  		</div>
  	</body>
  	
  	<script src="lib/jquery/jquery-2.1.1.min.js"></script>
  	<script type="text/javascript">
		$('#bodyy').hide();
		$.ajaxSetup({async:false});
		$('#bodyy').load('page/msFrame.html');
		
		//$.getScript("../lib/moment/moment-2.6.0.min.js");
		$.ajaxSetup({async:true});
	</script>
	
  	<script src="lib/moment/moment-2.6.0.min.js"></script>
  	
	<script src="lib/bootstrap/js/bootstrap.js"></script>
	<script src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker-3.0.0-min.js"></script>
	<script src="lib/bootstrap-notify/bootstrap-notify.min.js"></script>
	
	<script src="lib/metis-menu/metisMenu.min.js"></script>
	
	<script src="lib/angular/angular-1.2.13.js"></script>
	<script src="lib/angular/angular-resource-1.2.13.js"></script>
	<script src="lib/angular/angular-route-1.2.13.js"></script>
	<script src="lib/angular/angular-animate-1.2.13.js"></script>
	
	<script src="lib/ui-bootstrap/ui-bootstrap-tpls-0.12.1.min.js"></script>
	
	<script src="lib/ig/ig-core-0.2.js"></script>
	
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
	$('#loading-desc').html('Loading completed.');
	$('#loading-msg').fadeOut(1500, function(){
		$('#bodyy').fadeIn(500);
	});
	
</script>

</html>
