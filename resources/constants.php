<?
/*
 * A series of constants used throughout the tracking applications. This file is included in 'connect.php' so no need to include this on pages that already connect to the SQLite database
 */

//The name of the SQLite file. Unless you have good reason to change this you can leave it as is.
define('DB_NAME', '.gps.db');

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
define('GOOGLE_MAPS_API', 'AIzaSyAyGbRV7R-QKqRumYvtwZHmi8d9oi9KZU0');

//The maximum number of devices the system should allow to upload. Use this to stop people using your system to track their details. Set to < 0 to allow unlimited devices.
define('MAX_DEVICES', -1);

/*
A Google Analytics tracking ID if you wish to use GA to track visitors to the main page.
http://www.google.com/analytics/
Uncomment the following line and add your tracking ID to use GA
*/
//define('GOOGLE_ANALYTICS_TRACKING_ID', 'Your tracking ID');

//Customise the SPEED_MODE constant to show the speed of the person as either mph/kmph (cyclists) or miles/minute (runners). The default is both, in this case the speed will be shown in both formats.
//Do not change this enum
abstract class SpeedMode{
	const Both = 0;
	const Bike = 1;
	const Run = 2;
}

//Change this to customise your speed mode
define('SPEED_MODE', SpeedMode::Both);

//True if in developer mode, false otherwise
define('DEV_MODE', false);

//The creator name for GPX exports
define('GPX_CREATOR', 'Location Tracker by Philip Garner');

?>