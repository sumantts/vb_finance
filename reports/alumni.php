<!DOCTYPE html>
<html lang="en">
<head>
<title>Alumni Feedback Report</title>
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

    $sql_0 = "SELECT * FROM alumni_feedback";
    $result_0 = $con->query($sql_0);
    $total_reviews = $result_0->num_rows;    

    //1. Do you feel proud to be an alumnus of Gurudas College?
    $sql_1 = "SELECT 
    COUNT(CASE WHEN ans_1 = 'Yes' THEN 1 END) AS yes_1_count,
    COUNT(CASE WHEN ans_1 = 'No' THEN 1 END) AS no_1_count
    FROM alumni_feedback";

    $result_1 = $con->query($sql_1);

    if ($result_1->num_rows > 0) {
        while($row_1 = $result_1->fetch_array()){
            $yes_1_count = $row_1['yes_1_count'];
            $no_1_count = $row_1['no_1_count']; 
            
            $yes_1_percent = ($yes_1_count * 100) / $total_reviews;
            $no_1_percent = ($no_1_count * 100) / $total_reviews;
        }
    }//end if 
    
    //2. What are the greatest strengths of our College today?
    $all2_ans = '';
    $sql_28 = "SELECT ans_2 FROM alumni_feedback";
    $result_28 = $con->query($sql_28);

    if ($result_28->num_rows > 0) { 
        while($row_28 = $result_28->fetch_array()){
            $ans_2 = $row_28['ans_2'];
            if($ans_2 != ''){
                $all2_ans .= ' <br>&#8594;'.$ans_2;
            } 
        }
    }//end if 
    
    //3. What are the greatest weaknesses of our College today?
    $all3_ans = '';
    $sql_3 = "SELECT ans_3 FROM alumni_feedback";
    $result_3 = $con->query($sql_3);

    if ($result_3->num_rows > 0) { 
        while($row_3 = $result_3->fetch_array()){
            $ans_3 = $row_3['ans_3'];
            if($ans_3 != ''){
                $all3_ans .= ' <br>&#8594;'.$ans_3;
            } 
        }
    }//end if   

    //5. Academic environment.
    $sql_5 = "SELECT 
    COUNT(CASE WHEN ans_5 = 'Unsatisfactory' THEN 1 END) AS uns_5_count,
    COUNT(CASE WHEN ans_5 = 'Satisfactory' THEN 1 END) AS sat_5_count,
    COUNT(CASE WHEN ans_5 = 'Good' THEN 1 END) AS good_5_count,
    COUNT(CASE WHEN ans_5 = 'Very Good' THEN 1 END) AS vgood_5_count,
    COUNT(CASE WHEN ans_5 = 'Excellent' THEN 1 END) AS exc_5_count
    FROM alumni_feedback";

    $result_5 = $con->query($sql_5);

    if ($result_5->num_rows > 0) {
        while($row_5 = $result_5->fetch_array()){
            $uns_5_count = $row_5['uns_5_count'];
            $sat_5_count = $row_5['sat_5_count']; 
            $good_5_count = $row_5['good_5_count']; 
            $vgood_5_count = $row_5['vgood_5_count'];  
            $exc_5_count = $row_5['exc_5_count'];
            
            $uns_5_percent = ($uns_5_count * 100) / $total_reviews;
            $sat_5_percent = ($sat_5_count * 100) / $total_reviews;
            $good_5_percent = ($good_5_count * 100) / $total_reviews;
            $vgood_5_percent = ($vgood_5_count * 100) / $total_reviews;
            $exc_5_percent = ($exc_5_count * 100) / $total_reviews;
        }
    }//end if 

    //6. Knowledge gained from the curriculum
    $sql_6 = "SELECT 
    COUNT(CASE WHEN ans_6 = 'Unsatisfactory' THEN 1 END) AS uns_6_count,
    COUNT(CASE WHEN ans_6 = 'Satisfactory' THEN 1 END) AS sat_6_count,
    COUNT(CASE WHEN ans_6 = 'Good' THEN 1 END) AS good_6_count,
    COUNT(CASE WHEN ans_6 = 'Very Good' THEN 1 END) AS vgood_6_count,
    COUNT(CASE WHEN ans_6 = 'Excellent' THEN 1 END) AS exc_6_count
    FROM alumni_feedback";

    $result_6 = $con->query($sql_6);

    if ($result_6->num_rows > 0) {
        while($row_6 = $result_6->fetch_array()){
            $uns_6_count = $row_6['uns_6_count'];
            $sat_6_count = $row_6['sat_6_count']; 
            $good_6_count = $row_6['good_6_count']; 
            $vgood_6_count = $row_6['vgood_6_count'];  
            $exc_6_count = $row_6['exc_6_count'];
            
            $uns_6_percent = ($uns_6_count * 100) / $total_reviews;
            $sat_6_percent = ($sat_6_count * 100) / $total_reviews;
            $good_6_percent = ($good_6_count * 100) / $total_reviews;
            $vgood_6_percent = ($vgood_6_count * 100) / $total_reviews;
            $exc_6_percent = ($exc_6_count * 100) / $total_reviews;
        }
    }//end if 

    //7. Co-curricular/Extra-curricular life
    $sql_7 = "SELECT 
    COUNT(CASE WHEN ans_7 = 'Unsatisfactory' THEN 1 END) AS uns_7_count,
    COUNT(CASE WHEN ans_7 = 'Satisfactory' THEN 1 END) AS sat_7_count,
    COUNT(CASE WHEN ans_7 = 'Good' THEN 1 END) AS good_7_count,
    COUNT(CASE WHEN ans_7 = 'Very Good' THEN 1 END) AS vgood_7_count,
    COUNT(CASE WHEN ans_7 = 'Excellent' THEN 1 END) AS exc_7_count
    FROM alumni_feedback";

    $result_7 = $con->query($sql_7);

    if ($result_7->num_rows > 0) {
        while($row_7 = $result_7->fetch_array()){
            $uns_7_count = $row_7['uns_7_count'];
            $sat_7_count = $row_7['sat_7_count']; 
            $good_7_count = $row_7['good_7_count']; 
            $vgood_7_count = $row_7['vgood_7_count'];  
            $exc_7_count = $row_7['exc_7_count'];
            
            $uns_7_percent = ($uns_7_count * 100) / $total_reviews;
            $sat_7_percent = ($sat_7_count * 100) / $total_reviews;
            $good_7_percent = ($good_7_count * 100) / $total_reviews;
            $vgood_7_percent = ($vgood_7_count * 100) / $total_reviews;
            $exc_7_percent = ($exc_7_count * 100) / $total_reviews;
        }
    }//end if 

    //8. Faculty caliber
    $sql_8 = "SELECT 
    COUNT(CASE WHEN ans_8 = 'Unsatisfactory' THEN 1 END) AS uns_8_count,
    COUNT(CASE WHEN ans_8 = 'Satisfactory' THEN 1 END) AS sat_8_count,
    COUNT(CASE WHEN ans_8 = 'Good' THEN 1 END) AS good_8_count,
    COUNT(CASE WHEN ans_8 = 'Very Good' THEN 1 END) AS vgood_8_count,
    COUNT(CASE WHEN ans_8 = 'Excellent' THEN 1 END) AS exc_8_count
    FROM alumni_feedback";

    $result_8 = $con->query($sql_8);

    if ($result_8->num_rows > 0) {
        while($row_8 = $result_8->fetch_array()){
            $uns_8_count = $row_8['uns_8_count'];
            $sat_8_count = $row_8['sat_8_count']; 
            $good_8_count = $row_8['good_8_count']; 
            $vgood_8_count = $row_8['vgood_8_count'];  
            $exc_8_count = $row_8['exc_8_count'];
            
            $uns_8_percent = ($uns_8_count * 100) / $total_reviews;
            $sat_8_percent = ($sat_8_count * 100) / $total_reviews;
            $good_8_percent = ($good_8_count * 100) / $total_reviews;
            $vgood_8_percent = ($vgood_8_count * 100) / $total_reviews;
            $exc_8_percent = ($exc_8_count * 100) / $total_reviews;
        }
    }//end if 

    //9. Diversity of Student body
    $sql_9 = "SELECT 
    COUNT(CASE WHEN ans_9 = 'Unsatisfactory' THEN 1 END) AS uns_9_count,
    COUNT(CASE WHEN ans_9 = 'Satisfactory' THEN 1 END) AS sat_9_count,
    COUNT(CASE WHEN ans_9 = 'Good' THEN 1 END) AS good_9_count,
    COUNT(CASE WHEN ans_9 = 'Very Good' THEN 1 END) AS vgood_9_count,
    COUNT(CASE WHEN ans_9 = 'Excellent' THEN 1 END) AS exc_9_count
    FROM alumni_feedback";

    $result_9 = $con->query($sql_9);

    if ($result_9->num_rows > 0) {
        while($row_9 = $result_9->fetch_array()){
            $uns_9_count = $row_9['uns_9_count'];
            $sat_9_count = $row_9['sat_9_count']; 
            $good_9_count = $row_9['good_9_count']; 
            $vgood_9_count = $row_9['vgood_9_count'];  
            $exc_9_count = $row_9['exc_9_count'];
            
            $uns_9_percent = ($uns_9_count * 100) / $total_reviews;
            $sat_9_percent = ($sat_9_count * 100) / $total_reviews;
            $good_9_percent = ($good_9_count * 100) / $total_reviews;
            $vgood_9_percent = ($vgood_9_count * 100) / $total_reviews;
            $exc_9_percent = ($exc_9_count * 100) / $total_reviews;
        }
    }//end if 

    //10. Reputation of the college
    $sql_10 = "SELECT 
    COUNT(CASE WHEN ans_10 = 'Unsatisfactory' THEN 1 END) AS uns_10_count,
    COUNT(CASE WHEN ans_10 = 'Satisfactory' THEN 1 END) AS sat_10_count,
    COUNT(CASE WHEN ans_10 = 'Good' THEN 1 END) AS good_10_count,
    COUNT(CASE WHEN ans_10 = 'Very Good' THEN 1 END) AS vgood_10_count,
    COUNT(CASE WHEN ans_10 = 'Excellent' THEN 1 END) AS exc_10_count
    FROM alumni_feedback";

    $result_10 = $con->query($sql_10);

    if ($result_10->num_rows > 0) {
        while($row_10 = $result_10->fetch_array()){
            $uns_10_count = $row_10['uns_10_count'];
            $sat_10_count = $row_10['sat_10_count']; 
            $good_10_count = $row_10['good_10_count']; 
            $vgood_10_count = $row_10['vgood_10_count'];  
            $exc_10_count = $row_10['exc_10_count'];
            
            $uns_10_percent = ($uns_10_count * 100) / $total_reviews;
            $sat_10_percent = ($sat_10_count * 100) / $total_reviews;
            $good_10_percent = ($good_10_count * 100) / $total_reviews;
            $vgood_10_percent = ($vgood_10_count * 100) / $total_reviews;
            $exc_10_percent = ($exc_10_count * 100) / $total_reviews;
        }
    }//end if 

    //11. Activities organized by the College for students’ overall development
    $sql_11 = "SELECT 
    COUNT(CASE WHEN ans_11 = 'Unsatisfactory' THEN 1 END) AS uns_11_count,
    COUNT(CASE WHEN ans_11 = 'Satisfactory' THEN 1 END) AS sat_11_count,
    COUNT(CASE WHEN ans_11 = 'Good' THEN 1 END) AS good_11_count,
    COUNT(CASE WHEN ans_11 = 'Very Good' THEN 1 END) AS vgood_11_count,
    COUNT(CASE WHEN ans_11 = 'Excellent' THEN 1 END) AS exc_11_count
    FROM alumni_feedback";

    $result_11 = $con->query($sql_11);

    if ($result_11->num_rows > 0) {
        while($row_11 = $result_11->fetch_array()){
            $uns_11_count = $row_11['uns_11_count'];
            $sat_11_count = $row_11['sat_11_count']; 
            $good_11_count = $row_11['good_11_count']; 
            $vgood_11_count = $row_11['vgood_11_count'];  
            $exc_11_count = $row_11['exc_11_count'];
            
            $uns_11_percent = ($uns_11_count * 100) / $total_reviews;
            $sat_11_percent = ($sat_11_count * 100) / $total_reviews;
            $good_11_percent = ($good_11_count * 100) / $total_reviews;
            $vgood_11_percent = ($vgood_11_count * 100) / $total_reviews;
            $exc_11_percent = ($exc_11_count * 100) / $total_reviews;
        }
    }//end if 

    //12. Guidance received for career from the teachers and the Placement Cell
    $sql_12 = "SELECT 
    COUNT(CASE WHEN ans_12 = 'Unsatisfactory' THEN 1 END) AS uns_12_count,
    COUNT(CASE WHEN ans_12 = 'Satisfactory' THEN 1 END) AS sat_12_count,
    COUNT(CASE WHEN ans_12 = 'Good' THEN 1 END) AS good_12_count,
    COUNT(CASE WHEN ans_12 = 'Very Good' THEN 1 END) AS vgood_12_count,
    COUNT(CASE WHEN ans_12 = 'Excellent' THEN 1 END) AS exc_12_count
    FROM alumni_feedback";

    $result_12 = $con->query($sql_12);

    if ($result_12->num_rows > 0) {
        while($row_12 = $result_12->fetch_array()){
            $uns_12_count = $row_12['uns_12_count'];
            $sat_12_count = $row_12['sat_12_count']; 
            $good_12_count = $row_12['good_12_count']; 
            $vgood_12_count = $row_12['vgood_12_count'];  
            $exc_12_count = $row_12['exc_12_count'];
            
            $uns_12_percent = ($uns_12_count * 100) / $total_reviews;
            $sat_12_percent = ($sat_12_count * 100) / $total_reviews;
            $good_12_percent = ($good_12_count * 100) / $total_reviews;
            $vgood_12_percent = ($vgood_12_count * 100) / $total_reviews;
            $exc_12_percent = ($exc_12_count * 100) / $total_reviews;
        }
    }//end if 

    //13. Cooperation from teachers for academic as well as non academic matters
    $sql_13 = "SELECT 
    COUNT(CASE WHEN ans_13 = 'Unsatisfactory' THEN 1 END) AS uns_13_count,
    COUNT(CASE WHEN ans_13 = 'Satisfactory' THEN 1 END) AS sat_13_count,
    COUNT(CASE WHEN ans_13 = 'Good' THEN 1 END) AS good_13_count,
    COUNT(CASE WHEN ans_13 = 'Very Good' THEN 1 END) AS vgood_13_count,
    COUNT(CASE WHEN ans_13 = 'Excellent' THEN 1 END) AS exc_13_count
    FROM alumni_feedback";

    $result_13 = $con->query($sql_13);

    if ($result_13->num_rows > 0) {
        while($row_13 = $result_13->fetch_array()){
            $uns_13_count = $row_13['uns_13_count'];
            $sat_13_count = $row_13['sat_13_count']; 
            $good_13_count = $row_13['good_13_count']; 
            $vgood_13_count = $row_13['vgood_13_count'];  
            $exc_13_count = $row_13['exc_13_count'];
            
            $uns_13_percent = ($uns_13_count * 100) / $total_reviews;
            $sat_13_percent = ($sat_13_count * 100) / $total_reviews;
            $good_13_percent = ($good_13_count * 100) / $total_reviews;
            $vgood_13_percent = ($vgood_13_count * 100) / $total_reviews;
            $exc_13_percent = ($exc_13_count * 100) / $total_reviews;
        }
    }//end if 

    //14. College facilities
    $sql_14 = "SELECT 
    COUNT(CASE WHEN ans_14 = 'Unsatisfactory' THEN 1 END) AS uns_14_count,
    COUNT(CASE WHEN ans_14 = 'Satisfactory' THEN 1 END) AS sat_14_count,
    COUNT(CASE WHEN ans_14 = 'Good' THEN 1 END) AS good_14_count,
    COUNT(CASE WHEN ans_14 = 'Very Good' THEN 1 END) AS vgood_14_count,
    COUNT(CASE WHEN ans_14 = 'Excellent' THEN 1 END) AS exc_14_count
    FROM alumni_feedback";

    $result_14 = $con->query($sql_14);

    if ($result_14->num_rows > 0) {
        while($row_14 = $result_14->fetch_array()){
            $uns_14_count = $row_14['uns_14_count'];
            $sat_14_count = $row_14['sat_14_count']; 
            $good_14_count = $row_14['good_14_count']; 
            $vgood_14_count = $row_14['vgood_14_count'];  
            $exc_14_count = $row_14['exc_14_count'];
            
            $uns_14_percent = ($uns_14_count * 100) / $total_reviews;
            $sat_14_percent = ($sat_14_count * 100) / $total_reviews;
            $good_14_percent = ($good_14_count * 100) / $total_reviews;
            $vgood_14_percent = ($vgood_14_count * 100) / $total_reviews;
            $exc_14_percent = ($exc_14_count * 100) / $total_reviews;
        }
    }//end if     
    
    //15. Do you have any suggestion regarding the curriculum?
    $all15_ans = '';
    $sql_15 = "SELECT ans_15 FROM alumni_feedback";
    $result_15 = $con->query($sql_15);

    if ($result_15->num_rows > 0) { 
        while($row_15 = $result_15->fetch_array()){
            $ans_15 = $row_15['ans_15'];
            if($ans_15 != ''){
                $all15_ans .= ' <br>&#8594;'.$ans_15;
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
            <h3 class="text-center">Alumni Feedback Report</h3>

            <div class="row">
                <div class="col-md-12">
                    <div id="piechart_1"></div>
                </div>
                <div class="row"> 
                    <div class="col-md-12 text-left">
                        <h4>2. What are the greatest strengths of our College today?</h4>
                        <div id="piechart_2"><?=$all2_ans?></div>
                    </div>
                </div> 
                <div class="row"> 
                    <div class="col-md-12 text-left">
                        <h4>3. What are the greatest weaknesses of our College today?</h4>
                        <div id="piechart_3"><?=$all3_ans?></div>
                    </div>
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
                <div class="row"> 
                    <div class="col-md-12 text-left">
                        <h4>15. Do you have any suggestion regarding the curriculum?</h4>
                        <div id="piechart_15"><?=$all15_ans?></div>
                    </div>
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

    function drawChart() { 
        //1. Do you feel proud to be an alumnus of Gurudas College?
        var data_1 = google.visualization.arrayToDataTable([
        ['Task', 'Task_1'],
        ['Yes', <?=$yes_1_percent?>],
        ['No', <?=$no_1_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_1 = {'title':'1. Do you feel proud to be an alumnus of Gurudas College?', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_1 = new google.visualization.PieChart(document.getElementById('piechart_1'));
        chart_1.draw(data_1, options_1); 

        //5. Academic environment.
        var data_5 = google.visualization.arrayToDataTable([
        ['Task', 'Task_5'],
        ['Unsatisfactory', <?=$uns_5_percent?>],
        ['Satisfactory', <?=$sat_5_percent?>],
        ['Good', <?=$good_5_percent?>],
        ['Very Good', <?=$vgood_5_percent?>],
        ['Excellent', <?=$exc_5_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_5 = {'title':'5. Academic environment.', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_5 = new google.visualization.PieChart(document.getElementById('piechart_5'));
        chart_5.draw(data_5, options_5);

        //6. Knowledge gained from the curriculum
        var data_6 = google.visualization.arrayToDataTable([
        ['Task', 'Task_6'],
        ['Unsatisfactory', <?=$uns_6_percent?>],
        ['Satisfactory', <?=$sat_6_percent?>],
        ['Good', <?=$good_6_percent?>],
        ['Very Good', <?=$vgood_6_percent?>],
        ['Excellent', <?=$exc_6_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_6 = {'title':'6. Knowledge gained from the curriculum', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_6 = new google.visualization.PieChart(document.getElementById('piechart_6'));
        chart_6.draw(data_6, options_6);

        //7. Co-curricular/Extra-curricular life
        var data_7 = google.visualization.arrayToDataTable([
        ['Task', 'Task_7'],
        ['Unsatisfactory', <?=$uns_7_percent?>],
        ['Satisfactory', <?=$sat_7_percent?>],
        ['Good', <?=$good_7_percent?>],
        ['Very Good', <?=$vgood_7_percent?>],
        ['Excellent', <?=$exc_7_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_7 = {'title':'7. Co-curricular/Extra-curricular life', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_7 = new google.visualization.PieChart(document.getElementById('piechart_7'));
        chart_7.draw(data_7, options_7);

        //8. Faculty caliber
        var data_8 = google.visualization.arrayToDataTable([
        ['Task', 'Task_8'],
        ['Unsatisfactory', <?=$uns_8_percent?>],
        ['Satisfactory', <?=$sat_8_percent?>],
        ['Good', <?=$good_8_percent?>],
        ['Very Good', <?=$vgood_8_percent?>],
        ['Excellent', <?=$exc_8_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_8 = {'title':'8. Faculty caliber', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_8 = new google.visualization.PieChart(document.getElementById('piechart_8'));
        chart_8.draw(data_8, options_8);

        //9. Diversity of Student body
        var data_9 = google.visualization.arrayToDataTable([
        ['Task', 'Task_9'],
        ['Unsatisfactory', <?=$uns_9_percent?>],
        ['Satisfactory', <?=$sat_9_percent?>],
        ['Good', <?=$good_9_percent?>],
        ['Very Good', <?=$vgood_9_percent?>],
        ['Excellent', <?=$exc_9_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_9 = {'title':'9. Diversity of Student body', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_9 = new google.visualization.PieChart(document.getElementById('piechart_9'));
        chart_9.draw(data_9, options_9);

        //10. Reputation of the college
        var data_10 = google.visualization.arrayToDataTable([
        ['Task', 'Task_10'],
        ['Unsatisfactory', <?=$uns_10_percent?>],
        ['Satisfactory', <?=$sat_10_percent?>],
        ['Good', <?=$good_10_percent?>],
        ['Very Good', <?=$vgood_10_percent?>],
        ['Excellent', <?=$exc_10_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_10 = {'title':'10. Reputation of the college', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_10 = new google.visualization.PieChart(document.getElementById('piechart_10'));
        chart_10.draw(data_10, options_10);

        //11. Activities organized by the College for students’ overall development
        var data_11 = google.visualization.arrayToDataTable([
        ['Task', 'Task_11'],
        ['Unsatisfactory', <?=$uns_11_percent?>],
        ['Satisfactory', <?=$sat_11_percent?>],
        ['Good', <?=$good_11_percent?>],
        ['Very Good', <?=$vgood_11_percent?>],
        ['Excellent', <?=$exc_11_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_11 = {'title':'11. Activities organized by the College for students’ overall development', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_11 = new google.visualization.PieChart(document.getElementById('piechart_11'));
        chart_11.draw(data_11, options_11);

        //12. Guidance received for career from the teachers and the Placement Cell
        var data_12 = google.visualization.arrayToDataTable([
        ['Task', 'Task_12'],
        ['Unsatisfactory', <?=$uns_12_percent?>],
        ['Satisfactory', <?=$sat_12_percent?>],
        ['Good', <?=$good_12_percent?>],
        ['Very Good', <?=$vgood_12_percent?>],
        ['Excellent', <?=$exc_12_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_12 = {'title':'12. Guidance received for career from the teachers and the Placement Cell', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_12 = new google.visualization.PieChart(document.getElementById('piechart_12'));
        chart_12.draw(data_12, options_12);

        //13. Cooperation from teachers for academic as well as non academic matters
        var data_13 = google.visualization.arrayToDataTable([
        ['Task', 'Task_13'],
        ['Unsatisfactory', <?=$uns_13_percent?>],
        ['Satisfactory', <?=$sat_13_percent?>],
        ['Good', <?=$good_13_percent?>],
        ['Very Good', <?=$vgood_13_percent?>],
        ['Excellent', <?=$exc_13_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_13 = {'title':'13. Cooperation from teachers for academic as well as non academic matters', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_13 = new google.visualization.PieChart(document.getElementById('piechart_13'));
        chart_13.draw(data_13, options_13);

        //14. College facilities
        var data_14 = google.visualization.arrayToDataTable([
        ['Task', 'Task_14'],
        ['Unsatisfactory', <?=$uns_14_percent?>],
        ['Satisfactory', <?=$sat_14_percent?>],
        ['Good', <?=$good_14_percent?>],
        ['Very Good', <?=$vgood_14_percent?>],
        ['Excellent', <?=$exc_14_percent?>] 
        ]);
        // Optional; add a title and set the width and height of the chart
        var options_14 = {'title':'14. College facilities', 'width':550, 'height':400};
        // Display the chart inside the <div> element with id="piechart"
        var chart_14 = new google.visualization.PieChart(document.getElementById('piechart_14'));
        chart_14.draw(data_14, options_14);
        
        

    }//end if
</script>