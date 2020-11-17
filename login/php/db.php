<?php
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "";
	$db="boompanda";
	/*Create connection*/
	$conn = mysqli_connect($servername, $username, $password,$db);
?>