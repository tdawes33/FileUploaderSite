<?php

global $locale, $server;

$locale_file = ($locale == 'en') ? '' : '_j';

$please_contact_us = _('please contact us');

$activation_subject = _('activation email subject');
$activation_expiration = _('activation email expiration notice');
$activation_info = _('activation email login info');
$activation_thanks = _('activation email thanks');

$confirmation_subject = _('confirmation email subject');
$confirmation_thanks = _('confirmation email thanks');

$email_html_head_top = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
				"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$locale}" xml:lang="{$locale}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script>
HTML;
$email_style = '';//file_get_contents(SITE_ROOT.'/html/css/email.css');
$email_javascript = '';

$email_html_head = $email_html_head_top . $email_java_script . $email_style . $email_html_head_bottom;


$password_reset_body_html = '';

$confirmation_body = <<<TEXT

{$confirmation_thanks}

{$confirmation_link}

{$please_contact_us}

{$email_signature}

TEXT;

$activation_body = <<<TEXT

{$activation_thanks}

{$login_info_label}

http://{$server}

{$email_label}: xxxx
{$password_label}: xxxx

{$free_trial_expiration}

{$please_contact_us}

{$email_signature}

TEXT;

?>
