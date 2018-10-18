<?php
session_start();
require_once '../include/utils.php';
require_once '../include/utils.php';
require_once '../include/i18n.php';

$newsletter_language = '<select disabled="disabled" id="newsletter_language" name="newsletter_language" tabindex="20">';
foreach ($translations as $key=>$val) {
    $selected = ($_SESSION['Language'] == $val) ? 'selected="selected"' : '';
	$newsletter_language .= "\n<option {$selected} lang='{$val}' xml:lang='{$val}' value='{$val}'>{$key}</option>\n";
}
$newsletter_language .= '</select>';

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
            <div class="newsletter_headline alpha">&nbsp;</div>
            <div class="newsletter_content">
                <div>${subscribe_message} (${coming_soon})</div>
                <div class="newsletter_subscribe">
                    <form method="post" action="{$_SERVER['SCRIPT_NAME']}">
                        <div class="entry flow_container">
                            <input disabled="disabled" id="email" name="email" type="text" tabindex="5" /> <label for="email">{$email_label}:</label>
                        </div>
                        <div class="entry">
                            <input disabled="disabled" id="name" name="name" type="text" tabindex="10" /> <label for="name">{$name_label}:</label>
                        </div>
                        <div class="entry">
                            <select disabled="disabled" id="action" name="action" tabindex="15">
                                <option>{$subscribe_label}</option>
                                <option>{$unsubscribe_label}</option>
                            </select>
                            <label for="action">{$newsletter_action_label}:</label>
                        </div>
                        <div class="entry">
                            {$newsletter_language}
                            <label for="newsletter_language">{$language_label}:</label>
                        </div>
                        <button disabled="disabled" class="heading" type="submit" tabindex="25">{$submit_label}</button>
                    </form>
                </div>
            </div>
            <div class='language_label'>{$language_label}</div>
            <div class="language_link">{$alt_language}</div>
        </div>
HTML;

require '../include/footer.php';

print <<<HTML
        <script type="text/javascript" src="js/lib/jquery.form.js"></script>

    </div>

</body>
</html>
HTML;

?>
