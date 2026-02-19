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
		$stat_bool = false;

		$itemName = $_POST['itemName']; 
		$unitType = $_POST['unitType'];
		$itemCategory = $_POST['itemCategory']; 
		$serial_no = $_POST['serial_no']; 

		$ItmId = '';
		$ItmCd = '';

		$UsrId = $_SESSION['UsrId'];
		
		$query = "CALL usp_InsertItem('".$serial_no."', '".$ItmCd."', '".$itemName."', '".$unitType."', '".$itemCategory."')";
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
	}
	
	
	if($fn == 'saveFormData1'){
		$return_array = array();
		$status = true;
		$error_message = '';
		$stat_bool = false;

		$parent_c_id = $_POST['parent_c_id']; 
		$sub_c_id = $_POST['sub_c_id']; 
		$serial_no = $_POST['serial_no'];  
		
		
		if($serial_no > 0){
			$sql1 = "UPDATE bank_data SET parent_c_id = '" .$parent_c_id. "', sub_c_id = '" .$sub_c_id. "' WHERE b_id = '".$serial_no."'";
			$result1 = $con->query($sql1);
			$error_message = 'Data updated successfully';
		}

		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
    	echo json_encode($return_array);
	}
	//function end

	//function start
	if($fn == 'getTableData'){
		$return_array = array();
		$status = true;
		$mainData = array();
		$sql = "SELECT * FROM bank_data ORDER BY b_id DESC";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$b_id = $row['b_id'];
				$trans_date = $row['trans_date'];
				$narration = $row['narration'];
				$chq_ref_no = $row['chq_ref_no'];
				$value_date = $row['value_date'];
				$withdrawal_amount = $row['withdrawal_amount'];
				$deposit_amount = $row['deposit_amount'];

				if($b_id != ''){
					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas1" class="action-icon" onClick="editTabledata('.$b_id.')"> <i class="mdi mdi-pencil"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$b_id.')"> <i class="mdi mdi-delete"></i></a>';
						
					$data[0] = $slno; 
					$data[1] = date('d-m-Y', strtotime($trans_date));
					$data[2] = $narration; 
					$data[3] = $chq_ref_no;
					$data[4] = date('d-m-Y', strtotime($value_date));
					$data[5] = $withdrawal_amount;
					$data[6] = $deposit_amount;	
					$data[7] = $action_html;				
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

		$b_id = ''; 
		$trans_date = ''; 
		$narration = ''; 
		$chq_ref_no = ''; 
		$value_date = ''; 
		$withdrawal_amount = ''; 
		$deposit_amount = ''; 
		$parent_c_id = ''; 
		$sub_c_id = '';
		
		
		$sql = "SELECT * FROM bank_data WHERE b_id = '" .$serial_no. "' ";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$b_id = $row['b_id'];   
				$trans_date = $row['trans_date'];   
				$narration = $row['narration'];   
				$chq_ref_no = $row['chq_ref_no'];   
				$value_date = $row['value_date'];   
				$withdrawal_amount = $row['withdrawal_amount'];      
				$deposit_amount = $row['deposit_amount'];      
				$parent_c_id = $row['parent_c_id'];      
				$sub_c_id = $row['sub_c_id'];   
			}
		}

		$return_array['status'] = $status; 
		$return_array['serial_no'] = $serial_no;

		$return_array['trans_date'] = $trans_date;
		$return_array['narration'] = $narration;
		$return_array['chq_ref_no'] = $chq_ref_no;
		$return_array['value_date'] = $value_date;
		$return_array['withdrawal_amount'] = $withdrawal_amount;
		$return_array['deposit_amount'] = $deposit_amount;
		$return_array['parent_c_id'] = $parent_c_id;
		$return_array['sub_c_id'] = $sub_c_id;
		
    	echo json_encode($return_array);
	}//function end		 

	//Delete Food Item
	if($fn == 'deleteTableItem'){
		$return_array = array();
		$status = true;
		$sl_no = $_POST['sl_no'];
		
		$query2 = "CALL usp_getItemList_ED('" .$sl_no. "', 'D')";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) { 
					//$Id = $row2['Id'];
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));	

		$return_array['status'] = $status;		
    	echo json_encode($return_array);
	}//function end 

	//Unit Type 	
	if($fn == 'getUnitTypeDD'){
		$return_result = array();
		$datas = array();
		$status = true;
		$error_msg = ''; 

		$query2 = "CALL mst_getUnit()";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) { 
					$Id = $row2['Id'];
					$UName = $row2['UName'];

					if($Id != ''){
						$data = new stdClass();						
						$data->Id = $Id; 
						$data->UName = $UName;
						array_push($datas, $data);
					}
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));
		
		$return_result['status'] = $status;
		$return_result['datas'] = $datas;
		echo json_encode($return_result);
	}//end function 
	

	//Item Category 	
	if($fn == 'itemCategoryDD'){
		$return_result = array();
		$datas = array();
		$status = true;
		$error_msg = ''; 
		$tId = 1;

		$query2 = "CALL mst_getCategory('".$tId."')";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) { 
					$Id = $row2['Id'];
					$UName = $row2['UName'];

					if($Id != ''){
						$data = new stdClass();						
						$data->Id = $Id; 
						$data->UName = $UName;
						array_push($datas, $data);
					}
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));
		
		$return_result['status'] = $status;
		$return_result['datas'] = $datas;
		echo json_encode($return_result);
	}//end function 
	

?>