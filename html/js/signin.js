
var EMAIL_ADDRESS_MAX = 320;
var PASSWORD_MAX = 12;

$(document).ready(function() {
    $("#signin").ajaxForm({ beforeSubmit: validate, success: signinHandler, dataType: 'json'}); 
    $("#forgot_password_label").bind("click", function() { $("#forgot_password").attr("checked", "checked"); $("#signin").submit(); });
});

function signinHandler(data) {

    $(".signin_info").hide();
    if (data.result == "success") {
        location = decodeURI(data.home);
    } else {
        $(".signin_info").text(data.info);
        $(".signin_info").fadeIn();
    }
    $("#forgot_password").removeAttr("checked");

}
  
function validate() {
    var valid = true, info = "";

    $(".signin_info").hide();

    if ($("#forgot_password").attr("checked")) {
       if (!$("#username").val().length)
        info = email_required;
    } else {
        if (!$("#username").val().length || !$("#password").val().length)
            info = sign_in_fields_required;
        else if ($("#username").val().length > EMAIL_ADDRESS_MAX)
            info = username_label + " " + greater_than + " " + EMAIL_ADDRESS_MAX + " " + entry_type_max;
        else if ($("#password").val().length > PASSWORD_MAX)
            info = password_label + " " + greater_than + " " + PASSWORD_MAX + " " + entry_type_max;
    }

    if (info.length > 0) {
        $(".signin_info").text(info);
        $(".signin_info").fadeIn();
        return false;
    } else {
        return true;
    }
}
