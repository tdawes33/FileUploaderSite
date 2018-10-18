<?php
require_once 'utils.php';

//determine language choice
if (($lang = getRequest('Language')) && in_array($lang, $translations)) {
	$_SESSION['Language'] = $locale = $lang;    //user requested
} else if (($lang = getRequest('Language')) && array_key_exists($lang, $translations_en)) {
    $lang = $translations_en[$lang];
	$_SESSION['Language'] = $locale = $lang;    //user requested
} else if (($lang = getSession('Language')) && in_array($lang, $translations)) {
	$locale = $lang;    //user session
} else if (($langs = getServer('HTTP_ACCEPT_LANGUAGE'))) {
    foreach (split(',', $langs) as $key=>$val) {    //user's browser
        if (in_array(substr($val, 0, 2), $translations)) {
            $_SESSION['Language'] = $locale = substr($val, 0, 2);
            break;
        }
    }
} else {
    $_SESSION['Language'] = $locale = $translations['English'];
}
$language_en = array_search($locale, $translations_en);	//for passing to isapi megamail
putenv("LC_ALL=$locale");

//create markup for language menu
$alt_language = '<ul class="language_list">';
foreach ($translations as $key=>$val) {
    $selected = ($_SESSION['Language'] == $val) ? 'selected' : '';
    $alt_language .= "\n<li><a href='?Language={$val}' lang='{$val}' xml:lang='{$val}' class='{$selected}'>{$key}</a></li>\n";
}
$alt_language .= '</ul>';

 //.po & .mo files should be at $locale_dir/$locale/LC_MESSAGES/messages.{po,mo}
$domain = 'messages';   //file source default
setDomain($domain);

$product_html = <<<HTML
    <span class="product">
        <span class="product_first">Mega</span><span class="product_second">Mail</span>
    </span>
HTML;

/* global and public area translations */
$site_title = _('site title');
$page_title = $site_title . ' : ' . _(basename($_SERVER['SCRIPT_NAME'], '.php') . ' title');

/* navigation */
$home_tip = _('home tip');
$free_trial_link = _('free-trial link');
$free_trial_tip = _('free-trial tip');
$all_fields_required = _('all fields required');
$overview_link = _('overview link');
$overview_tip = _('overview tip');
$pricing_tip = _('pricing tip');
$pricing_link = _('pricing link');
$newsletter_link = _('newsletter link');
$newsletter_tip= _('newsletter tip');
$support_link = _('support link');
$support_tip = _('support tip');
$i18n_header = _('i18n header');
$i18n_link = _('i18n link');
$i18n_tip = _('i18n tip');
$psp_privacy_policy = _('psp privacy policy');
$psp_about_link = _('psp_about_link');
$psp_about_url = _('psp_about_url');
$sign_in_header = _('sign in header');
$sign_out_header = _('sign out header');

$please_contact = _('please contact us');

$sign_in_fields_required = _('sign in fields required');
$email_required = _('email required');
$sign_in_invalid = _('sign in invalid');
$username_label = _('username label');
$password_label = _('password label');
$forgot_password_label = _('forgot password label');
$greater_than = _('greater than');
$entry_type_max = _('entry type max');

$email_label = _('email label');
$first_name_label = _('first name label');
$last_name_label = _('last name label');
$name_label = _('name label');

$newsletter_action_label = _('newsletter action label');
$language_label = _('language label');
$os_label = _('os label');
$memory_label = _('memory label');
$disk_space_label = _('disk space label');
$subject_label = _('subject label');
$phone_number_label = _('phone number label');
$details_label = _('details label');
$support_subject_1 = _('support subject 1');
$support_subject_2 = _('support subject 2');
$submit_label = _('submit label');

$beta_message = sprintf(_('beta message'), $product_html, $product_html);
$beta_message_detail = _('beta message detail');
$beta_message_traffic = _('beta message traffic');
$beta_message_disk = _('beta message disk');
$beta_message_fineprint = _('beta message fineprint');

$subscribe_message = _('subscribe message');
$subscribe_label = _('subscribe label');
$unsubscribe_label = _('unsubscribe label');
$thank_you = _('thank you');
$coming_soon = _('coming soon');

$login_label = _('login label');
$login_message = _('login message');
$login_syntax = _('login syntax');
$password_syntax = _('password syntax');
$server_error = _('server error');
$expired_password_reset_request = _('expired password reset request');

$trial_request_error = _('trial request error');
$trial_request_received = _('trial request received');

$plan_label = _('plan label');
$plan_1_overview = _('plan 1 overview');
$plan_2_overview = _('plan 2 overview');
$plan_3_overview = _('plan 3 overview');
$plan_4_overview = _('plan 4 overview');
$applied_plan = _('applied plan');
$overlimit_plan = _('overlimit plan');
$subscription_form = '<a href="' . SUBSCRIPTION_LINK . '">' . _('subscription form') . '</a>';
$subscribe_instructions = sprintf(_('subscribe instructions'), $subscription_form);
$dreamersi_discount = sprintf(_('dreamersi discount'), SITE_ORDERS_EMAIL, SITE_ORDERS_EMAIL);

$beta_message = sprintf(_('beta message'), $product_html, $product_html);

function resetDomain() {
    global $domain;

    bindtextdomain($domain, LOCALE_DIR);   //load from domain.mo
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
}

function setDomain($domain) {
    bindtextdomain($domain, LOCALE_DIR);   //load from domain.mo
    bind_textdomain_codeset($domain, 'UTF-8');
    textdomain($domain);
}

?>
