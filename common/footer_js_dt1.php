

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

        function selectCompany(co_id, company_name){
            console.log('co_id: '+co_id+' company_name: '+ company_name);
            
            $.ajax({
                type: "POST",
                url: "company/function.php",
                dataType: "json",
                data: { fn: "updateSelectedCompany", co_id_key: co_id}
            })
            .done(function( res ) { 
                //console.log(JSON.stringify(res));
                if(res.status == true){
                    location.reload();
                    populateCompanyDD();                     
                }  
            });//end ajax 

        }//end fun

        $(document).ready(function () { 
            populateCompanyDD(); 
        });
    </script>