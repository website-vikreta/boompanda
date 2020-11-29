<?php
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "";
	$db="boompanda";
	/*Create connection*/
	// $servername = "localhost";
	// $username = "u295528632_boompanda";
	// $password = "Boompanda@123";
	// $db="u295528632_boompanda";
	/*Create connection*/
	$conn = mysqli_connect($servername, $username, $password,$db);
?>