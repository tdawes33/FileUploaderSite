
var IISModulePath = "/scripts/Inc/Inc/";
var IISPageModule = "mmpage.dll";

jQuery.fn.fadeToggle = function(speed, easing, callback) {
   return this.animate({opacity: 'toggle'}, speed, easing, callback);

}; 

$(document).ready(function() {

    if ($.browser.msie && $.browser.version.substr(0, 1) < 7)
        $(".alpha").pngfix();

    //setup i18n selector
    $(".language_label").bind("mousedown", function() { $(".language_link").toggle(); });

    $(".language_list a").attr("href", "#");    //override noscript url version
    $(".language_list a").bind("click", function() { 
        if ($(this).attr("class") != "selected") {
            $("#Language").val($(this).attr("lang"));
            $("#language_frm").submit(); 
            $(".language_link").toggle(); 
        } else {
            $(".language_link").toggle(); 
        }
    });

})

function getIISPagePath(page, access_type) {

    if (typeof access_type == "undefined")
        access_type = "User";

    return IISModulePath + IISPageModule + "?Page=" + page + "&Type=" + access_type + "&Language=" + language_en;
}

