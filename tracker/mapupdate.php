<?php
	
	if(isset($_GET['dl']) && isset($_GET[timestamp])){

		include '../resources/connect.php';
			
		$sql = 'SELECT lat, long, speed, altitude, date_time FROM gps INNER JOIN pairs ON pairs.upload = gps.upload WHERE pairs.download = :dl AND date_time > :time';
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':dl', $_GET['dl'], PDO::PARAM_STR);
		$statement->bindValue(':time', $_GET[timestamp], PDO::PARAM_INT);
		
		$statement->execute();
		
		$history = array();
		$found = false;
		while($row = $statement->fetch(PDO::FETCH_ASSOC)){
			$h = array('latitude' => $row['lat'], 'longditude' => $row['long']);
			array_push($history, $h);
			
			$current_speed = $row['speed'];
			$current_altitude = $row['altitude'];
			$current_date_time = $row['date_time'];
			$found = true;
		}
		
		$response = array("response" => $found, "history" => $history, "speed" => $current_speed, "altitude" => $current_altitude, "time"=>(int)$current_date_time);

	}
	else{
		$response = array("response" => false);
	}
	
	header('Content-Type: application/json');
	echo json_encode($response);
?>