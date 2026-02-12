<?php
	include('./assets/php/sql_conn.php');	
	
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
		
		case 'student-feedback':
		$title = "Student's Feedback";
		include('student_feedback/student_feedback.php');
		break;  
		
		case 'teachers-feedback':
		$title = "Teachers' Feedback";
		include('teachers_feedback/teachers_feedback.php');
		break;		

		case 'employers-feedback':
    	$title = "Employers’ Feedback";
		include('employers_feedback/employers_feedback.php');
		break;	

		case 'alumni-feedback':
    	$title = "Alumni Feedback";
		include('alumni_feedback/alumni_feedback.php');
		break;
						
		default:
		include('signin/signin.php');
	}
    

?>