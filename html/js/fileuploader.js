
var debug = "true";

var id = "fileuploader";
var className = "file_table";
var height = "158";
var width = "598";
var jars = "FileUploader.jar,swing-worker.jar,json_simple.jar";
var jar_versions = "2.2.11.b,1.2.0.0,0.1.0.0";
var classId = "com.Incinc.util.FileUploaderApplet.class";
var splash = "images/Inc.png";
var bg = "#ffffff";
var fg = "#000000";

var clsid = "8AD9C840-044E-11D1-B3E9-00805F499D93";     //request the latest installed version
var type = "application/x-java-applet;version=1.5";     //minimum version hopefully
var codebase = " codebase='http://java.sun.com/update/1.6.0/jinstall-6u10-windows-i586.cab'";

//Non-mac fonts
var ja_font = "Meiryo";
var en_font = "SansSerif";

	
function printParam(name, val) {
	document.write("<param name='" + name + "' value='" + val + "' />");
}

function printWarning() {
	document.write("<div style='text-align: center;'>");
	document.write("<p style='font-weight: bold;'>" + java_required + "</p></div>");
	document.write("<div><a href='http://www.java.com/en/download/index.jsp'>http://www.java.com/en/download/index.jsp</a></div>");
}

function printParams() {

	printParam("code", classId);
	printParam("cache_option", "Plugin");
	printParam("cache_archive", jars);
	printParam("cache_version", jar_versions);
	printParam("classloader_cache", "false");   //don't cache state
	printParam("mayscript", "true");
	printParam("initial_focus", "false");
	printParam("type", type);
	printParam("image", splash);
	printParam("boxbgcolor", bg);
	printParam("boxfgcolor", fg);
	printParam("boxborder", "true");
	printParam("centerimage", "true");
	printParam("java_version", "1.5+");
	printParam("debug", debug);
    printParam("java_arguments", "-ea:com.Incinc.util");
}

function printServerParams(server, service, username, token, session_id, locale) {

	printParam("server", server);
	printParam("service", service);
	printParam("username", username);
	printParam("token", token);
	printParam("locale", locale);
	printParam("session_id", session_id);
	printParam("ja_font", ja_font);
	printParam("en_font", en_font);

}

function printApplet(server, service, username, token, session_id, locale) {

	//not IE
	document.write("<!--[if !IE]> -->");
	document.write("<object id='" + id + "' name='" + id + "' height='" + height + "' width='" + width + "'");
	document.write("classid='java:" + classId + "' type='" + type + "' class='" + className + "'>");
	printParams();
	printServerParams(server, service, username, token, session_id, locale);
	printWarning();
	document.write("</object>");
	document.write("<!--<![endif]-->");

	//for IE; classid/codebase is used here to indicate what do download if jre is not installed
	document.write("<!--[if IE]>");
	document.write("<object id='" + id + "' name='" + id + "' classid='clsid:" + clsid + "' class='" + className + "' " + codebase + ">");
	printParams();
	printServerParams(server, service, username, token, session_id, locale);
	printWarning();
	document.write("</object><![endif]-->");

}
