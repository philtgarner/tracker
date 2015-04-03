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
$GOOGLE_MAPS_API = "AIzaSyCNVRCL5p4Byw-UJqxfmhpdeAwyEIDeonE";

try{
	//Connect to the SQLite database with the name provided above
	$pdo = new PDO('sqlite:../db/' . $DB_NAME);
	//Set PDO to throw exceptions
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//Turn on foreign keys
	$pdo->exec( 'PRAGMA foreign_keys = ON;' );

}catch(PDOException $e){
	//If it all goes wrong, this should never be thrown.
	echo $e->getMessage();
}
?>
