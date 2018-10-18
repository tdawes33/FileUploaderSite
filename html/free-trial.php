<?php
session_start();
require_once '../include/utils.php';
require_once '../include/i18n.php';
require_once '../include/mm-utils.php';
require_once '../include/emails.php'; 

$email = $first_name = $last_name = $phone = "";
$warning = 'warning';
$result = '';
if (($cmd = getRequest('cmd')) && $cmd == 'signup') {
    if (($email = getRequest('email')) && ($first_name = getRequest('first_name')) &&
        ($last_name = getRequest('last_name')) && ($phone = getRequest('phone'))) {

        //check for used name
        $key = create_unique_key();
        if (user_exists($email)) {
            $result = sprintf(_('user not available'), $email);
        } else if (!($result = create_trial_user($email, $first_name, $last_name, $phone, $key))) {

            $message = "\nUsername: {$email}\nName: {$first_name} {$last_name}\nPhone: {$phone}";
            $message .= "\n\nhttp://{$_SERVER['SERVER_NAME']}//admin.php";

            if (mail(SITE_ORDERS_EMAIL, 'Inc signup request', $message)) {
                $warning = '';
                $result = _('trial request received');

                $email = $first_name = $last_name = $phone = "";

            } else {
                $result = _('trial request error');
            }
        }
    }
} else if (($key = getRequest('confirm'))) {
    header('Location: ' . urlPath() . SIGNIN_PAGE . '?confirmed=' . confirm_trial_user($key));
}

$trial_language = '<select id="trial_language" name="trial_language" tabindex="15">';
foreach ($translations as $key=>$val) {
    $selected = ($_SESSION['Language'] == $val) ? 'selected="selected"' : '';
	$trial_language .= "\n<option {$selected} lang='{$val}' xml:lang='{$val}' value='{$val}'>{$key}</option>\n";
}
$trial_language .= '</select>';

header("Content-Type: text/html; charset=utf-8");
require '../include/head.php';

print <<<HTML
</head>
<body>
    <div class="main">
HTML;

require '../include/navigation.php';

print <<<HTML
        <div class="content">
            <div class="trial_headline alpha">&nbsp;</div>
            <div class="signup">
                <form class="signup alpha" id="signup_frm" method="post" action="{$_SERVER['SCRIPT_NAME']}">
                    <input id="cmd" name="cmd" type="hidden" value="signup" />
                    <div class="signup_inputs">
                        <div class="entry flow_container">
                            <input maxlength="128" id="first_name" name="first_name" class="required" type="text" tabindex="5" value ="{$first_name}" />
                            <label for="first_name">{$first_name_label}:</label>
                        </div>
                        <div class="entry flow_container">
                            <input maxlength="128" id="last_name" name="last_name" class="required" type="text" tabindex="10" value="{$last_name}" />
                            <label for="last_name">{$last_name_label}:</label>
                        </div>
                        <div class="entry flow_container email">
                            <input maxlength="320" id="email" name="email" class="required email" type="text" tabindex="15" value="{$email}" />
                            <label for="email">{$email_label}:</label>
                        </div>
                        <div class="entry">
                            <input maxlength="64" id="phone" name="phone" class="required" type="text" tabindex="20" value="{$phone}" />
                            <label for="phone">{$phone_number_label}:</label>
                        </div>
                    </div>
                    <div class="signup_button">
                        <button class="heading" id="signup" type="submit" tabindex="20">{$submit_label}</button>
                        <div id="signup_msg" class="important fineprint">{$all_fields_required}</div>
                    </div>
                </form>
                <div class="{$warning}">{$result}</div>
            </div>
            <div class="signup_content">
                <div>{$beta_message}</div>
                <div>{$beta_message_detail}</div>
                <div>
                    <ul>
                        <li><span>{$beta_message_traffic}</span></li>
                        <li><span>{$beta_message_disk}</span></li>
                    </ul>
                </div>
                <div class="fineprint">{$beta_message_fineprint}</div>
            </div>

            <div class='language_label'>{$language_label}</div>
            <div class="language_link">{$alt_language}</div>
        </div>
HTML;

require '../include/footer.php';

print <<<HTML
        <script type="text/javascript" src="js/lib/jquery.form.js"></script>
        <script type="text/javascript" src="js/lib/jquery.validate.js"></script>
        <script type="text/javascript" src="js/lib/additional-methods.js"></script>
        <script type="text/javascript" src="js/signin.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#signup_frm").validate({
                    messages: { 
                        first_name: { required: "*" },
                        last_name: { required: "*" },
                        email: { required: "*", email: "*" },
                        phone: { required: "*" }
                    }
                });
            });
        </script>
    </div>

</body>
</html>
HTML;

?>
