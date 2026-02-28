



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

            if($all_categories.length > 0){
                for($i = 0; $i < $all_categories.length; $i++){
                    $c_credit_balance = $all_categories[$i].credit_balance;
                    $c_debit_balance = $all_categories[$i].debit_balance; 
                    $category_name = $all_categories[$i].category_name;  
                    $nature = $all_categories[$i].nature;  
                    $sub_categories = $all_categories[$i].sub_categories; 

                    if(parseFloat($c_credit_balance) > 0 || parseFloat($c_debit_balance) > 0){
                        $table_body += '<tr>'; 
                            $table_body += '<td><button class="toggle-btn">+</button> '+$category_name+'</td>';
                            if(parseFloat($c_debit_balance) > 0){
                                $table_body += '<td style="text-align: right;">'+$c_debit_balance.toFixed(2)+'</td>';
                            }else{
                                $table_body += '<td>&nbsp;</td>';
                            }
                            
                            if(parseFloat($c_credit_balance) > 0){
                                $table_body += '<td style="text-align: right;">'+$c_credit_balance.toFixed(2)+'</td>';
                            }else{
                                $table_body += '<td>&nbsp;</td>';
                            } 
                        $table_body += '</tr>';
                        
                        //sub categories  
                        /*****/
                        if($sub_categories.length > 0){
                            $table_body += '<tr class="child-row">'; 
                                $table_body += '<td colspan="3">';
                                    $table_body += '<table class="table table-sm table-bordered mb-0">';

                                    for($j = 0; $j < $sub_categories.length; $j++){
                                        $sub_category_name = $sub_categories[$j].sub_category_name;
                                        $sub_c_debit_balance = $sub_categories[$j].debit_balance;
                                        $sub_c_credit_balance = $sub_categories[$j].credit_balance;
                                        /*****/
                                        if(parseFloat($sub_c_debit_balance) > 0 || parseFloat($sub_c_credit_balance) > 0){
                                            $table_body += '<tr>'; 
                                                $table_body += '<td>'+$sub_category_name+'</td>';
                                                if(parseFloat($sub_c_debit_balance) > 0){
                                                    $table_body += '<td style="text-align: right;">'+$sub_c_debit_balance.toFixed(2)+'</td>';
                                                }else{
                                                    $table_body += '<td>&nbsp;</td>';
                                                }
                                                
                                                if(parseFloat($sub_c_credit_balance) > 0){
                                                    $table_body += '<td style="text-align: right;">'+$sub_c_credit_balance.toFixed(2)+'</td>';
                                                }else{
                                                    $table_body += '<td>&nbsp;</td>';
                                                } 
                                            $table_body += '</tr>';
                                        }//end if
                                        /****/
                                    }//end for 
                                    
                                    $table_body += '</table>';
                                $table_body += '</td>';
                            $table_body += '</tr>';
                        }//end if 
                        /***/       
                    }//end if
                }//end for i  

                $table_body += '<tr><td><b>Total</b></td><td style="text-align: right;"><b>'+$total_debit_balance+'</b></td><td style="text-align: right;"><b>'+$total_credit_balance+'</b></td></tr>';
                console.log('table_body:: '+$table_body)
                $("#myTbody").html($table_body);
                
            }//end if
        }
    });//end ajax
    
    $messageText = '';
    hideMessage($messageId, $messageText); 
    
    return false;
}) //end fun

