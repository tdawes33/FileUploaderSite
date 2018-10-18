<?php
session_start();
require_once '../include/utils.php';
require_once '../include/i18n.php';

$result = '';
if (($cmd = getRequest('cmd')) && $cmd == 'support') {
    if (($email = getRequest('email'))) {
        $eol = "\n";

        $sender = ini_get('sendmail_from');

        $os = getRequest('os');
        $memory = getRequest('memory');
        $disk = getRequest('disk');
        $subject = '=?UTF-8?B?' . base64_encode(getRequest('subject')) . '?=';
        $phone = getRequest('phone');
        $details = getRequest('details');

        $message = "\nusername: {$email}\nos: {$os}\nmemory: {$memory}";
        $message .= "\ndisk {$disk}\nphone: {$phone}\n\n\nDetails: {$details}";

        $headers = "Content-Type: text/plain; charset=utf-8{$eol}";
        $headers .= "Content-Transfer-Encoding: 8bit{$eol}";
	    $headers .= 'X-Mailer: PHP v' . phpversion() . $eol;

        if (mail('timothy.dawes@Incinc.com', "Inc support request : {$subject}", $message, $headers, "-f{$sender}"))
            $result = _('support request received');
        else
            $result = _('support request error');
    }
}

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
            <div class="support_headline alpha">&nbsp;</div>
            <div class="support_content">
                <form method="post" action="{$_SERVER['SCRIPT_NAME']}">
                    <input id="cmd" name="cmd" type="hidden" value="support" />
                    <div class="support_left">
                        <div class="entry">
                            <input id="email" name="email" type="text" tabindex="5" /> <label for="email">* {$email_label}:</label>
                        </div>
                        <div class="entry">
                            <input id="os" name="os" type="text" tabindex="10" /> <label for="os">{$os_label}:</label>
                        </div>
                        <div class="entry">
                            <input id="memory" name="memory" type="text" tabindex="15" /> <label for="memory">${memory_label}:</label>
                        </div>
                        <div class="entry">
                            <input id="disk" name="disk" type="text" tabindex="20" /> <label for="disk">{$disk_space_label}</label>
                        </div>
                        <div class="entry">
                            <select id="subject" name="subject" tabindex="25">
                                <option>{$support_subject_1}</option>
                                <option>{$support_subject_2}</option>
                            </select>
                            <label for="subject">{$subject_label}:</label>
                        </div>
                        <div class="entry">
                            <input id="phone" name="phone" type="text" tabindex="30" /> <label for="phone">{$phone_number_label}:</label>
                        </div>
                    </div>
                    <div class="support_right">
                        <div class="entry"> 
                            <textarea id="details" name="details" tabindex="35" cols="30" rows="10"></textarea>
                            <label for="details">{$details_label}:</label>
                        </div>
                        <button class="heading" type="submit" tabindex="40">{$submit_label}</button>
                    </div>
                    <div class="result">{$result}</div>
                </form>
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
