<?php
	//Only do anything if there is a download key provided - can't really do anything without one.
	if(isset($_GET['dl'])){

		//Connect to the database
		include '../resources/connect.php';
		
		//Get all the points in order for the given download key
		$sql = 'SELECT lat, long, speed, altitude, date_time FROM gps INNER JOIN pairs ON pairs.upload = gps.upload WHERE pairs.download = :dl ORDER BY date_time ASC';
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':dl', $_GET['dl'], PDO::PARAM_STR);
		$statement->execute();
		
		//Count the number of points
		$count = 0;
		
		//Build the root element and add all the appropriate attributes (initially taken from a sample GPX file downloaded from Strava)
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><gpx></gpx>');
		$xml->addAttribute('creator', GPX_CREATOR);
		$xml->addAttribute('version', '1.1');
		$xml->addAttribute('xmlns', 'http://www.topografix.com/GPX/1/1');
		$xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$xml->addAttribute('xsi:schemaLocation', 'http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd');
		$xml->addAttribute('xmlns:gpxtpx', 'http://www.garmin.com/xmlschemas/TrackPointExtension/v1');
		$xml->addAttribute('xmlns:gpxx', 'http://www.garmin.com/xmlschemas/GpxExtensions/v3');
		
		//Add the metadata element to store the time
		$metadata = $xml->addChild('metadata');
		
		//Att the track element and give it a name (the download key is used as the name)
		$trk = $xml->addChild('trk');
		$trk->addChild('name', $_GET['dl']);
		
		//Build the track segment
		$trkseg = $trk->addChild('trkseg');
		
		//Loop through the results
		while($row = $statement->fetch(PDO::FETCH_ASSOC)){
			//Build a track point and ass the various details
			$trkpt = $trkseg->addChild('trkpt');
			$trkpt->addAttribute('lat', $row['lat']);
			$trkpt->addAttribute('lon', $row['long']);
			$trkpt->addChild('ele', $row['altitude']);
			$time = $row['date_time']/1000;
			$trkpt->addChild('time', date(DATE_ATOM, $time));
			
			//If we're on the first result use this time for the creation time of the GPX file metadata tag
			if($count <= 0){
				$metadata->addChild('time', date(DATE_ATOM, $time));	
			}
			
			//Count the number of rows
			$count++;
		}
		//Force the browser to download the file as a GPX file
		header("Content-disposition: attachment;filename={$_GET['dl']}.gpx");
		//Pretty print the XML
		$dom = dom_import_simplexml($xml)->ownerDocument;
		$dom->formatOutput = true;
		echo $dom->saveXML();
	}

?>