/****

function populateDataTable(){ 
    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        columnDefs: [
            { orderable: false, targets: 0 } // Disable sorting for first column
        ],
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'trial_balance/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun
  

//Delete
function deleteTabledata(serial_no){
    if(confirm('Are you sure?')){ 
        $.ajax({
            method: "POST",
            url: "trial_balance/function.php",
            data: { fn: "deleteTableItem", sl_no: serial_no }
        })
        .done(function( res ) {
            $res1 = JSON.parse(res); 
            if($res1.status == true){ 
                alert('Data Deleted Successfully');
                populateDataTable();
            }        
        });//end ajax
    }//end if confirm
}//end fun


function editTabledata(sl){
    console.log('sl: ' + sl);
    $.ajax({
        method: "POST",
        url: "trial_balance/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#serial_no').val($res1.serial_no);
            $('#parent_c_id').val($res1.parent_c_id).trigger('change');
            $sub_c_id = $res1.sub_c_id;
            setTimeout(function(){
                $('#sub_c_id').val($sub_c_id).trigger('change');
            },300);
             
        }        
    });//end ajax
}//end if  

//Category 
function configureParentCategoryDd(){
    $.ajax({
        method: "POST",
        url: "category/function.php",
        data: { fn: "configureParentCategoryDd" }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){
            $rows = $res1.data;

            if($rows.length > 0){
                $('#parent_c_id').html('');  
                $html = "<option value='0'>Select</option>";

                for($i = 0; $i < $rows.length; $i++){
                    $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                }//end for
                
                $('#parent_c_id').html($html); 
            }//end if
        }        
    });//end ajax
}//end 

//Category  1
function configureParentCategoryDd1(){
    $.ajax({
        method: "POST",
        url: "category/function.php",
        data: { fn: "configureParentCategoryDd" }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){
            $rows = $res1.data;

            if($rows.length > 0){
                $('#parent_c_id').html('');                
                $('#parent_c_id1').html('');
                $html = "<option value=''>Select</option>";

                for($i = 0; $i < $rows.length; $i++){
                    $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                }//end for
                
                $('#parent_c_id').html($html);
                $('#parent_c_id1').html($html);
            }//end if
        }        
    });//end ajax
}//end 

//Fetch sub category
$('#parent_c_id').on('change', function(){
    $parent_c_id = $('#parent_c_id').val();
    if(parseInt($parent_c_id) > 0){
        $.ajax({
            method: "POST",
            url: "category/function.php",
            data: { fn: "configureSubCategoryDd", parent_c_id: $parent_c_id }
        })
        .done(function( res ) {
            $res1 = JSON.parse(res); 
            if($res1.status == true){
                $rows = $res1.data;

                if($rows.length > 0){
                    $('#sub_c_id').html('');
                    $html = "<option value='0'>Select</option>";

                    for($i = 0; $i < $rows.length; $i++){
                        $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                    }//end for
                    
                    $('#sub_c_id').html($html);
                }//end if
            }        
        });//end ajax
    }else{
        $('#sub_c_id').html('');
        $html = "<option value='0'>Select</option>";
        $('#sub_c_id').html($html);
    }
});


//Fetch sub category 1
$('#parent_c_id1').on('change', function(){
    $parent_c_id1 = $('#parent_c_id1').val();
    if(parseInt($parent_c_id1) > 0){
        $.ajax({
            method: "POST",
            url: "category/function.php",
            data: { fn: "configureSubCategoryDd", parent_c_id: $parent_c_id1 }
        })
        .done(function( res ) {
            $res1 = JSON.parse(res); 
            if($res1.status == true){
                $rows = $res1.data;

                if($rows.length > 0){
                    $('#sub_c_id1').html('');
                    $html = "<option value=''>Select</option>";

                    for($i = 0; $i < $rows.length; $i++){
                        $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                    }//end for
                    
                    $('#sub_c_id1').html($html);
                }//end if
            }        
        });//end ajax
    }else{
        $('#sub_c_id1').html('');
        $html = "<option value=''>Select</option>";
        $('#sub_c_id1').html($html);
    }
});

$('#cancelForm1, #cancelForm2').on('click', function(){
    $('#myForm1, #myForm2').trigger('reset');
})

// Check All function
$(document).ready(function(){
    // When Select All is clicked
    $("#selectAll").click(function(){
        $(".rowCheckbox").prop("checked", $(this).prop("checked"));
    });

    // Optional: If all row checkboxes are checked, mark Select All checked
    $(".rowCheckbox").click(function(){
        if ($(".rowCheckbox:checked").length == $(".rowCheckbox").length) {
            $("#selectAll").prop("checked", true);
        } else {
            $("#selectAll").prop("checked", false);
        }
    });
});
*****/

// Working with Previous and Next page also
/**
$("#getData").click(function(){
    var table = $('#datatable-buttons').DataTable();
    var selectedData = [];
    table.$('input.rowCheckbox:checked').each(function () {
        var row = $(this).closest('tr');
        selectedData.push({
            sl: row.find('td:eq(1)').text(),
            date: row.find('td:eq(2)').text()
        });
    });
    console.log(JSON.stringify(selectedData));
});
**/

// Get Table data dynamically Working with current page only
/*$("#getData").click(function(){

    var selectedData = [];

    $("#datatable-buttons tbody input.rowCheckbox:checked").each(function(){

        var rowData = [];

        $(this).closest("tr").find("td").each(function(){
            rowData.push($(this).text());
        });

        selectedData.push(rowData);
    });

    console.log(JSON.stringify(selectedData));
});*/

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

/*$(document).ready(function () {
    $(".toggle-btn").click(function () {
        var childRow = $(this).closest("tr").next(".child-row");
        childRow.slideToggle(200);
        // Change + / -
        if ($(this).text() == "+") {
            $(this).text("-");
        } else {
            $(this).text("+");
        }
    });
});*/


$(document).ready(function () { 
    populateDataTable();
    configureParentCategoryDd();
    configureParentCategoryDd1();
});