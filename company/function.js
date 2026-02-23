
$('#myForm').on('submit', function(){
    console.log('Validated..'); 
    $serial_no = $('#serial_no').val(); 
    $parent_c_id = $('#parent_c_id').val();  
    $category_name = $('#category_name').val();  

    
    $nature = $('#nature').val();  
    $part_of_plbs = $('#part_of_plbs').val();  
    $opening_balance = $('#opening_balance').val();  

    $('#message_text').html('');
    $('#message_text').removeClass('d-block');
    $('#message_text').addClass('d-none');

    $.ajax({
        type: "POST",
        url: "company/function.php",
        dataType: "json",
        data: { fn: "saveFormData", serial_no: $serial_no, category_name: $category_name, parent_c_id: $parent_c_id, nature: $nature, part_of_plbs: $part_of_plbs, opening_balance: $opening_balance }
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){    
            $('#serial_no').val('');
            $('#myForm').trigger('reset');
            $('#theme-settings-offcanvas').offcanvas('hide'); 
            populateDataTable();
            configureParentCategoryDd();
        } 
        //alert(res.error_message);
        $('#message_text').html(res.error_message);
        $('#message_text').removeClass('d-none');
        $('#message_text').addClass('d-block');
    });//end ajax 

    return false;
}) //end fun

$('#cancelForm').on('click', function(){
    $('#myForm').trigger('reset');
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
            $('#serial_no').val($res1.c_id);
            $('#parent_c_id').val($res1.parent_c_id).trigger('change');
            $('#category_name').val($res1.category_name); 
            $('#nature').val($res1.nature).trigger('change');
            $('#part_of_plbs').val($res1.part_of_plbs).trigger('change');
            $('#opening_balance').val($res1.opening_balance); 
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
                showNotification($msg_text)
                populateDataTable(); 
            }        
        });//end ajax
    }//end fonfirm if
}//end if 

function showNotification($msg_text){
    $('#alert_div').removeClass('d-none');
    $('#alert_div').addClass('d-block');

    $('#alert_span').html($msg_text);
}//end fun

//Category 
function configureParentCategoryDd(){
    $.ajax({
        method: "POST",
        url: "company/function.php",
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

$('#parent_c_id').on('change', function(){
    $parent_c_id = $('#parent_c_id').val();

    if(parseInt($parent_c_id) > 0){
        $('#div_nature').removeClass('d-block');
        $('#div_plbs').removeClass('d-block');
        $('#div_opn_bal').removeClass('d-none');

        $('#div_nature').addClass('d-none');
        $('#div_plbs').addClass('d-none');
        $('#div_opn_bal').addClass('d-block');
    }else{
        $('#div_nature').removeClass('d-none');
        $('#div_plbs').removeClass('d-none');
        $('#div_opn_bal').removeClass('d-block');

        $('#div_nature').addClass('d-block');
        $('#div_plbs').addClass('d-block');
        $('#div_opn_bal').addClass('d-none');
    }
})

$(document).ready(function () {
    populateDataTable();
    configureParentCategoryDd();
});