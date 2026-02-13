
$('#myForm').on('submit', function(){
    console.log('Validated..'); 
    $serial_no = $('#serial_no').val(); 
    $category_name = $('#category_name').val();  

    $('#message_text').html('');
    $('#message_text').removeClass('d-block');
    $('#message_text').addClass('d-none');

    $.ajax({
        type: "POST",
        url: "category/function.php",
        dataType: "json",
        data: { fn: "saveFormData", serial_no: $serial_no, category_name: $category_name }
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){    
            $('#myForm').trigger('reset');
            $('#theme-settings-offcanvas').offcanvas('hide'); 
            populateDataTable();
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
        ajax: {'url': 'category/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun

function editTabledata(sl){
    console.log('sl: ' + sl);
    $.ajax({
        method: "POST",
        url: "category/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#serial_no').val($res1.c_id);
            $('#category_name').val($res1.category_name); 
        }        
    });//end ajax
}//end if 

//Service
/*****
function configureUserGroupDropDown(){
    $.ajax({
        method: "POST",
        url: "category/function.php",
        data: { fn: "configureUserGroupDropDown" }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){
            $rows = $res1.data;

            if($rows.length > 0){
                $('#GrpId').html('');
                $html = "<option value=''>Select</option>";

                for($i = 0; $i < $rows.length; $i++){
                    $html += "<option value='"+$rows[$i].Id+"'>"+$rows[$i].GrpNm+"</option>";                    
                }//end for
                
                $('#GrpId').html($html);
            }//end if
        }        
    });//end ajax
}//end
****/

$(document).ready(function () {
    populateDataTable();
    //configureUserGroupDropDown();
});