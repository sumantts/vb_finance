<?php
	include('../assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	} 
	
	
	/*if($fn == 'saveFormData1'){
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
	}*/
	//function end

	//save Form Data
	if($fn == 'saveFormData2'){
		$return_array = array();
		$status = true;
		$error_message = '';
		$parent_c_id = '0';
		$total_debit_balance = 0;
		$total_credit_balance = 0;

		$from_date = date('Y-m-d', strtotime($_POST['from_date'])); 
		$to_date = date('Y-m-d', strtotime($_POST['to_date']));
		$co_id = $_SESSION["co_id"];

		$all_categories = array();

		// Main category
		$sql = "SELECT * FROM category WHERE co_id = '" .$co_id. "' AND parent_c_id = '" .$parent_c_id. "' ORDER BY category_name ASC";
		$result = $con->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_array()){
				$c_id = $row['c_id'];
				$parent_c_id = $row['parent_c_id'];
				$category_name = $row['category_name']; 				
				$nature = $row['nature']; 
				$opening_balance = $row['opening_balance']; 

				$all_category = new stdClass();
				$all_category->c_id = $c_id;
				$all_category->category_name = $category_name;
				$all_category->nature = $nature;
				$all_category->opening_balance = $opening_balance;
				$all_category->sub_categories = array();
				$all_category->debit_balance = 0;
				$all_category->credit_balance = 0;

				array_push($all_categories, $all_category);
			}
		}//end if

		//Sub category
		if(sizeof($all_categories) > 0){
			for($i = 0; $i < sizeof($all_categories); $i++){
				$c_id = $all_categories[$i]->c_id;
				$sub_categories = $all_categories[$i]->sub_categories;

				$sql2 = "SELECT * FROM category WHERE parent_c_id = '" .$c_id. "' ORDER BY category_name ASC";
				$result2 = $con->query($sql2);
				if ($result2->num_rows > 0) {
					while($row2 = $result2->fetch_array()){
						$sub_c_id = $row2['c_id'];
						$sub_category_name = $row2['category_name'];

						$sub_category = new stdClass();
						$sub_category->sub_c_id = $sub_c_id;
						$sub_category->sub_category_name = $sub_category_name;
						$sub_category->debit_balance = 0;
						$sub_category->credit_balance = 0;
						$sub_category->transactions = array();
						array_push($sub_categories, $sub_category);
					}
				}
				$all_categories[$i]->sub_categories = $sub_categories;
			}//end for
		}//end if

		//Transactions		
		if(sizeof($all_categories) > 0){
			for($i = 0; $i < sizeof($all_categories); $i++){
				$c_id = $all_categories[$i]->c_id;
				$nature = $all_categories[$i]->nature;
				//$c_debit_balance = $all_categories[$i]->debit_balance;
				//$c_credit_balance = $all_categories[$i]->credit_balance;
				$sub_categories = $all_categories[$i]->sub_categories;
				$c_subtotal = 0;

				if(sizeof($sub_categories) > 0){
					for($j = 0; $j < sizeof($sub_categories); $j++){
						$sub_c_id = $sub_categories[$j]->sub_c_id;
						$transactions = $sub_categories[$j]->transactions;

						// Get Transaction
						$sub_c_sub_total = 0;
						$sql3 = "SELECT * FROM bank_data WHERE co_id = '" .$co_id. "' AND parent_c_id = '" .$c_id. "' AND sub_c_id = '" .$sub_c_id. "' AND trans_date >= '" .$from_date. "' AND trans_date <= '" .$to_date. "' ORDER BY trans_date DESC";
						$result3 = $con->query($sql3);

						if ($result3->num_rows > 0) {
							while($row3 = $result3->fetch_array()){
								$b_id = $row3['b_id'];
								$narration = $row3['narration'];
								$withdrawal_amount = $row3['withdrawal_amount'];
								$deposit_amount = $row3['deposit_amount'];

								$transaction = new stdClass();
								$transaction->b_id = $b_id;
								$transaction->withdrawal_amount = $withdrawal_amount;
								$transaction->deposit_amount = $deposit_amount;
								$transaction->debit_balance = 0;
								$transaction->credit_balance = 0;

								$sub_total = 0;
								$sub_total = ($withdrawal_amount + $deposit_amount);
								$sub_c_sub_total = $sub_c_sub_total + $sub_total;
								$c_subtotal = $c_subtotal + $sub_total;

								//1=Income 2=Expense
								if($nature == 1){
									$transaction->credit_balance = $sub_total;
									$total_credit_balance = $total_credit_balance + $sub_total;
								}else{
									$transaction->debit_balance = $sub_total;
									$total_debit_balance = $total_debit_balance + $sub_total;
								} 

								array_push($transactions, $transaction);
							}
						}//end if
						$sub_categories[$j]->transactions = $transactions;	

						//1=Income 2=Expense
						if($nature == 1){
							$sub_categories[$j]->credit_balance = $sub_c_sub_total;
						}else{
							$sub_categories[$j]->debit_balance = $sub_c_sub_total;
						}
					}//end for j
				}//end if				

				//1=Income 2=Expense
				if($nature == 1){
					$all_categories[$i]->credit_balance = $c_subtotal;
				}else{
					$all_categories[$i]->debit_balance = $c_subtotal;
				}
			}//end for i
		}//end if

		$error_message = 'Data updated successfully';
		$con->close();	

		$return_array['status'] = $status;  
		$return_array['error_message'] = $error_message; 
		$return_array['all_categories'] = $all_categories; 
		$return_array['total_debit_balance'] = $total_debit_balance;
		$return_array['total_credit_balance'] = $total_credit_balance;
    	echo json_encode($return_array);
	}//function end	

	/****
	//function start
	if($fn == 'getTableData'){
		$co_id = $_SESSION["co_id"];
		$return_array = array();
		$status = true;
		$mainData = array();
		$sql = "SELECT * FROM bank_data WHERE co_id = '" .$co_id. "' ORDER BY trans_date DESC";
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
				$sub_c_id = $row['sub_c_id'];

				$category_name = '';
				if($sub_c_id > 0){
					$sql3 = "SELECT * FROM category WHERE c_id = '" .$sub_c_id. "' ";
					$result3 = $con->query($sql3);
					if ($result3->num_rows > 0) {
						$row3 = $result3->fetch_array();
						$category_name = $row3['category_name'];
					}
				}//end if

				if($b_id != ''){
					
					$row_checkbox = '<input type="checkbox" class="rowCheckbox">';

					$action_html = '';
					$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas1" class="action-icon" onClick="editTabledata('.$b_id.')"> <i class="mdi mdi-pencil"></i></a>';
					$action_html .= '<a href="javascript: void(0);" class="action-icon" onClick="deleteTabledata('.$b_id.')"> <i class="mdi mdi-delete"></i></a>';
					
					$data[0] = $row_checkbox; 
					$data[1] = $slno; 
					$data[2] = date('d-m-Y', strtotime($trans_date));
					$data[3] = $narration; 
					$data[4] = $chq_ref_no;
					$data[5] = date('d-m-Y', strtotime($value_date));
					$data[6] = $withdrawal_amount;
					$data[7] = $deposit_amount;	
					$data[8] = $category_name;	
					$data[9] = $b_id;	
					$data[10] = $action_html;				
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
	*****/

?>