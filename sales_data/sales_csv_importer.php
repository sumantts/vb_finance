<!-- <form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv">
    <button type="submit" name="Import">Import CSV</button>
</form> -->

<?php
    # https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/

    ini_set('max_execution_time', 3600);
    session_start();

    if (isset($_POST["Import"])) {
        $filename = $_FILES["file"]["tmp_name"];
        
        // Check if the file is a CSV file
        if (pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION) != "csv") {
            echo "Please upload a CSV file.";
            exit;
        }
        
        // Proceed with importing the CSV file
        importCSV($filename);
    }


    function importCSV($filename) {
        
		$host = 'localhost';
        
		/*$username = 'a1760451_chat_n_trade';
		$password = 'chat_n_trade123!@#';
		$dbname = 'a1760451_chat_n_trade';*/
        
		$username = 'root';
		$password = '';
		$dbname = 'vb_finance';

        $conn = new mysqli($host, $username, $password, $dbname);
        
        // Open the CSV file
        $file = fopen($filename, "r");
        
        // Skip the header row (if it exists)
        fgetcsv($file, 10000, ",");  // Adjust delimiter if needed
        
        // Prepare the SQL query          
        $sql = "INSERT INTO sales_data (co_id, client_name, address, state, pin_code, contact_no, pan_number, email_id, kyc_verified, plan_subscribed, date_of_subscription, transaction_id, plan_duration_month, subscription_end_date, pay_made_tax_amt, igst, cgst, sgst, total_gst, total_payment, invoice_number, payment_gateway, hsh_code, gateway_charges, gst_on_charges, total_charges) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error); // Handle prepare error
        } 
        
        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssssssss", $co_id, $client_name, $address, $state, $pin_code, $contact_no, $pan_number, $email_id, $kyc_verified, $plan_subscribed, $date_of_subscription, $transaction_id, $plan_duration_month, $subscription_end_date, $pay_made_tax_amt, $igst, $cgst, $sgst, $total_gst, $total_payment, $invoice_number, $payment_gateway, $hsh_code, $gateway_charges, $gst_on_charges, $total_charges);
        
        
        
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Assign values 
            if($getData[1] != ''){
		        $co_id = $_SESSION["co_id"];
                $trans_date1 = $getData[0]; // A
                $client_name = $getData[1]; // B
                $address = $getData[2]; // C
                $state = $getData[3]; // D
                $pin_code = $getData[4]; // E
                $contact_no = $getData[5]; // F
                $pan_number = $getData[6]; // G
                $email_id = $getData[7]; // H
                $kyc_verified = $getData[8]; // I
                $plan_subscribed = $getData[9]; // J
                $date_of_subscription = $getData[10]; // K
                $transaction_id = $getData[11]; // L
                $plan_duration_month = $getData[12]; // M
                $subscription_end_date = $getData[13]; // N
                $pay_made_tax_amt = $getData[14]; // O
                $igst = $getData[15]; // P
                $cgst = $getData[16]; // Q
                $sgst = $getData[17]; // R
                $total_gst = $getData[18]; // S
                $total_payment = $getData[19]; // T
                $invoice_number = $getData[20]; // U
                $payment_gateway = $getData[21]; // V
                $hsh_code = $getData[22]; // W
                $gateway_charges = $getData[23]; // X
                $gst_on_charges = $getData[24]; // Y
                $total_charges = $getData[25]; // Z 
                
                // Execute the query
                if (!$stmt->execute()) {
                    error_log("Error inserting row: " . $stmt->error);
                }
            }
        }

       
        
        // Close the statement and connection
        $stmt->close();
        fclose($file);
        $conn->close();
        
       
        echo "<script type=\"text/javascript\"> alert(\"CSV File has been successfully Imported.\"); window.location = \"http://localhost/git_hub/vb_finance/?p=sales-data\" </script>";
    }
    

?>