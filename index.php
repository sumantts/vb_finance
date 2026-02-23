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

		case 'company':
        $title = "Company";
		include('company/company.php');
		break;

		case 'category':
        $title = "Category";
		include('category/category.php');
		break;   
		
		case 'bank-data':
		$title = "Bank Data";
		include('bank_data/bank_data.php');
		break; 
		
		case 'sales-data':
		$title = "Sales Data";
		include('sales_data/sales_data.php');
		break; 
		
						
		default:
		include('signin/signin.php');
	}
    

?>