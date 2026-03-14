

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Code Highlight js -->
    <script src="assets/vendor/highlightjs/highlight.pack.min.js"></script>
    <script src="assets/vendor/clipboard/clipboard.min.js"></script>
    <script src="assets/js/hyper-syntax.js"></script>

    <!-- Datatables js -->
     
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script> -->

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Datatable Demo Aapp js -->
    <script src="assets/js/pages/demo.datatable-init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    <script>
        function resetFormdata(){    
            $('#serial_no').val('');
            $('#myForm').trigger('reset');
        }

        function populateCompanyDD(){
            $text = '';
            $.ajax({
                type: "POST",
                url: "company/function.php",
                dataType: "json",
                data: { fn: "populateCompanyDD"}
            })
            .done(function( res ) { 
                //console.log(JSON.stringify(res));
                if(res.status == true){
                    $companies = res.data;
                    
                    if($companies.length > 0){
                        $sl = 1;
                        $s_company_name = '';
                        for($c = 0; $c < $companies.length; $c++){ 
                            $text += '<a href="javascript:void(0);" class="dropdown-item" onClick="selectCompany(\''+$companies[$c].co_id+'\',\''+$companies[$c].company_name+'\')">';
                                $text += '<span class="align-middle">'+$companies[$c].company_name+'</span>';
                            $text += '</a>';
                            $sl++;

                            if($companies[$c].last_selected == '1'){
                                $s_company_name = $companies[$c].company_name;
                            }
                        } 
                        $('#company_list').html($text);
                        $('#selected_company').html($s_company_name);
                    }    
                }  
            });//end ajax 
        }//end fun

        function populateAcYearDD(){
            $text_acyr = '';
            $.ajax({
                type: "POST",
                url: "company/function.php",
                dataType: "json",
                data: { fn: "populateAcYearDD"}
            })
            .done(function( res ) { 
                //console.log(JSON.stringify(res));
                if(res.status == true){
                    $ac_years = res.data;
                    
                    if($ac_years.length > 0){
                        $sl = 1;
                        $s_ac_year_name = '';
                        for($c = 0; $c < $ac_years.length; $c++){ 
                            $text_acyr += '<a href="javascript:void(0);" class="dropdown-item" onClick="selectAcYear(\''+$ac_years[$c].ac_year+'\',\''+$ac_years[$c].ac_year_name+'\')">';
                                $text_acyr += '<span class="align-middle">'+$ac_years[$c].ac_year_name+'</span>';
                            $text_acyr += '</a>';
                            $sl++;

                            if($ac_years[$c].last_selected == '1'){
                                $s_ac_year_name = $ac_years[$c].ac_year_name;
                            }
                        } 
                        $('#accounting_year_list').html($text_acyr);
                        $('#selected_ac_yr').html($s_ac_year_name);
                    }    
                }  
            });//end ajax 
        }//end fun

        //Company Dropdown
        function selectCompany(co_id, company_name){            
            $.ajax({
                type: "POST",
                url: "company/function.php",
                dataType: "json",
                data: { fn: "updateSelectedCompany", co_id_key: co_id}
            })
            .done(function( res ) { 
                if(res.status == true){
                    location.reload();
                    populateCompanyDD();                     
                }  
            });//end ajax 
        }//end fun

        // Accounting Year
        function selectAcYear(ac_year, ac_year_name){            
            $.ajax({
                type: "POST",
                url: "company/function.php",
                dataType: "json",
                data: { fn: "updateSelectedAcYr", ac_year_id: ac_year}
            })
            .done(function( res ) { 
                if(res.status == true){
                    location.reload();
                    populateAcYearDD();                     
                }  
            });//end ajax 
        }//end fun

        $(document).ready(function () { 
            populateCompanyDD(); 
            populateAcYearDD(); 
        });
    </script>