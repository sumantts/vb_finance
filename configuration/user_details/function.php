<?php
	include('../../assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	}	
	
	//save Form Data
	if($fn == 'saveFormData'){
		$return_array = array();
		$status = true;
		$error_message = '';

		$serial_no = $_POST['serial_no']; 
		$FullNm = $_POST['FullNm']; 
		$UsrNm = $_POST['UsrNm']; 
		$password = $_POST['password']; 
		$GrpId = $_POST['GrpId'];  
		$status = 1;

		$UsrId = $_SESSION['UsrId'];
		
		$query = "CALL usp_User('".$serial_no."', '".$GrpId."', '".$FullNm."', '".$UsrNm."', '".$password."', '".$status."')";
		mysqli_multi_query($con, $query);
		do {
			if ($result = mysqli_store_result($con)) {
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					 
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con)); 

		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
    	echo json_encode($return_array);
	}//function end	

	//function start
	if($fn == 'getTableData'){
		$return_array = array();
		$status = true;
		$mainData = array();
		$email1 = '';		

		//Get Group Members
		$query2 = "CALL usp_ViewUsrDtlSlist()";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
					//printf("%s\n", $row[0]);
					$Sl = $row2['Sl'];
					$FullNm = $row2['FullNm'];
					$UsrNm = $row2['UsrNm'];
					$GrpNm = $row2['GrpNm'];

					if($Sl != ''){
						$action_html = '';
						$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$Sl.')" > <i class="mdi mdi-pencil"></i></a>';
						$action_html .= '<a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
						 
						$data[0] = $Sl; 
						$data[1] = $FullNm;
						$data[2] = $UsrNm;
						$data[3] = $GrpNm;
						$data[4] = $action_html;			
						array_push($mainData, $data);
					}
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));

		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end	
			
	//Get Table Data
	if($fn == 'editTabledata'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['serial_no'];
		$Id = 0;
		$GrpId = 0;
		$FullNm = '';
		$UsrNm = '';
		
		//Get Group Members
		$query2 = "CALL usp_GetUsrDtls('" .$serial_no. "')";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) { 
					$Id = $row2['Id'];
					$GrpId = $row2['GrpId'];
					$FullNm = $row2['FullNm']; 
					$UsrNm = $row2['UsrNm'];
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));

		$return_array['status'] = $status;
		$return_array['Id'] = $Id;
		$return_array['GrpId'] = $GrpId;
		$return_array['FullNm'] = $FullNm;
		$return_array['UsrNm'] = $UsrNm;
    	echo json_encode($return_array);
	}//function end

	//Get User Group
	if($fn == 'configureUserGroupDropDown'){
		$return_array = array();
		$status = true;
		$mainData = array();
		
		//Get Group Members
		$query2 = "CALL usp_GetUsrGrp()";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
					//printf("%s\n", $row[0]);
					$Id = $row2['Id'];
					$GrpNm = $row2['GrpNm']; 
					
					$data = new stdClass();
					$data->Id = $Id;
					$data->GrpNm = $GrpNm;
					array_push($mainData, $data);
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));

		$return_array['status'] = $status;
		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end

?>