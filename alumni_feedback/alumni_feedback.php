<?php 
include('common/header_dt.php');  
if(!$_SESSION['login_id'] || $_SESSION['login_id'] == ''){header("location: ?p=signin");}
?>

<body>
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
                                    <div class="col-md-2"> <a href="./reports/alumni.php" target="_blank">PDF Report</a></div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="buttons-table-preview">
                                            <table id="datatable-buttons" class="table table-sm dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Employee Name</th>
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
                                        <label class="form-label">Name</label>
                                        <div id="alumni_name">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Date of Birth</label>
                                        <div id="alumni_dob">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Year of passing</label>
                                        <div id="passout_year">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Department & Course</label>
                                        <div id="dept_course">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Address</label>
                                        <div id="alumni_address">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Phone Number</label>
                                        <div id="alumni_phone">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Email ID</label>
                                        <div id="alumni_email">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Present Occupation</label>
                                        <div id="alumni_occupation">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 1. Do you feel proud to be an alumnus of Gurudas College?</label>
                                        <div id="ans_1">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 2. What are the greatest strengths of our College today?</label>
                                        <div id="ans_2">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 3. What are the greatest weaknesses of our College today?</label>
                                        <div id="ans_3">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 4. How are you most motivated to "stay connected"with the College? Check all that apply.</label>
                                        <div id="motivated_connected">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <h5>As an alumnus, please rate each of the following to the best of your knowledge for the College as it is today.</h5>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 5. Academic environment</label>
                                        <div id="ans_5">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 6. Knowledge gained from the curriculum</label>
                                        <div id="ans_6">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 7. Co-curricular/Extra-curricular life</label>
                                        <div id="ans_7">Feedback:  </div>
                                        <hr>
                                    </div> 
                                    <div class="col-md-12">
                                        <label class="form-label">Question 8. Faculty caliber</label>
                                        <div id="ans_8">Feedback:  </div>
                                        <hr>
                                    </div>  
                                    <div class="col-md-12">
                                        <label class="form-label">Question 9. Diversity of Student body</label>
                                        <div id="ans_9">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 10. Reputation of the college</label>
                                        <div id="ans_10">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 11. Activities organized by the College for students’ overall development</label>
                                        <div id="ans_11">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 12. Guidance received for career from the teachers and the Placement Cell</label>
                                        <div id="ans_12">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 13. Cooperation from teachers for academic as well as non academic matters</label>
                                        <div id="ans_13">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 14. College facilities</label>
                                        <div id="ans_14">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 15. Do you have any suggestion regarding the curriculum?</label>
                                        <div id="ans_15">Feedback:  </div>
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
    
    <script src="alumni_feedback/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>