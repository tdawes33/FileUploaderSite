<?php

define('PASSWORD_RESET_TIMEOUT', "1");  //in hours

define('WINDOWS_DIR_ENV', "WINDIR");
define('FILE_NAME_Inc', "Inc.ini");
define('SECTION_System', 'System');
define('VALUE_Administrator', 'Administrator');
define('VALUE_AccountDirectory', 'AccountDirectory');
define('VALUE_ConfigDirectory', 'ConfigDirectory');
define('SECTION_Settings', 'Settings');
define('VALUE_Code', 'Code');
define('VALUE_Group', 'Group');
define('VALUE_CreateDate', 'CreateDate');
define('VALUE_DeleteDate', 'DeleteDate');

define('GROUP_DIRECTORY_NAME', 'Group');
define('USERS_DIRECTORY_NAME', 'Users');

/*
 * check credentials by finding user's ini file and testing against code
 */
function isUserInformationCorrect($userId, $password) {

    if (($ini = getUserIni($userId))) {
        if (encode($password) == strtolower($ini[SECTION_Settings][VALUE_Code]))
            return true;
    }

    return false;
}

/*
 * an active user has an empty (or noexisting) deletedata in ini
 */
function isUserActive($userId) {

    if (($ini = getUserIni($userId))) {
        if (!isset($ini[SECTION_Settings][VALUE_DeleteDate]) || strlen($ini[SECTION_Settings][VALUE_DeleteDate]) == 0)
         return true;
    }

    return false;
}

function get_user_token($email) {
    $token = '';

    if (($ini = getUserIni($email)))
        $token = $ini[SECTION_Settings][VALUE_Code];

    return $token;
}

/*
 * return array of user's ini file entries, if it exists
 */
function getUserIni($userId) {

    if (($accounts = getAccountDirectory())) {
        $user_ini = $accounts . DIRECTORY_SEPARATOR . USERS_DIRECTORY_NAME .  DIRECTORY_SEPARATOR . bin2hex($userId) . '.ini';

        if (file_exists($user_ini) && ($ini = parse_ini_file($user_ini, true)))
            return $ini;
    }

    return false;
}

function create_trial_user($email, $first_name, $last_name, $phone, $key) {

    try {
        if (!($db = new PDO(DATA_SOURCE)))
            return _('trial request error');

        $sql = 'insert into "main"."trial" (email, password, first_name, last_name, phone, key) values (?, ?, ?, ?, ?, ?)';
        if (!($stmt = $db->prepare($sql)))
            return _('trial request error');

        $args = func_get_args();
        array_splice($args, 1, 0, substr(create_unique_key(), 0, 6));

        if (!$stmt->execute($args))  {
            $err = $stmt->errorInfo();

            if ($err[1] == DB_DUPLICATE_ERR)
                return sprintf(_('user not available'), $email);
            else
                return _('trial request error');
        }
        send_confirmation_email($email, $first_name, $last_name, $key);

    } catch(PDOException $e) {
        return _('trial request error');
    }

    return '';
}

function confirm_trial_user($key) {

    try {
        if (!($db = new PDO(DATA_SOURCE)))
            return 'false';

        $sql = "update 'main'.'trial' set state=(select id from 'main'.'trial_state' where value='confirmed') where key='{$key}'";
        if (!$db->exec($sql))
            return 'false';

    } catch(PDOException $e) {
        return 'false';
    }

    return 'true';
}

function create_password_reset($email, $key, $language) {
    $error = sprintf(_('request error'), _('password reset description'));

    try {
        if (!($db = new PDO(DATA_SOURCE)))
            return $error;

        //return error on an active request
        $sql = "select (strftime('%s', 'now', 'localtime') - strftime('%s', expiry))/3600 from password_reset_request where email='{$email}'";
        if (!($stmt = $db->query($sql)))
            return $error;
        else if (($expiry = $stmt->fetch()) && $expiry[0] <= PASSWORD_RESET_TIMEOUT)
            return _('password reset duplicate error') . ' ' . _('password reset sent');

        $sql = 'select datetime("now","localtime", "+' . PASSWORD_RESET_TIMEOUT  . ' hours")';
        if (!($stmt = $db->query($sql)) || !($expiry = $stmt->fetch()))
            return $error;

	    $args = func_get_args();
        $args[] = $expiry[0];

        $sql = 'replace into "main"."password_reset_request" (email, key, language, expiry) values (?, ?, ?, ?)';
        if (!($stmt = $db->prepare($sql)))
            return $error;

        if (!$stmt->execute($args))
            return $error;

    } catch(PDOException $e) {
        return $error;
    }

    return '';
}

function remove_password_reset($db, $email) {
    $sql = "delete from 'main'.'password_reset_request' where email='{$email}'";
    return !($stmt = $db->query($sql));
}

function process_password_reset_link($key) {
    $url = "http://{$_SERVER['SERVER_NAME']}/";
    global $redirect_url;

    try {
        if (!($db = new PDO(DATA_SOURCE)))
            return "{$url}{$redirect_url}?reset_status=1";

        $sql = "select (strftime('%s', 'now', 'localtime') - strftime('%s', expiry))/3600, email, language from password_reset_request where key='{$key}'";
        if (!($stmt = $db->query($sql)))
            return "{$url}{$redirect_url}?reset_status=2"; //count non-existence as timeout

        if (!($reset = $stmt->fetch()) || $reset[0] >= PASSWORD_RESET_TIMEOUT)
            return "{$url}{$redirect_url}?reset_status=2";

        $email = $reset[1];
        $language = $reset[2];
        remove_password_reset($db, $email);

        if (!($token = get_user_token($email)))
            return "{$url}{$redirect_url}?reset_status=1";

        return "{$url}scripts/psp/Inc/mmpage.dll?Page=ResetPassword&Type=User&Language={$language}&UserId={$email}&Code={$token}";
    } catch(PDOException $e) {
        ;
    }

}

function user_exists($email) {

    $user_ini = getAccountDirectory() . DIRECTORY_SEPARATOR . USERS_DIRECTORY_NAME .  DIRECTORY_SEPARATOR . bin2hex($email) . '.ini';
    return file_exists($user_ini);

}

/*
 * php version of di encode function. password obfuscation
 */
function encode($str) {
    $encoded = '';

    for ($i = 0; $i < strlen($str); $i++) {

        $val = ord($str[$i]) + $i;

        if (!($i % 2))
            $val ^= 0xff;

        $encoded .= sprintf('%02x', $val);
    }

    return strrev($encoded);
}

/*
 * Retrieve from Inc's ini file, the directory which contains ALL user ini files
 */
function getAccountDirectory() {
    
    if (!($windir = getWindowsDirectory()))
        return false;

    $ini_file = $windir . '/' . FILE_NAME_Inc;
    if (!($ini = parse_ini_file($ini_file, true)))
        return false;

    return $ini[SECTION_System][VALUE_AccountDirectory];
}

function getWindowsDirectory() { return getenv(WINDOWS_DIR_ENV); }

?>
