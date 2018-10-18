<?php 
require_once '../include/utils.php';

function sendPasswordResetEmail($recipients, $key) {
    $click_the_link = _('click the link for new password');
    $expiry = sprintf(ngettext('link expires after %1$s hour', 'link expires after %1$s hours', PASSWORD_RESET_TIMEOUT), PASSWORD_RESET_TIMEOUT);
    $copy_the_link = _('copy/paste the link for new password');
    $safely_ignore = _('safely ignore this message');

	$password_reset_body = <<<TEXT


{$click_the_link}
{$expiry}
http://{$_SERVER['SERVER_NAME']}/signin.php?reset={$key}

{$copy_the_link}

{$safely_ignore}


TEXT;

    return sendEmail(SITE_NO_REPLY_EMAIL, $recipients, _('password reset email subject'), $password_reset_body);
}

function send_confirmation_email($email, $first_name, $last_name, $key) {
    $thanks = _('confirmation email thanks');
    $contact_us = _('please contact us');
    $copy_the_link = _('copy/paste the link for new password');

	$confirmation_body = <<<TEXT


$thanks

http://{$_SERVER['SERVER_NAME']}/free-trial.php?confirm={$key}

$copy_the_link

$contact_us


TEXT;

    return sendEmail(SITE_NO_REPLY_EMAIL, $email, _('confirmation email subject'), $confirmation_body);

}

function send_activation_email($email) {
    $password;


	$activation_body = <<<TEXT
    Inc.com Free Trial (Activation Email)

Thank you for confirming your request for a free trial of Inc. Your trial account is now active.

Here is your login information.

http://www.Inc.com
Email:
Password:

*This account will expire one month from today.

Please contact us if you have any questions.


TEXT;
    return sendEmail(SITE_NO_REPLY_EMAIL, $email, _('activation email subject'), $activation_body);



}

function sendEmail($sender, $recipients, $subject, $body, $body_html = '', $cc = '', $bcc = '') {
    $eol = "\n";
	$email_signature = <<<TEXT
Inc, Inc
Contact: orders@Incinc.com
TEXT;

    date_default_timezone_set(TIMEZONE);
	# Common Headers
    $headers = "To: {$recipients}{$eol}";
	$headers .= "From: {$sender}{$eol}";
    if ($cc)
        $headers .= "Cc: {$cc}{$eol}";
    if ($bcc)
        $headers .= "Bcc: {$bcc}{$eol}";
	$headers .= "Reply-To: {$sender}{$eol}";
	$headers .= "Return-Path: {$sender}{$eol}";
	$headers .= 'Message-ID: <' . time() . " {$sender}>{$eol}";
	$headers .= 'X-Mailer: PHP v' . phpversion() . $eol;

    $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=\n";

    if (!$body_html) { //send text only
        $headers .= "Content-Type: text/plain; charset=utf-8{$eol}";
        $headers .= "Content-Transfer-Encoding: 8bit{$eol}";
        return mail($recipients, SITE_TITLE . " - {$subject}", $body . $email_signature, $headers, "-f{$sender}");
    }

	$mime_boundary = sha1(date('r', time()));
	$htmlalt_mime_boundary = "{$mime_boundary}_htmlalt";

	$headers .= "MIME-Version: 1.0{$eol}";
	$headers .= "Content-Type: multipart/related; boundary=\"{$mime_boundary}\"{$eol}"; 
		
    $msg = <<<MSG
--{$mime_boundary}
Content-Type: multipart/alternative; boundary="{$htmlalt_mime_boundary}"
--{$htmlalt_mime_boundary}
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: 8bit
{$body}

--{$htmlalt_mime_boundary}
Content-Type: text/html; charset=utf-8
Content-Transfer-Encoding: 8bit
{$body_html}

--{$htmlalt_mime_boundary}--

--{$mime_boundary}--

MSG;
		
    return mail($recipients, SITE_TITLE . " - {$subject}", $msg, $headers, "-f{$sender}");
}

?>
