<!-- <form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv">
    <button type="submit" name="Import">Import CSV</button>
</form> -->

<?php
    # https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/

    ini_set('max_execution_time', 3600);

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
        $sql = "INSERT INTO bank_data (trans_date, narration, chq_ref_no, value_date, withdrawal_amount, deposit_amount) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error); // Handle prepare error
        } 
        
        // Bind parameters
        $stmt->bind_param("ssssss", $trans_date, $narration, $chq_ref_no, $value_date, $withdrawal_amount, $deposit_amount);
        
        
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            // Assign values 
            $trans_date1 = explode('/', $getData[0]); // A
            $trans_date = '20'.$trans_date1[2].'-'.$trans_date1[1].'-'.$trans_date1[0]; 
            $narration = $getData[1]; // B
            $chq_ref_no = $getData[2]; // C
            $value_date1 = explode('/', $getData[3]); //D
            $value_date = '20'.$value_date1[2].'-'.$value_date1[1].'-'.$value_date1[0]; 
            $withdrawal_amount = $getData[4]; // E
            $deposit_amount = $getData[5]; // F 
            
            // Execute the query
            if (!$stmt->execute()) {
                error_log("Error inserting row: " . $stmt->error);
            }
        }
        
        // Close the statement and connection
        $stmt->close();
        fclose($file);
        $conn->close();
        
        echo "<script type=\"text/javascript\"> alert(\"CSV File has been successfully Imported.\"); window.location = \"http://localhost/git_hub/vb_finance/?p=bank-data\" </script>";
    }
    

?>