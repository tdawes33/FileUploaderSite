
var MAX_EMAIL_MSG = 65535;
var PWD_MIN_LENGTH = 4;
var PWD_MAX_LENGTH = 10;

var logPageName = "MonthlyTrafficStatusDetail";

function checkUnload() {

	if (document.fileuploader.isEmailing())
		return emailing_warning;
	if ($("#complete").text() != finished_label 
            && $("#recipients").val().length || $("#subject").val().length 
            || $("#message").val().length || document.fileuploader.getFileCount())
		return unsent_warning;
}

$(document).ready(function() {

    $(".toolbar").fadeIn(); 

    //disable rounded corner (with bg) for gecko rendering engine less than 9 (firefox 2)
    if ($.browser.mozilla && $.browser.version.substr(2, 1) < 9) {
        $(".email_form").css("-moz-border-radius", "0");
        $(".preferences").css("-moz-border-radius", "0");
    }

	if (document.fileuploader == undefined)
		document.fileuploader = document.getElementById("fileuploader");

    $("#recipients").focus();   //init focus

    //style
    $("input[type='checkbox']").css("backgroundColor", "transparent");  //ie6 fix

	window.onbeforeunload = checkUnload;
	window.onunload = function() {
        if (document.fileuploader.isEmailing())
            document.fileuploader.finishEmail();
    }

	//hook up buttons
	$("#clear").bind("click", function() { 

			if (!$("#recipients").val().length && !$("#subject").val().length && 
					!$("#message").val().length && !$("#password").val().length && !document.fileuploader.getFileCount())
				return;

			if (confirm(confirm_clear)) {
				$("#recipients").val("");
				$("#subject").val("");
				$("#password").val("");
				$("#message").val("");
				document.fileuploader.clearFileList();
				statsUpdate("0");
			}

		}
	);
	$("#view_incomplete").bind("click", function() { document.fileuploader.viewIncompleteEmail(); });
	$("#view_history").bind("click", function() { location = getLogURL(); });
	$("#view_preferences").bind("click", function() { $(".slidefx").toggle("slide", { direction: "left" }, 200); });
	$("#view_help").bind("click", function() { $("#fileuploader").css("visibility", "hidden"); $.facebox({ajax: "help.php"}); });
    $(document).bind('close.facebox', function() { $("#fileuploader").css("visibility", "visible"); })

	$("#send").bind("click", function() { 

			if ($("#message").val().length > MAX_EMAIL_MSG)
				return alert(message_exceeds_msg + MAX_EMAIL_MSG);

			if (!document.fileuploader.getFileCount())
				return alert(no_attachments_msg);

			if (document.fileuploader.isProcessing())
				return alert(email_processing_conflict);

			if (validateEmail()) 
				document.fileuploader.sendEmail(); 

			doneProcessing();
			return false; 
		}
	);
	$("#delete_button").bind("click", function() { document.fileuploader.removeSelection(); return false; });
	$("#filechooser_button").bind("click", function() { document.fileuploader.openFileDialog(); return false; });
	$("div.processing").bind("click", function() { if (confirm(confirm_processing_abort)) document.fileuploader.abortProcessing(); });

	//progress indicator
	$("#file_progress").progressBar({ barImage: 'images/progressbg_orange.gif', showText: true, speed: 10} );
	$("#total_progress").progressBar({ barImage: 'images/progressbg_orange.gif', showText: true, speed: 10} );

	//button which stops active txfer or registers acceptance
	$("#complete").bind("click", function() { 
			if ($(this).text() == finished_label) {
				document.fileuploader.finishEmail();
				reset(true);
			} else if ($(this).text() == unable_label) {
				reset(false); //finish notification from applet
			} else { //cancel requested
				document.fileuploader.finishEmail();
			}
	});

	//settings are save immediately
	$("#archive").bind("click", function() { document.fileuploader.savePreferences(); });
	$("#compress").bind("click", function() { document.fileuploader.savePreferences(); });
	$("#ssl").bind("click", function() { document.fileuploader.savePreferences(); });
	$("#notice").bind("click", function() { document.fileuploader.savePreferences(); });
	$("#preservation").bind("change", function() { document.fileuploader.savePreferences(); });

});

function isEnter(event) {
	var key = (event.which) ? event.which: event.keyCode;
	return (key == 13);
}

