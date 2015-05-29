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
		<link href='http://fonts.googleapis.com/css?family=Roboto:100,300,500' rel='stylesheet' type='text/css'>
		<!-- Set the zoom stuff for mobile devices -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<!-- Set the share icon for Facebook -->
		<meta property="og:image" content="../resources/icon.png"/>
		<!-- Get the CSS file -->
		<link href='../resources/main.css' rel='stylesheet' type='text/css'>
	</head>
	<body>
			<div id="launcher">
			<img id="header" src="../resources/icon.png" />
			<h1>Raw tables</h1>
			
			<table>
				<tr>
					<th colspan="7">gps</th>				
					<th colspan="3">pairs</th>				
				</tr>
				<tr>
					<th>id</th>
					<th>upload</th>
					<th>date_time</th>
					<th>lat</th>
					<th>long</th>
					<th>speed</th>
					<th>altitude</th>
					
					<th>download</th>
					<th>upload</th>
					<th>device</th>
				</tr>
			<?php
					
				include '../resources/connect.php';
				
				$sql = "SELECT gps.id AS `gps.id`, gps.upload AS `gps.upload`, datetime(gps.date_time/1000, 'unixepoch') || ' (' || gps.date_time || ')' AS `gps.date_time`, gps.lat AS `gps.lat`, gps.long AS `gps.long`, gps.speed AS `gps.speed`, gps.altitude AS `gps.altitude`, pairs.download AS `pairs.download`, pairs.upload AS `pairs.upload`, pairs.device AS `pairs.device` FROM gps INNER JOIN pairs ON gps.upload=pairs.upload";
				
				$statement = $pdo->prepare($sql);
				$statement->execute();
				
				
				while($row = $statement->fetch(PDO::FETCH_ASSOC)){
					echo '<tr>';
					foreach($row as $key => $r){
						echo "<td title=\"$key\">$r</td>";
					}
					echo '</tr>';
				}
			?>
			</table>
		</div>
	</body>
<html>
