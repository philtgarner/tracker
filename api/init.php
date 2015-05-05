<?php

//Generator for building upload keys
function generateRandomString($length = 15) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//Default is that the initialization hasn't worked
$output = false;

/*
Must provide the following to continue with initialization:
	-Download key (the friendly key used to view the tracker)
	-Whether or not to reset (remove all entries for this download key)
	-A device ID, once a device has registered a download key it may not be used by any other device
*/
if(isset($_GET['down']) && isset($_GET['reset']) && isset($_GET['device'])){
	//Generate an upload key (secret between device and web service)
	$up = generateRandomString();
	$down = $_GET['down'];
	$reset = $_GET['reset'];
	$device = $_GET['device'];

	//Connect to the database
	include '../resources/connect.php';
	
	try{
		//Build the database (connecting makes the file so this just builds the tables if they don't already exist)
		$pdo->query('CREATE TABLE IF NOT EXISTS pairs(
		download TEXT,
		upload TEXT PRIMARY KEY,
		device TEXT
		);');
		
		$pdo->query('CREATE TABLE IF NOT EXISTS gps(
		id INT AUTO_INCRIMENT PRIMARY KEY,
		upload TEXT,
		date_time TEXT,
		lat REAL,
		long REAL,
		speed REAL,
		altitude REAL,
		FOREIGN KEY(upload) REFERENCES pairs(upload)
		);');
		
		$device_max_reached = false;
		//Check to see if we're at the maximum device limit. If $MAX_DEVICES is < 0 then no need to check - unlimited devices allowed.
		if($MAX_DEVICES >= 0){
			$sql = "SELECT COUNT(DISTINCT device) AS cnt FROM pairs";
			$statement = $pdo->prepare($sql);
			$statement->execute();
			//If the upload key has been used before then store the upload key and the ID of its owner
			if($row = $statement->fetch(PDO::FETCH_ASSOC)){
				if($row['cnt'] >= $MAX_DEVICES){
					$device_max_reached = true;
				}
			}
		}
		
		if(!$device_max_reached){

			//Check to see if the upload key exists already (i.e. this download key has been used before)
			$sql = "SELECT upload, device FROM pairs WHERE download = :dl";
			$statement = $pdo->prepare($sql);
			$statement->bindValue(':dl', $down, PDO::PARAM_STR);
			$statement->execute();
			//If the upload key has been used before then store the upload key and the ID of its owner
			if($row = $statement->fetch(PDO::FETCH_ASSOC)){
				$up = $row['upload'];
				$owner = $row['device'];
				$output = true;
			}
			
			//If the upload key doesn't exist, add it
			if($output == false){
				$sql = "INSERT INTO pairs (download, upload, device) VALUES (:dl, :ul, :dev)";
				$statement = $pdo->prepare($sql);
				$statement->bindValue(':dl', $down, PDO::PARAM_STR);
				$statement->bindValue(':ul', $up, PDO::PARAM_STR);
				$statement->bindValue(':dev', $device, PDO::PARAM_STR);
				$statement->execute();

				$rows = $statement->rowCount();
				//If we had to insert the down/upload keys then it must be the first time its been used, this is the owner.
				if($rows > 0){
					$output = true;
					$owner = $device;
				}
			}
			
			//Check if this device is the owner, if its not then return false
			if($device != $owner){
				$output = false;
			}
			//If this device is the owner then check if reset is needed
			else{
				//If reset is needed then delete old entries.
				if($reset){
					$sql = "DELETE FROM gps WHERE upload = :ul";
					$statement = $pdo->prepare($sql);
					$statement->bindValue(':ul', $up, PDO::PARAM_STR);
					$statement->execute();
				}
			}
		}
	}
	//If something has gone wrong there isn't a lot the user can do, just return false
	catch(PDOException $e){
		$output = false;
	}

}

//Build the response into a nice JSON string and output it.
$response = array("success" => $output, "key" => $up, "device" => $device);
header('Content-Type: application/json');
echo json_encode($response);
?>