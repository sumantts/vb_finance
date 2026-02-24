
$('#myForm').on('submit', function(){
    console.log('Validated..'); 
    $serial_no = $('#serial_no').val();   
    $company_name = $('#company_name').val();   

    $('#message_text').html('');
    $('#message_text').removeClass('d-block');
    $('#message_text').addClass('d-none');

    $.ajax({
        type: "POST",
        url: "company/function.php",
        dataType: "json",
        data: { fn: "saveFormData", serial_no: $serial_no, company_name: $company_name}
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){             
            $('#serial_no').val('');
            $('#myForm').trigger('reset');
            $('#theme-settings-offcanvas').offcanvas('hide'); 
            populateDataTable();   
            populateCompanyDD();
        } 
        alert(res.error_message); 
    });//end ajax 

    return false;
}) //end fun

$('#cancelForm').on('click', function(){
    $('#myForm').trigger('reset');   
    $msg_text = '';
    showNotification($msg_text);
})

function populateDataTable(){ 
    $('#message_text').html('');
    $('#message_text').removeClass('d-block');
    $('#message_text').addClass('d-none');

    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'company/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun

function editTabledata(sl){
    console.log('sl: ' + sl);
    $.ajax({
        method: "POST",
        url: "company/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#serial_no').val($res1.co_id); 
            $('#company_name').val($res1.company_name);  
        }        
    });//end ajax
}//end if 

function deleteTabledata(sl){
    console.log('sl: ' + sl);
    if(confirm('Are you sure to delete the record?')){
        $.ajax({
            method: "POST",
            url: "company/function.php",
            data: { fn: "deleteTabledata", serial_no: sl }
        })
        .done(function( res ) {
            $res1 = JSON.parse(res); 
            if($res1.status == true){ 
                $msg_text = 'Data Deleted successfully';
                showNotification($msg_text);
                populateDataTable();                  
                populateCompanyDD();
            }        
        });//end ajax
    }//end fonfirm if
}//end if 

function showNotification($msg_text){
    $('#alert_div').removeClass('d-none');
    $('#alert_div').addClass('d-block');

    $('#alert_span').html($msg_text);
}//end fun  


$(document).ready(function () {
    populateDataTable(); 
});