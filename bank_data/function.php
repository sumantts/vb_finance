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
	}//function end

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
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$b_id.')"> <i class="mdi mdi-eye"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$b_id.')"> <i class="mdi mdi-delete"></i></a>';
						
					$data[0] = $slno; 
					$data[1] = date('d-m-Y', strtotime($trans_date));
					$data[2] = $narration; 
					$data[3] = $chq_ref_no;
					$data[4] = date('d-m-Y', strtotime($value_date));
					$data[5] = $withdrawal_amount;
					$data[6] = $deposit_amount;	
					//$data[7] = $action_html;				
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
		$Id = '';
		$ItmNm = '';
		$Unit = '';
		$UnitName = '';
		$Category = '';
		$CatgName = '';
		
		
		$sql = "SELECT * FROM students_feedback WHERE sf_id = '" .$serial_no. "' ";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				//$sf_id = $row['sf_id'];
				$return_array['sf_id'] = $row['sf_id']; 
				$return_array['stdn_id'] = $row['stdn_id']; 
				$return_array['stdn_name'] = $row['stdn_name']; 
				$return_array['stdn_email'] = $row['stdn_email']; 
				$return_array['stdn_phone'] = $row['stdn_phone']; 
				$return_array['ans_1'] = $row['ans_1']; 
				$return_array['ans_2'] = $row['ans_2']; 
				$return_array['ans_3'] = $row['ans_3']; 
				$return_array['ans_4'] = $row['ans_4']; 
				$return_array['ans_5'] = $row['ans_5']; 
				$return_array['ans_6'] = $row['ans_6']; 
				$return_array['ans_7'] = $row['ans_7']; 
				$return_array['ans_8'] = $row['ans_8']; 
				$return_array['ans_9'] = $row['ans_9']; 
				$return_array['ans_10'] = $row['ans_10']; 
				$return_array['ans_11'] = $row['ans_11']; 
				$return_array['ans_12'] = $row['ans_12']; 
				$return_array['ans_13'] = $row['ans_13']; 
				$return_array['ans_14'] = $row['ans_14']; 
				$return_array['ans_15'] = $row['ans_15']; 
				$return_array['ans_16'] = $row['ans_16']; 
				$return_array['ans_17'] = $row['ans_17']; 
				$return_array['ans_18'] = $row['ans_18']; 
				$return_array['ans_19'] = $row['ans_19']; 
				$return_array['ans_20'] = $row['ans_20']; 
				$return_array['ans_21'] = $row['ans_21']; 
				$return_array['ans_22'] = $row['ans_22']; 
				$return_array['ans_23'] = $row['ans_23']; 
				$return_array['ans_24'] = $row['ans_24']; 
				$return_array['ans_25'] = $row['ans_25']; 
				$return_array['ans_26'] = $row['ans_26']; 
				$return_array['ans_27'] = $row['ans_27']; 
				$return_array['ans_28'] = $row['ans_28']; 
				$return_array['created_at'] = $row['created_at']; 
				$return_array['updated_at'] = $row['updated_at']; 
			}
		}

		$return_array['status'] = $status; 
		$return_array['serial_no'] = $Id;
		
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