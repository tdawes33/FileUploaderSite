<?php
@session_start();
require_once '../../include/mm-utils.php';

define('DATA_FORMAT', 'json');
define('SHARED_SECRET', '4dkKGAw4Xdrw3wrFWNFwQxjzunYVJPrD8aV8BjBM');
// how much leeway to allow in timestamps, in seconds
define('TIME_VARIANCE', 3600);

/**
 * Get a user's hashed password
 *
 * @param string the username
 * @return string the hashed password, or FALSE if the user doesn't exist
 **/
function getHashedPassword($username) {

    if (($ini = getUserIni($username))) {
        $code = strtolower($ini[SECTION_Settings][VALUE_Code]);
        $password = decode($code);
        return sha1($password);
    } else {
        return false;
    }
}

/**
 * Setup a session for the verified user
 *
 * @param string the verified username
 */
function doUserLogin($username) {
    $_SESSION['username'] = $username;
    if (($ini = getUserIni($username))) {
        $_SESSION['code'] = strtolower($ini[SECTION_Settings][VALUE_Code]);
    }

    header('Location: /client.php');
    exit;
}

/**
 * Destroy the user's session
 */
function doUserLogout() {
    @session_start();
    @session_destroy();
}
//////////// There should be no need to edit below this line. ////////


// process the request
echo wda_encode(verifyToken(ifset($_GET)));


/**
 * verify a token from the SSO server
 *
 * @param $p Array containing 'token', 'username' and 'password' indices.
 * @return Array with 'valid' containing the result and the username
 **/
function verifyToken($p) {
    $action = ifset($p['action'], 'verify');
    // first deal with logout, as it requires no parameters
    if ($action === 'logout') {
        doUserLogout();
        echo '<img src="http://dev/img/check-mark.png" />';exit;
    }


    $token = ifset($p['token']);
    $user  = ifset($p['username']);
    $time  = ifset($p['timestamp']);

    // input supplied?
    if (empty($token) || empty($user) || empty($time)) {
        return array('valid' => false, 'error' => 'bad_request');
    }

    // recent?
    $now = time();
    if (abs($now - $time) > TIME_VARIANCE) {
        return array('valid' => false, 'error' => 'bad_timestamp');
    }

    // user exists?
    $pass = getHashedPassword($user);
    if ($pass === false) {
        return array('valid' => false, 'error' => 'user_mismatch');
    }

    // this needs to match UsersController::_genHash on the SSO server
    $check = sha1(SHARED_SECRET . $pass . $time . $user);

    $valid = ($check === $token);
    $return = array('valid' => $valid);
    if ($valid) {
        $return['username'] = $user;
    } else {
        $return['error'] = 'token_mismatch';
    }


    if ($action === 'login') {
        doUserLogin($user);
    }

    // right IP?
    $ip = ifset($_SERVER['REMOTE_ADDR']);
    if (!(substr($ip, 0, 11) === '216.230.241' || substr($ip, 0, 5) === '10.0.')) {
        return array('valid' => false, 'error' => 'ip_not_allowed');
    }

    return $return;
}

// Utilities
function wda_encode($data) {
    // DATA_FORMAT is an external dependency - try to failover
    if (!defined('DATA_FORMAT')) define('DATA_FORMAT', 'json');

    switch (DATA_FORMAT) {
    case 'json':
        return json_encode($data);
    case 'php':
        return serialize($data);
    default:
        return false;
    }
}

function ifset(&$var, $default=null) {
    return (isset($var) ? $var : $default);
}

