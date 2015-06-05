<!DOCTYPE html>
<html>
	<head>
		<!-- Android notification bar colour -->
		<meta name="theme-color" content="#009900">
		<!-- Favicon -->
		<link rel="shortcut icon" href="favicon.png" />
		<!-- Page title -->
		<title>Location tracker | PTG</title>
		<!-- Google fonts -->
		<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto:100,300,500' rel='stylesheet' type='text/css'>
		<!-- Link to CSS file -->
		<link href='resources/main.css' rel='stylesheet' type='text/css'>
		<!-- Disable scrolling on mobile devices -->
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<!-- Facebook sharing image-->
		<meta property="og:image" content="resources/icon.png"/>
		<?php
			include './resources/constants.php';
			include './resources/ga_tracker.php';
		?>
	</head>
	<body>
		<div id="launcher">
			<img id="header" src="./resources/icon.png" />
			<h1>Location Tracker</h1>
			<p>To track someone enter their download key in the box below and click "Track".</p>
			<form action="./tracker/index.php" method="get">
				<input type="text" placeholder="Download Key" name="dl" />
				<input type="submit" value="Track" />
			</form>
		</div>
	</body>
</html>