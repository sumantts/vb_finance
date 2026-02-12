<?php
	include '../../assets/php/sql_conn.php';

	$i=1;
	$csv_export1 = "";

	
	$csv_export = '';
	$csv_export .="Name," ;
	$csv_export .="Contact Number," ;
	$csv_export .="1. The depth of the course content is adequate to have significant learning outcomes," ;
	$csv_export .="2. Coverage of the syllabus is possible in the stipulated time,";
	$csv_export .="3. The units/sections in the syllabus are properly sequenced,";
	$csv_export .="4. recommended textbooks and learning resources are adequate updated and map onto the syllabus,";
	$csv_export .="5. Sufficient reference material and books are available for the topics mentioned in the syllabus,";
	$csv_export .="6.The programme and curriculum are enriched as compared to similar programmes offered in other universities,"; 
	$csv_export .="7. the designed experiments stimulate the interest of students in the subject and deepen their understanding through relating theory to practice (Experiential Learning),";
	$csv_export .="8. The syllabus for practical enables students to develop experimental problem solving and analytic skills,";
	$csv_export .="9. The courses or syllabi taught have a good balance between theory and application,";
	$csv_export .="10. The objectives of the syllabi are well defined,";
	$csv_export .="11. The course/syllabi of the subjects increase interest knowledge and perspective in the subject area,";
	$csv_export .="12. The college has given full freedom to adopt new techniques or strategies of teaching such as group discussions seminar presentations and learners participation,";
	$csv_export .="13. The college has given full freedom viz. to adopt new techniques/strategies of testing and assessment of students,";
	$csv_export .="14. Tests and examinations are conducted well in time with proper coverage of all units in the syllabus,";
	$csv_export .="15. The prescribed books and learning resources are available in the library in sufficient numbers,";
	$csv_export .="16. The environment in the College is conducive to teaching and research,";
	$csv_export .="17. The administration is Teacher-friendly,";
	$csv_export .="18. The college provides adequate opportunities and support to faculty members for upgrading their skills and qualifications," ;
	$csv_export .="19. ICT facilities in the college are adequate and satisfactory,";
	$csv_export .="20. Canteen is available for teachers,";
	$csv_export .="21. Toilets or Washrooms are clean and Properly maintained,";
	$csv_export .="22. The classrooms are clean and well maintained,";
	$csv_export .="23. What suggestions: would you give for the betterment of your institution?,";  
	$csv_export .= "\n";

	$today = date('Y_m_d_H_i_s');
	$csv_fileName = 'teacher_feedback_'.$today.'.csv';  

	$sql = "SELECT * FROM teacher_feedback";
	$result = $con->query($sql);
	$total_reviews = $result->num_rows;
	$result = $con->query($sql);

    if ($result->num_rows > 0) { 
		while($row = $result->fetch_array()){
			$stdn_name = $row['teacher_name'];  
			$stdn_phone = $row['teacher_phone'];
			if($row['ans_1'] == ''){
				$ans_1 = '-';
			}else{
				$ans_1 = $row['ans_1'];
			}
			if($row['ans_2'] == ''){
				$ans_2 = '-';
			}else{
				$ans_2 = $row['ans_2'];
			}
			if($row['ans_3'] == ''){
				$ans_3 = '-';
			}else{
				$ans_3 = $row['ans_3'];
			}
			if($row['ans_4'] == ''){
				$ans_4 = '-';
			}else{
				$ans_4 = $row['ans_4'];
			}
			if($row['ans_5'] == ''){
				$ans_5 = '-';
			}else{
				$ans_5 = $row['ans_5'];
			}
			if($row['ans_6'] == ''){
				$ans_6 = '-';
			}else{
				$ans_6 = $row['ans_6'];
			}
			if($row['ans_7'] == ''){
				$ans_7 = '-';
			}else{
				$ans_7 = $row['ans_7'];
			}
			if($row['ans_8'] == ''){
				$ans_8 = '-';
			}else{
				$ans_8 = $row['ans_8'];
			}
			if($row['ans_9'] == ''){
				$ans_9 = '-';
			}else{
				$ans_9 = $row['ans_9'];
			}
			if($row['ans_10'] == ''){
				$ans_10 = '-';
			}else{
				$ans_10 = $row['ans_10'];
			}
			if($row['ans_11'] == ''){
				$ans_11 = '-';
			}else{
				$ans_11 = $row['ans_11'];
			}
			if($row['ans_12'] == ''){
				$ans_12 = '-';
			}else{
				$ans_12 = $row['ans_12'];
			}
			if($row['ans_13'] == ''){
				$ans_13 = '-';
			}else{
				$ans_13 = $row['ans_13'];
			}
			if($row['ans_14'] == ''){
				$ans_14 = '-';
			}else{
				$ans_14 = $row['ans_14'];
			}
			if($row['ans_15'] == ''){
				$ans_15 = '-';
			}else{
				$ans_15 = $row['ans_15'];
			}
			if($row['ans_16'] == ''){
				$ans_16 = '-';
			}else{
				$ans_16 = $row['ans_16'];
			}
			if($row['ans_17'] == ''){
				$ans_17 = '-';
			}else{
				$ans_17 = $row['ans_17'];
			}
			if($row['ans_18'] == ''){
				$ans_18 = '-';
			}else{
				$ans_18 = $row['ans_18'];
			}
			if($row['ans_19'] == ''){
				$ans_19 = '-';
			}else{
				$ans_19 = str_replace(",", "", $row['ans_19']);
			}
			if($row['ans_20'] == ''){
				$ans_20 = '-';
			}else{
				$ans_20 = $row['ans_20'];
			}
			if($row['ans_21'] == ''){
				$ans_21 = '-';
			}else{
				$ans_21 = $row['ans_21'];
			}
			if($row['ans_22'] == ''){
				$ans_22 = '-';
			}else{
				$ans_22 = $row['ans_22'];
			}
			if($row['ans_23'] == ''){
				$ans_23 = '-';
			}else{
				$ans_23 = str_replace(",", "", $row['ans_23']);
			}

			$csv_export1 .= "$stdn_name,";
			$csv_export1 .= "$stdn_phone,"; 
			$csv_export1 .= "$ans_1,";
			$csv_export1 .= "$ans_2,";
			$csv_export1 .= "$ans_3,";
			$csv_export1 .= "$ans_4,";
			$csv_export1 .= "$ans_5,";
			$csv_export1 .= "$ans_6,"; 
			$csv_export1 .= "$ans_7,";
			$csv_export1 .= "$ans_8,";
			$csv_export1 .= "$ans_9,";
			$csv_export1 .= "$ans_10,";
			$csv_export1 .= "$ans_11,";
			$csv_export1 .= "$ans_12,";
			$csv_export1 .= "$ans_13,";
			$csv_export1 .= "$ans_14,";
			$csv_export1 .= "$ans_15,";
			$csv_export1 .= "$ans_16,";
			$csv_export1 .= "$ans_17,";
			$csv_export1 .= "$ans_18,";
			$csv_export1 .= "$ans_19,";
			$csv_export1 .= "$ans_20,";
			$csv_export1 .= "$ans_21,";
			$csv_export1 .= "$ans_22,";
			$csv_export1 .= "$ans_23,";
			$csv_export1 .= "\n";
			$i++;
		}
	} 
	$csv_export .= $csv_export1;
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$csv_fileName."");
	echo($csv_export);
?>
