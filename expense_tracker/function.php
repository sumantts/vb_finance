<?php
	include('../assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	} 
	
	
	if($fn == 'saveFormData1'){
		$return_array = array();
		$status = true;
		$error_message = '';
		$stat_bool = false;

		$sess_co_id = $_SESSION["co_id"];
		$sess_login_id = $_SESSION["login_id"];

		$parent_c_id = $_POST['parent_c_id']; 
		$sub_c_id = $_POST['sub_c_id']; 
		$narration = $_POST['narration'];  
		$exp_amount = $_POST['exp_amount'];  
		$exp_date = $_POST['exp_date'];  
		$serial_no = $_POST['serial_no'];  
		
		
		if($serial_no > 0){
			$sql1 = "UPDATE expense_tracker SET parent_c_id = '" .$parent_c_id. "', sub_c_id = '" .$sub_c_id. "', narration = '" .$narration. "', exp_amount = '" .$exp_amount. "', exp_date = '" .$exp_date. "' WHERE exp_id = '".$serial_no."'";
			$result1 = $con->query($sql1);
			$error_message = 'Data updated successfully';
		}else{
			$sql1 = "INSERT INTO expense_tracker (login_id, co_id, parent_c_id, sub_c_id, narration, exp_amount, exp_date) VALUES ('" .$sess_login_id. "','" .$sess_co_id. "', '" .$parent_c_id. "', '" .$sub_c_id. "', '" .$narration. "', '" .$exp_amount. "', '" .$exp_date. "') ";
			$result1 = $con->query($sql1);
			$error_message = 'Data inserted successfully';
		}

		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
    	echo json_encode($return_array);
	}
	//function end

	

	//save Form Data
	/*****
	if($fn == 'saveFormData2'){
		$return_array = array();
		$status = true;
		$error_message = '';

		$selectedData = $_POST['selectedData']; 
		$parent_c_id = $_POST['parent_c_id'];  
		$sub_c_id = $_POST['sub_c_id'];  

		if(sizeof($selectedData) > 0){
			for($i = 0; $i < sizeof($selectedData); $i++){
				$b_id = $selectedData[$i]['obj_id'];
				$sql1 = "UPDATE bank_data SET parent_c_id = '" .$parent_c_id. "', sub_c_id = '" .$sub_c_id. "' WHERE b_id = '".$b_id."'";
				$result1 = $con->query($sql1);
			}
		}//end if		
		$error_message = 'Data updated successfully';
		$con->close();	

		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
    	echo json_encode($return_array);
	}//function end	
****/
	//function start
	if($fn == 'getTableData'){
		$sess_co_id = $_SESSION["co_id"];
		$sess_login_id = $_SESSION["login_id"];
		
		$sess_from_date = $_SESSION["from_date"];
		$sess_to_date = $_SESSION["to_date"];		

		$return_array = array();
		$status = true;
		$mainData = array();

		

		$sql = "SELECT expense_tracker.exp_id, expense_tracker.co_id, expense_tracker.parent_c_id, expense_tracker.sub_c_id, expense_tracker.narration, expense_tracker.exp_amount, expense_tracker.exp_date, expense_tracker.login_id, category.category_name FROM expense_tracker JOIN category ON expense_tracker.parent_c_id = category.c_id WHERE expense_tracker.login_id = '" .$sess_login_id. "' AND expense_tracker.co_id = '" .$sess_co_id. "' AND expense_tracker.exp_date >= '" .$sess_from_date. "' AND  expense_tracker.exp_date <= '" .$sess_to_date. "'";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$exp_id = $row['exp_id'];
				$co_id = $row['co_id'];
				$parent_c_id = $row['parent_c_id'];
				$sub_c_id = $row['sub_c_id'];
				$narration = $row['narration'];
				$exp_amount = $row['exp_amount'];
				$exp_date = $row['exp_date'];
				$category_name = $row['category_name'];

				
				$sub_category_name = '';
				if($sub_c_id > 0){
					$sql3 = "SELECT * FROM category WHERE c_id = '" .$sub_c_id. "' ";
					$result3 = $con->query($sql3);
					if ($result3->num_rows > 0) {
						$row3 = $result3->fetch_array();
						$sub_category_name = $row3['category_name'];
					}
				}//end if
				

				if($exp_id != ''){
					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$exp_id.')"> <i class="mdi mdi-pencil"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$exp_id.')"> <i class="mdi mdi-delete"></i></a>';
					
					$data[0] = $slno; 
					$data[1] = $category_name; 
					$data[2] = $sub_category_name;
					$data[3] = $narration; 
					$data[4] = $exp_amount;
					$data[5] = date('d-F-Y', strtotime($exp_date));
					$data[6] = $action_html;				
					array_push($mainData, $data);
					$slno++;
				}
			}
		}

		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end		
			
	//Get Edit Data
	if($fn == 'editTabledata'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['serial_no'];  		
		
		$sql = "SELECT * FROM expense_tracker WHERE exp_id = '" .$serial_no. "' ";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			while($row = $result->fetch_array()){
				$return_array['exp_id'] = $row['exp_id']; 
				$return_array['login_id'] = $row['login_id']; 
				$return_array['co_id'] = $row['co_id'];  
				$return_array['parent_c_id'] = $row['parent_c_id']; 
				$return_array['sub_c_id'] = $row['sub_c_id'];  
				$return_array['narration'] = $row['narration']; 
				$return_array['exp_amount'] = $row['exp_amount'];  
				$return_array['exp_date'] = $row['exp_date'];				
			}
		}

		$return_array['status'] = $status; 
		$return_array['serial_no'] = $serial_no;
				
    	echo json_encode($return_array);
	}//function end		 

	//Delete Food Item
	if($fn == 'deleteTableItem'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['sl_no']; 
		
		$sql = "DELETE FROM expense_tracker WHERE exp_id = '" .$serial_no. "' ";
		$result = $con->query($sql); 

		$return_array['status'] = $status; 
    	echo json_encode($return_array);
	}//function end   
	

?>