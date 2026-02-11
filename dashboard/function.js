
function getCalenderData(){
    $.ajax({
        method: "POST",
        url: "dashboard/function.php",
        data: { fn: "getCalenderData" }
    })
    .done(function( res ) {
        $res1 = JSON.parse(res); 
        if($res1.status == true){ 
            console.log($res1.reserved);
            localStorage.setItem('events', JSON.stringify($res1.reserved));
        }        
    });//end ajax
    
}


$(document).ready(function () { 
    getCalenderData();
});