<?php
session_start();
require_once '../include/utils.php';
require_once '../include/index-i18n.php';

if (getSession('username')) //index page redirects to mail client page when user is logged in
    header('Location: ' . urlPath() . CLIENT_PAGE);

$signin_info = '';
if (($error = getRequest('reset_status'))) {
    if ($error == 1)
        $signin_info = $server_error;
    else if ($error == 2)
        $signin_info = $expired_password_reset_request;
} else if (($confirmed = getRequest('confirmed'))) {
    if ($confirmed == 'true')
        $signin_info = $trial_request_received;
    else
        $signin_info = $server_error;
}

$feature_list = '';
for ($i=0; $i<FEATURE_COUNT; $i++)
    $feature_list .= "<div>{$feature[$i]}</div>\n";

$requirement_list = '';
for ($i=0; $i<REQUIREMENT_COUNT; $i++)
    $requirement_list .= "<div>{$requirement_title[$i]}:<span class=\"requirement\">{$requirement_info[$i]}</span></div>\n";

header("Content-Type: text/html; charset=utf-8");
require '../include/head.php';

print <<<HTML
	<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>
    <div class="main">
HTML;

require '../include/navigation.php';

print <<<HTML
        <div class="content">
            <div class="pitch">
                <div class="pitch_headline alpha">&nbsp;</div>
                <div class="pitch_visual">
                    <div id="pitch_animation">&nbsp; </div>
                    <div class="free_trial">
                        <a href="free-trial.php" class="free_trial">
                            <img class="free_trial alpha" src="images/{$locale}/free-trial.png" alt="{$free_trial_tip}" title="" />
                        </a>
                    </div>
                </div>
                <div class="pitch_content">
                    <div>{$pitch_one}</div>
                    <div>{$pitch_two}</div>
                </div>
            </div>
            <div class="signin_info_container">
                <div class="signin_info">$signin_info</div>
            </div>
            <div class="signin_area">
                <div class="signin_bg alpha">
                    <form id="signin" method="post" action="signin.php">
                        <input id="cmd" name="cmd" type="hidden" value="signin" />
                        <div class="signin_entry">
                            <label for="username">{$username_label}:</label>
                            <input id="username" name="username" type="text" maxlength="320" tabindex="5" />
                        </div>
                        <div class="signin_entry">
                            <label for="password">{$password_label}:</label>
                            <input id="password" name="password" type="password" maxlength="16" tabindex="10" />
                        </div>
                        <div class="signin_button">
                            <button class="heading" type="submit" tabindex="15" accesskey="s">{$sign_in_header}</button>
                            <label for="forgot_password" id="forgot_password_label">{$forgot_password_label}</label>
                            <div class="forgot_password">
                                <input id="forgot_password" name="forgot_password" type="checkbox" tabindex="0" value="true" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bullet_points">
                <div class="bullets">
                    <div class="heading">{$feature_header}</div>
                    {$feature_list}
                </div>
                <div class="bullets">
                    <div class="heading">{$requirement_header}</div>
                    {$requirement_list}
                </div>
            </div>

            <div class='language_label'>{$language_label}</div>
            <div class="language_link">{$alt_language}</div>
        </div>
HTML;

require '../include/footer.php';

print <<<HTML
        <script type="text/javascript" src="js/lib/jquery.form.js"></script>
        <script type="text/javascript" src="js/lib/swfobject.js"></script>
        <script type="text/javascript" src="js/signin.js"></script>
        <script type="text/javascript">
            var flashvars = {};
            var params = { play: "true", loop: "true", quality: "high", wmode: "transparent", bgcolor: "#e0dfe3" };
            var attributes = { align: "middle" };
            swfobject.embedSWF("images/{$locale}/pitch.swf", "pitch_animation", "360", "134", "9.0.0", "", flashvars, params, attributes);

            language = "{$locale}";
            language_en = "{$language_en}";
            username_label = "{$username_label}";
            password_label = "{$password_label}";
            sign_in_fields_required = "{$sign_in_fields_required}";
            email_required = "{$email_required}";
            greater_than = "{$greater_than}";
            entry_type_max = "{$entry_type_max}";

            $(document).ready(function() { $("#username").focus(); });
        </script>

    </div>
</body>
</html>
HTML;

?>
