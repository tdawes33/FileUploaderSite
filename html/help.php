<?php
session_start();
require_once '../include/utils.php';
require_once '../include/help-i18n.php';

$help = "help.php";

header('Content-type: text/html; charset=utf-8');
$request = getRequest("category");

print <<<HTML
<div class="help">
    <script type="text/javascript">
        $(".help_index_title").bind("click", function() { $(".help_category").hide(); $(".help_index").fadeIn(); });
        $(".help_to_index").bind("click", function() { $(".help_category").hide(); $(".help_index").fadeIn(); });

        /*
        $("div.help_composing_title").bind("click", function() { displayHelpContent("div.composing_help"); });
        $("div.help_attachments_title").bind("click", function() { displayHelpContent("div.attachments_help"); });
        $("div.help_sending_title").bind("click", function() { displayHelpContent("div.sending_help"); });
        $("div.help_resuming_title").bind("click", function() { displayHelpContent("div.resuming_help"); });
        $("div.help_toolbar_title").bind("click", function() { displayHelpContent("div.toolbar_help"); });
        $("div.help_settings_title").bind("click", function() { displayHelpContent("div.settings_help"); });
        $("div.help_history_title").bind("click", function() { displayHelpContent("div.history_help"); });
        $("div.help_security_title").bind("click", function() { displayHelpContent("div.security_help"); });
        $("div.help_support_title").bind("click", function() { displayHelpContent("div.support_help"); });
         */

        function displayHelpContent(contentClass) { 
            $("div.help_index,div.help_category").hide();
            $(contentClass).fadeIn();
        }

        function displayHelpSection(section) { 
            $("div.help_index,div.help_category").hide();
            section.fadeIn();
        }

    </script>

    <a class="heading help_index_title">Inc Online Help</a>

    <div class="help_index">
        <div class="help_composing_title">{$help_composing_title}</div>
        <div class="help_attachments_title">{$help_attachments_title}</div>
        <div class="help_sending_title">{$help_sending_title}</div>
        <div class="help_resuming_title">{$help_resuming_title}</div>
        <div class="help_toolbar_title">{$help_toolbar_title}</div>
        <div class="help_settings_title">{$help_settings_title}</div>
        <div class="help_history_title">{$help_history_title}</div>
        <div class="help_security_title">{$help_security_title}</div>
        <div class="help_support_title">{$help_support_title}</div>
    </div>

    <div class="help_category composing_help">
        <div class="help_info">
            <div>{$help_composing}</div>
            <div>{$help_composing_recipients}</div>
            <div>{$help_composing_subject}</div>
            <div>{$help_composing_message}</div>
        </div>
        <div class="help_attachments_title">{$help_attachments_title}</div>
        <img class="example" title="{$help_composing_example}" alt="{$help_composing_example}" src="images/{$locale}/help/compose.png" />
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category attachments_help">
        <div class="help_info">
            attachments
        </div>
        <img class="example" src="images/{$locale}/help/attachments.png" />
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category sending_help">
        <div class="help_info">
            sending
        </div>
        <div class="caption">{$help_composing_example}</div>
        <img class="example" title="{$help_composing_example}" alt="{$help_composing_example}" src="images/{$locale}/help/sending.png" />
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category resuming_help">
        resuming
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category toolbar_help">
        toolbar
        <img class="example" src="images/{$locale}/help/toolbar.png" />
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category settings_help">
        settings
        <img class="example" src="images/{$locale}/help/settings.png" />
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category history_help">
        history
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category security_help">
        security
        <img class="example" src="images/{$locale}/help/security.png" />
        <div class="help_to_index">up</div>
        <div class="help_to_index">up</div>
    </div>

    <div class="help_category support_help">
        support
        <img class="example" src="images/{$locale}/help/support.png" />
        <div class="help_to_index">up</div>
        <div class="help_to_index">up</div>
    </div>

</div>
HTML;

