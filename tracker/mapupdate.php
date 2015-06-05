<?php
	
	if(isset($_GET['dl']) && isset($_GET[timestamp])){

		include '../resources/connect.php';
		
		//Need to look for all points that happened later than or at the same time as the previous record.
		//Need to include the previous record to get the distance right
		$sql = 'SELECT lat, long, speed, altitude, date_time FROM gps INNER JOIN pairs ON pairs.upload = gps.upload WHERE pairs.download = :dl AND date_time >= :time ORDER BY date_time ASC';
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':dl', $_GET['dl'], PDO::PARAM_STR);
		$statement->bindValue(':time', $_GET[timestamp], PDO::PARAM_INT);
		
		$statement->execute();
		
		$current_lat = 0;
		$current_long = 0;
		$prev_lat = 0;
		$prev_long = 0;
		$distance = 0;
		
		$history = array();
		$count = 0;
		while($row = $statement->fetch(PDO::FETCH_ASSOC)){
			$h = array('latitude' => $row['lat'], 'longditude' => $row['long']);
			
			$current_lat = $row['lat'];
			$current_long = $row['long'];
			$current_speed = $row['speed'];
			$current_altitude = $row['altitude'];
			$current_date_time = $row['date_time'];
			
			//If we're on the second or later point then store it and add the distance
			if($count > 0){
				$distance += distance($current_lat, $current_long, $prev_lat, $prev_long);
				array_push($history, $h);
			}
			
			//Store the current positions as the previous for the next iteration
			$prev_lat = $current_lat;
			$prev_long = $current_long;
			
			$count++;
		}
		//If we've found one or fewer points then at the most all we have is a duplicate of the previous known last point. Don't issue an update
		$found = false;
		if($count > 1){
			$found = true;
		}
		
		$response = array("response" => $found, "history" => $history, "speed" => $current_speed, "altitude" => $current_altitude, "time"=>(int)$current_date_time, "distance"=>$distance);

	}
	else{
		$response = array("response" => false);
	}
	
	header('Content-Type: application/json');
	echo json_encode($response);
?>