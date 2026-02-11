<?php
	include('assets/php/sql_conn.php');	
	
	if(isset($_GET["p"])){
		$p = $_GET["p"];
	}else{
		$p = '';
	}
	
	if(isset($_GET["gr"])){
		$gr = $_GET["gr"];
	}else{
		$gr = '';
	}
	//session_start();

	switch($p){
		case 'signin':
        $title = "Signin";
		include('signin/signin.php');
		break;

		case 'dashboard':
        $title = "Dashboard";
		include('dashboard/dashboard.php');
		break;

		case 'user-group':
        $title = "User Group";
		include('configuration/user_group/user_group.php');
		break;

		case 'user-details':
        $title = "User Details";
		include('configuration/user_details/user_details.php');
		break; 
		
						
		default:
		include('signin/signin.php');
	}
    

?>