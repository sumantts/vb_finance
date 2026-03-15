
$('#myForm2').on('submit', function(){ 
    $from_date = $('#from_date').val(); 
    $to_date = $('#to_date').val(); 
    $messageId = 'message_text1'; 

    $table_body = '';
    // Call Svc
    $.ajax({
        type: "POST",
        url: "trial_balance/function.php",
        dataType: "json",
        data: { fn: "saveFormData2", from_date: $from_date, to_date: $to_date}
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){ 
            $all_categories = res.all_categories; 
            $total_debit_balance = res.total_debit_balance; 
            $total_credit_balance = res.total_credit_balance; 

            if(($all_categories.length) > 0 && (parseFloat($total_debit_balance) > 0 || parseFloat($total_credit_balance) > 0)){
                for($i = 0; $i < $all_categories.length; $i++){
                    $c_credit_balance = $all_categories[$i].credit_balance;
                    $c_debit_balance = $all_categories[$i].debit_balance; 
                    $category_name = $all_categories[$i].category_name;  
                    $nature = $all_categories[$i].nature;  
                    $sub_categories = $all_categories[$i].sub_categories; 

                    $table_body += '<tr class="parent-row">';
                        $table_body += '<td>';
                            $table_body += '<div class="ledger-name">';
                            $table_body += '<span class="expand-btn"></span>'+$category_name+'</div>';
                        $table_body += '</td>';

                        if(parseFloat($c_debit_balance) > 0){
                            $table_body += '<td>'+$c_debit_balance.toFixed(2)+'</td>';
                        }else{
                            $table_body += '<td>&nbsp;</td>';
                        }                        
                            
                        if(parseFloat($c_credit_balance) > 0){
                            $table_body += '<td>'+$c_credit_balance.toFixed(2)+'</td>';
                        }else{
                            $table_body += '<td>&nbsp;</td>';
                        } 
                    $table_body += '</tr>';

                    
                        
                        //sub categories  
                        if($sub_categories.length > 0){
                            for($j = 0; $j < $sub_categories.length; $j++){
                                $sub_category_name = $sub_categories[$j].sub_category_name;
                                $sub_c_debit_balance = $sub_categories[$j].debit_balance;
                                $sub_c_credit_balance = $sub_categories[$j].credit_balance;
                                
                                if(parseFloat($sub_c_debit_balance) > 0 || parseFloat($sub_c_credit_balance) > 0){                                    
                                    $table_body += '<tr class="child-row">';
                                        $table_body += '<td>'+$sub_category_name+'</td>';
                                        
                                        if(parseFloat($sub_c_debit_balance) > 0){
                                            $table_body += '<td>'+$sub_c_debit_balance.toFixed(2)+'</td>';
                                        }else{
                                            $table_body += '<td>&nbsp;</td>';
                                        }

                                        if(parseFloat($sub_c_credit_balance) > 0){
                                            $table_body += '<td>'+$sub_c_credit_balance.toFixed(2)+'</td>';
                                        }else{
                                            $table_body += '<td>&nbsp;</td>';
                                        }
                                    $table_body += '</tr>';
                                }//end if                                        
                            }//end for 
                        }//end if  
                }//end for i  

                $table_body += '<tr class="parent-row"><td><b>Total</b></td><td style="text-align: right;"><b>'+$total_debit_balance+'</b></td><td style="text-align: right;"><b>'+$total_credit_balance+'</b></td></tr>';
                console.log('table_body:: '+$table_body)
                $("#myTbody").html($table_body);
                
            }else{
                $table_body += '<tr class="parent-row"><td colspan="3"><b>Sorry! No Record Found</b></td> </tr>'; 
                $("#myTbody").html($table_body);
            }//end if
        }
    });//end ajax
    
    $messageText = '';
    hideMessage($messageId, $messageText); 
    
    return false;
}) //end fun 

function showMessage($messageId, $messageText){    
    $('#'+$messageId).html($messageText);
    $('#'+$messageId).removeClass('d-none');
    $('#'+$messageId).addClass('d-block');
}

function hideMessage($messageId, $messageText){    
    $('#'+$messageId).html($messageText);
    $('#'+$messageId).removeClass('d-block');
    $('#'+$messageId).addClass('d-none');
} 

$(document).on("click", ".expand-btn", function () { 

    $(this).toggleClass('active');

    $(this)
        .closest('tr')
        .nextUntil('.parent-row')
        .toggle();

});



$(document).ready(function () { 
    var financialYearStart = "<?=$_SESSION['from_date']?>";
    var financialYearEnd   = "<?=$_SESSION['to_date']?>";

    $("#from_date").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: new Date(financialYearStart),
        maxDate: new Date(financialYearEnd)
    });

    $("#to_date").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: new Date(financialYearStart),
        maxDate: new Date(financialYearEnd)
    });

    populateDataTable();
    configureParentCategoryDd();
    configureParentCategoryDd1();
});

