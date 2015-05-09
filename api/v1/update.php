<?php
$output = false;

//The information we need to update is the position (latitude and longditude) and the password (upload key) and the date/time
if(isset($_POST['lat']) && isset($_POST['long']) && isset($_GET['ul']) && isset($_POST['dt'])){
	$lat = $_POST['lat'];
	$long = $_POST['long'];
	$pass = $_GET['ul'];
	$dt = $_POST['dt'];
	$speed = $_POST['speed'];
	$altitude = $_POST['alt'];
	
	//Connect to the database
	include dirname(__FILE__) . '/../../resources/connect.php';
	
	//Try inserting the data
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

		//Make sure we've added a row and set the output to true to show the update has been made
		$rows = $statement->rowCount();
		if($rows > 0){
			$output = true;
		}
	//If it all goes wrong just return false, not a lot the client can do
	}catch(PDOException $e){
		$output = false;
	}
}

//Build and output the JSON response. Outputs true/false for success and the date/time initially sent by the client
$response = array("response" => $output, "date_time" => $dt);
header('Content-Type: application/json');
echo json_encode($response);

?>