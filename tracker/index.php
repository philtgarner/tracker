<?php
	//Make sure the download key is set
	if(isset($_GET['dl'])){
		$dl = $_GET['dl'];
		
		//Connect to the database
		include '../resources/connect.php';
		
		//Build the SELECT clause to get the tracking info
		$sql = "SELECT lat, long, speed, altitude, date_time FROM gps INNER JOIN pairs ON pairs.upload = gps.upload WHERE pairs.download = :dl ORDER BY date_time ASC";
		
		//Add the parameters
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':dl', $dl, PDO::PARAM_STR);
		
		//Execute
		$statement->execute();
		$history = array();
		$current_lat = 0;
		$current_long = 0;
		$current_speed = 0;
		$current_altitude = 0;
		$current_time = 0;
		//Build the results up
		while($row = $statement->fetch(PDO::FETCH_ASSOC)){
			$h = array('latitude' => $row['lat'], 'longditude' => $row['long'], 'speed' => $row['speed'], 'altitude' => $row['altitude']);
			array_push($history, $h);
			
			$current_lat = $row['lat'];
			$current_long = $row['long'];
			$current_speed = $row['speed'];
			$current_altitude = $row['altitude'];
			$current_time = $row['date_time'];
		}
	}
	//If the download key is not set send the user back to the home page
	else{
		header('Location: ../');
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Android notification bar colour -->
		<meta name="theme-color" content="#009900">
		<!-- Set the favicon -->
		<link rel="shortcut icon" href="../resources/favicon.png" />
		<title>Location tracker | PTG</title>
		<!-- Get the fonts from Google -->
		<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200' rel='stylesheet' type='text/css'>
		<!-- Set the zoom stuff for mobile devices -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<!-- Set the share icon for Facebook -->
		<meta property="og:image" content="../resources/icon.png"/>
		<!-- Get the CSS file -->
		<link href='../resources/main.css' rel='stylesheet' type='text/css'>
		<!-- Get the Google Maps JavaScript library -->
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLE_MAPS_API; ?>"></script>
		<!-- Get the JQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<!-- The JavaScript to build the map with the path and markers -->
		<script type="text/javascript">
			var map;
			var marker;
			var historyPath;
			var time = <?php echo $current_time; ?>;
			var dl = '<?php echo $dl; ?>';
			//The frequency with which the timer is updated (milliseconds)
			var updateTimer = 1000;
			//The frequency with which the map is updated (milliseconds)
			var updateMap = 10000;
			
			
			//Set the refresh rate for the time update
			window.setInterval(function(){
				$('#time_ago').html(millisToTime(time));
			}, updateTimer);
			
			//Set the refresh rate for the path and marker
			window.setInterval(function(){
				periodicUpdate();
			}, updateMap);
			
			//The function to update the map and the information at the top of the page
			function periodicUpdate(){
				//The AJAX call to update the map
				$.ajax({
					type: 'GET',
					url: './mapupdate.php',
					data: {dl: dl, timestamp: time},
					dataType: 'json',
					success: function (success){
						//Only update the UP if there is a successful response from the AJAX call
						if(success.response == true){
						
							//console.log(success);
						
							//Update current stats
							time = success.time;	//The time will get updated within one second
							$('#speed_mph').html(msToMPH(success.speed));
							$('#speed_kph').html(msToKPH(success.speed));
							$('#speed_min_mile').html(msToMinMile(success.speed));
							$('#speed_min_km').html(msToMinKM(success.speed));
							$('#alt_m').html(success.altitude);
							$('#alt_ft').html(mToFt(success.altitude));
							
							//Update the path
							var latestPos;
							var path = historyPath.getPath();
							$.each(success.history, function(i, item) {
								latestPos = new google.maps.LatLng(item.latitude, item.longditude);
								path.push(latestPos);
							});
							
							//Update the marker
							marker.setPosition(latestPos);
							map.panTo(latestPos);
							
							
						}
						else{
							//No updates to be done
							//console.log('No updates');
						}
					}
					
				});
			}
		
			//Convert metres per second to miles per hour
			function msToMPH(ms){
				var mph = ms * 2.23693629;
				return (mph).toFixed(1);
			}
			
			//Convert metres per second to kilometers per hour
			function msToKPH(ms){
				var kph = ms * 3.6;
				return (kph).toFixed(1);
			}
			
			//Convert metres to feet
			function mToFt(m){
				return (m * 3.2808399).toFixed(0);
			}
			
			//Nicely format the time
			function decimalTimeToTime(dec){
				var min = Math.floor(Math.abs(dec))
				var sec = Math.floor((Math.abs(dec) * 60) % 60);
				if(isNaN(min) || isNaN(sec)){
					return '--';
				}
				return min + ":" + (sec < 10 ? "0" : "") + sec;
			}
			
			//Convert metres per second to minutes per mile
			function msToMinMile(ms){
				var mins =  26.8224 / ms;
				var output = decimalTimeToTime(mins);
				return decimalTimeToTime(mins);
			}
			
			//Convert metres per second to minutes per kilometer
			function msToMinKM(ms){
				var mins =  16.6666667 / ms;
				return decimalTimeToTime(mins);
			}
			
			//Convert difference between the last update (param) and the current time into a nicely formatted date
			function millisToTime(millis){
				var lastUpdate = new Date(millis);
				var now = Date.now();
				var dif = now - lastUpdate;
				
				return msToTime(dif);
			}
			
			//Convert milliseconds to nicely formatted time
			function msToTime(duration) {
				var milliseconds = parseInt((duration%1000)/100)
					, seconds = parseInt((duration/1000)%60)
					, minutes = parseInt((duration/(1000*60))%60)
					, hours = parseInt((duration/(1000*60*60))%24);

				hours = (hours < 10) ? "0" + hours : hours;
				minutes = (minutes < 10) ? "0" + minutes : minutes;
				seconds = (seconds < 10) ? "0" + seconds : seconds;

				return hours + ":" + minutes + ":" + seconds;
			}
			
			//Run some tests to show how the speed conversions work
			function speedTests(speed){
				console.log('MPH: ' + msToMPH(speed));
				console.log('KPH: ' + msToKPH(speed));
				console.log('/mile: ' + msToMinMile(speed));
				console.log('/km: ' + msToMinKM(speed));
			}
			
			//Show the current user's position on the map (not the person being tracked but the user tracking them)
			function showUserPosition(position){
				var userLat = position.coords.latitude;
				var userLong = position.coords.longitude;
				
				var myLatlng = new google.maps.LatLng(userLat,userLong);
				user = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title:"You",
					icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
				});	
				user.setMap(map);
				
			}
	
	
		//Initialize the map
		function initialize() {
			//Set the zoom and centre the map over the latest position
			var mapOptions = {
				center: { lat: <?php echo $current_lat; ?>, lng: <?php echo $current_long; ?>},
				zoom: 16
			};
			//Set the map location
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

			//Build marker
			var myLatlng = new google.maps.LatLng(<?php echo "$current_lat,$current_long"; ?>);
			marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title:"Me"
			});	
			marker.setMap(map);
			//End of building marker

			//Build history path
			var history = [

			<?php
			$count = 0;
			foreach($history as $h){
				if($count != 0){
					echo ',';
				}
				echo 'new google.maps.LatLng(';
				echo $h['latitude'];
				echo ',';
				echo $h['longditude'];
				echo ')';

				$count++;
			}		
			?>
			];

			historyPath = new google.maps.Polyline({
				path: history,
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 2
			});

			historyPath.setMap(map);
			//End of building history path


			//Set display fields
			$('#speed_mph').html(msToMPH(<?php echo $current_speed;?>));
			$('#speed_kph').html(msToKPH(<?php echo $current_speed;?>));
			$('#speed_min_mile').html(msToMinMile(<?php echo $current_speed;?>));
			$('#speed_min_km').html(msToMinKM(<?php echo $current_speed;?>));
			$('#alt_m').html(<?php echo $current_altitude;?>);
			$('#alt_ft').html(mToFt(<?php echo $current_altitude;?>));
			$('#time_ago').html(millisToTime(time));

			//Get the users position
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showUserPosition);
			}
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	</head>
	<body>
		<div id="info">
			<div id="speed_bike" class="info_item"><img src="../resources/bike.png"/><p><span id="speed_mph">0</span>mph / <span id="speed_kph">0</span>kph</p></div><div id="speed_run" class="info_item"><img src="../resources/run.png"/><p><span id="speed_min_mile">0:00</span>/mile / <span id="speed_min_km">0:00</span>/km</p></div><div id="altitude" class="info_item"><img src="../resources/altitude.png"/><p><span id="alt_ft">0</span>ft / <span id="alt_m">0</span>m</p></div><div id="time" class="info_item"><img src="../resources/time.png"/><p><span id="time_ago">0:00</span> ago</p></div>
		</div>
		<div id="map-canvas"></div>
	</body>
</html>