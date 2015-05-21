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
			<h2>gps</h2>
			<?php
					
				include '../resources/connect.php';
				
				$sql = "SELECT * FROM gps";
				
				$statement = $pdo->prepare($sql);
				$statement->execute();
				
				$count = 0;
				echo '<table>';
				while($row = $statement->fetch(PDO::FETCH_ASSOC)){
					//Display the headers
					if($count <= 0){
						echo '<tr>';
						foreach($row as $key => $r){
							echo "<th>$key</th>";
						}
						echo '</tr>';
					}
					
					echo '<tr>';
					foreach($row as $key => $r){
						echo "<td title=\"$key\">$r</td>";
					}
					echo '</tr>';
					$count++;
				}
				echo '</table>';
			?>
			<h2>pairs</h2>
			<?php
					
				$sql = "SELECT * FROM pairs";
				
				$statement = $pdo->prepare($sql);
				$statement->execute();
				
				$count = 0;
				echo '<table>';
				while($row = $statement->fetch(PDO::FETCH_ASSOC)){
					//Display the headers
					if($count <= 0){
						echo '<tr>';
						foreach($row as $key => $r){
							echo "<th>$key</th>";
						}
						echo '</tr>';
					}
					
					echo '<tr>';
					foreach($row as $key => $r){
						echo "<td title=\"$key\">$r</td>";
					}
					echo '</tr>';
					$count++;
				}
				echo '</table>';
				
			?>
		</div>
	</body>
<html>
