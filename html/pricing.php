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
            <div class="pricing_headline alpha">&nbsp;</div>
            <div class="pricing_content">
                <div class="beta_message">{$beta_message}</div>
                <div class="plans">
                    <div class="plan alpha">
                        <div class="heading">{$plan_label} 1</div>
                        <div class="detail">{$plan_1_overview}</div>
                    </div>
                    <div class="plan alpha">
                        <div class="heading">{$plan_label} 2</div>
                        <div class="detail">{$plan_2_overview}</div>
                    </div>
                </div>
                <div class="fineprint">{$applied_plan}</div>
                <div class="plans">
                    <div class="plan alpha">
                        <div class="heading">{$plan_label} 3</div>
                        <div class="detail">{$plan_3_overview}</div>
                    </div>
                    <div class="plan alpha">
                        <div class="heading">{$plan_label} 4</div>
                        <div class="detail">{$plan_4_overview}</div>
                    </div>
                </div>
                <div class="fineprint">{$overlimit_plan}</div>
                <div class="subscribe_instructions">{$subscribe_instructions}</div>
                <div class="discount">{$discount}</div>
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
