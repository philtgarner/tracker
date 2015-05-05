<!DOCTYPE html>
<html>
	<head>
		<!-- Android notification bar colour -->
		<meta name="theme-color" content="#009900">
		<!-- Favicon -->
		<link rel="shortcut icon" href="../favicon.png" />
		<!-- Page title -->
		<title>Location tracker | PTG</title>
		<!-- Google fonts -->
		<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto:100,300,500' rel='stylesheet' type='text/css'>
		<!-- Link to CSS file -->
		<link href='../resources/main.css' rel='stylesheet' type='text/css'>
		<!-- Disable scrolling on mobile devices -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<!-- Facebook sharing image-->
		<meta property="og:image" content="../resources/icon.png"/>
	</head>
	<body>
		<div id="launcher">
			<img id="header" src="../resources/icon.png" />
			<h1>Location Tracker</h1>
			<p>We couldn't find any tracking information for the download key you provided. Please click <a  href="..">here</a> to try again.</p>
			
			<?php
				//Check the reason for no info:
				if(isset($_GET['i'])){
					switch($_GET['i']){
						//1 = No GPS points
						case 1: 
							$message = 'No GPS points were found.';
							break;
						//2 = Database error
						case 2:
							$message = 'A database error occurred.';
							break;
					}
					echo "<p class=\"small\">$message</p>";
				}
			?>
		</div>
	</body>
</html>