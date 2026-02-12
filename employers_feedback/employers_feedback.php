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
                                    <div class="col-md-2"> <a href="./reports/employers.php" target="_blank">PDF Report</a></div>
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
                                        <label class="form-label">Question 1. The role of the Institution in taking active interest in organizing Seminars, Conferences & Workshop</label>
                                        <div id="ans_1">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 2. The Quality of teaching and mentoring process in the institution facilitates cognitive, social & emotional growth.</label>
                                        <div id="ans_2">Feedback: </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 3. The performance of the Institution providing opportunities, learning & holistic growth</label>
                                        <div id="ans_3">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 4. The process undertaken by the Institution in informing stakeholders about expected competencies, course outcomes & programme outcomes.</label>
                                        <div id="ans_4">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 5. The mechanism provided by the Institution to identify strengths, remedy to overcome weakness in students.</label>
                                        <div id="ans_5">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 6.The role of the Institution in engaging students with monitoring, review & continuous quality improvement of the teaching learning process.</label>
                                        <div id="ans_6">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 7. The role of the Institution in encouraging the student-centric methods for enhancing learning experiences.</label>
                                        <div id="ans_7">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 8. The role of the Institution in encouraging participation to extra-curricular activities.</label>
                                        <div id="ans_8">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 9. The role of the Institution in encouraging sporting activities & good cultures of health.</label>
                                        <div id="ans_9">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 10. The role of the Institution in inculcating skills enhancing employability with social responsibility</label>
                                        <div id="ans_10">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 11. The role of the Central Library of the College in providing comprehensive computerised services.</label>
                                        <div id="ans_11">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 12. Rate the services provided by the College Office.</label>
                                        <div id="ans_12">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 13. Rate Institutional infrastructure for providing Peaceful & Calm Environment.</label>
                                        <div id="ans_13">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 14. Rate the Institutional Standard of Hygiene & Cleanliness.</label>
                                        <div id="ans_14">Feedback:  </div>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Question 15. The overall quality of teaching-learning experience of the Institution.</label>
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
    
    <script src="employers_feedback/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>