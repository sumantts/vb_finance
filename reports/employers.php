<!DOCTYPE html>
<html lang="en">
<head>
<title>Employers’ Feedback Report</title>
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

    $sql_0 = "SELECT * FROM employ_feedback";
    $result_0 = $con->query($sql_0);
    $total_reviews = $result_0->num_rows;    

    //1. The role of the Institution in taking active interest in organizing Seminars, Conferences & Workshop
    $sql_1 = "SELECT 
    COUNT(CASE WHEN ans_1 = 'Excellent' THEN 1 END) AS exc_1_count,
    COUNT(CASE WHEN ans_1 = 'Good' THEN 1 END) AS goo_1_count,
    COUNT(CASE WHEN ans_1 = 'Satisfactory' THEN 1 END) AS sat_1_count,
    COUNT(CASE WHEN ans_1 = 'Poor' THEN 1 END) AS poo_1_count
    FROM employ_feedback";

    $result_1 = $con->query($sql_1);

    if ($result_1->num_rows > 0) {
        while($row_1 = $result_1->fetch_array()){
            $exc_1_count = $row_1['exc_1_count'];
            $goo_1_count = $row_1['goo_1_count'];
            $sat_1_count = $row_1['sat_1_count'];
            $poo_1_count = $row_1['poo_1_count']; 
            
            $exc_1_percent = ($exc_1_count * 100) / $total_reviews;
            $goo_1_percent = ($goo_1_count * 100) / $total_reviews;
            $sat_1_percent = ($sat_1_count * 100) / $total_reviews;
            $poo_1_percent = ($poo_1_count * 100) / $total_reviews; 
        }
    }//end if   

    //2. The Quality of teaching and mentoring process in the institution facilitates cognitive, social & emotional growth.
    $sql_2 = "SELECT 
    COUNT(CASE WHEN ans_2 = 'Excellent' THEN 1 END) AS exc_2_count,
    COUNT(CASE WHEN ans_2 = 'Good' THEN 1 END) AS goo_2_count,
    COUNT(CASE WHEN ans_2 = 'Satisfactory' THEN 1 END) AS sat_2_count,
    COUNT(CASE WHEN ans_2 = 'Poor' THEN 1 END) AS poo_2_count
    FROM employ_feedback";

    $result_2 = $con->query($sql_2);

    if ($result_2->num_rows > 0) {
        while($row_2 = $result_2->fetch_array()){
            $exc_2_count = $row_2['exc_2_count'];
            $goo_2_count = $row_2['goo_2_count'];
            $sat_2_count = $row_2['sat_2_count'];
            $poo_2_count = $row_2['poo_2_count']; 
            
            $exc_2_percent = ($exc_2_count * 100) / $total_reviews;
            $goo_2_percent = ($goo_2_count * 100) / $total_reviews;
            $sat_2_percent = ($sat_2_count * 100) / $total_reviews;
            $poo_2_percent = ($poo_2_count * 100) / $total_reviews; 
        }
    }//end if
    

    //3. The performance of the Institution providing opportunities, learning & holistic growth
    $sql_3 = "SELECT 
    COUNT(CASE WHEN ans_3 = 'Strongly Agree' THEN 1 END) AS sa_3_count,
    COUNT(CASE WHEN ans_3 = 'Agree' THEN 1 END) AS ag_3_count,
    COUNT(CASE WHEN ans_3 = 'Neutral' THEN 1 END) AS nu_3_count,
    COUNT(CASE WHEN ans_3 = 'Disagree' THEN 1 END) AS dis_3_count,
    COUNT(CASE WHEN ans_3 = 'Strongly Disagree' THEN 1 END) AS std_3_count
    FROM employ_feedback";

    $result_3 = $con->query($sql_3);

    if ($result_3->num_rows > 0) {
        while($row_3 = $result_3->fetch_array()){
            $sa_3_count = $row_3['sa_3_count'];
            $ag_3_count = $row_3['ag_3_count'];
            $nu_3_count = $row_3['nu_3_count'];
            $dis_3_count = $row_3['dis_3_count'];
            $std_3_count = $row_3['std_3_count'];
            
            $sa_3_percent = ($sa_3_count * 100) / $total_reviews;
            $ag_3_percent = ($ag_3_count * 100) / $total_reviews;
            $nu_3_percent = ($nu_3_count * 100) / $total_reviews;
            $dis_3_percent = ($dis_3_count * 100) / $total_reviews;
            $std_3_percent = ($std_3_count * 100) / $total_reviews;
        }
    }//end if    

    //4. The process undertaken by the Institution in informing stakeholders about expected competencies, course outcomes & programme outcomes.
    $sql_4 = "SELECT 
    COUNT(CASE WHEN ans_4 = 'Strongly Agree' THEN 1 END) AS sa_4_count,
    COUNT(CASE WHEN ans_4 = 'Agree' THEN 1 END) AS ag_4_count,
    COUNT(CASE WHEN ans_4 = 'Neutral' THEN 1 END) AS nu_4_count,
    COUNT(CASE WHEN ans_4 = 'Disagree' THEN 1 END) AS dis_4_count,
    COUNT(CASE WHEN ans_4 = 'Strongly Disagree' THEN 1 END) AS std_4_count
    FROM employ_feedback";

    $result_4 = $con->query($sql_4);

    if ($result_4->num_rows > 0) {
        while($row_4 = $result_4->fetch_array()){
            $sa_4_count = $row_4['sa_4_count'];
            $ag_4_count = $row_4['ag_4_count'];
            $nu_4_count = $row_4['nu_4_count'];
            $dis_4_count = $row_4['dis_4_count'];
            $std_4_count = $row_4['std_4_count'];
            
            $sa_4_percent = ($sa_4_count * 100) / $total_reviews;
            $ag_4_percent = ($ag_4_count * 100) / $total_reviews;
            $nu_4_percent = ($nu_4_count * 100) / $total_reviews;
            $dis_4_percent = ($dis_4_count * 100) / $total_reviews;
            $std_4_percent = ($std_4_count * 100) / $total_reviews;
        }
    }//end if   

    //5. The mechanism provided by the Institution to identify strengths, remedy to overcome weakness in students.
    $sql_5 = "SELECT 
    COUNT(CASE WHEN ans_5 = 'Strongly Agree' THEN 1 END) AS sa_5_count,
    COUNT(CASE WHEN ans_5 = 'Agree' THEN 1 END) AS ag_5_count,
    COUNT(CASE WHEN ans_5 = 'Neutral' THEN 1 END) AS nu_5_count,
    COUNT(CASE WHEN ans_5 = 'Disagree' THEN 1 END) AS dis_5_count,
    COUNT(CASE WHEN ans_5 = 'Strongly Disagree' THEN 1 END) AS std_5_count
    FROM employ_feedback";

    $result_5 = $con->query($sql_5);

    if ($result_5->num_rows > 0) {
        while($row_5 = $result_5->fetch_array()){
            $sa_5_count = $row_5['sa_5_count'];
            $ag_5_count = $row_5['ag_5_count'];
            $nu_5_count = $row_5['nu_5_count'];
            $dis_5_count = $row_5['dis_5_count'];
            $std_5_count = $row_5['std_5_count'];
            
            $sa_5_percent = ($sa_5_count * 100) / $total_reviews;
            $ag_5_percent = ($ag_5_count * 100) / $total_reviews;
            $nu_5_percent = ($nu_5_count * 100) / $total_reviews;
            $dis_5_percent = ($dis_5_count * 100) / $total_reviews;
            $std_5_percent = ($std_5_count * 100) / $total_reviews;
        }
    }//end if  

    //6.The role of the Institution in engaging students with monitoring, review & continuous quality improvement of the teaching learning process.
    $sql_6 = "SELECT 
    COUNT(CASE WHEN ans_6 = 'Strongly Agree' THEN 1 END) AS sa_6_count,
    COUNT(CASE WHEN ans_6 = 'Agree' THEN 1 END) AS ag_6_count,
    COUNT(CASE WHEN ans_6 = 'Neutral' THEN 1 END) AS nu_6_count,
    COUNT(CASE WHEN ans_6 = 'Disagree' THEN 1 END) AS dis_6_count,
    COUNT(CASE WHEN ans_6 = 'Strongly Disagree' THEN 1 END) AS std_6_count
    FROM employ_feedback";

    $result_6 = $con->query($sql_6);

    if ($result_6->num_rows > 0) {
        while($row_6 = $result_6->fetch_array()){
            $sa_6_count = $row_6['sa_6_count'];
            $ag_6_count = $row_6['ag_6_count'];
            $nu_6_count = $row_6['nu_6_count'];
            $dis_6_count = $row_6['dis_6_count'];
            $std_6_count = $row_6['std_6_count'];
            
            $sa_6_percent = ($sa_6_count * 100) / $total_reviews;
            $ag_6_percent = ($ag_6_count * 100) / $total_reviews;
            $nu_6_percent = ($nu_6_count * 100) / $total_reviews;
            $dis_6_percent = ($dis_6_count * 100) / $total_reviews;
            $std_6_percent = ($std_6_count * 100) / $total_reviews;
        }
    }//end if 

    //7. The role of the Institution in encouraging the student-centric methods for enhancing learning experiences.
    $sql_7 = "SELECT 
    COUNT(CASE WHEN ans_7 = 'Strongly Agree' THEN 1 END) AS sa_7_count,
    COUNT(CASE WHEN ans_7 = 'Agree' THEN 1 END) AS ag_7_count,
    COUNT(CASE WHEN ans_7 = 'Neutral' THEN 1 END) AS nu_7_count,
    COUNT(CASE WHEN ans_7 = 'Disagree' THEN 1 END) AS dis_7_count,
    COUNT(CASE WHEN ans_7 = 'Strongly Disagree' THEN 1 END) AS std_7_count
    FROM employ_feedback";

    $result_7 = $con->query($sql_7);

    if ($result_7->num_rows > 0) {
        while($row_7 = $result_7->fetch_array()){
            $sa_7_count = $row_7['sa_7_count'];
            $ag_7_count = $row_7['ag_7_count'];
            $nu_7_count = $row_7['nu_7_count'];
            $dis_7_count = $row_7['dis_7_count'];
            $std_7_count = $row_7['std_7_count'];
            
            $sa_7_percent = ($sa_7_count * 100) / $total_reviews;
            $ag_7_percent = ($ag_7_count * 100) / $total_reviews;
            $nu_7_percent = ($nu_7_count * 100) / $total_reviews;
            $dis_7_percent = ($dis_7_count * 100) / $total_reviews;
            $std_7_percent = ($std_7_count * 100) / $total_reviews;
        }
    }//end if

    //8. The role of the Institution in encouraging participation to extra-curricular activities.
    $sql_8 = "SELECT 
    COUNT(CASE WHEN ans_8 = 'Strongly Agree' THEN 1 END) AS sa_8_count,
    COUNT(CASE WHEN ans_8 = 'Agree' THEN 1 END) AS ag_8_count,
    COUNT(CASE WHEN ans_8 = 'Neutral' THEN 1 END) AS nu_8_count,
    COUNT(CASE WHEN ans_8 = 'Disagree' THEN 1 END) AS dis_8_count,
    COUNT(CASE WHEN ans_8 = 'Strongly Disagree' THEN 1 END) AS std_8_count
    FROM employ_feedback";

    $result_8 = $con->query($sql_8);

    if ($result_8->num_rows > 0) {
        while($row_8 = $result_8->fetch_array()){
            $sa_8_count = $row_8['sa_8_count'];
            $ag_8_count = $row_8['ag_8_count'];
            $nu_8_count = $row_8['nu_8_count'];
            $dis_8_count = $row_8['dis_8_count'];
            $std_8_count = $row_8['std_8_count'];
            
            $sa_8_percent = ($sa_8_count * 100) / $total_reviews;
            $ag_8_percent = ($ag_8_count * 100) / $total_reviews;
            $nu_8_percent = ($nu_8_count * 100) / $total_reviews;
            $dis_8_percent = ($dis_8_count * 100) / $total_reviews;
            $std_8_percent = ($std_8_count * 100) / $total_reviews;
        }
    }//end if

    //9. The role of the Institution in encouraging sporting activities & good cultures of health.
    $sql_9 = "SELECT 
    COUNT(CASE WHEN ans_9 = 'Strongly Agree' THEN 1 END) AS sa_9_count,
    COUNT(CASE WHEN ans_9 = 'Agree' THEN 1 END) AS ag_9_count,
    COUNT(CASE WHEN ans_9 = 'Neutral' THEN 1 END) AS nu_9_count,
    COUNT(CASE WHEN ans_9 = 'Disagree' THEN 1 END) AS dis_9_count,
    COUNT(CASE WHEN ans_9 = 'Strongly Disagree' THEN 1 END) AS std_9_count
    FROM employ_feedback";

    $result_9 = $con->query($sql_9);

    if ($result_9->num_rows > 0) {
        while($row_9 = $result_9->fetch_array()){
            $sa_9_count = $row_9['sa_9_count'];
            $ag_9_count = $row_9['ag_9_count'];
            $nu_9_count = $row_9['nu_9_count'];
            $dis_9_count = $row_9['dis_9_count'];
            $std_9_count = $row_9['std_9_count'];
            
            $sa_9_percent = ($sa_9_count * 100) / $total_reviews;
            $ag_9_percent = ($ag_9_count * 100) / $total_reviews;
            $nu_9_percent = ($nu_9_count * 100) / $total_reviews;
            $dis_9_percent = ($dis_9_count * 100) / $total_reviews;
            $std_9_percent = ($std_9_count * 100) / $total_reviews;
        }
    }//end if

    //10. The role of the Institution in inculcating skills enhancing employability with social responsibility
    $sql_10 = "SELECT 
    COUNT(CASE WHEN ans_10 = 'Strongly Agree' THEN 1 END) AS sa_10_count,
    COUNT(CASE WHEN ans_10 = 'Agree' THEN 1 END) AS ag_10_count,
    COUNT(CASE WHEN ans_10 = 'Neutral' THEN 1 END) AS nu_10_count,
    COUNT(CASE WHEN ans_10 = 'Disagree' THEN 1 END) AS dis_10_count,
    COUNT(CASE WHEN ans_10 = 'Strongly Disagree' THEN 1 END) AS std_10_count
    FROM employ_feedback";

    $result_10 = $con->query($sql_10);

    if ($result_10->num_rows > 0) {
        while($row_10 = $result_10->fetch_array()){
            $sa_10_count = $row_10['sa_10_count'];
            $ag_10_count = $row_10['ag_10_count'];
            $nu_10_count = $row_10['nu_10_count'];
            $dis_10_count = $row_10['dis_10_count'];
            $std_10_count = $row_10['std_10_count'];
            
            $sa_10_percent = ($sa_10_count * 100) / $total_reviews;
            $ag_10_percent = ($ag_10_count * 100) / $total_reviews;
            $nu_10_percent = ($nu_10_count * 100) / $total_reviews;
            $dis_10_percent = ($dis_10_count * 100) / $total_reviews;
            $std_10_percent = ($std_10_count * 100) / $total_reviews;
        }
    }//end if

    //11. The role of the Central Library of the College in providing comprehensive computerised services.
    $sql_11 = "SELECT 
    COUNT(CASE WHEN ans_11 = 'Strongly Agree' THEN 1 END) AS sa_11_count,
    COUNT(CASE WHEN ans_11 = 'Agree' THEN 1 END) AS ag_11_count,
    COUNT(CASE WHEN ans_11 = 'Neutral' THEN 1 END) AS nu_11_count,
    COUNT(CASE WHEN ans_11 = 'Disagree' THEN 1 END) AS dis_11_count,
    COUNT(CASE WHEN ans_11 = 'Strongly Disagree' THEN 1 END) AS std_11_count
    FROM employ_feedback";

    $result_11 = $con->query($sql_11);

    if ($result_11->num_rows > 0) {
        while($row_11 = $result_11->fetch_array()){
            $sa_11_count = $row_11['sa_11_count'];
            $ag_11_count = $row_11['ag_11_count'];
            $nu_11_count = $row_11['nu_11_count'];
            $dis_11_count = $row_11['dis_11_count'];
            $std_11_count = $row_11['std_11_count'];
            
            $sa_11_percent = ($sa_11_count * 100) / $total_reviews;
            $ag_11_percent = ($ag_11_count * 100) / $total_reviews;
            $nu_11_percent = ($nu_11_count * 100) / $total_reviews;
            $dis_11_percent = ($dis_11_count * 100) / $total_reviews;
            $std_11_percent = ($std_11_count * 100) / $total_reviews;
        }
    }//end if

    //12. Rate the services provided by the College Office.
    $sql_12 = "SELECT 
    COUNT(CASE WHEN ans_12 = 'Strongly Agree' THEN 1 END) AS sa_12_count,
    COUNT(CASE WHEN ans_12 = 'Agree' THEN 1 END) AS ag_12_count,
    COUNT(CASE WHEN ans_12 = 'Neutral' THEN 1 END) AS nu_12_count,
    COUNT(CASE WHEN ans_12 = 'Disagree' THEN 1 END) AS dis_12_count,
    COUNT(CASE WHEN ans_12 = 'Strongly Disagree' THEN 1 END) AS std_12_count
    FROM employ_feedback";

    $result_12 = $con->query($sql_12);

    if ($result_12->num_rows > 0) {
        while($row_12 = $result_12->fetch_array()){
            $sa_12_count = $row_12['sa_12_count'];
            $ag_12_count = $row_12['ag_12_count'];
            $nu_12_count = $row_12['nu_12_count'];
            $dis_12_count = $row_12['dis_12_count'];
            $std_12_count = $row_12['std_12_count'];
            
            $sa_12_percent = ($sa_12_count * 100) / $total_reviews;
            $ag_12_percent = ($ag_12_count * 100) / $total_reviews;
            $nu_12_percent = ($nu_12_count * 100) / $total_reviews;
            $dis_12_percent = ($dis_12_count * 100) / $total_reviews;
            $std_12_percent = ($std_12_count * 100) / $total_reviews;
        }
    }//end if

    //13. Rate Institutional infrastructure for providing Peaceful & Calm Environment.
    $sql_13 = "SELECT 
    COUNT(CASE WHEN ans_13 = 'Strongly Agree' THEN 1 END) AS sa_13_count,
    COUNT(CASE WHEN ans_13 = 'Agree' THEN 1 END) AS ag_13_count,
    COUNT(CASE WHEN ans_13 = 'Neutral' THEN 1 END) AS nu_13_count,
    COUNT(CASE WHEN ans_13 = 'Disagree' THEN 1 END) AS dis_13_count,
    COUNT(CASE WHEN ans_13 = 'Strongly Disagree' THEN 1 END) AS std_13_count
    FROM employ_feedback";

    $result_13 = $con->query($sql_13);

    if ($result_13->num_rows > 0) {
        while($row_13 = $result_13->fetch_array()){
            $sa_13_count = $row_13['sa_13_count'];
            $ag_13_count = $row_13['ag_13_count'];
            $nu_13_count = $row_13['nu_13_count'];
            $dis_13_count = $row_13['dis_13_count'];
            $std_13_count = $row_13['std_13_count'];
            
            $sa_13_percent = ($sa_13_count * 100) / $total_reviews;
            $ag_13_percent = ($ag_13_count * 100) / $total_reviews;
            $nu_13_percent = ($nu_13_count * 100) / $total_reviews;
            $dis_13_percent = ($dis_13_count * 100) / $total_reviews;
            $std_13_percent = ($std_13_count * 100) / $total_reviews;
        }
    }//end if

    //14. Rate the Institutional Standard of Hygiene & Cleanliness.
    $sql_14 = "SELECT 
    COUNT(CASE WHEN ans_14 = 'Strongly Agree' THEN 1 END) AS sa_14_count,
    COUNT(CASE WHEN ans_14 = 'Agree' THEN 1 END) AS ag_14_count,
    COUNT(CASE WHEN ans_14 = 'Neutral' THEN 1 END) AS nu_14_count,
    COUNT(CASE WHEN ans_14 = 'Disagree' THEN 1 END) AS dis_14_count,
    COUNT(CASE WHEN ans_14 = 'Strongly Disagree' THEN 1 END) AS std_14_count
    FROM employ_feedback";

    $result_14 = $con->query($sql_14);

    if ($result_14->num_rows > 0) {
        while($row_14 = $result_14->fetch_array()){
            $sa_14_count = $row_14['sa_14_count'];
            $ag_14_count = $row_14['ag_14_count'];
            $nu_14_count = $row_14['nu_14_count'];
            $dis_14_count = $row_14['dis_14_count'];
            $std_14_count = $row_14['std_14_count'];
            
            $sa_14_percent = ($sa_14_count * 100) / $total_reviews;
            $ag_14_percent = ($ag_14_count * 100) / $total_reviews;
            $nu_14_percent = ($nu_14_count * 100) / $total_reviews;
            $dis_14_percent = ($dis_14_count * 100) / $total_reviews;
            $std_14_percent = ($std_14_count * 100) / $total_reviews;
        }
    }//end if

    //15. The overall quality of teaching-learning experience of the Institution.
    $sql_15 = "SELECT 
    COUNT(CASE WHEN ans_15 = 'Strongly Agree' THEN 1 END) AS sa_15_count,
    COUNT(CASE WHEN ans_15 = 'Agree' THEN 1 END) AS ag_15_count,
    COUNT(CASE WHEN ans_15 = 'Neutral' THEN 1 END) AS nu_15_count,
    COUNT(CASE WHEN ans_15 = 'Disagree' THEN 1 END) AS dis_15_count,
    COUNT(CASE WHEN ans_15 = 'Strongly Disagree' THEN 1 END) AS std_15_count
    FROM employ_feedback";

    $result_15 = $con->query($sql_15);

    if ($result_15->num_rows > 0) {
        while($row_15 = $result_15->fetch_array()){
            $sa_15_count = $row_15['sa_15_count'];
            $ag_15_count = $row_15['ag_15_count'];
            $nu_15_count = $row_15['nu_15_count'];
            $dis_15_count = $row_15['dis_15_count'];
            $std_15_count = $row_15['std_15_count'];
            
            $sa_15_percent = ($sa_15_count * 100) / $total_reviews;
            $ag_15_percent = ($ag_15_count * 100) / $total_reviews;
            $nu_15_percent = ($nu_15_count * 100) / $total_reviews;
            $dis_15_percent = ($dis_15_count * 100) / $total_reviews;
            $std_15_percent = ($std_15_count * 100) / $total_reviews;
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
            <h3 class="text-center">Employers’ Feedback Report</h3>

            <div class="row">
                <div class="col-md-12">
                    <div id="piechart_1"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_2"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_3"></div>
                </div>
                <div class="col-md-12">
                    <div id="piechart_4"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_5"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_6"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_7"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_8"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_9"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_10"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_11"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_12"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_13"></div>
                </div> 
                <div class="col-md-12">
                    <div id="piechart_14"></div>
                </div> 
                <div class="col-md-12 page-break">
                    <div id="piechart_15"></div>
                </div>  
            </div>             
        </div>
    </section>
    </body>
</body>
</html>
<script>
    //window.print();    
</script>
<script type="text/javascript">
    // Load google charts
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() { 
    //1. The role of the Institution in taking active interest in organizing Seminars, Conferences & Workshop
    var data_1 = google.visualization.arrayToDataTable([
    ['Task', 'Task_1'],
    ['Excellent', <?=$exc_1_percent?>],
    ['Good', <?=$goo_1_percent?>],
    ['Satisfactory', <?=$sat_1_percent?>],
    ['Poor', <?=$poo_1_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_1 = {'title':'1. The role of the Institution in taking active interest in organizing Seminars, Conferences & Workshop', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_1 = new google.visualization.PieChart(document.getElementById('piechart_1'));
    chart_1.draw(data_1, options_1);

    //2. The Quality of teaching and mentoring process in the institution facilitates cognitive, social & emotional growth.
    var data_2 = google.visualization.arrayToDataTable([
    ['Task', 'Task_2'],
    ['Excellent', <?=$exc_2_percent?>],
    ['Good', <?=$goo_2_percent?>],
    ['Satisfactory', <?=$sat_2_percent?>],
    ['Poor', <?=$poo_2_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_2 = {'title':'2. The Quality of teaching and mentoring process in the institution facilitates cognitive, social & emotional growth.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_2 = new google.visualization.PieChart(document.getElementById('piechart_2'));
    chart_2.draw(data_2, options_2);

    //3. The performance of the Institution providing opportunities, learning & holistic growth
    var data_3 = google.visualization.arrayToDataTable([
    ['Task', 'Task_3'],
    ['Strongly Agree', <?=$sa_3_percent?>],
    ['Agree', <?=$ag_3_percent?>],
    ['Neutral', <?=$nu_3_percent?>],
    ['Disagree', <?=$dis_3_percent?>],
    ['Strongly Disagree', <?=$std_3_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_3 = {'title':'3. The performance of the Institution providing opportunities, learning & holistic growth', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_3 = new google.visualization.PieChart(document.getElementById('piechart_3'));
    chart_3.draw(data_3, options_3);

    //4. The process undertaken by the Institution in informing stakeholders about expected competencies, course outcomes & programme outcomes.
    var data_4 = google.visualization.arrayToDataTable([
    ['Task', 'Task_4'],
    ['Strongly Agree', <?=$sa_4_percent?>],
    ['Agree', <?=$ag_4_percent?>],
    ['Neutral', <?=$nu_4_percent?>],
    ['Disagree', <?=$dis_4_percent?>],
    ['Strongly Disagree', <?=$std_4_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_4 = {'title':'4. The process undertaken by the Institution in informing stakeholders about expected competencies, course outcomes & programme outcomes.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_4 = new google.visualization.PieChart(document.getElementById('piechart_4'));
    chart_4.draw(data_4, options_4);

    //5. The mechanism provided by the Institution to identify strengths, remedy to overcome weakness in students.
    var data_5 = google.visualization.arrayToDataTable([
    ['Task', 'Task_5'],
    ['Strongly Agree', <?=$sa_5_percent?>],
    ['Agree', <?=$ag_5_percent?>],
    ['Neutral', <?=$nu_5_percent?>],
    ['Disagree', <?=$dis_5_percent?>],
    ['Strongly Disagree', <?=$std_5_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_5 = {'title':'5. The mechanism provided by the Institution to identify strengths, remedy to overcome weakness in students.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_5 = new google.visualization.PieChart(document.getElementById('piechart_5'));
    chart_5.draw(data_5, options_5);

    //6.The role of the Institution in engaging students with monitoring, review & continuous quality improvement of the teaching learning process.
    var data_6 = google.visualization.arrayToDataTable([
    ['Task', 'Task_6'],
    ['Strongly Agree', <?=$sa_6_percent?>],
    ['Agree', <?=$ag_6_percent?>],
    ['Neutral', <?=$nu_6_percent?>],
    ['Disagree', <?=$dis_6_percent?>],
    ['Strongly Disagree', <?=$std_6_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_6 = {'title':'6.The role of the Institution in engaging students with monitoring, review & continuous quality improvement of the teaching learning process.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_6 = new google.visualization.PieChart(document.getElementById('piechart_6'));
    chart_6.draw(data_6, options_6);

    //7. The role of the Institution in encouraging the student-centric methods for enhancing learning experiences.
    var data_7 = google.visualization.arrayToDataTable([
    ['Task', 'Task_7'],
    ['Strongly Agree', <?=$sa_7_percent?>],
    ['Agree', <?=$ag_7_percent?>],
    ['Neutral', <?=$nu_7_percent?>],
    ['Disagree', <?=$dis_7_percent?>],
    ['Strongly Disagree', <?=$std_7_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_7 = {'title':'7. The role of the Institution in encouraging the student-centric methods for enhancing learning experiences.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_7 = new google.visualization.PieChart(document.getElementById('piechart_7'));
    chart_7.draw(data_7, options_7);

    //8. The role of the Institution in encouraging participation to extra-curricular activities.
    var data_8 = google.visualization.arrayToDataTable([
    ['Task', 'Task_8'],
    ['Strongly Agree', <?=$sa_8_percent?>],
    ['Agree', <?=$ag_8_percent?>],
    ['Neutral', <?=$nu_8_percent?>],
    ['Disagree', <?=$dis_8_percent?>],
    ['Strongly Disagree', <?=$std_8_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_8 = {'title':'8. The role of the Institution in encouraging participation to extra-curricular activities.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_8 = new google.visualization.PieChart(document.getElementById('piechart_8'));
    chart_8.draw(data_8, options_8);

    //9. The role of the Institution in encouraging sporting activities & good cultures of health.
    var data_9 = google.visualization.arrayToDataTable([
    ['Task', 'Task_9'],
    ['Strongly Agree', <?=$sa_9_percent?>],
    ['Agree', <?=$ag_9_percent?>],
    ['Neutral', <?=$nu_9_percent?>],
    ['Disagree', <?=$dis_9_percent?>],
    ['Strongly Disagree', <?=$std_9_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_9 = {'title':'9. The role of the Institution in encouraging sporting activities & good cultures of health.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_9 = new google.visualization.PieChart(document.getElementById('piechart_9'));
    chart_9.draw(data_9, options_9);

    //10. The role of the Institution in inculcating skills enhancing employability with social responsibility
    var data_10 = google.visualization.arrayToDataTable([
    ['Task', 'Task_10'],
    ['Strongly Agree', <?=$sa_10_percent?>],
    ['Agree', <?=$ag_10_percent?>],
    ['Neutral', <?=$nu_10_percent?>],
    ['Disagree', <?=$dis_10_percent?>],
    ['Strongly Disagree', <?=$std_10_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_10 = {'title':'10. The role of the Institution in inculcating skills enhancing employability with social responsibility', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_10 = new google.visualization.PieChart(document.getElementById('piechart_10'));
    chart_10.draw(data_10, options_10);

    //11. The role of the Central Library of the College in providing comprehensive computerised services.
    var data_11 = google.visualization.arrayToDataTable([
    ['Task', 'Task_11'],
    ['Strongly Agree', <?=$sa_11_percent?>],
    ['Agree', <?=$ag_11_percent?>],
    ['Neutral', <?=$nu_11_percent?>],
    ['Disagree', <?=$dis_11_percent?>],
    ['Strongly Disagree', <?=$std_11_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_11 = {'title':'11. The role of the Central Library of the College in providing comprehensive computerised services.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_11 = new google.visualization.PieChart(document.getElementById('piechart_11'));
    chart_11.draw(data_11, options_11);

    //12. Rate the services provided by the College Office.
    var data_12 = google.visualization.arrayToDataTable([
    ['Task', 'Task_12'],
    ['Strongly Agree', <?=$sa_12_percent?>],
    ['Agree', <?=$ag_12_percent?>],
    ['Neutral', <?=$nu_12_percent?>],
    ['Disagree', <?=$dis_12_percent?>],
    ['Strongly Disagree', <?=$std_12_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_12 = {'title':'12. Rate the services provided by the College Office.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_12 = new google.visualization.PieChart(document.getElementById('piechart_12'));
    chart_12.draw(data_12, options_12);

    //13. Rate Institutional infrastructure for providing Peaceful & Calm Environment.
    var data_13 = google.visualization.arrayToDataTable([
    ['Task', 'Task_13'],
    ['Strongly Agree', <?=$sa_13_percent?>],
    ['Agree', <?=$ag_13_percent?>],
    ['Neutral', <?=$nu_13_percent?>],
    ['Disagree', <?=$dis_13_percent?>],
    ['Strongly Disagree', <?=$std_13_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_13 = {'title':'13. Rate Institutional infrastructure for providing Peaceful & Calm Environment.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_13 = new google.visualization.PieChart(document.getElementById('piechart_13'));
    chart_13.draw(data_13, options_13);

    //14. Rate the Institutional Standard of Hygiene & Cleanliness.
    var data_14 = google.visualization.arrayToDataTable([
    ['Task', 'Task_14'],
    ['Strongly Agree', <?=$sa_14_percent?>],
    ['Agree', <?=$ag_14_percent?>],
    ['Neutral', <?=$nu_14_percent?>],
    ['Disagree', <?=$dis_14_percent?>],
    ['Strongly Disagree', <?=$std_14_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_14 = {'title':'14. Rate the Institutional Standard of Hygiene & Cleanliness.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_14 = new google.visualization.PieChart(document.getElementById('piechart_14'));
    chart_14.draw(data_14, options_14);

    //15. The overall quality of teaching-learning experience of the Institution.
    var data_15 = google.visualization.arrayToDataTable([
    ['Task', 'Task_15'],
    ['Strongly Agree', <?=$sa_15_percent?>],
    ['Agree', <?=$ag_15_percent?>],
    ['Neutral', <?=$nu_15_percent?>],
    ['Disagree', <?=$dis_15_percent?>],
    ['Strongly Disagree', <?=$std_15_percent?>]  
    ]);
    // Optional; add a title and set the width and height of the chart
    var options_15 = {'title':'15. The overall quality of teaching-learning experience of the Institution.', 'width':550, 'height':400};
    // Display the chart inside the <div> element with id="piechart"
    var chart_15 = new google.visualization.PieChart(document.getElementById('piechart_15'));
    chart_15.draw(data_15, options_15);
        
        

    }//end if
</script>