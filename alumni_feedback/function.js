
$('#myForm').on('submit', function(){ 
    $itemName = $('#itemName').val(); 
    $unitType = $('#unitType').val();
    $itemCategory = $('#itemCategory').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "alumni_feedback/function.php",
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

function populateDataTable(){ 
    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'alumni_feedback/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun
  

//Delete
function deleteTabledata(serial_no){
    if(confirm('Are you sure?')){ 
        $.ajax({
            method: "POST",
            url: "alumni_feedback/function.php",
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
        url: "alumni_feedback/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#welcome_text').html('Feedback Details of ' + $res1.alumni_name);

            $('#ans_1').html('Feedback: '+$res1.ans_1);
            $('#alumni_id').html($res1.alumni_id); 
            $('#alumni_name').html($res1.alumni_name); 
            //$alumni_name = $('#alumni_name);
            $('#alumni_dob').html($res1.alumni_dob);  
            $('#passout_year').html($res1.passout_year); 
            $('#dept_course').html($res1.dept_course); 
            $('#alumni_address').html($res1.alumni_address); 
            $('#alumni_phone').html($res1.alumni_phone); 
            $('#alumni_email').html($res1.alumni_email); 
            $('#alumni_occupation').html($res1.alumni_occupation); 
            $('#ans_1').html('Feedback: '+$res1.ans_1); 
            $('#ans_2').html('Feedback: '+$res1.ans_2); 
            $('#ans_3').html('Feedback: '+$res1.ans_3); 
            $('#motivated_connected').html('Feedback: '+$res1.option1+'<br> '+$res1.option2+'<br> '+$res1.option3+'<br> '+$res1.option4+'<br> '+$res1.option5+'<br> '+$res1.option6+'<br> '+$res1.option7); 
            $('#ans_5').html('Feedback: '+$res1.ans_5); 
            $('#ans_6').html('Feedback: '+$res1.ans_6); 
            $('#ans_7').html('Feedback: '+$res1.ans_7); 
            $('#ans_8').html('Feedback: '+$res1.ans_8); 
            $('#ans_9').html('Feedback: '+$res1.ans_9); 
            $('#ans_10').html('Feedback: '+$res1.ans_10); 
            $('#ans_11').html('Feedback: '+$res1.ans_11); 
            $('#ans_12').html('Feedback: '+$res1.ans_12); 
            $('#ans_13').html('Feedback: '+$res1.ans_13); 
            $('#ans_14').html('Feedback: '+$res1.ans_14); 
            $('#ans_15').html('Feedback: '+$res1.ans_15);
             
        }        
    });//end ajax
}//end if  


$(document).ready(function () { 
    populateDataTable();
});