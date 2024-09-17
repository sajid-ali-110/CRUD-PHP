<?php 
	
	$host = "localhost";
	$username = "root";
	$password = null;
	$databse = "crud";

	$conn = new PDO("mysql:host=$host;dbname=$databse", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// echo "connection done";

 ?>