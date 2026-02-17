<?php
include('common/header_dt1.php')
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
                                    <!-- <h4 class="header-title">Basic Data Table</h4> -->
                                     
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="basic-datatable-preview">
                                        
                                        <div class="table-responsive">
                                        <table id="scroll-horizontal-datatable" class="table dt-responsive nowrap w-100">
                                            <thead>
                                                <tr> 
                                                        <th>SL#</th>
                                                        <th>Client Name</th>
                                                        <th>Address</th>  
                                                        <th>State</th>  
                                                        <th>Pincode</th>  
                                                        <th>Contact No</th>
                                                        <th>PAN Number</th>  
                                                        <th>Email ID</th>  
                                                         
                                                        <th>KYC Verified</th>  
                                                        <th>Plan Subscribed</th>  
                                                        <th>Date of Subscription</th>  
                                                        <th>Transaction ID</th>  
                                                        <th>Plan Duration (Months)</th>  
                                                        <th>Subscription End Date</th>  
                                                        <th>Payment Made Taxable Amount</th>  
                                                        <th>IGST</th>  
                                                        <th>CGST</th>  
                                                        <th>SGST</th>  
                                                         
                                                        <th>Total GST</th>  
                                                        <th>Total Payment</th>  
                                                        <th>Invoice Number</th>  
                                                        <th>Payment Gateway</th>  
                                                        <th>HSH Code</th>  
                                                        <th>Gateway Charges</th>  
                                                        <th>GST on Charges</th>  
                                                        <th>Total Charges</th>   
                                                        <!-- <th>Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                        </div>

                                        </div> <!-- end preview-->
                                        

                                    </div> <!-- end tab-content-->

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div> <!-- end row--> 
                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Theme Settings Right Panel -->     
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
                                    <form id="myForm2" name="myForm2" action="sales_data/sales_csv_importer.php" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Sales Master CSV File*</label>
                                                <input type="file" name="file" accept=".csv" class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small id="emailHelp" class="d-none form-text text-muted">Data saved successfully</small>   
                                        </div>                            
                                        <button type="submit" class="btn btn-primary" name="Import">Import CSV</button>  
                                        <input type="hidden" name="serial_no" id="serial_no" value="0">                          
                                        <button type="button" class="btn btn-dark" id="cancelForm">Clear</button> 
                                    </form>
                                </div> 
                            </div>  
                        </div>
                    </div>
                </div>         
            </div>  
            <!-- Theme Settings Right Panel -->

            <!-- Footer Start -->
            <?php include('common/footer.php'); ?>    
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->
     

    <!-- Footer JS Start -->
    <?php include('common/footer_js_dt1.php'); ?>        
    <!-- end Footer JS -->
     <script src="sales_data/function.js?d=<?=date('Ymdhis')?>"></script>
     

</body> 
</html>