<?php
	include('./assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	}	
	
	
	//Get Room Type
	if($fn == 'getCalenderData'){
		$return_array = array();
		$status = true;
		$mainData = array();
		$reserved = array();
		
		$fromDate = date('Y-m').'-01';
		$toDate = date('Y-m').'-30';
		
		//Get Group Members
		$query2 = "CALL usp_RptBkCalendar('" .$fromDate. "', '" .$toDate. "')";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
					//printf("%s\n", $row[0]);
					//echo json_encode($row2);
					$RptDt = $row2['RptDt'];
					$RmCnt = $row2['RmCnt'];

					if($RmCnt > 0){
						$reserve = new stdClass();
						$reserve->id = rand();
						$reserve->title = $RmCnt;
						$reserve->start = $RptDt;
						$reserve->backgroundColor = 'green';
						$reserve->allDay = false;
						$reserve->editable = false;

						array_push($reserved, $reserve);
					}					
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));
		

		$return_array['status'] = $status;
		$return_array['reserved'] = $reserved;
		
    	echo json_encode($return_array);
	}//function end	
	

?>