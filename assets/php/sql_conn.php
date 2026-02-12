<?php
	//echo "DB Connection start here";
	date_default_timezone_set("Asia/Calcutta");

	session_start();
	
  	$p_name = 'VB Finance';
	$logo = 'logo.jpg';
	$ico = 'logo-mahila.ico';

	//Mysqli DB Connection Procedural style	

	if($_SERVER['HTTP_HOST'] == 'localhost'){
		//Localhost connection
		$host_name = "localhost";
		$user_name = "root";
		$password = "";
		$db_name = "vb_finance";
	}else{
		//Server connection
		$host_name = "localhost";
		$user_name = "root";
		$password = "";
		$db_name = "ezidesk";
	}

	$con = mysqli_connect($host_name, $user_name, $password, $db_name);
	
	// Check connection
	if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
	exit();
	}
	
	?>