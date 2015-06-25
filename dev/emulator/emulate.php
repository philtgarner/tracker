<?php
//Connect to the database and get the constants - may as well connect now, chances are they've got dev permissions if they've got this far
include '../../resources/connect.php';
//If not in dev mode just take the user to the home page
if(!defined('DEV_MODE') || !DEV_MODE){
	header('Location: ..');
}
//If in dev mode then show the dev tools
else{
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Android notification bar colour -->
		<meta name="theme-color" content="#009900">
		<!-- Set the favicon -->
		<link rel="shortcut icon" href="../../resources/favicon.png" />
		<title>Location tracker | PTG</title>
		<!-- Get the fonts from Google -->
		<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto:100,300,500' rel='stylesheet' type='text/css'>
		<!-- Set the zoom stuff for mobile devices -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<!-- Set the share icon for Facebook -->
		<meta property="og:image" content="../resources/icon.png"/>
		<!-- Get the CSS file -->
		<link href='../../resources/main.css' rel='stylesheet' type='text/css'>
		<!-- Get the JQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		
		<?php
			include '../../resources/ga_tracker.php';
		?>
		<script>
			//The starting lat and longs
			var lat = <?php echo $_GET['lat']; ?>;
			var lon = <?php echo $_GET['long']; ?>;
			//The upload key, will be set when initialisation is complete
			var upload;
			//The frequency with which to send a position
			var sendUpdates = 10000;
			//1 to reset when the emulator starts, 0 to not reset
			var reset = 1
			
			//Initialise the tracker
			function init(){
				$.ajax({
					type: 'POST',
					url: '../../api/<?php echo EMULATOR_API_VERSION; ?>/init/<?php echo EMULATOR_DOWNLOAD; ?>',
					data: {'reset': reset, 'device': <?php echo EMULATOR_ID; ?>},
					success: function(success){
						//If it all worked then display the success
						$('#init_response').text(JSON.stringify(success));
						console.log(success);
						if(success.success){
							upload = success.key;
						}
					}
				});				
			}
			
			
			//Generate some random movement and then broadcast the location
			function broadcast(){
				if(upload){
					var _lat = lat;
					var _lon = lon;
					//The time in milliseconds
					var date = new Date();
					var millis = date.getTime();
					//Maximum distnace (in metres) the emulator can move in one go
					var maxDistanceMoved = 100;
					//Random numbers for distance moved 
					var dy = Math.floor(Math.random() * (maxDistanceMoved*2))-maxDistanceMoved;
					var dx = Math.floor(Math.random() * (maxDistanceMoved*2))-maxDistanceMoved;
					//Calculate the distance travelled
					var distance = Math.sqrt((dx*dx) + (dy*dy));
					//Calculate the speed in m/s
					var speed = distance/(10000/1000);
					//Calculate the new positions
					lat = _lat + (180/Math.PI)*(dy/6378137);
					lon = _lon + (180/Math.PI)*(dx/6378137)/Math.cos(Math.PI/180.0*_lat);
					
					//Send off the new positions
					$.ajax({
						type: 'POST',
						url: '../../api/<?php echo EMULATOR_API_VERSION; ?>/update/' + upload,
						data: {'lat': lat, 'long': lon, 'dt': millis, 'speed': speed, 'alt': 10},
						success: function(success){
							//After the position has been sent display the response
							$('#update_response').text(JSON.stringify(success));
							console.log(success);
						}
					});
				}
			}
			
			//On the specified interval broadcast the location
			window.setInterval(function(){
				broadcast();
			}, sendUpdates);
			
			
			//When the page is loaded start the initialisation process
			$( document ).ready(function() {
				init();
			});
			
		</script>
		
	</head>
	<body>
			<div id="launcher">
				<img id="header" src="../../resources/icon.png" />
				<h1>Emulator</h1>
				<p><a href="../../track/<?php echo EMULATOR_DOWNLOAD; ?>" target="_blank">View tracker</a></p>
				
				<h2>Initialisation response</h2>
				<pre class="code_middle" id="init_response"></pre>
				
				<h2>Update response</h2>
				<pre class="code_middle" id="update_response"></pre>
			</div>
	</body>
<html>

<?php
//End of if developer mode
}
?>
