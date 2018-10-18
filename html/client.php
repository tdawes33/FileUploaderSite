<?php
session_start();
require_once '../include/utils.php';
require_once '../include/client-i18n.php';

$service = '/scripts/Inc/Inc/mmwebsrvc.dll';

$max_email_subject = 512;
$max_email_recipient = 512;

//require username & token for client page
if (!$username || !$token)
    header('Location: ' . urlPath() . SIGNIN_PAGE);

//create log page link
$log_url = getTemplatePagePath("MonthlyTrafficStatusDetail", false);

header('Content-type: text/html; charset=utf-8');
require '../include/head.php';

print <<<HTML
	<link rel="stylesheet" type="text/css" href="css/client.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/facebox.css" />
	<script type="text/javascript">
		username = "{$username}";
		token = "{$token}";
		language = "{$locale}";
		language_en = "{$language_en}";

		invalid_email_msg = "{$invalid_email_msg}";
		invalid_password_msg = "{$invalid_password_msg}";
		empty_subject_warn = "{$empty_subject_warn}";
		no_attachments_msg = "{$no_attachments_msg}";
		message_exceeds_msg = "{$message_exceeds_msg}";
		recipients_exceeds_msg = "{$recipients_exceeds_msg}";
		subject_exceeds_msg = "{$subject_exceeds_msg}";
		empty_message_warn = "{$empty_message_warn}";
		confirm_send = "{$confirm_send}";
		confirm_clear = "{$confirm_clear}";
		txing = "{$txing}";
		files_submit_info = "{$files_submit_info}";
		cancel_label  = "{$cancel_label}";
		pause = "{$pause}";
		finished_label = "{$finished_label}";
		unable_label = "{$unable_label}";
		server = "{$server}";
		session_id = "{$session_id}";
		processing = "{$processing}";
		processing_done = "{$processing_done}";
		confirm_processing_abort = "{$confirm_processing_abort}";
		email_processing_conflict = "{$email_processing_conflict}";
		emailing_warning = "${emailing_warning}";
		unsent_warning = "${unsent_warning}";
	    java_required = "${java_required}";
	</script>
</head>
<body>
	<div class="main">
HTML;

require '../include/navigation.php';

