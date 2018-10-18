<?php
session_start();
require_once '../include/utils.php';
require_once '../include/i18n.php';

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
            <div class="overview">
                <div class="overview_headline alpha">&nbsp;</div>
                <div class="howitworks alpha">&nbsp;</div>
            </div>
            <div class="overview_trial">
                <a href="free-trial.php" class="overview_trial">
                    <img class="overview_trial alpha" src="images/{$locale}/free-trial2.png" alt="{$free_trial_tip}" title="" />
                </a>
            </div>

            <div class='language_label'>{$language_label}</div>
            <div class="language_link">{$alt_language}</div>
        </div>
HTML;

require '../include/footer.php';

print <<<HTML

    </div>

</body>
</html>
HTML;

?>
