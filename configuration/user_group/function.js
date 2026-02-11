
function validateForm(){
    console.log('Validated..'); 

    $('#myForm')[0].reset();

    $('#theme-settings-offcanvas').offcanvas('hide')

    return false;
}//end fun

function populateDataTable(){ 
    $('#datatable-buttons').dataTable().fnClearTable();
    $('#datatable-buttons').dataTable().fnDestroy();

    $('#datatable-buttons').DataTable({  
        responsive: true,
        serverMethod: 'GET',
        ajax: {'url': 'configuration/user_group/function.php?fn=getTableData' },
        dom: 'Bfrtip' 
    });
}//end fun


$(document).ready(function () {
    console.log('before call');
    populateDataTable();
});