<?php
// Connect to DB
function connect(){
	$servername = "localhost:3306";
	$username = "root";
	$password = "toor";
	$dbname = "ticket";
	try 
	{
		static $conn;
		if ($conn===NULL){ 
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	        // Set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $conn;
	}
	catch(PDOException $e)
	{
		fwrite(STDOUT, "Error: " . $e->getMessage());
		return null;
	}
}
