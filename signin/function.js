$('#myForm').on('submit', function(){     
    $emailaddress = $('#emailaddress').val();
    $password = $('#password').val();

    $.ajax({
        method: "POST",
        url: "signin/function.php",
        data: { fn: "doLogin", emailaddress: $emailaddress, password: $password }
    })
    .done(function( res ) {
        console.log(res);
        $res1 = JSON.parse(res);
        if($res1.status == true){
            window.location.href = '?p=dashboard';
        }else{
            alert($res1.message);
            //$('#error_text').html('Wrong username or password');
        }
    });//end ajax    
    return false;
})

//Loading screen
$body = $("body");
$(document).on({
    ajaxStart: function() { $body.addClass("loading"); },
    ajaxStop: function() { $body.removeClass("loading"); }    
});