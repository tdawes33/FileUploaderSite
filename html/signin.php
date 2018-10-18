<?php
session_start();
require_once '../include/utils.php';
require_once '../include/i18n.php';
require_once '../include/mm-utils.php';
require_once '../include/emails.php'; 

if (isAjax())
    header(AJAX_CONTENT);

$ret['result'] = 'failure';

if (($key = getRequest('reset'))) {
    header('Location: ' .  process_password_reset_link($key));
    exit;
} else if (getRequest('forgot_password')) {
    $email = strtolower(trim(getRequest('username')));
    if (isUserActive($email)) {
        $key = create_unique_key();

        if (!($ret['info'] = create_password_reset($email, $key, $locale_display[$locale]))) {
            if (!sendPasswordResetEmail($email, $key)) {
                $ret['info'] = sprintf(_('request error'), _('password reset description'));
                remove_password_reset($email);
            } else {
                $ret['info'] = _('password reset sent');
            }
        }
    } else {
        $ret['info'] = _("email not recognized");
    }
} else if (!($email = strtolower(trim(getRequest('username')))) || !($password = getRequest('password'))) {
    $ret['info'] = $sign_in_fields_required;
} else if (strlen($email) > EMAIL_ADDRESS_MAX) {
    $ret['info'] = "{$username_label} {$greater_than} " . EMAIL_ADDRESS_MAX . " {$entry_type_max}.";
} else if (strlen($password) > EMAIL_ADDRESS_MAX) {
    $ret['info'] = "{$password_label} {$greater_than} " . PASSWORD_MAX . " {$entry_type_max}.";
} else if (!isUserInformationCorrect($email, $password) || !isUserActive($email)) {
    $ret['info'] = $sign_in_invalid;
} else {
    $ret['result'] = 'success';
    $ret['home'] = urlPath() . 'client.php';

    $_SESSION['username'] = $email;
    $_SESSION['code'] = encode($password);
}

if (isAjax())
    print json_encode($ret);
else if ($ret['result'] == 'success')
    header("Location: {$ret['home']}");
else
    header('Location: ' . urlPath());

?>
