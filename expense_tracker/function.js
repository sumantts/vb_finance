/****
$('#myForm').on('submit', function(){ 
    $itemName = $('#itemName').val(); 
    $unitType = $('#unitType').val();
    $itemCategory = $('#itemCategory').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "expense_tracker/function.php",
        dataType: "json",
        data: { fn: "saveFormData", itemName: $itemName, unitType: $unitType, itemCategory: $itemCategory, serial_no: $serial_no}
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){    
            $('#myForm').trigger('reset');
            $('#theme-settings-offcanvas').offcanvas('hide'); 
            alert('Data updated successfully');
            populateDataTable();
        }else{
            alert(res.error_message);
        }
    });//end ajax 
    return false;
}) //end fun
****/

$('#myForm1').on('submit', function(){ 
    $parent_c_id = $('#parent_c_id').val(); 
    $sub_c_id = $('#sub_c_id').val(); 
    $narration = $('#narration').val();
    $exp_amount = $('#exp_amount').val(); 
    $exp_date = $('#exp_date').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "expense_tracker/function.php",
        dataType: "json",
        data: { fn: "saveFormData1", parent_c_id: $parent_c_id, sub_c_id: $sub_c_id, narration: $narration, exp_amount: $exp_amount, exp_date: $exp_date, serial_no: $serial_no}
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){   
            $("#myForm1")[0].reset(); 
            $('#theme-settings-offcanvas').offcanvas('hide'); 
            alert(res.error_message);
            populateDataTable();
        }else{
            alert(res.error_message);
        }
    });//end ajax 

    return false;
}) //end fun


function populateDataTable(){ 
    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        columnDefs: [
            { orderable: false, targets: 0 } // Disable sorting for first column
        ],
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'expense_tracker/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun
  

//Delete
function deleteTabledata(serial_no){
    if(confirm('Are you sure to delete the record?')){ 
        $.ajax({
            method: "POST",
            url: "expense_tracker/function.php",
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
        url: "expense_tracker/function.php",
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
            $('#narration').val($res1.narration);
            $('#exp_amount').val($res1.exp_amount);
            $('#exp_date').val($res1.exp_date); 
             
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
                $html = "<option value=''>Select</option>";

                for($i = 0; $i < $rows.length; $i++){
                    $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                }//end for
                
                $('#parent_c_id').html($html); 
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
                    $html = "<option value=''>Select</option>";

                    for($i = 0; $i < $rows.length; $i++){
                        $html += "<option value='"+$rows[$i].c_id+"'>"+$rows[$i].category_name+"</option>";                    
                    }//end for
                    
                    $('#sub_c_id').html($html);
                }//end if
            }        
        });//end ajax
    }else{
        $('#sub_c_id').html('');
        $html = "<option value=''>Select</option>";
        $('#sub_c_id').html($html);
    }
});


$('#cancelForm1').on('click', function(){ 
    $("#myForm1")[0].reset(); 
})

$('#offcanvas').on('click', function(){
    $("#myForm1")[0].reset();  
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

$(document).ready(function () { 
    populateDataTable();
    configureParentCategoryDd(); 
});