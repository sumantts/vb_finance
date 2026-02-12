<?php
	include('../assets/php/sql_conn.php');
	
	$fn = '';
	if(isset($_POST["fn"])){
	$fn = $_POST["fn"];
	}
	
	//Login function
	if($fn == 'doLogin'){
		$return_result = array();
		$emailaddress = $_POST["emailaddress"];
		$password = $_POST["password"];
		$status = true;
		$message = ''; 
		$login_id = 0;
		$username = '';
	
		$sql = "SELECT * FROM login WHERE username = '".$emailaddress."' && password = '".$password."'";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {	
			$row = $result->fetch_array();
			$login_id = $row['login_id'];			
			$username = $row['username'];
		} else {
			$status = false;
		}
		$con->close();				
		
		
		if($login_id > 0){
			$_SESSION["username"] = $username;
			$_SESSION["login_id"] = $login_id;
		}else{
			$status = false;
			$message = 'Wrong Username or Password'; 	
		}//end if

		$return_result['status'] = $status;
		$return_result['message'] = $message; 
		echo json_encode($return_result);
	}//end function doLogin
	
	//Login function
	if($fn == 'updateProfile'){
		$return_result = array();
		$profile_name = $_POST["profile_name"];
		$username = $_POST["username"];
		$password = $_POST["password"];
		$author_photo = $_POST["author_photo"];
		$login_id = $_SESSION["login_id"];
		$author_id = $_SESSION["author_id"];
		$status = true;			
		
		$sql = "UPDATE login SET profile_name = '" .$profile_name. "', username = '".$username."', password = '".$password."' WHERE login_id = '" .$login_id. "'";
		$result = $con->query($sql);

		//Update Author Table
		if($author_photo != ''){
			$sql1 = "UPDATE author_details SET author_name = '" .$profile_name. "', email = '".$username."', author_photo = '".$author_photo."' WHERE author_id = '" .$author_id. "'";
		}else{
			$sql1 = "UPDATE author_details SET author_name = '" .$profile_name. "', email = '".$username."' WHERE author_id = '" .$author_id. "'";
		}
		$result1 = $con->query($sql1);

		$ststus = true;
		//$con->close();

		$return_result['status'] = $status;
		//sleep(2);
		echo json_encode($return_result);
	}//end function doLogin

    ?>