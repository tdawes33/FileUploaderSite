<?php

if (basename($_SERVER['SCRIPT_NAME']) == 'client.php')
    $languages = "<div class='language_label'>{$language_label}</div><div class='language_link'>{$alt_language}</div>";
else
    $languages = '';

printSigninStatus($sign_in_header, $sign_out_header);
print <<<HTML
    <div class="navigation">
        <div class="navigation_bg alpha">
            <form id="language_frm" action="{$_SERVER['PHP_SELF']}" method="post">
                <input type="hidden" id="Language" name="Language" value="{$locale}" />
            </form>
            <a href="index.php" class="home_link">
                <img class="home_link" src="images/home.png" title="{$home_tip}" alt="{$home_tip}" />
            </a>
            <div class="site_links">
                <a href="free-trial.php" title="{$free_trial_tip}">{$free_trial_link}</a>
                <a href="overview.php" title="{$overview_tip}">{$overview_link}</a>
                <a href="pricing.php" title="{$pricing_tip}">{$pricing_link}</a>
                <a href="newsletter.php" title="{$newsletter_tip}">{$newsletter_link}</a>
                <a href="support.php" title="{$support_tip}">{$support_link}</a>
            </div>
        </div>
        {$languages}
    </div>
HTML;

?>
