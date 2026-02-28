 


$('#myForm2').on('submit', function(){ 
    $from_date = $('#from_date').val(); 
    $to_date = $('#to_date').val(); 
    $messageId = 'message_text1'; 

    $myTbodyDr = '';
    $myTbodyCr = '';

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

            if($all_categories.length > 0){
                for($i = 0; $i < $all_categories.length; $i++){
                    $c_credit_balance = $all_categories[$i].credit_balance;
                    $c_debit_balance = $all_categories[$i].debit_balance; 
                    $category_name = $all_categories[$i].category_name;  
                    $nature = $all_categories[$i].nature;  
                    $sub_categories = $all_categories[$i].sub_categories; 

                    //Left side Dr 
                    if($nature == '2' && parseFloat($c_debit_balance) > 0){
                        $myTbodyDr += '<tr class="parent-row">';
                            $myTbodyDr += '<td><button class="toggle-btn">+</button> '+$category_name+'</td>';
                            $myTbodyDr += '<td>'+$c_debit_balance+'</td>';
                        $myTbodyDr += '</tr>';
                        
                        if($sub_categories.length > 0){
                            $myTbodyDr += '<tr class="child-row">'; 
                                $myTbodyDr += '<td colspan="2" style="padding: 0;">';
                                    $myTbodyDr += '<table class="table table-sm table-bordered mb-0">';
                                        for($j = 0; $j < $sub_categories.length; $j++){
                                            $sub_category_name = $sub_categories[$j].sub_category_name;
                                            $sub_c_debit_balance = $sub_categories[$j].debit_balance;
                                            $sub_c_credit_balance = $sub_categories[$j].credit_balance;
                                            if(parseFloat($sub_c_debit_balance) > 0){
                                                $myTbodyDr += '<tr>'; 
                                                    $myTbodyDr += '<td>'+$sub_category_name+'</td>';
                                                    $myTbodyDr += '<td>'+$sub_c_debit_balance+'</td>';  
                                                $myTbodyDr += '</tr>';
                                            } 
                                        }//end loop j
                                    $myTbodyDr += '</table>';
                                $myTbodyDr += '</td>';
                            $myTbodyDr += '</tr>';    
                        }//end if 
                    }//end if                    

                    // Right side Dr 
                    if($nature == '1' && parseFloat($c_credit_balance) > 0){
                        $myTbodyCr += '<tr class="parent-row">';
                            $myTbodyCr += '<td><button class="toggle-btn">+</button> '+$category_name+'</td>';
                            $myTbodyCr += '<td>'+$c_credit_balance+'</td>';
                        $myTbodyCr += '</tr>';
                        
                        if($sub_categories.length > 0){
                            $myTbodyCr += '<tr class="child-row">'; 
                                $myTbodyCr += '<td colspan="2" style="padding: 0;">';
                                    $myTbodyCr += '<table class="table table-sm table-bordered mb-0">';
                                        for($j = 0; $j < $sub_categories.length; $j++){
                                            $sub_category_name = $sub_categories[$j].sub_category_name;
                                            $sub_c_credit_balance = $sub_categories[$j].debit_balance;
                                            $sub_c_credit_balance = $sub_categories[$j].credit_balance;
                                            if(parseFloat($sub_c_credit_balance) > 0){
                                                $myTbodyCr += '<tr>'; 
                                                    $myTbodyCr += '<td>'+$sub_category_name+'</td>';
                                                    $myTbodyCr += '<td>'+$sub_c_credit_balance+'</td>';  
                                                $myTbodyCr += '</tr>'; 
                                            }
                                        }//end loop j
                                    $myTbodyCr += '</table>';
                                $myTbodyCr += '</td>';
                            $myTbodyCr += '</tr>';    
                        }//end if 
                    }//end if

                }//end for i  
                
                console.log('myTbodyDr:: '+$myTbodyDr)
                $("#myTbodyDr").html($myTbodyDr); 
                
                console.log('myTbodyCr:: '+$myTbodyCr)
                $("#myTbodyCr").html($myTbodyCr);
                
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

$(document).on("click", ".toggle-btn", function () {

    var parentRow = $(this).closest("tr");
    var childRow = parentRow.next(".child-row");

    childRow.toggle();

    // Optional: change button text
    if ($(this).text() == "+") {
        $(this).text("-");
    } else {
        $(this).text("+");
    }

}); 


$(document).ready(function () { 
    populateDataTable();
    configureParentCategoryDd();
    configureParentCategoryDd1();
});