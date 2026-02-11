<?php
	include('../../assets/php/sql_conn.php');
	$fn = '';
    
	if(isset($_GET["fn"])){
	    $fn = $_GET["fn"];
	}else if(isset($_POST["fn"])){
	    $fn = $_POST["fn"];
	}	

	//function start
	if($fn == 'getTableData'){
		$return_array = array();
		$status = true;
		$mainData = array();
		$email1 = '';		

		//Get Group Members
		$query2 = "CALL usp_ViewUsrGroupList()";
		mysqli_multi_query($con, $query2);
		do {
			if ($result2 = mysqli_store_result($con)) {
				while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
					//printf("%s\n", $row[0]);
					$Sl = $row2['Sl'];
					$GrpNm = $row2['GrpNm'];
					$GrpDesc = $row2['GrpDesc'];
					$Admn = $row2['Admn'];

					if($Sl != ''){
						$action_html = '';
						$action_html .= '<a data-bs-toggle="offcanvas" href="#theme-settings-offcanvas" class="action-icon"> <i class="mdi mdi-pencil"></i></a>';
						$action_html .= '<a href="javascript: void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>';
						 
						$data[0] = $Sl; 
						$data[1] = $GrpNm;
						$data[2] = $GrpDesc;
						$data[3] = $Admn;
						$data[4] = $action_html;			
						array_push($mainData, $data);
					}
				}
			}
			if (mysqli_more_results($con)) {
			}
		} while (mysqli_next_result($con));

		$return_array['data'] = $mainData;
    	echo json_encode($return_array);
	}//function end	
	

?>