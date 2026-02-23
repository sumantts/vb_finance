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
		$parent_c_id = $_POST['parent_c_id'];  
		$category_name = $_POST['category_name'];  
		$nature = $_POST['nature']; 
		$part_of_plbs = $_POST['part_of_plbs']; 
		$opening_balance = $_POST['opening_balance']; 

		$status = 1;

		if($serial_no > 0){
			$sql1 = "UPDATE category SET category_name = '" .$category_name. "', parent_c_id = '" .$parent_c_id. "', nature = '" .$nature. "', part_of_plbs = '" .$part_of_plbs. "', opening_balance = '" .$opening_balance. "' WHERE c_id = '".$serial_no."'";
			$result1 = $con->query($sql1);
			$error_message = 'Data updated successfully';
		}else{
			$sql = "SELECT * FROM category WHERE category_name = '".$category_name."'";
			$result = $con->query($sql);

			if ($result->num_rows > 0) {
				$status = false;
				$error_message = 'Category name already exists';
			} else {
				$status = true;
				$sql2 = "INSERT INTO category (parent_c_id, category_name, nature, part_of_plbs, opening_balance) VALUES ('" .$parent_c_id. "', '".$category_name."', '" .$nature."', '" .$part_of_plbs."', '" .$opening_balance."')";
				$result2 = $con->query($sql2);
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
		$sql = "SELECT * FROM category ORDER BY category_name ASC";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$c_id = $row['c_id'];
				$parent_c_id = $row['parent_c_id'];
				$category_name = $row['category_name']; 
				$nature = $row['nature']; 
				$part_of_plbs = $row['part_of_plbs']; 
				$opening_balance = $row['opening_balance']; 

				$nature_txt = '';
				if($nature == 1){
					$nature_txt = 'Income';
				}else if($nature == 2){
					$nature_txt = 'Expense';
				}else{}

				$part_of_plbs_txt = '';
				if($part_of_plbs == 1){
					$part_of_plbs_txt = 'Profit and Loss';
				}else if($part_of_plbs == 2){
					$part_of_plbs_txt = 'Balance Sheet';
				}else{}

				if($c_id != ''){
					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$c_id.')"> <i class="mdi mdi-pencil"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$c_id.')"> <i class="mdi mdi-delete"></i></a>';

					$p_category_name = '';
					$sql1 = "SELECT category_name FROM category WHERE c_id = '" .$parent_c_id. "' ";
					$result1 = $con->query($sql1);
					if ($result1->num_rows > 0) {
						$row1 = $result1->fetch_array();
						$p_category_name = $row1['category_name']; 
					}

						
					$data[0] = $slno; 
					$data[1] = $p_category_name;  	
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
		$c_id = '';
		$category_name = '';
		$nature = '';
		$part_of_plbs = '';
		$opening_balance = '';
		
		$sql = "SELECT * FROM category WHERE c_id = '" .$serial_no. "' ";
		$result = $con->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
			$c_id = $row['c_id'];
			$parent_c_id = $row['parent_c_id'];
			$category_name = $row['category_name'];
			
			$nature = $row['nature']; 
			$part_of_plbs = $row['part_of_plbs']; 
			$opening_balance = $row['opening_balance']; 
		}

		$return_array['status'] = $status;
		$return_array['c_id'] = $c_id;
		$return_array['parent_c_id'] = $parent_c_id;
		$return_array['category_name'] = $category_name;
		$return_array['nature'] = $nature;
		$return_array['part_of_plbs'] = $part_of_plbs;
		$return_array['opening_balance'] = $opening_balance;
    	echo json_encode($return_array);
	}//function end
			
	//Delete Table Data
	if($fn == 'deleteTabledata'){
		$return_array = array();
		$status = true; 
		$serial_no = $_POST['serial_no']; 
		
		$sql = "DELETE FROM category WHERE c_id = '" .$serial_no. "' ";
		$result = $con->query($sql); 

		$return_array['status'] = $status; 
    	echo json_encode($return_array);
	}//function end

	//Get Category Group
	if($fn == 'configureParentCategoryDd'){
		$return_array = array();
		$status = true;
		$mainData = array();
		
		$sql = "SELECT * FROM category WHERE parent_c_id = '0'";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$c_id = $row['c_id'];
				$parent_c_id = $row['parent_c_id'];
				$category_name = $row['category_name'];

				$data_obj = new stdClass();
				$data_obj->c_id = $c_id;
				$data_obj->parent_c_id = $parent_c_id;
				$data_obj->category_name = $category_name;
				array_push($mainData, $data_obj);
			}
		}

		$return_array['status'] = $status;
		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end

	
	if($fn == 'configureSubCategoryDd'){
	    $parent_c_id = $_POST["parent_c_id"];
		$return_array = array();
		$status = true;
		$mainData = array();
		
		$sql = "SELECT * FROM category WHERE parent_c_id = '".$parent_c_id."'";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$c_id = $row['c_id'];
				$parent_c_id = $row['parent_c_id'];
				$category_name = $row['category_name'];

				$data_obj = new stdClass();
				$data_obj->c_id = $c_id;
				$data_obj->parent_c_id = $parent_c_id;
				$data_obj->category_name = $category_name;
				array_push($mainData, $data_obj);
			}
		}

		$return_array['status'] = $status;
		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end

?>