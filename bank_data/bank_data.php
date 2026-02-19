<?php 
include('common/header_dt1.php');  
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
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
                                     <!-- <div class="row">
                                        <div class="col-md-2"> <a href="./reports/student.php" target="_blank">PDF Report</a></div>
                                        <div class="col-md-2"> <a href="./reports/student_csv.php" target="_blank">Export CSV</a></div>
                                     </div> -->
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="buttons-table-preview">
                                            <div class="table-responsive">
                                                <table id="datatable-buttons" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>SL#</th>
                                                            <th>Date</th>
                                                            <th>Narration</th>  
                                                            <th>Chq./Ref. No</th>  
                                                            <th>Value Date</th>  
                                                            <th>Withdrawal Amt.</th>
                                                            <th>Deposit Amt.</th> 
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> 
                                                    </tbody>
                                                </table>
                                            </div>
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

    <!-- Excel File Upload Panel -->     
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas">
        <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
            <h5 class="text-white m-0"><?=$title?></h5>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close" id="modalCloser"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="card mb-0 p-3"> 
                    <div class="row">
                        <div class="col-12">                            
                            <form id="myForm2" name="myForm2" action="bank_data/bank_csv_importer.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Bank Master CSV File*</label>
                                        <input type="file" name="file" accept=".csv" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <small id="emailHelp" class="d-none form-text text-muted">Data saved successfully</small>   
                                </div>                            
                                <button type="submit" class="btn btn-primary" name="Import">Import CSV</button>           
                                <button type="button" class="btn btn-dark" id="cancelForm">Clear</button> 
                            </form>
                        </div> 
                    </div>  
                </div>
            </div>
        </div>         
    </div>  
    <!-- Excel File Upload Panel -->

    

    <!-- Content Edit Panel -->     
    <div class="offcanvas offcanvas-end" tabindex="-1" id="theme-settings-offcanvas1">
        <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
            <h5 class="text-white m-0"><?=$title?></h5>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="card mb-0 p-3">
                    <h5 class="mt-0 font-16 fw-bold mb-2">All the(*) Fields are Required</h5>
                    <div class="row">
                        <div class="col-12">                            
                            <form id="myForm1" name="myForm1" action="#" method="POST"> 
                                <div class="mb-2">
                                    <label for="parent_c_id" class="form-label">Parent Category Name</label>
                                    <select class="form-select" id="parent_c_id" name="parent_c_id">
                                        <option value='0'>Select</option>
                                    </select>
                                </div> 
                                <div class="mb-2">
                                    <label for="sub_c_id" class="form-label">Sub Category Name</label>
                                    <select class="form-select" id="sub_c_id" name="sub_c_id">
                                        <option value='0'>Select</option>
                                    </select>
                                </div> 
                                
                                <div class="mb-2">
                                    <small id="message_text" class="d-none form-text text-muted"> </small>   
                                </div> 
                                    <input type="hidden" name="serial_no" id="serial_no" value="0">
                                    <button type="submit" class="btn btn-primary" id="submitForm1">Save</button>                            
                                    <button type="button" class="btn btn-dark" id="cancelForm1">Clear</button>
                            </form>
                        </div> 
                    </div>  
                </div>
            </div>
        </div>         
    </div>  
    <!-- Content Edit Panel -->

    <!-- Footer JS Start -->
    <?php include('common/footer_js_dt1.php'); ?>        
    <!-- end Footer JS -->
    
    <script src="bank_data/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>