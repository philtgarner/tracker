# tracker
A simple REST interface for uploading and viewing GPS coordinates. To be used in conjunction with a client app.

##How to use
1. Download ZIP file for this project and copy it to a directory on your website e.g. `www.example.org/mytracker`
  1. Open the `resources/constants.php` file in a text editor and customise the following constants, the majority of them can be left as their default values but it's worth checking:
    * `GOOGLE_MAPS_API` - Add your [Google Maps API](https://console.developers.google.com/project) key
	* `MAX_DEVICES` - Maximum number of devices, use -1 for unlimited devices _(default: -1)_
	* `SPEED_MODE` - Tells the system whether to show speeds for cyclists, runners or both. _(default: both)_
	* `DEV_MODE` - Enables developer options such as viewing the SQLite tables and constants, if you're not a developer leave this mode off _(default: false)_
	* `GOOGLE_ANALYTICS_TRACKING_ID` - [Google Analytics Tracking ID](http://www.google.com/analytics/) _(optional) (default: none set)_
2. Use an app on your phone to periodically upload the users location.
  1. The first call should be to `http://www.example.org/mytracker/api/v1/init/dl` (where `dl` is the download key that will be used to access view the tracking points) with the following `POST` parameters:
	* `reset` - `1` to clear the previous GPS coordinates. `0` to keep them
	* `device` - a unique, secret, ID for the device.
  2. Initializing will create the SQLite database and generate an upload key used to add GPS coordinates to the database. The response will be a JSON string in the following format:
    ```
    {"success":true,"key":"DtBFiyiwvH62YfZ","device":"abc123"}
    ```
  3. The JSON string contains three fields:
    * `success` - true if the initialisation was a success
	* `key` - The key to be used to upload GPS coordinates. The system will generate a key regardless of success so always check `success` before using the key
	* `device` - The device ID which was originally given
  4. After successful initialisation coordinates can be uploaded by accessing `http://www.example.org/mytracker/api/v1/update/ul` (where `ul` is the upload key that is used to add coordinates to the database) with the following `POST` parameters:
    * `lat` - The latitude value
	* `long` - The longditude value
	* `dt` - The timestamp for the coordinates (in milliseconds since midnight 1 January 1970)
	* `speed` _(optional)_ - The speed value (in m/s)
	* `alt` _(optional)_ - The altitude (in m)
  5. The response to this will be a JSON string in the following format: 
    ```
    {"response":true,"date_time":"1430425590477"}
    ```
  6.  The JSON string contains three fields:
    * `response` - true if the upload was successful
    * `date_time` - the timestamp provided when the upload was sent - allows you to match a response to a given upload request
  7. Repeat _2.4 - 2.6_ periodically to provide live tracking
3. Details of your position can be viewed by accessing `http://www.example.org/mytracker/download` where `download` is the download key set in step _2.1_.
