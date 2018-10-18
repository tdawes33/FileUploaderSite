<?php
session_start();
session_destroy();
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
        <div class="content admin_content">
            <form method="post" action="/scripts/Inc/Inc/mmlogin.dll">
                <input type="hidden" name="Command" value="GlobalLogin" />
                <input type="hidden" name="Page" value="Index" />
                <input type="hidden" name="Language" value="{$locale_display[$locale]}" />

                <div class="heading">{$login_label}</div>
                <div>{$login_message}</div>

                <div class="subcontent">
                    <div class="entry">
                        <input type="text" id="UserId" name="UserId" size="30" maxlength="127" />
                        <label for="UserId">{$username_label}: </label>
                    </div>
                    <div>{$login_syntax}</div>

                    <div class="entry">
                        <input type="password" id="Password" name="Password" size="20" maxlength="16" />
                        <label for="Password">{$password_label}: </label>
                    </div>

                    <div>{$password_syntax}</div>

                    <button class="heading" type="submit" id="submit" name="submit">   {$login_label}  </button>
                </div>
            </form>
            <div class='language_label'>{$language_label}</div>
            <div class="language_link">{$alt_language}</div>
        </div>

HTML;

require '../include/footer.php';

print <<<HTML
        <script type="text/javascript">
            $(document).ready(function() { $("#UserId").focus(); });
        </script>

    </div>

</body>
</html>
HTML;
?>
