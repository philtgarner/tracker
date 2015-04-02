<?php
$output = false;

if(isset($_GET['lat']) && isset($_GET['long']) && isset($_GET['pass']) && isset($_GET['dt'])){
	$lat = $_GET['lat'];
	$long = $_GET['long'];
	$pass = $_GET['pass'];
	$dt = $_GET['dt'];
	$speed = $_GET['speed'];
	$altitude = $_GET['alt'];
	
	include '../resources/connect.php';
	
	try{
		$sql = "INSERT INTO gps (upload, date_time, lat, long, speed, altitude) VALUES (:pass,:dt,:lat,:long,:speed,:altitude)";
		
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':pass', $pass, PDO::PARAM_STR);
		$statement->bindValue(':dt', $dt, PDO::PARAM_STR);
		$statement->bindValue(':lat', $lat, PDO::PARAM_STR);
		$statement->bindValue(':long', $long, PDO::PARAM_STR);
		$statement->bindValue(':speed', $speed, PDO::PARAM_STR);
		$statement->bindValue(':altitude', $altitude, PDO::PARAM_STR);

		$statement->execute();

		$rows = $statement->rowCount();
		if($rows > 0){
			$output = true;
		}
	}catch(PDOException $e){
		$output = false;
	}
}


$response = array("response" => $output);
header('Content-Type: application/json');
echo json_encode($response);

?>