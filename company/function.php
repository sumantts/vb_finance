<?php
	include('../assets/php/sql_conn.php');
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
		$company_name = $_POST['company_name']; 
		$login_id = $_SESSION['login_id'];

		$status = 1;

		if($serial_no > 0){
			$sql1 = "UPDATE company SET company_name = '" .$company_name. "' WHERE co_id = '".$serial_no."'";
			$result1 = $con->query($sql1);
			$error_message = 'Data updated successfully';
		}else{
			$sql = "SELECT * FROM company WHERE company_name = '".$company_name."'";
			$result = $con->query($sql);

			if ($result->num_rows > 0) {
				$status = false;
				$error_message = 'company name already exists';
			} else {
				$status = true;
				$last_selected = 1;
				$sql3 = "SELECT * FROM company WHERE login_id = '" .$login_id. "' AND last_selected = '".$last_selected."'";
				$result3 = $con->query($sql3);
				if ($result3->num_rows > 0) {
					$sql2 = "INSERT INTO company (login_id, company_name) VALUES ('" .$login_id. "', '".$company_name."')";
					$result2 = $con->query($sql2);
				}else{
					$sql4 = "INSERT INTO company (login_id, company_name, last_selected) VALUES ('" .$login_id. "', '".$company_name."', '".$last_selected."')";
					$result4 = $con->query($sql4);
				}
				$error_message = 'Data inserted successfully';
			}
		}
		$con->close();	


		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
    	echo json_encode($return_array);
	}//function end	

	//function start
	if($fn == 'getTableData'){
		$return_array = array();
		$status = true;
		$mainData = array();
		$login_id = $_SESSION['login_id'];

		$sql = "SELECT * FROM company WHERE login_id = '" .$login_id. "' ORDER BY company_name ASC";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$co_id = $row['co_id'];
				$login_id = $row['login_id'];
				$company_name = $row['company_name']; 
				$created_at = $row['created_at']; 
				

				if($co_id != ''){
					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$co_id.')"> <i class="mdi mdi-pencil"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$co_id.')"> <i class="mdi mdi-delete"></i></a>'; 
						
					$data[0] = $slno; 
					$data[1] = $company_name;  	
					$data[2] = $action_html;				
					array_push($mainData, $data);
					$slno++;
				}
			}
		}

		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end	
			
	//Get Table Data
	if($fn == 'editTabledata'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['serial_no'];
		$co_id = '';
		$company_name = ''; 
		
		$sql = "SELECT * FROM company WHERE co_id = '" .$serial_no. "' ";
		$result = $con->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
			$co_id = $row['co_id']; 
			$company_name = $row['company_name']; 
		}

		$return_array['status'] = $status;
		$return_array['co_id'] = $co_id;
		$return_array['company_name'] = $company_name;
		
    	echo json_encode($return_array);
	}//function end
			
	//Delete Table Data
	if($fn == 'deleteTabledata'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['serial_no']; 
		
		$sql = "DELETE FROM company WHERE co_id = '" .$serial_no. "' ";
		$result = $con->query($sql); 

		$return_array['status'] = $status; 
    	echo json_encode($return_array);
	}//function end
	

	// Company dropdown
	if($fn == 'populateCompanyDD'){
	    $login_id = $_SESSION['login_id'];
		$return_array = array();
		$status = true;
		$mainData = array();
		
		$sql = "SELECT * FROM company WHERE login_id = '".$login_id."'";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$co_id = $row['co_id']; 
				$company_name = $row['company_name'];
				$last_selected = $row['last_selected'];

				$data_obj = new stdClass();
				$data_obj->co_id = $co_id; 
				$data_obj->company_name = $company_name;
				$data_obj->last_selected = $last_selected;
				array_push($mainData, $data_obj);
			}
		}

		$return_array['status'] = $status;
		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end

	
			
	//Get Table Data
	if($fn == 'updateSelectedCompany'){
		$return_array = array();
		$status = true; 
	    $login_id = $_SESSION['login_id'];
		$co_id = $_POST['co_id_key'];
		$_SESSION["co_id"] = $co_id;
		
		$last_selected_zero = 0;
		$sql1 = "UPDATE company SET last_selected = '" .$last_selected_zero. "' WHERE login_id = '".$login_id."'";
		$result1 = $con->query($sql1);

		$last_selected_one = 1;
		$sql2 = "UPDATE company SET last_selected = '" .$last_selected_one. "' WHERE login_id = '".$login_id."' AND co_id = '" .$co_id. "' ";
		$result2 = $con->query($sql2);

		$return_array['status'] = $status;
		
    	echo json_encode($return_array);
	}//function end

?>