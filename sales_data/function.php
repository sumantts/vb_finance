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
		$co_id = $_SESSION["co_id"];
		$return_array = array();
		$status = true;
		$mainData = array();
		$sql = "SELECT * FROM sales_data WHERE co_id = '" .$co_id. "' ORDER BY sa_id DESC";
		$result = $con->query($sql);

		if ($result->num_rows > 0) {
			$status = true;
			$slno = 1;

			while($row = $result->fetch_array()){
				$sa_id = $row['sa_id'];
				$client_name = $row['client_name'];
				$address = $row['address'];
				$state = $row['state'];
				$pin_code = $row['pin_code'];
				$contact_no = $row['contact_no'];
				$pan_number = $row['pan_number'];
				$email_id = $row['email_id'];
				
				$kyc_verified = $row['kyc_verified'];
				$plan_subscribed = $row['plan_subscribed'];
				$date_of_subscription = $row['date_of_subscription'];
				$transaction_id = $row['transaction_id'];
				$plan_duration_month = $row['plan_duration_month'];
				$subscription_end_date = $row['subscription_end_date'];
				$pay_made_tax_amt = $row['pay_made_tax_amt'];
				$igst = $row['igst'];
				$cgst = $row['cgst'];
				$sgst = $row['sgst'];
				
				$total_gst = $row['total_gst'];
				$total_payment = $row['total_payment'];
				$invoice_number = $row['invoice_number'];
				$payment_gateway = $row['payment_gateway'];
				$hsh_code = $row['hsh_code'];
				$gateway_charges = $row['gateway_charges'];
				$gst_on_charges = $row['gst_on_charges'];
				$total_charges = $row['total_charges']; 

				if($sa_id != ''){
					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon" onClick="editTabledata('.$sa_id.')"> <i class="mdi mdi-eye"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$sa_id.')"> <i class="mdi mdi-delete"></i></a>';
						
					$data[0] = $slno; 
					$data[1] = $client_name;
					$data[2] = $address; 
					$data[3] = $state;
					$data[4] = $pin_code;
					$data[5] = $contact_no;
					$data[6] = $pan_number;	
					$data[7] = $email_id;

					$data[8] = $kyc_verified;	
					$data[9] = $plan_subscribed;	
					$data[10] = $date_of_subscription;	
					$data[11] = $transaction_id;	
					$data[12] = $plan_duration_month;	
					$data[13] = $subscription_end_date;	
					$data[14] = $pay_made_tax_amt;	
					$data[15] = $igst;	
					$data[16] = $cgst;	
					$data[17] = $sgst;

					$data[18] = $total_gst;	
					$data[19] = $total_payment;	
					$data[20] = $invoice_number;	
					$data[21] = $payment_gateway;	
					$data[22] = $hsh_code;	
					$data[23] = $gateway_charges;	
					$data[24] = $gst_on_charges;	
					$data[25] = $total_charges;	
					//$data[26] = $action_html; 

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

	   
	

?>