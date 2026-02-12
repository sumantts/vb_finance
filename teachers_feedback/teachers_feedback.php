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
                                        <div class="col-md-2"> <a href="./reports/teachers.php" target="_blank">PDF Report</a></div>
                                        <div class="col-md-2"> <a href="./reports/teachers_csv.php" target="_blank">Export CSV</a></div>
                                     </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="buttons-table-preview">
                                            <table id="datatable-buttons" class="table table-sm dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Teacher Name</th>
                                                        <th>Contact Number</th>   
                                                        <th>Review Date & Time</th> 
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
                                        <label class="form-label">Question 1. The depth of the course content is adequate to have significant learning outcomes</label>
                                        <div id="ans_1">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 2. Coverage of the syllabus is possible in the stipulated time</label>
                                        <div id="ans_2">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 3. The units/sections in the syllabus are properly sequenced</label>
                                        <div id="ans_3">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 4. recommended textbooks and learning resources are adequate, updated and map onto the syllabus</label>
                                        <div id="ans_4">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 5. Sufficient reference material and books are available for the topics mentioned in the syllabus</label>
                                        <div id="ans_5">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 6.The programme and curriculum are enriched as compared to similar programmes offered in other universities</label>
                                        <div id="ans_6">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 7. the designed experiments stimulate the interest of students in the subject and deepen their understanding through relating theory to practice (Experiential Learning)</label>
                                        <div id="ans_7">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 8. The syllabus for practical enables students to develop experimental, problem solving and analytic skills</label>
                                        <div id="ans_8">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 9. The courses/syllabi taught have a good balance between theory and application</label>
                                        <div id="ans_9">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 10. The objectives of the syllabi are well defined</label>
                                        <div id="ans_10">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 11. The course/syllabi of the subjects increase interest, knowledge and perspective in the subject area</label>
                                        <div id="ans_11">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 12. The college has given full freedom to adopt new techniques/strategies of teaching such as group discussions, seminar presentations and learners’ participation</label>
                                        <div id="ans_12">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 13. The college has given full freedom viz. to adopt new techniques/strategies of testing and assessment of students</label>
                                        <div id="ans_13">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 14. Tests and examinations are conducted well in time with proper coverage of all units in the syllabus</label>
                                        <div id="ans_14">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 15. The prescribed books and learning resources are available in the library in sufficient numbers</label>
                                        <div id="ans_15">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 16. The environment in the College is conducive to teaching and research</label>
                                        <div id="ans_16">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 17. The administration is Teacher-friendly</label>
                                        <div id="ans_17">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 18. The college provides adequate opportunities and support to faculty members for upgrading their skills and qualifications</label>
                                        <div id="ans_18">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 19. ICT facilities in the college are adequate and satisfactory</label>
                                        <div id="ans_19">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 20. Canteen is available for teachers</label>
                                        <div id="ans_20">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 21. Toilets / Washrooms are clean and Properly maintained</label>
                                        <div id="ans_21">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 22. The classrooms are clean and well maintained</label>
                                        <div id="ans_22">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 23. What suggestions: would you give for the betterment of your institution?</label>
                                        <div id="ans_23">Feedback:  </div>
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
    
    <script src="teachers_feedback/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>