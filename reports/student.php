<!DOCTYPE html>
<html lang="en">
<head>
<title>Student Feedback Report</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css"> -->
  <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>@page { size: A4 }</style>
    <!-- Source code: https://github.com/cognitom/paper-css?tab=readme-ov-file -->    
    <!-- Google Chart   -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
</head>
<?php
	include('../../assets/php/sql_conn.php');    
    $datas = array();  
    $total_reviews = 0;  

    $sql_0 = "SELECT * FROM students_feedback";
    $result_0 = $con->query($sql_0);
    $total_reviews = $result_0->num_rows;
    
    // SQL query to count occurrences of 'Female', 'Male', and 'Other'
    $sql = "SELECT 
    COUNT(CASE WHEN ans_2 = 'Female' THEN 1 END) AS female_count,
    COUNT(CASE WHEN ans_2 = 'Male' THEN 1 END) AS male_count,
    COUNT(CASE WHEN ans_2 = 'Transgender' THEN 1 END) AS other_count
    FROM students_feedback";

    $result = $con->query($sql);

    if ($result->num_rows > 0) { 
        while($row = $result->fetch_array()){
            $female_count = $row['female_count'];
            $male_count = $row['male_count'];
            $other_count = $row['other_count'];
            
            $female_percent = ($female_count * 100) / $total_reviews;
            $male_percent = ($male_count * 100) / $total_reviews;
            $other_percent = ($other_count * 100) / $total_reviews;
        }
    }//end if 
    
    // SQL query to count occurrences of Degree programme 'Bachelors', 'Masters'
    $sql_3 = "SELECT 
    COUNT(CASE WHEN ans_3 = 'Bachelors' THEN 1 END) AS bachelors_count,
    COUNT(CASE WHEN ans_3 = 'Masters' THEN 1 END) AS masters_count
    FROM students_feedback";

    $result_3 = $con->query($sql_3);

    if ($result_3->num_rows > 0) { 
        while($row_3 = $result_3->fetch_array()){
            $bachelors_count = $row_3['bachelors_count'];
            $masters_count = $row_3['masters_count']; 
            
            $bachelors_percent = ($bachelors_count * 100) / $total_reviews;
            $masters_percent = ($masters_count * 100) / $total_reviews; 
        }
    }//end if 
    
    // SQL query to count occurrences of Degree programme 'Part system (1+1+1)', 'Masters'
    $sql_4 = "SELECT 
    COUNT(CASE WHEN ans_4 = 'Part system (1+1+1)' THEN 1 END) AS part_sys_count,
    COUNT(CASE WHEN ans_4 = 'Semester System' THEN 1 END) AS semester_count
    FROM students_feedback";

    $result_4 = $con->query($sql_4);

    if ($result_4->num_rows > 0) { 
        while($row_4 = $result_4->fetch_array()){
            $part_sys_count = $row_4['part_sys_count'];
            $semester_count = $row_4['semester_count']; 
            
            $part_sys_percent = ($part_sys_count * 100) / $total_reviews;
            $semester_percent = ($semester_count * 100) / $total_reviews; 
        }
    }//end if 
    
    // SQL query to count occurrences of Subject area currently pursuing 'Arts', 'science', 'commerce'
    $sql_5 = "SELECT 
    COUNT(CASE WHEN ans_5 = 'Arts' THEN 1 END) AS arts_count,
    COUNT(CASE WHEN ans_5 = 'science' THEN 1 END) AS science_count,
    COUNT(CASE WHEN ans_5 = 'commerce' THEN 1 END) AS commerce_count
    FROM students_feedback";

    $result_5 = $con->query($sql_5);

    if ($result_5->num_rows > 0) { 
        while($row_5 = $result_5->fetch_array()){
            $arts_count = $row_5['arts_count'];
            $science_count = $row_5['science_count']; 
            $commerce_count = $row_5['commerce_count'];
            
            $arts_percent = ($arts_count * 100) / $total_reviews;
            $science_percent = ($science_count * 100) / $total_reviews; 
            $commerce_percent = ($commerce_count * 100) / $total_reviews; 
        }
    }//end if 
    
    // SQL query to count occurrences of 6. How well are the teachers prepare for class? 'Thoroughly', 'Satisfactorily', 'Poorly', 'indifferently', 'won’t teach at all'
    $sql_6 = "SELECT 
    COUNT(CASE WHEN ans_6 = 'Thoroughly' THEN 1 END) AS thoroughly_count,
    COUNT(CASE WHEN ans_6 = 'Satisfactorily' THEN 1 END) AS satis_count,
    COUNT(CASE WHEN ans_6 = 'Poorly' THEN 1 END) AS poorly_count,
    COUNT(CASE WHEN ans_6 = 'indifferently' THEN 1 END) AS indifferently_count,
    COUNT(CASE WHEN ans_6 = 'won’t teach at all' THEN 1 END) AS won_teach_count
    FROM students_feedback";

    $result_6 = $con->query($sql_6);

    if ($result_6->num_rows > 0) { 
        while($row_6 = $result_6->fetch_array()){
            $thoroughly_count = $row_6['thoroughly_count'];
            $satis_count = $row_6['satis_count']; 
            $poorly_count = $row_6['poorly_count'];
            $indifferently_count = $row_6['indifferently_count'];
            $won_teach_count = $row_6['won_teach_count'];
            
            $thoroughly_percent = ($thoroughly_count * 100) / $total_reviews;
            $satis_percent = ($satis_count * 100) / $total_reviews; 
            $poorly_percent = ($poorly_count * 100) / $total_reviews;  
            $indifferently_percent = ($indifferently_count * 100) / $total_reviews;  
            $won_teach_percent = ($won_teach_count * 100) / $total_reviews; 
        }
    }//end if     
    
    // SQL query to count occurrences of 7. Extent of the syllabus covered in the class '85-100%', '70-84%', '55-69%', '30-54%', 'Below 30%'
    $sql_7 = "SELECT 
    COUNT(CASE WHEN ans_7 = '85-100%' THEN 1 END) AS eighty_five_count,
    COUNT(CASE WHEN ans_7 = '70-84%' THEN 1 END) AS eighty_four_count,
    COUNT(CASE WHEN ans_7 = '55-69%' THEN 1 END) AS sixtynine_count,
    COUNT(CASE WHEN ans_7 = '30-54%' THEN 1 END) AS fifty_four_count,
    COUNT(CASE WHEN ans_7 = 'Below 30%' THEN 1 END) AS below_thirty_count
    FROM students_feedback";

    $result_7 = $con->query($sql_7);

    if ($result_7->num_rows > 0) { 
        while($row_7 = $result_7->fetch_array()){
            $eighty_five_count = $row_7['eighty_five_count'];
            $eighty_four_count = $row_7['eighty_four_count']; 
            $sixtynine_count = $row_7['sixtynine_count'];
            $fifty_four_count = $row_7['fifty_four_count'];
            $below_thirty_count = $row_7['below_thirty_count'];
            
            $eighty_five_percent = ($eighty_five_count * 100) / $total_reviews;
            $eighty_four_percent = ($eighty_four_count * 100) / $total_reviews; 
            $sixtynine_percent = ($sixtynine_count * 100) / $total_reviews;  
            $fifty_four_percent = ($fifty_four_count * 100) / $total_reviews;  
            $below_thirty_percent = ($below_thirty_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 7. Extent of the syllabus covered in the class 'Very Effective', 'Sometimes Effective', 'Satisfactorily', 'Generally Effective', 'Very Poor'
    $sql_7 = "SELECT 
    COUNT(CASE WHEN ans_7 = 'Very Effective' THEN 1 END) AS very_eff_count,
    COUNT(CASE WHEN ans_7 = 'Sometimes Effective' THEN 1 END) AS some_eff_count,
    COUNT(CASE WHEN ans_7 = 'Satisfactorily' THEN 1 END) AS satis_count,
    COUNT(CASE WHEN ans_7 = 'Generally Effective' THEN 1 END) AS gen_eff_count,
    COUNT(CASE WHEN ans_7 = 'Very Poor' THEN 1 END) AS very_poor_count
    FROM students_feedback";

    $result_7 = $con->query($sql_7);

    if ($result_7->num_rows > 0) { 
        while($row_7 = $result_7->fetch_array()){
            $very_eff_count = $row_7['very_eff_count'];
            $some_eff_count = $row_7['some_eff_count']; 
            $satis_count = $row_7['satis_count'];
            $gen_eff_count = $row_7['gen_eff_count'];
            $very_poor_count = $row_7['very_poor_count'];
            
            $very_eff_percent = ($very_eff_count * 100) / $total_reviews;
            $some_eff_percent = ($some_eff_count * 100) / $total_reviews; 
            $satis_percent = ($satis_count * 100) / $total_reviews;  
            $gen_eff_percent = ($gen_eff_count * 100) / $total_reviews;  
            $very_poor_percent = ($very_poor_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 8. How well are the teachers able to communicate? 'Very Effective', 'Sometimes Effective', 'Satisfactorily', 'Generally Effective', 'Very Poor'
    $sql_8 = "SELECT 
    COUNT(CASE WHEN ans_8 = 'Very Effective' THEN 1 END) AS very_eff_count,
    COUNT(CASE WHEN ans_8 = 'Sometimes Effective' THEN 1 END) AS some_eff_count,
    COUNT(CASE WHEN ans_8 = 'Satisfactorily' THEN 1 END) AS satis_count,
    COUNT(CASE WHEN ans_8 = 'Generally Effective' THEN 1 END) AS gen_eff_count,
    COUNT(CASE WHEN ans_8 = 'Very Poor' THEN 1 END) AS very_poor_count
    FROM students_feedback";

    $result_8 = $con->query($sql_8);

    if ($result_8->num_rows > 0) { 
        while($row_8 = $result_8->fetch_array()){
            $very_eff_count = $row_8['very_eff_count'];
            $some_eff_count = $row_8['some_eff_count']; 
            $satis_count = $row_8['satis_count'];
            $gen_eff_count = $row_8['gen_eff_count'];
            $very_poor_count = $row_8['very_poor_count'];
            
            $very_eff_percent = ($very_eff_count * 100) / $total_reviews;
            $some_eff_percent = ($some_eff_count * 100) / $total_reviews; 
            $satis_percent = ($satis_count * 100) / $total_reviews;  
            $gen_eff_percent = ($gen_eff_count * 100) / $total_reviews;  
            $very_poor_percent = ($very_poor_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 9. The teachers’ approach to teaching can be best described as 'Excellent', 'Very Good', 'Good', 'fair', 'Poor'
    $sql_9 = "SELECT 
    COUNT(CASE WHEN ans_9 = 'Excellent' THEN 1 END) AS excellent_count,
    COUNT(CASE WHEN ans_9 = 'Very Good' THEN 1 END) AS very_good_count,
    COUNT(CASE WHEN ans_9 = 'Good' THEN 1 END) AS good_count,
    COUNT(CASE WHEN ans_9 = 'fair' THEN 1 END) AS fair_count,
    COUNT(CASE WHEN ans_9 = 'Poor' THEN 1 END) AS poor_count
    FROM students_feedback";

    $result_9 = $con->query($sql_9);

    if ($result_9->num_rows > 0) { 
        while($row_9 = $result_9->fetch_array()){
            $excellent_count = $row_9['excellent_count'];
            $very_good_count = $row_9['very_good_count']; 
            $good_count = $row_9['good_count'];
            $fair_count = $row_9['fair_count'];
            $poor_count = $row_9['poor_count'];
            
            $excellent_percent = ($excellent_count * 100) / $total_reviews;
            $very_good_percent = ($very_good_count * 100) / $total_reviews; 
            $good_percent = ($good_count * 100) / $total_reviews;  
            $fair_percent = ($fair_count * 100) / $total_reviews;  
            $poor_percent = ($poor_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 10. The teachers illustrate the concepts through examples and applications 'Everytime', 'Usually', 'Sometimes', 'Rarely', 'Never'
    $sql_10 = "SELECT 
    COUNT(CASE WHEN ans_10 = 'Everytime' THEN 1 END) AS everytime_count,
    COUNT(CASE WHEN ans_10 = 'Usually' THEN 1 END) AS usually_count,
    COUNT(CASE WHEN ans_10 = 'Sometimes' THEN 1 END) AS sometimes_count,
    COUNT(CASE WHEN ans_10 = 'Rarely' THEN 1 END) AS rarely_count,
    COUNT(CASE WHEN ans_10 = 'Never' THEN 1 END) AS never_count
    FROM students_feedback";

    $result_10 = $con->query($sql_10);

    if ($result_10->num_rows > 0) { 
        while($row_10 = $result_10->fetch_array()){
            $everytime_count = $row_10['everytime_count'];
            $usually_count = $row_10['usually_count']; 
            $sometimes_count = $row_10['sometimes_count'];
            $rarely_count = $row_10['rarely_count'];
            $never_count = $row_10['never_count'];
            
            $everytime_percent = ($everytime_count * 100) / $total_reviews;
            $usually_percent = ($usually_count * 100) / $total_reviews; 
            $sometimes_percent = ($sometimes_count * 100) / $total_reviews;  
            $rarely_percent = ($rarely_count * 100) / $total_reviews;  
            $never_percent = ($never_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 11. Fairness of the internal evaluation process by the teachers 
    $sql_11 = "SELECT 
    COUNT(CASE WHEN ans_11 = 'Always Fair' THEN 1 END) AS af_count,
    COUNT(CASE WHEN ans_11 = 'Usually Fair' THEN 1 END) AS uf_count,
    COUNT(CASE WHEN ans_11 = 'Sometimes Fair' THEN 1 END) AS sf_count,
    COUNT(CASE WHEN ans_11 = 'Usually Unfair' THEN 1 END) AS uu_count,
    COUNT(CASE WHEN ans_11 = 'Unfair' THEN 1 END) AS unf_count
    FROM students_feedback";

    $result_11 = $con->query($sql_11);

    if ($result_11->num_rows > 0) { 
        while($row_11 = $result_11->fetch_array()){
            $af_count = $row_11['af_count'];
            $uf_count = $row_11['uf_count']; 
            $sf_count = $row_11['sf_count'];
            $uu_count = $row_11['uu_count'];
            $unf_count = $row_11['unf_count'];
            
            $af_percent = ($af_count * 100) / $total_reviews;
            $uf_percent = ($uf_count * 100) / $total_reviews; 
            $sf_percent = ($sf_count * 100) / $total_reviews;  
            $uu_percent = ($uu_count * 100) / $total_reviews;  
            $unf_percent = ($unf_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 12. Performance in assignments discussed with students
    $sql_12 = "SELECT 
    COUNT(CASE WHEN ans_12 = 'Always' THEN 1 END) AS al_count,
    COUNT(CASE WHEN ans_12 = 'Usually' THEN 1 END) AS usu_count,
    COUNT(CASE WHEN ans_12 = 'Sometimes' THEN 1 END) AS som_count,
    COUNT(CASE WHEN ans_12 = 'Rarely' THEN 1 END) AS ry_count,
    COUNT(CASE WHEN ans_12 = 'Never' THEN 1 END) AS nev_count
    FROM students_feedback";

    $result_12 = $con->query($sql_12);

    if ($result_12->num_rows > 0) { 
        while($row_12 = $result_12->fetch_array()){
            $al_count = $row_12['al_count'];
            $usu_count = $row_12['usu_count']; 
            $som_count = $row_12['som_count'];
            $ry_count = $row_12['ry_count'];
            $nev_count = $row_12['nev_count'];
            
            $al_percent = ($al_count * 100) / $total_reviews;
            $usu_percent = ($usu_count * 100) / $total_reviews; 
            $som_percent = ($som_count * 100) / $total_reviews;  
            $ry_percent = ($ry_count * 100) / $total_reviews;  
            $nev_percent = ($nev_count * 100) / $total_reviews; 
        }
    }//end if
    
    // SQL query to count occurrences of 12. Performance in assignments discussed with students
    $sql_13 = "SELECT 
    COUNT(CASE WHEN ans_13 = 'Always' THEN 1 END) AS al_count,
    COUNT(CASE WHEN ans_13 = 'Usually' THEN 1 END) AS usu_count,
    COUNT(CASE WHEN ans_13 = 'Sometimes' THEN 1 END) AS som_count,
    COUNT(CASE WHEN ans_13 = 'Rarely' THEN 1 END) AS ry_count,
    COUNT(CASE WHEN ans_13 = 'Never' THEN 1 END) AS nev_count
    FROM students_feedback";

    $result_13 = $con->query($sql_13);

    if ($result_13->num_rows > 0) { 
        while($row_13 = $result_13->fetch_array()){
            $al_count_13 = $row_13['al_count'];
            $usu_count_13 = $row_13['usu_count']; 
            $som_count_13 = $row_13['som_count'];
            $ry_count_13 = $row_13['ry_count'];
            $nev_count_13 = $row_13['nev_count'];
            
            $al_percent_13 = ($al_count_13 * 100) / $total_reviews;
            $usu_percent_13 = ($usu_count_13 * 100) / $total_reviews; 
            $som_percent_13 = ($som_count_13 * 100) / $total_reviews;  
            $ry_percent_13 = ($ry_count_13 * 100) / $total_reviews;  
            $nev_percent_13 = ($nev_count_13 * 100) / $total_reviews; 
        }
    }//end if
    
    //14. Mentor does a necessary follow-up with an assigned task
    $sql_14 = "SELECT 
    COUNT(CASE WHEN ans_14 = 'Always' THEN 1 END) AS al_count,
    COUNT(CASE WHEN ans_14 = 'Usually' THEN 1 END) AS usu_count,
    COUNT(CASE WHEN ans_14 = 'Sometimes' THEN 1 END) AS som_count,
    COUNT(CASE WHEN ans_14 = 'Rarely' THEN 1 END) AS ry_count,
    COUNT(CASE WHEN ans_14 = 'I don’t have a mentor' THEN 1 END) AS nev_count
    FROM students_feedback";

    $result_14 = $con->query($sql_14);

    if ($result_14->num_rows > 0) { 
        while($row_14 = $result_14->fetch_array()){
            $al_count_14 = $row_14['al_count'];
            $usu_count_14 = $row_14['usu_count']; 
            $som_count_14 = $row_14['som_count'];
            $ry_count_14 = $row_14['ry_count'];
            $nev_count_14 = $row_14['nev_count'];
            
            $al_percent_14 = ($al_count_14 * 100) / $total_reviews;
            $usu_percent_14 = ($usu_count_14 * 100) / $total_reviews; 
            $som_percent_14 = ($som_count_14 * 100) / $total_reviews;  
            $ry_percent_14 = ($ry_count_14 * 100) / $total_reviews;  
            $nev_percent_14 = ($nev_count_14 * 100) / $total_reviews; 
        }
    }//end if
    
    //15. The teachers identify strengths and encourage the students by providing the right level of challenges
    $sql_15 = "SELECT 
    COUNT(CASE WHEN ans_15 = 'Fully' THEN 1 END) AS fly_count,
    COUNT(CASE WHEN ans_15 = 'Reasonably' THEN 1 END) AS res_count,
    COUNT(CASE WHEN ans_15 = 'Partially' THEN 1 END) AS par_count,
    COUNT(CASE WHEN ans_15 = 'Slightly' THEN 1 END) AS sly_count,
    COUNT(CASE WHEN ans_15 = 'Unable to' THEN 1 END) AS unbl_count
    FROM students_feedback";

    $result_15 = $con->query($sql_15);

    if ($result_15->num_rows > 0) { 
        while($row_15 = $result_15->fetch_array()){
            $fly_count = $row_15['fly_count'];
            $res_count = $row_15['res_count']; 
            $par_count = $row_15['par_count'];
            $sly_count = $row_15['sly_count'];
            $unbl_count = $row_15['unbl_count'];
            
            $fly_percent = ($fly_count * 100) / $total_reviews;
            $res_percent = ($res_count * 100) / $total_reviews; 
            $par_percent = ($par_count * 100) / $total_reviews;  
            $sly_percent = ($sly_count * 100) / $total_reviews;  
            $unbl_percent = ($unbl_count * 100) / $total_reviews; 
        }
    }//end if
    
    //16. Teachers can identify the weaknesses of students and help them to overcome
    $sql_16 = "SELECT 
    COUNT(CASE WHEN ans_16 = 'Everytime' THEN 1 END) AS ev_count,
    COUNT(CASE WHEN ans_16 = 'Usually' THEN 1 END) AS usly_count,
    COUNT(CASE WHEN ans_16 = 'Sometimes' THEN 1 END) AS somt_count,
    COUNT(CASE WHEN ans_16 = 'Rarely' THEN 1 END) AS rly_count,
    COUNT(CASE WHEN ans_16 = 'Never' THEN 1 END) AS nvr_count
    FROM students_feedback";

    $result_16 = $con->query($sql_16);

    if ($result_16->num_rows > 0) { 
        while($row_16 = $result_16->fetch_array()){
            $ev_count = $row_16['ev_count'];
            $usly_count = $row_16['usly_count']; 
            $somt_count = $row_16['somt_count'];
            $rly_count = $row_16['rly_count'];
            $nvr_count = $row_16['nvr_count'];
            
            $ev_percent = ($ev_count * 100) / $total_reviews;
            $usly_percent = ($usly_count * 100) / $total_reviews; 
            $somt_percent = ($somt_count * 100) / $total_reviews;  
            $rly_percent = ($rly_count * 100) / $total_reviews;  
            $nvr_percent = ($nvr_count * 100) / $total_reviews; 
        }
    }//end if
    
    //17. The institute / teachers use student-centric methods, such as experiential learning, participatuve learning and problem-solving methodologies for enhancing learning experiences
    $sql_17 = "SELECT 
    COUNT(CASE WHEN ans_17 = 'To a great extent' THEN 1 END) AS tage_count,
    COUNT(CASE WHEN ans_17 = 'Moderate' THEN 1 END) AS mod_count,
    COUNT(CASE WHEN ans_17 = 'Somewhat' THEN 1 END) AS somwt_count,
    COUNT(CASE WHEN ans_17 = 'Very Little' THEN 1 END) AS vlt_count,
    COUNT(CASE WHEN ans_17 = 'Not at All' THEN 1 END) AS nta_count
    FROM students_feedback";

    $result_17 = $con->query($sql_17);

    if ($result_17->num_rows > 0) { 
        while($row_17 = $result_17->fetch_array()){
            $tage_count = $row_17['tage_count'];
            $mod_count = $row_17['mod_count']; 
            $somwt_count = $row_17['somwt_count'];
            $vlt_count = $row_17['vlt_count'];
            $nta_count = $row_17['nta_count'];
            
            $tage_percent = ($tage_count * 100) / $total_reviews;
            $mod_percent = ($mod_count * 100) / $total_reviews; 
            $somwt_percent = ($somwt_count * 100) / $total_reviews;  
            $vlt_percent = ($vlt_count * 100) / $total_reviews;  
            $nta_percent = ($nta_count * 100) / $total_reviews; 
        }
    }//end if
    
    //18. Teachers encourage the students to participate in extracurricular activities
    $sql_18 = "SELECT 
    COUNT(CASE WHEN ans_18 = 'strongly agree' THEN 1 END) AS sa_count,
    COUNT(CASE WHEN ans_18 = 'agree' THEN 1 END) AS agr_count,
    COUNT(CASE WHEN ans_18 = 'neutral' THEN 1 END) AS neu_count,
    COUNT(CASE WHEN ans_18 = 'disagree' THEN 1 END) AS dis_count,
    COUNT(CASE WHEN ans_18 = 'strongly disagree' THEN 1 END) AS sda_count
    FROM students_feedback";

    $result_18 = $con->query($sql_18);

    if ($result_18->num_rows > 0) { 
        while($row_18 = $result_18->fetch_array()){
            $sa_count = $row_18['sa_count'];
            $agr_count = $row_18['agr_count']; 
            $neu_count = $row_18['neu_count'];
            $dis_count = $row_18['dis_count'];
            $sda_count = $row_18['sda_count'];
            
            $sa_percent = ($sa_count * 100) / $total_reviews;
            $agr_percent = ($agr_count * 100) / $total_reviews; 
            $neu_percent = ($neu_count * 100) / $total_reviews;  
            $dis_percent = ($dis_count * 100) / $total_reviews;  
            $sda_percent = ($sda_count * 100) / $total_reviews; 
        }
    }//end if
    
    //19. Efforts are made by the institute/ teachers to inculcate soft skills, life skills and employability to make the students ready for the world of work
    $sql_19 = "SELECT 
    COUNT(CASE WHEN ans_19 = 'To a great extent' THEN 1 END) AS tage1_count,
    COUNT(CASE WHEN ans_19 = 'Moderate' THEN 1 END) AS mod_count,
    COUNT(CASE WHEN ans_19 = 'Somewhat' THEN 1 END) AS swt_count,
    COUNT(CASE WHEN ans_19 = 'Very little' THEN 1 END) AS vlt_count,
    COUNT(CASE WHEN ans_19 = 'Not at all' THEN 1 END) AS nal1_count
    FROM students_feedback";

    $result_19 = $con->query($sql_19);

    if ($result_19->num_rows > 0) { 
        while($row_19 = $result_19->fetch_array()){
            $tage1_count = $row_19['tage1_count'];
            $mod_count = $row_19['mod_count']; 
            $swt_count = $row_19['swt_count'];
            $vlt_count = $row_19['vlt_count'];
            $nal1_count = $row_19['nal1_count'];
            
            $tage1_percent = ($tage1_count * 100) / $total_reviews;
            $mod_percent = ($mod_count * 100) / $total_reviews; 
            $swt_percent = ($swt_count * 100) / $total_reviews;  
            $vlt_percent = ($vlt_count * 100) / $total_reviews;  
            $nal1_percent = ($nal1_count * 100) / $total_reviews; 
        }
    }//end if
    
    //20. The institution makes effort to engage students in the monitoring, review and continuous quality improvement of the teaching learning process
    $sql_20 = "SELECT 
    COUNT(CASE WHEN ans_20 = 'Strongly agree' THEN 1 END) AS sta_count,
    COUNT(CASE WHEN ans_20 = 'Agree' THEN 1 END) AS agg_count,
    COUNT(CASE WHEN ans_20 = 'Neutral' THEN 1 END) AS nut_count,
    COUNT(CASE WHEN ans_20 = 'Disagree' THEN 1 END) AS dia_count,
    COUNT(CASE WHEN ans_20 = 'Strongly Disagree' THEN 1 END) AS std_count
    FROM students_feedback";

    $result_20 = $con->query($sql_20);

    if ($result_20->num_rows > 0) { 
        while($row_20 = $result_20->fetch_array()){
            $sta_count = $row_20['sta_count'];
            $agg_count = $row_20['agg_count']; 
            $nut_count = $row_20['nut_count'];
            $dia_count = $row_20['dia_count'];
            $std_count = $row_20['std_count'];
            
            $sta_percent = ($sta_count * 100) / $total_reviews;
            $agg_percent = ($agg_count * 100) / $total_reviews; 
            $nut_percent = ($nut_count * 100) / $total_reviews;  
            $dia_percent = ($dia_count * 100) / $total_reviews;  
            $std_percent = ($std_count * 100) / $total_reviews; 
        }
    }//end if
    
    //21. The percentage of teachers using ICT tools such as LCD projector, Multimedia, etc. while teaching
    $sql_21 = "SELECT 
    COUNT(CASE WHEN ans_21 = '85-100%' THEN 1 END) AS eighty_five_count,
    COUNT(CASE WHEN ans_21 = '70-84%' THEN 1 END) AS seventy_count,
    COUNT(CASE WHEN ans_21 = '55-69%' THEN 1 END) AS fifty_five_count,
    COUNT(CASE WHEN ans_21 = '30-54%' THEN 1 END) AS thirty_count,
    COUNT(CASE WHEN ans_21 = 'Below 30%' THEN 1 END) AS below_thirty_count
    FROM students_feedback";

    $result_21 = $con->query($sql_21);

    if ($result_21->num_rows > 0) { 
        while($row_21 = $result_21->fetch_array()){
            $eighty_five_count = $row_21['eighty_five_count'];
            $seventy_count = $row_21['seventy_count']; 
            $fifty_five_count = $row_21['fifty_five_count'];
            $thirty_count = $row_21['thirty_count'];
            $below_thirty_count = $row_21['below_thirty_count'];
            
            $eighty_five_percent = ($eighty_five_count * 100) / $total_reviews;
            $seventy_percent = ($seventy_count * 100) / $total_reviews; 
            $fifty_five_percent = ($fifty_five_count * 100) / $total_reviews;  
            $thirty_percent = ($thirty_count * 100) / $total_reviews;  
            $below_thirty_percent = ($below_thirty_count * 100) / $total_reviews; 
        }
    }//end if
    
    //22. The overall quality of education in your college is very good
    $sql_22 = "SELECT 
    COUNT(CASE WHEN ans_22 = 'Strongly agree' THEN 1 END) AS sta2_count,
    COUNT(CASE WHEN ans_22 = 'Agree' THEN 1 END) AS agr2_count,
    COUNT(CASE WHEN ans_22 = 'Neutral' THEN 1 END) AS neu2_count,
    COUNT(CASE WHEN ans_22 = 'Disagree' THEN 1 END) AS dis2_count,
    COUNT(CASE WHEN ans_22 = 'Strongly Disagree' THEN 1 END) AS st_dis2_count
    FROM students_feedback";

    $result_22 = $con->query($sql_22);

    if ($result_22->num_rows > 0) { 
        while($row_22 = $result_22->fetch_array()){
            $sta2_count = $row_22['sta2_count'];
            $agr2_count = $row_22['agr2_count']; 
            $neu2_count = $row_22['neu2_count'];
            $dis2_count = $row_22['dis2_count'];
            $st_dis2_count = $row_22['st_dis2_count'];
            
            $sta2_percent = ($sta2_count * 100) / $total_reviews;
            $agr2_percent = ($agr2_count * 100) / $total_reviews; 
            $neu2_percent = ($neu2_count * 100) / $total_reviews;  
            $dis2_percent = ($dis2_count * 100) / $total_reviews;  
            $st_dis2_percent = ($st_dis2_count * 100) / $total_reviews; 
        }
    }//end if
    
    //23. The institute takes an active part in promoting internship, student exchange, field visit opportunities for students
    $sql_23 = "SELECT 
    COUNT(CASE WHEN ans_23 = 'Regularly' THEN 1 END) AS rarly22_count,
    COUNT(CASE WHEN ans_23 = 'Often' THEN 1 END) AS oftn22_count,
    COUNT(CASE WHEN ans_23 = 'Sometimes' THEN 1 END) AS som2_count,
    COUNT(CASE WHEN ans_23 = 'Rarely' THEN 1 END) AS rary2_count,
    COUNT(CASE WHEN ans_23 = 'Never' THEN 1 END) AS nev2_count
    FROM students_feedback";

    $result_23 = $con->query($sql_23);

    if ($result_23->num_rows > 0) { 
        while($row_23 = $result_23->fetch_array()){
            $rarly22_count = $row_23['rarly22_count'];
            $oftn22_count = $row_23['oftn22_count']; 
            $som2_count = $row_23['som2_count'];
            $rary2_count = $row_23['rary2_count'];
            $nev2_count = $row_23['nev2_count'];
            
            $rarly23_percent = ($rarly22_count * 100) / $total_reviews;
            $oftn23_percent = ($oftn22_count * 100) / $total_reviews; 
            $som2_percent = ($som2_count * 100) / $total_reviews;  
            $rary2_percent = ($rary2_count * 100) / $total_reviews;  
            $nev2_percent = ($nev2_count * 100) / $total_reviews; 
        }
    }//end if
    
    //24. The institution provides multiple opportunities to learn and grow
    $sql_24 = "SELECT 
    COUNT(CASE WHEN ans_24 = 'Strongly agree' THEN 1 END) AS st24_count,
    COUNT(CASE WHEN ans_24 = 'Agree' THEN 1 END) AS ag24_count,
    COUNT(CASE WHEN ans_24 = 'Neutral' THEN 1 END) AS neu24_count,
    COUNT(CASE WHEN ans_24 = 'Disagree' THEN 1 END) AS dis24_count,
    COUNT(CASE WHEN ans_24 = 'Strongly Disagree' THEN 1 END) AS sd24_count
    FROM students_feedback";

    $result_24 = $con->query($sql_24);

    if ($result_24->num_rows > 0) { 
        while($row_24 = $result_24->fetch_array()){
            $st24_count = $row_24['st24_count'];
            $ag24_count = $row_24['ag24_count']; 
            $neu24_count = $row_24['neu24_count'];
            $dis24_count = $row_24['dis24_count'];
            $sd24_count = $row_24['sd24_count'];
            
            $st24_percent = ($st24_count * 100) / $total_reviews;
            $ag24_percent = ($ag24_count * 100) / $total_reviews; 
            $neu24_percent = ($neu24_count * 100) / $total_reviews;  
            $dis24_percent = ($dis24_count * 100) / $total_reviews;  
            $sd24_percent = ($sd24_count * 100) / $total_reviews; 
        }
    }//end if
    
    //25. Do you get your required documents from College Library?
    $sql_25 = "SELECT 
    COUNT(CASE WHEN ans_25 = 'To a Great Extent' THEN 1 END) AS tag25_count,
    COUNT(CASE WHEN ans_25 = 'Moderate' THEN 1 END) AS mod25_count,
    COUNT(CASE WHEN ans_25 = 'Occasional' THEN 1 END) AS occ25_count,
    COUNT(CASE WHEN ans_25 = 'Very Little' THEN 1 END) AS vl25_count,
    COUNT(CASE WHEN ans_25 = 'Not at All' THEN 1 END) AS nal25_count
    FROM students_feedback";

    $result_25 = $con->query($sql_25);

    if ($result_25->num_rows > 0) { 
        while($row_25 = $result_25->fetch_array()){
            $tag25_count = $row_25['tag25_count'];
            $mod25_count = $row_25['mod25_count']; 
            $occ25_count = $row_25['occ25_count'];
            $vl25_count = $row_25['vl25_count'];
            $nal25_count = $row_25['nal25_count'];
            
            $tag25_percent = ($tag25_count * 100) / $total_reviews;
            $mod25_percent = ($mod25_count * 100) / $total_reviews; 
            $occ25_percent = ($occ25_count * 100) / $total_reviews;  
            $vl25_percent = ($vl25_count * 100) / $total_reviews;  
            $nal25_percent = ($nal25_count * 100) / $total_reviews; 
        }
    }//end if
    
    //26. Are the Librarian and Library Staff helpful in seeking required documents or in other matters?
    $sql_26 = "SELECT 
    COUNT(CASE WHEN ans_26 = 'To a great extent' THEN 1 END) AS tag26_count,
    COUNT(CASE WHEN ans_26 = 'Moderate' THEN 1 END) AS mod26_count,
    COUNT(CASE WHEN ans_26 = 'Occasional' THEN 1 END) AS occ26_count,
    COUNT(CASE WHEN ans_26 = 'Very Little' THEN 1 END) AS vl26_count,
    COUNT(CASE WHEN ans_26 = 'Not at All' THEN 1 END) AS nal26_count
    FROM students_feedback";

    $result_26 = $con->query($sql_26);

    if ($result_26->num_rows > 0) { 
        while($row_26 = $result_26->fetch_array()){
            $tag26_count = $row_26['tag26_count'];
            $mod26_count = $row_26['mod26_count']; 
            $occ26_count = $row_26['occ26_count'];
            $vl26_count = $row_26['vl26_count'];
            $nal26_count = $row_26['nal26_count'];
            
            $tag26_percent = ($tag26_count * 100) / $total_reviews;
            $mod26_percent = ($mod26_count * 100) / $total_reviews; 
            $occ26_percent = ($occ26_count * 100) / $total_reviews;  
            $vl26_percent = ($vl26_count * 100) / $total_reviews;  
            $nal26_percent = ($nal26_count * 100) / $total_reviews; 
        }
    }//end if
    
    //27. Overall experience from Library
    $sql_27 = "SELECT 
    COUNT(CASE WHEN ans_27 = 'To a great extent' THEN 1 END) AS tag27_count,
    COUNT(CASE WHEN ans_27 = 'Excellent' THEN 1 END) AS elnt27_count,
    COUNT(CASE WHEN ans_27 = 'Very Good' THEN 1 END) AS vg27_count,
    COUNT(CASE WHEN ans_27 = 'Good' THEN 1 END) AS go27_count,
    COUNT(CASE WHEN ans_27 = 'fair' THEN 1 END) AS fr27_count,
    COUNT(CASE WHEN ans_27 = 'poor' THEN 1 END) AS poo27_count
    FROM students_feedback";

    $result_27 = $con->query($sql_27);

    if ($result_27->num_rows > 0) { 
        while($row_27 = $result_27->fetch_array()){
            $tag27_count = $row_27['tag27_count'];
            $elnt27_count = $row_27['elnt27_count']; 
            $vg27_count = $row_27['vg27_count'];
            $go27_count = $row_27['go27_count'];
            $fr27_count = $row_27['fr27_count'];
            $poo27_count = $row_27['poo27_count'];
            
            $tag27_percent = ($tag27_count * 100) / $total_reviews;
            $elnt27_percent = ($elnt27_count * 100) / $total_reviews; 
            $vg27_percent = ($vg27_count * 100) / $total_reviews;  
            $go27_percent = ($go27_count * 100) / $total_reviews;  
            $fr27_percent = ($fr27_count * 100) / $total_reviews; 
            $poo27_percent = ($poo27_count * 100) / $total_reviews;  
        }
    }//end if
    
    //28. What suggestion(s) would you give for the betterment of your institution?
    $all28_ans = '';
    $sql_28 = "SELECT ans_28 FROM students_feedback";
    $result_28 = $con->query($sql_28);

    if ($result_28->num_rows > 0) { 
        while($row_28 = $result_28->fetch_array()){
            $ans_28 = $row_28['ans_28'];
            if($ans_28 != ''){
                $all28_ans .= ' <br>&#8594;'.$ans_28;
            } 
        }
    }//end if
    
?>
    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->
    <body class="A4">
    <style>
    .page-break {
      break-before: page; /* Hard page break before this element */
    }
  </style>
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">
        <!-- Write HTML just like a web page -->
        <!-- <article>Kitchen Order Takeing Slip</article> -->  
        <div class="container">   
            <div class="row">
                <div class="col-md-12 text-center">
                <a href="#" class="logo-dark">
                    <span><img src="https://gurudascollege.edu.in/wp-content/themes/gurudas/assets/images/logo.png" alt="dark logo" style="width: 30%;height: 70px;"></span>
                </a>
                <div>
            <div>        
            <h3>Student feedback report</h3>
            <div class="row">
                <div class="col-md-12">
                    <div id="piechart"></div>
                </div> 
            </div>            
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_3"></div>
                </div>
            </div>            
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_4"></div>
                </div>
            </div>            
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_5"></div>
                </div>
            </div>            
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_6"></div>
                </div>
            </div>           
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_7"></div>
                </div>
            </div>           
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_8"></div>
                </div>
            </div>          
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_9"></div>
                </div>
            </div>          
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_10"></div>
                </div>
            </div>          
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_11"></div>
                </div>
            </div>          
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_12"></div>
                </div>
            </div>         
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_13"></div>
                </div>
            </div>        
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_14"></div>
                </div>
            </div>       
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_15"></div>
                </div>
            </div>      
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_16"></div>
                </div>
            </div>      
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_17"></div>
                </div>
            </div>     
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_18"></div>
                </div>
            </div>     
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_19"></div>
                </div>
            </div>     
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_20"></div>
                </div>
            </div>    
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_21"></div>
                </div>
            </div>   
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_22"></div>
                </div>
            </div>   
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_23"></div>
                </div>
            </div>  
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_24"></div>
                </div>
            </div>  
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_25"></div>
                </div>
            </div>  
            <div class="row page-break"> 
                <div class="col-md-12">
                    <div id="piechart_26"></div>
                </div>
            </div> 
            <div class="row"> 
                <div class="col-md-12">
                    <div id="piechart_27"></div>
                </div>
            </div>
            <div class="row page-break"> 
                <div class="col-md-12 text-left">
                    <h4>28. What suggestion(s) would you give for the betterment of your institution?</h4>
                    <div id="piechart_28"><?=$all28_ans?></div>
                </div>
            </div>
        </div>
    </section>
    </body>
</body>
</html>
<script>
    window.print();    
</script>
<script type="text/javascript">
    // Load google charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    // Draw the chart and set the chart values
    function drawChart() { 
        var data = google.visualization.arrayToDataTable([
        ['Task', 'Gender Composition'],
        ['Female', <?=$female_percent?>],
        ['Male', <?=$male_percent?>],
        ['Transgender', <?=$other_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options = {'title':'2. Gender Composition:', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);

        //Degree Programme                
        var data_3 = google.visualization.arrayToDataTable([
            ['Task', 'Degree Programme'],
            ['Bachelor\'s', <?=$bachelors_percent?>],
            ['Master\'s', <?=$masters_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_3 = {'title':'3. Degree Programme', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_3 = new google.visualization.PieChart(document.getElementById('piechart_3'));
        chart_3.draw(data_3, options_3);

        //Examination system                
        var data_4 = google.visualization.arrayToDataTable([
            ['Task', 'Examination system'],
            ['Part system (1+1+1)', <?=$part_sys_percent?>],
            ['Semester System', <?=$semester_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_4 = {'title':'4. Examination system', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_4 = new google.visualization.PieChart(document.getElementById('piechart_4'));
        chart_4.draw(data_4, options_4);

        //Subject area currently pursuing                
        var data_5 = google.visualization.arrayToDataTable([
            ['Task', 'Subject area currently pursuing'],
            ['Arts', <?=$part_sys_percent?>],
            ['Science', <?=$semester_percent?>],
            ['Commerce', <?=$semester_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_5 = {'title':'5. Subject area currently pursuing', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_5 = new google.visualization.PieChart(document.getElementById('piechart_5'));
        chart_5.draw(data_5, options_5);

        //6. How well are the teachers prepare for class?              
        var data_6 = google.visualization.arrayToDataTable([
            ['Task', 'How well are the teachers prepare for class'],
            ['Thoroughly', <?=$thoroughly_percent?>],
            ['Satisfactorily', <?=$satis_percent?>],
            ['Poorly', <?=$poorly_percent?>],
            ['Indifferently', <?=$indifferently_percent?>],
            ['Won’t teach at all', <?=$won_teach_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_6 = {'title':'6. How well are the teachers prepare for class?', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_6 = new google.visualization.PieChart(document.getElementById('piechart_6'));
        chart_6.draw(data_6, options_6);

        //7. Extent of the syllabus covered in the class              
        var data_7 = google.visualization.arrayToDataTable([
            ['Task', 'Extent of the syllabus covered in the class'],
            ['85-100%', <?=$eighty_five_percent?>],
            ['70-84%', <?=$eighty_four_percent?>],
            ['55-69%', <?=$sixtynine_percent?>],
            ['30-54%', <?=$fifty_four_percent?>],
            ['Below 30%', <?=$below_thirty_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_7 = {'title':'7. Extent of the syllabus covered in the class', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_7 = new google.visualization.PieChart(document.getElementById('piechart_7'));
        chart_7.draw(data_7, options_7);

        //8. How well are the teachers able to communicate?              
        var data_8 = google.visualization.arrayToDataTable([
            ['Task', 'How well are the teachers able to communicate?'],
            ['Very Effective', <?=$very_eff_percent?>],
            ['Sometimes Effective', <?=$some_eff_percent?>],
            ['Satisfactorily', <?=$satis_percent?>],
            ['Generally Effective', <?=$gen_eff_percent?>],
            ['Very Poor', <?=$very_poor_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_8 = {'title':'8. How well are the teachers able to communicate?', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_8 = new google.visualization.PieChart(document.getElementById('piechart_8'));
        chart_8.draw(data_8, options_8);

        //9. The teachers’ approach to teaching can be best described as              
        var data_9 = google.visualization.arrayToDataTable([
            ['Task', 'The teachers approach to teaching can be best described as'],
            ['Excellent', <?=$excellent_percent?>],
            ['Very Good', <?=$very_good_percent?>],
            ['Good', <?=$good_percent?>],
            ['Fair', <?=$fair_percent?>],
            ['Poor', <?=$poor_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_9 = {'title':'9. The teachers approach to teaching can be best described as', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_9 = new google.visualization.PieChart(document.getElementById('piechart_9'));
        chart_9.draw(data_9, options_9);

        //10. The teachers illustrate the concepts through examples and applications              
        var data_10 = google.visualization.arrayToDataTable([
            ['Task', 'The teachers illustrate the concepts through examples and applications'],
            ['Everytime', <?=$everytime_percent?>],
            ['Usually', <?=$usually_percent?>],
            ['Sometimes', <?=$sometimes_percent?>],
            ['Rarely', <?=$rarely_percent?>],
            ['Never', <?=$never_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_10 = {'title':'10. The teachers illustrate the concepts through examples and applications', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_10 = new google.visualization.PieChart(document.getElementById('piechart_10'));
        chart_10.draw(data_10, options_10);

        //11. Fairness of the internal evaluation process by the teachers              
        var data_11 = google.visualization.arrayToDataTable([
            ['Task', 'Fairness of the internal evaluation process by the teachers'],
            ['Always Fair', <?=$af_percent?>],
            ['Usually Fair', <?=$uf_percent?>],
            ['Sometimes Fair', <?=$sf_percent?>],
            ['Usually Unfair', <?=$uu_percent?>],
            ['Unfair', <?=$unf_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_11 = {'title':'11. Fairness of the internal evaluation process by the teachers', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_11 = new google.visualization.PieChart(document.getElementById('piechart_11'));
        chart_11.draw(data_11, options_11);

        //12. Performance in assignments discussed with students             
        var data_12 = google.visualization.arrayToDataTable([
            ['Task', 'Performance in assignments discussed with students'],
            ['Always', <?=$al_percent?>],
            ['Usually', <?=$usu_percent?>],
            ['Sometimes', <?=$som_percent?>],
            ['Rarely', <?=$ry_percent?>],
            ['Never', <?=$nev_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_12 = {'title':'12. Performance in assignments discussed with students', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_12 = new google.visualization.PieChart(document.getElementById('piechart_12'));
        chart_12.draw(data_12, options_12);

        //13. Teachers inform about expected competencies, course outcomes and programme outcomes             
        var data_13 = google.visualization.arrayToDataTable([
            ['Task', 'Teachers inform about expected competencies, course outcomes and programme outcomes'],
            ['Always', <?=$al_percent_13?>],
            ['Usually', <?=$usu_percent_13?>],
            ['Sometimes', <?=$som_percent_13?>],
            ['Rarely', <?=$ry_percent_13?>],
            ['Never', <?=$nev_percent_13?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_13 = {'title':'13. Teachers inform about expected competencies, course outcomes and programme outcomes', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_13 = new google.visualization.PieChart(document.getElementById('piechart_13'));
        chart_13.draw(data_13, options_13);

        // 14. Mentor does a necessary follow-up with an assigned task             
        var data_14 = google.visualization.arrayToDataTable([
            ['Task', 'Mentor does a necessary follow-up with an assigned task'],
            ['Always', <?=$al_percent_14?>],
            ['Usually', <?=$usu_percent_14?>],
            ['Sometimes', <?=$som_percent_14?>],
            ['Rarely', <?=$ry_percent_14?>],
            ['I don’t have a mentor', <?=$nev_percent_14?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_14 = {'title':'14. Mentor does a necessary follow-up with an assigned task', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_14 = new google.visualization.PieChart(document.getElementById('piechart_14'));
        chart_14.draw(data_14, options_14);

        // 15. The teachers identify strengths and encourage the students by providing the right level of challenges           
        var data_15 = google.visualization.arrayToDataTable([
            ['Task', 'The teachers identify strengths and encourage the students by providing the right level of challenges'],
            ['Fully', <?=$fly_percent?>],
            ['Reasonably', <?=$res_percent?>],
            ['Partially', <?=$par_percent?>],
            ['Slightly', <?=$sly_percent?>],
            ['Unable to', <?=$unbl_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_15 = {'title':'15. The teachers identify strengths and encourage the students by providing the right level of challenges', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_15 = new google.visualization.PieChart(document.getElementById('piechart_15'));
        chart_15.draw(data_15, options_15);

        // 16. Teachers can identify the weaknesses of students and help them to overcome           
        var data_16 = google.visualization.arrayToDataTable([
            ['Task', 'Teachers can identify the weaknesses of students and help them to overcome'],
            ['Everytime', <?=$ev_percent?>],
            ['Usually', <?=$usly_percent?>],
            ['Sometimes', <?=$somt_percent?>],
            ['Rarely', <?=$rly_percent?>],
            ['Never', <?=$nvr_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_16 = {'title':'16. Teachers can identify the weaknesses of students and help them to overcome', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_16 = new google.visualization.PieChart(document.getElementById('piechart_16'));
        chart_16.draw(data_16, options_16);

        // 17. The institute / teachers use student-centric methods, such as experiential learning, participatuve learning and problem-solving methodologies for enhancing learning experiences          
        var data_17 = google.visualization.arrayToDataTable([
            ['Task', 'The institute / teachers use student-centric methods, such as experiential learning, participatuve learning and problem-solving methodologies for enhancing learning experiences'],
            ['To a great extent', <?=$tage_percent?>],
            ['Moderate', <?=$mod_percent?>],
            ['Somewhat', <?=$somwt_percent?>],
            ['Very Little', <?=$vlt_percent?>],
            ['Not at All', <?=$nta_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_17 = {'title':'17. The institute / teachers use student-centric methods, such as experiential learning, participatuve learning and problem-solving methodologies for enhancing learning experiences', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_17 = new google.visualization.PieChart(document.getElementById('piechart_17'));
        chart_17.draw(data_17, options_17);

        //18. Teachers encourage the students to participate in extracurricular activities        
        var data_18 = google.visualization.arrayToDataTable([
            ['Task', 'Teachers encourage the students to participate in extracurricular activities'],
            ['Strongly Agree', <?=$sa_percent?>],
            ['Agree', <?=$agr_percent?>],
            ['Neutral', <?=$neu_percent?>],
            ['Disagree', <?=$dis_percent?>],
            ['Strongly Disagree', <?=$sda_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_18 = {'title':'18. Teachers encourage the students to participate in extracurricular activities', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_18 = new google.visualization.PieChart(document.getElementById('piechart_18'));
        chart_18.draw(data_18, options_18);

        //19. Efforts are made by the institute/ teachers to inculcate soft skills, life skills and employability to make the students ready for the world of work        
        var data_19 = google.visualization.arrayToDataTable([
            ['Task', 'Efforts are made by the institute/ teachers to inculcate soft skills, life skills and employability to make the students ready for the world of work'],
            ['To a great extent', <?=$tage1_percent?>],
            ['Moderate', <?=$mod_percent?>],
            ['Somewhat', <?=$swt_percent?>],
            ['Very little', <?=$vlt_percent?>],
            ['Not at all', <?=$nal1_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_19 = {'title':'19. Efforts are made by the institute/ teachers to inculcate soft skills, life skills and employability to make the students ready for the world of work', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_19 = new google.visualization.PieChart(document.getElementById('piechart_19'));
        chart_19.draw(data_19, options_19);

        //20. The institution makes effort to engage students in the monitoring, review and continuous quality improvement of the teaching learning process      
        var data_20 = google.visualization.arrayToDataTable([
            ['Task', 'The institution makes effort to engage students in the monitoring, review and continuous quality improvement of the teaching learning process'],
            ['Strongly agree', <?=$sta_percent?>],
            ['Agree', <?=$agg_percent?>],
            ['Neutral', <?=$nut_percent?>],
            ['Disagree', <?=$dia_percent?>],
            ['Strongly Disagree', <?=$std_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_20 = {'title':'20. The institution makes effort to engage students in the monitoring, review and continuous quality improvement of the teaching learning process', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_20 = new google.visualization.PieChart(document.getElementById('piechart_20'));
        chart_20.draw(data_20, options_20);

        //21. The percentage of teachers using ICT tools such as LCD projector, Multimedia, etc. while teaching      
        var data_21 = google.visualization.arrayToDataTable([
            ['Task', 'The percentage of teachers using ICT tools such as LCD projector, Multimedia, etc. while teaching'],
            ['85-100%', <?=$eighty_five_percent?>],
            ['70-84%', <?=$seventy_percent?>],
            ['55-69%', <?=$fifty_five_percent?>],
            ['30-54%', <?=$thirty_percent?>],
            ['Below 30%', <?=$below_thirty_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_21 = {'title':'21. The percentage of teachers using ICT tools such as LCD projector, Multimedia, etc. while teaching', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_21 = new google.visualization.PieChart(document.getElementById('piechart_21'));
        chart_21.draw(data_21, options_21);

        //22. The overall quality of education in your college is very good      
        var data_22 = google.visualization.arrayToDataTable([
            ['Task', 'The overall quality of education in your college is very good'],
            ['Strongly agree', <?=$sta2_percent?>],
            ['Agree', <?=$agr2_percent?>],
            ['Neutral', <?=$neu2_percent?>],
            ['Disagree', <?=$dis2_percent?>],
            ['Strongly Disagree', <?=$st_dis2_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_22 = {'title':'22. The overall quality of education in your college is very good', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_22 = new google.visualization.PieChart(document.getElementById('piechart_22'));
        chart_22.draw(data_22, options_22);

        //23. The institute takes an active part in promoting internship, student exchange, field visit opportunities for students      
        var data_23 = google.visualization.arrayToDataTable([
            ['Task', 'The institute takes an active part in promoting internship, student exchange, field visit opportunities for students'],
            ['Regularly', <?=$rarly23_percent?>],
            ['Often', <?=$oftn23_percent?>],
            ['Sometimes', <?=$som2_percent?>],
            ['Rarely', <?=$rary2_percent?>],
            ['Never', <?=$nev2_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_23 = {'title':'23. The institute takes an active part in promoting internship, student exchange, field visit opportunities for students', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_23 = new google.visualization.PieChart(document.getElementById('piechart_23'));
        chart_23.draw(data_23, options_23);

        //24. The institution provides multiple opportunities to learn and grow      
        var data_24 = google.visualization.arrayToDataTable([
            ['Task', 'The institution provides multiple opportunities to learn and grow'],
            ['Strongly agree', <?=$st24_percent?>],
            ['Agree', <?=$ag24_percent?>],
            ['Neutral', <?=$neu24_percent?>],
            ['Disagree', <?=$dis24_percent?>],
            ['Strongly Disagree', <?=$sd24_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_24 = {'title':'24. The institution provides multiple opportunities to learn and grow', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_24 = new google.visualization.PieChart(document.getElementById('piechart_24'));
        chart_24.draw(data_24, options_24);


        //25. Do you get your required documents from College Library?      
        var data_25 = google.visualization.arrayToDataTable([
            ['Task', 'Do you get your required documents from College Library?'],
            ['To a Great Extent', <?=$tag25_percent?>],
            ['Moderate', <?=$mod25_percent?>],
            ['Occasional', <?=$occ25_percent?>],
            ['Very Little', <?=$vl25_percent?>],
            ['Not at All', <?=$nal25_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_25 = {'title':'25. Do you get your required documents from College Library?', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_25 = new google.visualization.PieChart(document.getElementById('piechart_25'));
        chart_25.draw(data_25, options_25);


        //26. Are the Librarian and Library Staff helpful in seeking required documents or in other matters?      
        var data_26 = google.visualization.arrayToDataTable([
            ['Task', 'Are the Librarian and Library Staff helpful in seeking required documents or in other matters?'],
            ['To a Great Extent', <?=$tag26_percent?>],
            ['Moderate', <?=$mod26_percent?>],
            ['Occasional', <?=$occ26_percent?>],
            ['Very Little', <?=$vl26_percent?>],
            ['Not at All', <?=$nal26_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_26 = {'title':'26. Are the Librarian and Library Staff helpful in seeking required documents or in other matters?', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_26 = new google.visualization.PieChart(document.getElementById('piechart_26'));
        chart_26.draw(data_26, options_26);


        //27. Overall experience from Library      
        var data_27 = google.visualization.arrayToDataTable([
            ['Task', 'Overall experience from Library'],
            ['To a Great Extent', <?=$tag27_percent?>],
            ['Excellent', <?=$elnt27_percent?>],
            ['Very Good', <?=$vg27_percent?>],
            ['Good', <?=$go27_percent?>],
            ['Fair', <?=$fr27_percent?>],
            ['Poor', <?=$poo27_percent?>]
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_27 = {'title':'27. Overall experience from Library', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart1"
        var chart_27 = new google.visualization.PieChart(document.getElementById('piechart_27'));
        chart_27.draw(data_27, options_27);

    }//end if
</script>