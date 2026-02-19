
$('#myForm').on('submit', function(){ 
    $itemName = $('#itemName').val(); 
    $unitType = $('#unitType').val();
    $itemCategory = $('#itemCategory').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "bank_data/function.php",
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

$('#myForm1').on('submit', function(){ 
    $parent_c_id = $('#parent_c_id').val(); 
    $sub_c_id = $('#sub_c_id').val(); 
    $serial_no = $('#serial_no').val();

    $.ajax({
        type: "POST",
        url: "bank_data/function.php",
        dataType: "json",
        data: { fn: "saveFormData1", parent_c_id: $parent_c_id, sub_c_id: $sub_c_id, serial_no: $serial_no}
    })
    .done(function( res ) {
        //$res1 = JSON.parse(res);
        if(res.status == true){    
            $('#myForm1').trigger('reset');
            $('#theme-settings-offcanvas1').offcanvas('hide'); 
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
        ajax: {'url': 'bank_data/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun
  

//Delete
function deleteTabledata(serial_no){
    if(confirm('Are you sure?')){ 
        $.ajax({
            method: "POST",
            url: "bank_data/function.php",
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
        url: "bank_data/function.php",
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

$('#cancelForm1').on('click', function(){
    $('#myForm1').trigger('reset');
})

$(document).ready(function () { 
    populateDataTable();
    configureParentCategoryDd();
});