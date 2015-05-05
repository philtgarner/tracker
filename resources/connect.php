<?php
//The name of the SQLite file. Unless you have good reason to change this you can leave it as is.
$DB_NAME = '.gps.db';

/*
The following is an example API key, to generate your own:
 - Go to https://console.developers.google.com/project
 - Click "API project"
 - Select "APIs & auth" then "APIs" in the left hand menu
 - Select "Google Maps JavaScript API" to enable the Maps JS v3 API
 - If it isn't already, click "Enable API" at the top of the page
 - Click "Credentials" under the "APIs & auth" in the left hand menu
 - Create a new API key and copy it into the variable below, it will look similar to this example one
*/
$GOOGLE_MAPS_API = "AIzaSyAyGbRV7R-QKqRumYvtwZHmi8d9oi9KZU0";

//The maximum number of devices the system should allow to upload. Use this to stop people using your system to track their details. Set to < 0 to allow unlimited devices.
$MAX_DEVICES = 5;

try{
	$pdo = new PDO('sqlite:../db/' . $DB_NAME);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//Turn of foreign keys
	$pdo->exec( 'PRAGMA foreign_keys = ON;' );

}catch(PDOException $e){
	echo $e->getMessage();
}

function distance($lat1, $lon1, $lat2, $lon2) {
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;

	return ($miles * 1.609344);
}

?>
