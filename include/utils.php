<?php
require_once '../include/db.php';

//all available languages defined here (primary language codes only ...)
$translations = array('English'=>'en', '日本語'=>'ja');
$translations_en = array('English'=>'en', 'Japanese'=>'ja');    //for xml:lang
$locale_display = array('en'=>'English', 'ja'=>'Japanese');    //for xml:lang

define('DEV_CONFIG', '../include/dev.php');
if (file_exists(DEV_CONFIG) and filetype(DEV_CONFIG) !== 'dir') {
    include '../include/dev.php';
} else {
    define('SITE_ORDERS_EMAIL', 'orders@Incinc.com');
    define('SITE_ADMIN_EMAIL', 'Inc Admin <support@Incinc.com>');
}

define('SITE_EMAIL', 'Inc <' . ini_get('sendmail_from') . '>');
define('SITE_NO_REPLY_EMAIL', 'Inc Support <noreply@Incinc.com>');
define('SITE_TITLE', 'SendInc.com');
define('SUBSCRIPTION_LINK', '#');

define('SITE_ROOT', dirname(__FILE__));
define('SITE_DIR', dirname(dirname(__FILE__)));
define('LOCALE_DIR', '../locale');
define('TIMEZONE', 'America/Los_Angeles');
define('DATA_SOURCE', 'sqlite:' . SITE_DIR . "\\" . DATA_DIR . "\\" . DATA_FILE);
putenv('TMP=C:/temp');

define('AJAX_CONTENT', 'Content-type: application/json; charset=utf-8');
define('PAGE_CONTENT', 'Content-type: text/plain; charset=utf-8');

define('SIGNIN_PAGE', 'index.php');
define('SIGNOUT_PAGE', 'signout.php');
define('CLIENT_PAGE', 'client.php');

define('EMAIL_ADDRESS_MAX', 320);
define('PASSWORD_MAX', 12);

$redirect_url = 'index.php';

$server = $_SERVER['SERVER_NAME'];
$session_id = session_id();

$username = getSession('username');
$token = getSession('code');

function urlPath() {
    $proto = ($_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
    $path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

    if (strlen($path) == 0)
        $path = '/';
    else if (substr($path, -1, 1) != '/')
        $path .= '/';
        
    return $proto . $_SERVER['SERVER_NAME'] . $path;
}

//assume no country code
function rootDomain() {
    $domain_parts = explode('.', $_SERVER['SERVER_NAME']);
    return $domain_parts[count($domain_parts)-2] . '.' . $domain_parts[count($domain_parts)-1];
}

function isAjax() {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        return true;
    else
        return false;
}

function getTemplatePagePath($page, $encode = false) {
    global $language_en, $username, $token;

    $path = '/scripts/Inc/Inc/mmpage.dll?';
    $query = array( 'Page' => $page, 'Type' => 'User', 'Language' => $language_en, 'UserId' => $username, 'Code' => $token); 

    if ($encode)
        $path .= http_build_query($query, '', '&amp;');
    else
        $path .= http_build_query($query);

    return $path;

}

function printSigninStatus($sign_in_header, $sign_out_header) {
    global $username;
    $userinfo = '';

    if (isset($_SESSION['username'])) {
        $userinfo = '<span><a href="' . getTemplatePagePath('ChangePassword', true) . '">';
        $userinfo .= "{$username}</a></span><span>|</span>";
        $action = $sign_out_header;
        $link = urlPath() . SIGNOUT_PAGE;
    } else {
        $action = $sign_in_header;
        $link = urlPath() . SIGNIN_PAGE;
    }

    if (strtolower(basename($_SERVER['SCRIPT_NAME'])) != SIGNIN_PAGE)
        print <<<HTML
        <div class="signin_status">
            {$userinfo}
            <span><a href="{$link}">{$action}</a></span>
        </div>
HTML;
    else
        print '<div class="signin_status">&nbsp;</div>';

}

function create_unique_key() {
    return sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] .  time() . rand()); 
}

function getGlobal($collection, $name) { return isset($collection[$name]) ? $collection[$name] : ''; }
function getSession($name) { return getGlobal($_SESSION, $name); }
function getRequest($name) { return getGlobal($_REQUEST, $name); }
function getServer($name) { return getGlobal($_SERVER, $name); }

?>
