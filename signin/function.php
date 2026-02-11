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
		$UsrId = 0;
		$UsrGrpId = 0;
		$UsrNm = '';

		$query = "CALL usp_LogIn('".$emailaddress."', '".$password."')";
		mysqli_multi_query($con, $query);
		do {
			/* store the result set in PHP */
			if ($result = mysqli_store_result($con)) {
				$status = true;
				$message = 'Username or password match';
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					//printf("%s\n", $row[0]);
					$UsrId = $row['UsrId'];
					$UsrGrpId = $row['UsrGrpId']; 
					$UsrNm = $row['UsrNm']; 
					
					$_SESSION['UsrId'] = $UsrId;
					$_SESSION['UsrGrpId'] = $UsrGrpId;
					$_SESSION['UsrNm'] = $UsrNm;
				}
			}
			/* print divider */
			if (mysqli_more_results($con)) {
				//printf("-----------------\n");
			}
		} while (mysqli_next_result($con));
		
		if($UsrId > 0){

		}else{
			$status = false;
			$message = 'Wrong Username or Password'; 	
		}//end if

		$return_result['status'] = $status;
		$return_result['message'] = $message;
		//sleep(2);
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
		$result = $mysqli->query($sql);

		//Update Author Table
		if($author_photo != ''){
			$sql1 = "UPDATE author_details SET author_name = '" .$profile_name. "', email = '".$username."', author_photo = '".$author_photo."' WHERE author_id = '" .$author_id. "'";
		}else{
			$sql1 = "UPDATE author_details SET author_name = '" .$profile_name. "', email = '".$username."' WHERE author_id = '" .$author_id. "'";
		}
		$result1 = $mysqli->query($sql1);

		$ststus = true;
		//$mysqli->close();

		$return_result['status'] = $status;
		//sleep(2);
		echo json_encode($return_result);
	}//end function doLogin

    ?>