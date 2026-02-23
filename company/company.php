<?php 
include('common/header_dt1.php');  
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

                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="buttons-table-preview">
                                            <table id="datatable-buttons" class="table table-sm dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>company Name</th>  
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
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div data-simplebar class="h-100">
                <div class="card mb-0 p-3">
                    <h5 class="mt-0 font-16 fw-bold mb-3">All the(*) Fields are Required</h5>
                    <div class="row">
                        <div class="col-12">                            
                            <form id="myForm" name="myForm" action="#" method="POST">
                                <div class="mb-2">
                                    <label for="category_name" class="form-label text-danger">Category Name*</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name" aria-describedby="category_name" required>
                                </div> 
                                <div class="mb-2">
                                    <label for="parent_c_id" class="form-label">Parent Category Name</label>
                                    <select class="form-select" id="parent_c_id" name="parent_c_id" aria-describedby="parent_c_id">
                                    </select>
                                </div>  

                                <div class="mb-2 d-block" id="div_nature">
                                    <label for="nature" class="form-label">Nature</label>
                                    <select class="form-select" id="nature" name="nature">
                                        <option value='0'>Select</option>
                                        <option value='1'>Income</option>
                                        <option value='2'>Expense</option>
                                    </select>
                                </div>   

                                <div class="mb-2 d-block" id="div_plbs">
                                    <label for="part_of_plbs" class="form-label">Part of (P/L or B/S)</label>
                                    <select class="form-select" id="part_of_plbs" name="part_of_plbs">
                                        <option value='0'>Select</option>
                                        <option value='1'>Profit and Loss</option>
                                        <option value='2'>Balance Sheet</option>
                                    </select>
                                </div> 

                                <div class="mb-2 d-none" id="div_opn_bal">
                                    <label for="opening_balance" class="form-label">Opening Balance</label>
                                    <input type="text" class="form-control" id="opening_balance" name="opening_balance">
                                </div> 
                                
                                <div class="mb-3">
                                    <small id="message_text" class="d-none form-text text-muted"> </small>   
                                </div> 
                                    <input type="hidden" name="serial_no" id="serial_no" value="0">
                                    <button type="submit" class="btn btn-primary" id="submitForm">Save</button>                            
                                    <button type="button" class="btn btn-dark" id="cancelForm">Clear</button>
                            </form>
                        </div> 
                    </div>  
                </div>
            </div>
        </div>         
    </div>  
    <!-- Theme Settings Right Panel -->

    <!-- Footer JS Start -->
    <?php include('common/footer_js_dt1.php'); ?>        
    <!-- end Footer JS -->
    
    <script src="company/function.js?d=<?=date('Ymdhis')?>"></script>

</body> 
</html>