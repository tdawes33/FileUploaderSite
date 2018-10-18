
$(document).ready(function() {
	$(".languageLink").bind("click", function() { 
		$("#Language").val(this.lang);
		$("#language_frm").submit();
		return false;
	});
});
