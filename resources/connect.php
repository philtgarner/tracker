<?php
$DB_NAME = '.gps.db';
$GOOGLE_MAPS_API = "AIzaSyCNVRCL5p4Byw-UJqxfmhpdeAwyEIDeonE";

try{
	$pdo = new PDO('sqlite:../db/' . $DB_NAME);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//Turn of foreign keys
	$pdo->exec( 'PRAGMA foreign_keys = ON;' );

}catch(PDOException $e){
	echo $e->getMessage();
}
?>
