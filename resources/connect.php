<?php
include dirname(__FILE__) . '/constants.php';

try{
	//Get the path to the database files (this remains constant even if included in another directory)
	$db_path = dirname(__FILE__) . '/../db';
	$pdo = new PDO("sqlite:$db_path/" . DB_NAME);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//Turn of foreign keys
	$pdo->exec( 'PRAGMA foreign_keys = ON;' );

}catch(PDOException $e){
	echo $e->getMessage();
}

//Get distance in KM
function distance($lat1, $lon1, $lat2, $lon2) {
	if($lat1 == $lat2 && $lon1 == $lon2){
		return 0;
	}
	else{
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;

		$output = ($miles * 1.609344);
		
		if(is_nan($output)){
			return 0;
		}
		return $output;
	}
}

?>
