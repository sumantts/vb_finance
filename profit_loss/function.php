<?php
	include('../assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	}  

	//save Form Data
	if($fn == 'saveFormData2'){
		$return_array = array();
		$status = true;
		$error_message = '';
		$parent_c_id = '0';
		$total_debit_balance = 0;
		$total_credit_balance = 0;
		$part_of_plbs = '1';

		$from_date = date('Y-m-d', strtotime($_POST['from_date'])); 
		$to_date = date('Y-m-d', strtotime($_POST['to_date']));
		$co_id = $_SESSION["co_id"];

		$all_categories = array();

		// Main category
		$sql = "SELECT * FROM category WHERE co_id = '" .$co_id. "' AND part_of_plbs = '" .$part_of_plbs. "' ORDER BY category_name ASC";
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

?>