print <<<HTML
        <form id="email_formm" name="email_formm" action="{$_SERVER['PHP_SELF']}" method="post" onsubmit="return false;">
            <div class="email_form">
                <div class="preferences slidefx" id="preferences_block">
					<div class="preference">
						<input id="archive" name="archive" type="checkbox" class="disableable" tabindex="65" />
							<label for="archive" title="{$archive_tooltip}">{$archiveLabel}</label>
					</div>
					<div class="preference">
						<input id="compress" name="compress" type="checkbox" class="disableable" tabindex="70" />
						<label for="compress" title="{$compress_tooltip}">{$compressLabel}</label>
					</div>
					<div class="preference">
						<input id="ssl" name="ssl" type="checkbox" class="disableable" tabindex="75" />
						<label for="ssl" title="{$ssl_tooltip}">{$sslLabel}</label>
					</div>
					<div class="preference">
						<input id="notice" name="notice" type="checkbox" class="disableable" tabindex="80" />
							<label for="notice" title="{$notice_tooltip}">{$noticeLabel}</label>
					</div>
					<div class="preference">
						<select id="preservation" name="preservation" class="disableable" tabindex="85">
							<option value="1" selected="selected">{$one}</option>
							<option value="3">{$three}</option>
							<option value="5">{$five}</option>
							<option value="7">{$seven}</option>
						</select>
						<label for="preservation" title="{$preservation_tooltip}">{$preservationLabel}</label>
					</div>
					<div class="preference">
						<input id="password" name="password" type="text" class="disableable" maxlength="10" tabindex="90" />
							<label for="password" title="{$password_tooltip}">{$password_label}</label>
					</div>
				</div>
                <div class="preference_beam">&nbsp;</div>
				<div class="send_button">
                    <button id="send" class="disableable" tabindex="20" accesskey="{$send_key}" title="{$send_tooltip}">
                        <img src="images/send.png" class="toolbar_button" alt="{$send_tooltip}" title="{send_tooltip}" />
                        <span>{$sendLabel}</span>
                    </button>
				</div>
				<div class="toolbar">
					<button id="clear" class="disableable" title="{$clear_tooltip}" tabindex="35" accesskey="{$clear_key}">
						<img src="images/clear.png" class="toolbar_button" alt="{$clear_tooltip}" title="{$clear_tooltip}" />
					</button>
					<button id="view_incomplete" class="disableable" title="{$paused_tooltip}" tabindex="40" accesskey="{$list_key}">
						<img src="images/cancelled.png" class="toolbar_button" alt="{$paused_tooltip}" title="{$paused_tooltip}" />
					</button>
					<button id="view_history" class="disableable" title="{$history_tooltip}" tabindex="45" accesskey="{$history_key}">
						<img src="images/history.png" class="toolbar_button" alt="{$history_tooltip}" title="{$history_tooltip}" />
					</button>
					<button id="view_preferences" class="disableable" title="{$preferences_tooltip}" tabindex="55" accesskey="{$option_key}">
						<img src="images/preferences.png" class="toolbar_button" alt="{$preferences_tooltip}" title="{$preferences_tooltip}" />
					</button>
					<button id="view_help" class="disableable" title="{$help_tooltip}" tabindex="60" accesskey="{$help_key}">
						<img src="images/help.png" class="toolbar_button" alt="{$help_tooltip}" title="{$help_tooltip}" />
					</button>
				</div>
				<div class="recipients flow_container">
					<input type="text" name="recipients" id="recipients" maxlength="{$max_email_recipient}" value="" class="disableable" tabindex="5" />
					<label for="recipients" title="{$to_tooltip}">{$toLabel}</label>
				</div>
				<div class="subject flow_container">
					<input type="text" name="subject" id="subject" maxlength="{$max_email_subject}" value="" class="disableable" tabindex="10" />
					<label for="subject">{$subjectLabel}</label>
				</div>
				<div class="message_area flow_container">
					<textarea name="message" id="message" rows="5" cols="10" class="disableable" tabindex="15"></textarea>
				</div>
                <div class="attachments flow_container">
					<div class="uploading_info"><!-- hidden until uploading -->
						<div class="progress">
							<div class="progress_info">
								<div class="progressbars"><span class="progressbar" id="file_progress">0</span></div>
                                <div id="current_file">&nbsp;</div>
							</div>
							<div class="progress_info">
								<div class="progressbars"><span class="progressbar" id="total_progress">0</span></div>
								<div id="total_upload">{$total_progress_label}</div>
							</div>
						</div>
						<div id="rate">&nbsp;</div>
						<button id="complete" class="cancel_upload">cancel</button>
						<div id="results">&nbsp;</div>
					</div>
                    <script type="text/javascript" src="js/fileuploader.js"></script>
                    <div class="file_area">
                        <script type="text/javascript">
                            printApplet("{$server}", "{$service}", "{$username}", "{$token}", "{$session_id}", "{$locale}");
                        </script>
                    </div>
                    <div class="attachments_buttons">
						<div class="status" id="status">&nbsp;</div>
                        <button id="filechooser_button" class="disableable" tabindex="30" 
                            title="{$file_browse_tooltip}" accesskey="{$file_key}">{$fileLabel}</button>
                        <button id="delete_button" class="disableable" tabindex="25" 
                            title="{$delete_tooltip}" accesskey="{$delete_key}">{$deleteLabel}</button>
						<div class="processing_holder">&nbsp;</div>
						<div class="processing">
							<img src="images/wait2.gif" class="processing_icon" alt="processing" />
							<span id="processing_info">&nbsp;</span>
						</div>
						<br />
					</div>
				</div>
			</div>
		</form>
HTML;

require '../include/footer.php';

print <<<HTML
        <script type="text/javascript" src="js/lib/jquery.progressbar.js"></script>
        <script type="text/javascript" src="js/lib/facebox.js"></script>
        <script type="text/javascript" src="js/client.js"></script>

	</div>
</body>
</html>
HTML;

?>
