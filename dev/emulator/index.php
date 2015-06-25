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
		<!-- Get the Google Maps JavaScript library -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API; ?>"></script>
		<!-- Get the JQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
				
		<?php
			include '../../resources/ga_tracker.php';
		?>
		<script>
			var map;
			var marker;
			//Initialize the map
			function initializeMap() {
				//Set the zoom and centre the map over the latest position
				var mapOptions = {
					center: { lat: 51.5286417, lng: -0.1015987},
					zoom: 7,
					mapTypeId: google.maps.MapTypeId.TERRAIN
				};
				//Set the map location
				map = new google.maps.Map(document.getElementById('emulator_map'), mapOptions);
				
				google.maps.event.addListener(map, 'click', function(pos) {
					$('#lat').val(pos.latLng.lat());
					$('#long').val(pos.latLng.lng());
					if(marker){	
						marker.setPosition(pos.latLng);
					}
					else{
						marker = new google.maps.Marker({
							position: pos.latLng,
							map: map
						});	
						marker.setMap(map);
					}
					map.panTo(pos.latLng);
				});
			}
			google.maps.event.addDomListener(window, 'load', initializeMap);
		</script>
	</head>
	<body>
			<div id="launcher">
				<h1>Emulator</h1>
				
				<div id="emulator_map" style="height: 300px;"></div>
				
				<form method="get" action="emulate.php">
					<input id="lat" type="hidden" name="lat" value="51.5286417" />
					<input id="long" type="hidden" name="long" value="-0.1015987" />
					<input type="submit" value="EMULATE" />
				</form>
			</div>
	</body>
<html>

<?php
//End of if developer mode
}
?>