function validateEmail() {
	var email_rfc2822_regexp = /^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/;
    //var email_rfc2822_regexp = /^[\w!#$%&'*+\-/=?^_`{|}~]+(\.?[\w!#$%&'*+\-/=?^_`{|}~]+)*@([\w\-]+\.)+[\w\-]+$/;

	emails = $("#recipients").val().split(",");	//TODO allow other delimiters w/ %s//
	if (emails.length > 0) {
		for (var i = 0; i<emails.length; i++) {
			if (!email_rfc2822_regexp.test($.trim(emails[i]))) {
				alert(invalid_email_msg);
				return false;
			}
		}
	} else {
		return false;
	}

	if ($("#subject").val() == "" || $("#message").val() == "") {
		var out_warn = "";
		if ($("#subject").val() == "")
			out_warn = empty_subject_warn + "\n";
		if ($("#message").val() == "")
			out_warn += empty_message_warn + "\n";

		out_warn += "\n\t" + confirm_send;

		return confirm(out_warn);
	}

    if ($("#password").val().length > 0 
            && ($("#password").val().length < PWD_MIN_LENGTH || $("#password").val().length > PWD_MAX_LENGTH)) {

        alert(invalid_password_msg);
        return false;
    }

	return true;
}


function getInput(id) { return $("#"+id).val(); }
function setInput(id, value) { $("#"+id).val(value); }
function getCheckboxPref(id) { return $("#"+id).attr("checked"); }
function setCheckboxPref(id, val) { $("#"+id).attr("checked", (val == "true")); }

function getSelectPref(id) { return $("#"+id+" option:selected").val(); }
function setSelectPref(id, val) {
	var obj;
	if ((obj = document.getElementById(id))) {
		for (var i=0; i<obj.length; i++)
			obj.options[i].selected = (obj.options[i].value == val);
	}
}

function noAttachmentError() { alert(no_attachments_msg); }

function consoleMsg(out) { if (console) console.log(out); }
function alertMsg(out) { alert(out); }

function clearInput() {
	$("#recipients").val("");
	$("#subject").val("");
	$("#password").val("");
	$("#message").val("");
}

function progressStart() { 
	var leftOffset = 70, bottomOffset = 128;
    var pos = { top: document.fileuploader.offsetTop, left: document.fileuploader.offsetLeft };

    $(".disableable").attr("disabled", "disabled");
    $("#complete").text(cancel_label);
    $("#file_progress").progressBar(0); 
    $("#total_progress").progressBar(0); 
	$("div.uploading_info").show(); 

    $("div.uploading_info").css("position", "absolute");
    $("div.uploading_info").css("bottom", $("div.uploading_info").height() + bottomOffset + "px"); 
    $("div.uploading_info").css("left", pos.left + leftOffset + "px"); 

}

/*
* set's completion button according to result
 */
function progressEnd() { $("#complete").text(finished_label); }

function progressUnable() { reset(false); }

/*
 * accept any upload rate string
 */
function rateUpdate(rate) { $("#rate").text(rate + ""); }

function reset(clear_input) {
	if (typeof clear_input == "undefined" || clear_input)
		clearInput();

	$(".disableable").removeAttr("disabled");
	$("#file_progress").progressBar(0); 
	$("#total_progress").progressBar(0); 
	$("div.uploading_info").fadeOut(); 
	progressUpdate("", 0, 0);
	rateUpdate("");

}

function showProcessing() { 
	$("#processing_info").text(processing);
	$("div.processing").fadeIn(2000);
}

function doneProcessing() { 
	$("#processing_info").text(processing_done);
	$("div.processing").hide();
}

function progressUpdate(current_file, file_perc, total_perc) { 
    if (current_file.length > 0)
        $("#current_file").text(txing + " : " + current_file);
	$("#file_progress").progressBar(parseInt(file_perc)); 
	$("#total_progress").progressBar(parseInt(total_perc)); 
}

function getLogURL() { return getIISPagePath(logPageName) + "&UserId=" + username + "&Code=" + token; }

function statsUpdate(out) { $("#status").html(files_submit_info + out); }

function disableCancel() { $("#complete").attr("disabled", "disable"); }
function enableCancel() { $("#complete").removeAttr("disabled"); }
