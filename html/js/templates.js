
var PWD_MIN_LENGTH = 4;
var PWD_MAX_LENGTH = 10;
var translations_en = { "en": "English", "ja": "Japanese" };    //for Inc templates processing

function add_query_to_form(form) 
{
  var query_string = window.location.search.substring(1);
  var queries = query_string.split("&");

  for (var i=0; i < queries.length; i++) {
    pair = queries[i].split("=");
    $(form).after("<input type='hidden' name='" + pair[0] + "' value='" + pair[1] + "' />");
  }
}

/*  * This function parses ampersand-separated name=value argument pairs from 
 * the query string of the URL. It stores the name=value pairs in 
 * properties of an object and returns that object. Use it like this: 
 * 
 * var args = getArgs( );  // Parse args from URL 
 * var q = args.q || "";  // Use argument, if defined, or a default value 
 * var n = args.n ? parseInt(args.n) : 10; 
 */ 
function getArgs() 
{
    var args = new Object(); 
    var query = location.search.substring(1);     // Get query string 
    var pairs = query.split("&");                 // Break at ampersand 

    for (var i = 0; i < pairs.length; i++) { 
        var pos = pairs[i].indexOf('=');          // Look for "name=value" 

        if (pos == -1)
            continue;                  // If not found, skip 

        var argname = pairs[i].substring(0,pos);  // Extract the name 
        var value = pairs[i].substring(pos+1);    // Extract the value 
        value = decodeURIComponent(value);        // Decode it, if needed 
        args[argname] = value;                    // Store as a property 
    } 
    return args;                                  // Return the object 
} 

$(document).ready(function() {
    $("#password_entry").focus();
    $("#CurrentPassword").focus();

    if ($("div.result").css("display") == "block") {
        $("div.result").fadeOut();
        $("div.result").fadeIn();
    }

    //alert($.browser.version.substr(0, 1));
    if ($.browser.msie && $.browser.version.substr(0, 1) < 7)
        $(".alpha").pngfix();

    $(".language_list a").unbind("click");
    $(".language_list a").bind("click", function() { 
        if ($(this).attr("class") != "selected") {
            $("#Language").val(translations_en[$(this).attr("lang")]);
            $("#language_frm").submit(); 
            $(".language_link").toggle(); 
        } else {
            $(".language_link").toggle(); 
        }
    });



    $("#submit_user_add").bind("click", function() { 
        if ($("#free_trial_notify:checked").val()) {
            ;

        }
    });

    //on changepassword page for admin, rewrite index to admin index
    var args = getArgs();
    if (args.Page != undefined && args.password_type != undefined) {
        if (args.Page.toLowerCase().search(/^changepassword/) == 0 && args.password_type.toLowerCase() == "admin") {
            var query = "";
            args.Page = "Index"; 
            args.Type = "Admin"; 

            for (prop in args)
                query += prop + "=" + args[prop] + "&";
            query = query.slice(0, -1);

            $("a.home_link").attr("href", "/scripts/Inc/Inc/mmpage.dll?" + query);
        }
    }

    //this is for the generic Error template, which will have varying module/query requirements 
    $(".language_list_errorpage a").bind("click", function() { 
        if ($(this).attr("class") != "selected") {
            add_query_to_form($("#language_frm"));
            $("#Language").val(translations_en[$(this).attr("lang")]);
            $("#language_frm").submit(); 
            $(".language_link").toggle(); 
        }
    });
    $("select.update").bind("change", function() { this.form.submit(); });

})
