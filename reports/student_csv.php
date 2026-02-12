<?php
	include '../../assets/php/sql_conn.php';

	$i=1;
	$csv_export1 = "";

	
	$csv_export = '';
	$csv_export .="Name," ;
	$csv_export .="Contact Number," ;
	$csv_export .="1. Age," ;
	$csv_export .="2. Gender,";
	$csv_export .="3. Degree Programme,";
	$csv_export .="4. Examination system,";
	$csv_export .="5. Subject area currently pursuing,";
	$csv_export .="6. How well are the teachers prepare for class?,"; 
	$csv_export .="7. Extent of the syllabus covered in the class," ;
	$csv_export .="8. How well are the teachers able to communicate?," ;
	$csv_export .="9. The teachers’ approach to teaching can be best described as," ;
	$csv_export .="10. The teachers illustrate the concepts through examples and applications," ;
	$csv_export .="11. Fairness of the internal evaluation process by the teachers," ;
	$csv_export .="12. Performance in assignments discussed with students," ;
	$csv_export .="13. Teachers inform about expected competencies course outcomes and programme outcomes," ;
	$csv_export .="14. Mentor does a necessary follow-up with an assigned task," ;
	$csv_export .="15. The teachers identify strengths and encourage the students by providing the right level of challenges," ;
	$csv_export .="16. Teachers can identify the weaknesses of students and help them to overcome," ;
	$csv_export .="17. The institute or teachers use student-centric methods such as experiential learning participatuve learning and problem-solving methodologies for enhancing learning experiences," ;
	$csv_export .="18. Teachers encourage the students to participate in extracurricular activities," ;
	$csv_export .="19. Efforts are made by the institute or teachers to inculcate soft skills life skills and employability to make the students ready for the world of work," ;
	$csv_export .="20. The institution makes effort to engage students in the monitoring review and continuous quality improvement of the teaching learning process," ;
	$csv_export .="21. The percentage of teachers using ICT tools such as LCD projector Multimedia etc. while teaching," ;
	$csv_export .="22. The overall quality of education in your college is very good," ;
	$csv_export .="23. The institute takes an active part in promoting internship student exchange field visit opportunities for students," ;
	$csv_export .="24. The institution provides multiple opportunities to learn and grow," ;
	$csv_export .="25. Do you get your required documents from College Library?," ;
	$csv_export .="26. Are the Librarian and Library Staff helpful in seeking required documents or in other matters?," ;
	$csv_export .="27. Overall experience from Library," ;
	$csv_export .="28. What suggestion(s) would you give for the betterment of your institution?," ;  
	$csv_export .= "\n";

	$today = date('Y_m_d_H_i_s');
	$csv_fileName = 'student_feedback_'.$today.'.csv';  

	$sql = "SELECT * FROM students_feedback";
	$result = $con->query($sql);
	$total_reviews = $result->num_rows;
	$result = $con->query($sql);

    if ($result->num_rows > 0) { 
		while($row = $result->fetch_array()){
			$stdn_name = $row['stdn_name'];  
			$stdn_phone = $row['stdn_phone'];
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
				$ans_19 = $row['ans_19'];
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
				$ans_23 = $row['ans_23'];
			}
			if($row['ans_24'] == ''){
				$ans_24 = '-';
			}else{
				$ans_24 = $row['ans_24'];
			}
			if($row['ans_25'] == ''){
				$ans_25 = '-';
			}else{
				$ans_25 = $row['ans_25'];
			}
			if($row['ans_26'] == ''){
				$ans_26 = '-';
			}else{
				$ans_26 = $row['ans_26'];
			}
			if($row['ans_27'] == ''){
				$ans_27 = '-';
			}else{
				$ans_27 = $row['ans_27'];
			}
			if($row['ans_28'] == ''){
				$ans_28 = '-';
			}else{
				$ans_28 = str_replace(",", "", $row['ans_28']);
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
			$csv_export1 .= "$ans_24,";
			$csv_export1 .= "$ans_25,";
			$csv_export1 .= "$ans_26,";
			$csv_export1 .= "$ans_27,";
			$csv_export1 .= "$ans_28,";
			$csv_export1 .= "\n";
			$i++;
		}
	} 
	$csv_export .= $csv_export1;
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$csv_fileName."");
	echo($csv_export);
?>
