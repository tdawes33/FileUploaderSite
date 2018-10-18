
//ISO 639-1 Code
var languages = new Array();
languages["en"] = "English";    //default language
languages["ja"] = "Japanese";

function getBrowserLanguage() {
    var language = languages[0];

    if (navigator.language && languages[navigator.language.split("-")[0]])
        language = languages[navigator.language.split("-")[0]];
    else if (navigator.userLanguage && languages[navigator.userLanguage.split("-")[0]])
        language = languages[navigator.userLanguage.split("-")[0]];

    return language;
}

