
$('#myForm').on('submit', function(){ 
    $itemName = $('#itemName').val(); 
    $unitType = $('#unitType').val();
    $itemCategory = $('#itemCategory').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "sales_data/function.php",
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
        ajax: {'url': 'sales_data/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun
  

//Delete
function deleteTabledata(serial_no){
    if(confirm('Are you sure?')){ 
        $.ajax({
            method: "POST",
            url: "sales_data/function.php",
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
        url: "sales_data/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#welcome_text').html('Feedback Details of ' + $res1.stdn_name);
            $('#ans_1').html('Feedback: '+$res1.ans_1);
            $('#ans_2').html('Feedback: '+$res1.ans_2);
            $('#ans_3').html('Feedback: '+$res1.ans_3);
            $('#ans_4').html('Feedback: '+$res1.ans_4);
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
            $('#ans_16').html('Feedback: '+$res1.ans_16);
            $('#ans_17').html('Feedback: '+$res1.ans_17);
            $('#ans_18').html('Feedback: '+$res1.ans_18);
            $('#ans_19').html('Feedback: '+$res1.ans_19);
            $('#ans_20').html('Feedback: '+$res1.ans_20);
            $('#ans_21').html('Feedback: '+$res1.ans_21);
            $('#ans_22').html('Feedback: '+$res1.ans_22);
            $('#ans_23').html('Feedback: '+$res1.ans_23);
            $('#ans_24').html('Feedback: '+$res1.ans_24);
            $('#ans_25').html('Feedback: '+$res1.ans_25);
            $('#ans_26').html('Feedback: '+$res1.ans_26);
            $('#ans_27').html('Feedback: '+$res1.ans_27);
            $('#ans_28').html('Feedback: '+$res1.ans_28); 
             
        }        
    });//end ajax
}//end if  


$(document).ready(function () { 
    populateDataTable();
});