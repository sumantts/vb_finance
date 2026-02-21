
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



$('#myForm2').on('submit', function(){ 
    $parent_c_id1 = $('#parent_c_id1').val(); 
    $sub_c_id1 = $('#sub_c_id1').val(); 
    $messageId = 'message_text1';    
    
    var table = $('#datatable-buttons').DataTable();
    $selectedData = [];
    table.$('input.rowCheckbox:checked').each(function () {
        var row = $(this).closest('tr');
        $selectedData.push({
            //sl: row.find('td:eq(1)').text(),
            //date: row.find('td:eq(2)').text(),
            obj_id: row.find('td:eq(9)').text()
        });
    });
    console.log(JSON.stringify($selectedData));

    if($selectedData.length == 0){
        $messageText = 'Warning! Please chech the checkbox, select the table row first';
        showMessage($messageId, $messageText); 
    }else{
        $messageText = '';
        hideMessage($messageId, $messageText); 

        // Call Svc
        $.ajax({
            type: "POST",
            url: "bank_data/function.php",
            dataType: "json",
            data: { fn: "saveFormData2", selectedData: $selectedData, parent_c_id: $parent_c_id1, sub_c_id: $sub_c_id1}
        })
        .done(function( res ) {
            //$res1 = JSON.parse(res);
            if(res.status == true){    
                $('#myForm2').trigger('reset');
                $messageText = 'Category name updated successfully';
                populateDataTable();
            }else{
                $messageText = res.error_message;
            }
            showMessage($messageId, $messageText); 
        });//end ajax 
    }//end 
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

$(document).ready(function () { 
    populateDataTable();
    configureParentCategoryDd();
    configureParentCategoryDd1();
});