
    <!-- Fullscreen Modal -->
     <style>
        .amount{
        text-align:right;
        font-weight:500;
        }
     </style>
    <div class="modal fade" id="scrollable-modal" tabindex="-1" aria-labelledby="scrollable-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scrollable-modalLabel">Transactin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-centered mb-0">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Date</th>
                            <th>trans_date</th>
                            <th class="amount">Withdrawal Amt.</th>
                            <th class="amount">Deposit Amt.</th>
                        </tr>
                    </thead>
                    <tbody id="trans_details">
                        <!-- <tr>
                            <td>ASOS Ridley High Waist</td>
                            <td>$79.49</td>
                            <td><span class="badge bg-primary">82 Pcs</span></td>
                            <td>$6,518.18</td>
                        </tr> -->
                    </tbody>
                </table>    
                
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal">Close</a>
                    <!-- <button type="button" class="btn btn-primary">Save Changes</button> -->
                </div>
            </div>
        </div>
    </div>



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

        // View Transaction Details
        function viewDetailsTrans(c_id, sub_c_id){
            console.log('c_id: ' + c_id + ' sub_c_id: ' + sub_c_id);                      
            $.ajax({
                type: "POST",
                url: "trial_balance/function.php",
                dataType: "json",
                data: { fn: "viewDetailsTrans", category_id: c_id, sub_category_id: sub_c_id}
            })
            .done(function( res ) { 
                if(res.status == true){
                    $transactions = res.transactions;
                    if($transactions.length > 0){
                        $('#scrollable-modal').modal('show'); 
                        
                        $("#trans_details").html(''); 
                        $tr_txt = '';
                        $sl = 1;
                        $total_depo = 0;
                        $total_wid = 0;
                        for($t = 0; $t < $transactions.length; $t++){
                            $tr_txt += '<tr>';
                                $tr_txt += '<td>'+$sl+'</td>';                                 
                                $tr_txt += '<td>'+$transactions[$t].trans_date+'</td>';
                                $tr_txt += '<td>'+$transactions[$t].narration+'</td>';
                                $tr_txt += '<td class="amount">'+$transactions[$t].withdrawal_amount+'</td>';
                                $tr_txt += '<td class="amount">'+$transactions[$t].deposit_amount+'</td>';
                            $tr_txt += '</tr>';
                            $sl++;
                            $total_depo = parseFloat($total_depo) + parseFloat($transactions[$t].deposit_amount);
                            $total_wid = parseFloat($total_wid) + parseFloat($transactions[$t].withdrawal_amount);
                        }

                        $tr_txt += '<tr>';
                            $tr_txt += '<td colspan="3">Total</td>';
                            $tr_txt += '<td class="amount">'+$total_wid.toFixed(2)+'</td>';
                            $tr_txt += '<td class="amount">'+$total_depo.toFixed(2)+'</td>';
                        $tr_txt += '</tr>';
                        $("#trans_details").html($tr_txt);
                    }else{
                        alert('Sorry! No transaction available');
                    }
                }else{
                    alert('Sorry! No transaction available');
                }
            });//end ajax 
        }//end 

        $(document).ready(function () { 
            populateCompanyDD(); 
            populateAcYearDD(); 
        });
    </script>