
$('#myForm').on('submit', function(){
    console.log('Validated..'); 
    $serial_no = $('#serial_no').val(); 
    $FullNm = $('#FullNm').val(); 
    $UsrNm = $('#UsrNm').val(); 
    $password = $('#password').val(); 
    $GrpId = $('#GrpId').val();  

    $.ajax({
        type: "POST",
        url: "configuration/user_details/function.php",
        dataType: "json",
        data: { fn: "saveFormData", serial_no: $serial_no, FullNm: $FullNm, UsrNm: $UsrNm, password: $password, GrpId: $GrpId }
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

$('#cancelForm').on('click', function(){
    $('#myForm').trigger('reset');
})

function populateDataTable(){ 
    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'configuration/user_details/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun

function editTabledata(sl){
    console.log('sl: ' + sl);
    $.ajax({
        method: "POST",
        url: "configuration/user_details/function.php",
        data: { fn: "editTabledata", serial_no: sl }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            $('#serial_no').val($res1.Id);
            $('#FullNm').val($res1.FullNm);
            $('#UsrNm').val($res1.UsrNm);
            $('#GrpId').val($res1.GrpId).trigger('change');
        }        
    });//end ajax
}//end if 

//Service
function configureUserGroupDropDown(){
    $.ajax({
        method: "POST",
        url: "configuration/user_details/function.php",
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

$(document).ready(function () {
    populateDataTable();
    configureUserGroupDropDown();
});