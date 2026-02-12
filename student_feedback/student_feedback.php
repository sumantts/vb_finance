<?php 
include('common/header_dt.php');  
if(!$_SESSION['login_id'] || $_SESSION['login_id'] == ''){header("location: ?p=signin");}
?>

<body>
    <style>
        div.dt-buttons {
            position: initial;
            display: none;
        }
    </style>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- ========== Topbar Start ========== -->
        <?php include('common/top_bar.php'); ?>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('common/left_menu.php'); ?>      
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Feedback</a></li>
                                        <li class="breadcrumb-item active"><?=$title?></li>
                                    </ol>
                                </div>
                                <h4 class="page-title"><?=$title?></h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title --> 


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body"> 
                                    <!-- end nav-->
                                     <div class="row">
                                        <div class="col-md-2"> <a href="./reports/student.php" target="_blank">PDF Report</a></div>
                                        <div class="col-md-2"> <a href="./reports/student_csv.php" target="_blank">Export CSV</a></div>
                                     </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="buttons-table-preview">
                                            <table id="datatable-buttons" class="table table-sm dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Student Name</th>
                                                        <th>Contact Number</th>  
                                                        <th>Age</th>  
                                                        <th>Gender</th>  
                                                        <th>Feedback date & Time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                </tbody>
                                            </table>
                                        </div> <!-- end preview-->
                                        
                                        
                                        <!-- end preview code-->
                                    </div> <!-- end tab-content-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div> <!-- end row-->
                    
                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('common/footer.php'); ?>     
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings Right Panel -->     
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
            <h5 class="text-white m-0"><?=$title?></h5>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close" id="modalCloser"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="card mb-0 p-3">
                    <h5 class="mt-0 font-16 fw-bold mb-3" id="welcome_text">Feedback Details of </h5>
                    <div class="row">
                        <div class="col-12">                            
                            <form id="myForm" name="myForm" onsubmit="return validateForm()">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Question 1. Age</label>
                                        <div id="ans_1">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 2. Gender</label>
                                        <div id="ans_2">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 3. Degree programme</label>
                                        <div id="ans_3">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 4. Examination system </label>
                                        <div id="ans_4">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 5. Subject area currently pursuing</label>
                                        <div id="ans_5">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 6. How well are the teachers prepare for class? </label>
                                        <div id="ans_6">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 7. Extent of the syllabus covered in the class</label>
                                        <div id="ans_7">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 8. How well are the teachers able to communicate?</label>
                                        <div id="ans_8">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 9. The teachers’ approach to teaching can be best described as</label>
                                        <div id="ans_9">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 10. The teachers illustrate the concepts through examples and applications</label>
                                        <div id="ans_10">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 11. Fairness of the internal evaluation process by the teachers</label>
                                        <div id="ans_11">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 12. Performance in assignments discussed with students</label>
                                        <div id="ans_12">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 13. Teachers inform about expected competencies, course outcomes and programme outcomes</label>
                                        <div id="ans_13">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 14. Mentor does a necessary follow-up with an assigned task</label>
                                        <div id="ans_14">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 15. The teachers identify strengths and encourage the students by providing the right level of challenges</label>
                                        <div id="ans_15">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 16. Teachers can identify the weaknesses of students and help them to overcome</label>
                                        <div id="ans_16">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 17. The institute / teachers use student-centric methods, such as experiential learning, participatuve learning and problem-solving methodologies for enhancing learning experiences:</label>
                                        <div id="ans_17">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 18. Teachers encourage the students to participate in extracurricular activities</label>
                                        <div id="ans_18">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 19. Efforts are made by the institute/ teachers to inculcate soft skills, life skills and employability to make the students ready for the world of work</label>
                                        <div id="ans_19">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 20. The institution makes effort to engage students in the monitoring, review and continuous quality improvement of the teaching learning process</label>
                                        <div id="ans_20">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 21. The percentage of teachers using ICT tools such as LCD projector, Multimedia, etc. while teaching</label>
                                        <div id="ans_21">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 22. The overall quality of education in your college is very good</label>
                                        <div id="ans_22">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 23. The institute takes an active part in promoting internship, student exchange, field visit opportunities for students</label>
                                        <div id="ans_23">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 24. The institution provides multiple opportunities to learn and grow</label>
                                        <div id="ans_24">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 25. Do you get your required documents from College Library?</label>
                                        <div id="ans_25">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 26. Are the Librarian and Library Staff helpful in seeking required
                                        documents or in other matters?</label>
                                        <div id="ans_26">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 27. Overall experience from Library</label>
                                        <div id="ans_27">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 28. What suggestion(s) would you give for the betterment of your institution?</label>
                                        <div id="ans_28">Feedback:  </div>
                                        <hr>
                                    </div>   
                                    
                                </div>
                                
                                <!-- <div class="mb-3">
                                    <small id="emailHelp" class="d-none form-text text-muted">Data saved successfully</small>   
                                </div>                            
                                <button type="submit" class="btn btn-primary" id="submitForm">Submit</button>  
                                <input type="hidden" name="serial_no" id="serial_no" value="0">                          
                                <button type="button" class="btn btn-dark" id="cancelForm">Clear</button>  -->
                            </form>
                        </div> 
                    </div>  
                </div>
            </div>
        </div>         
    </div>  
    <!-- Theme Settings Right Panel -->

    <!-- Footer JS Start -->
    <?php include('common/footer_js_dt.php'); ?>        
    <!-- end Footer JS -->
    
    <script src="student_feedback/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>