<?php
require_once 'i18n.php';
setDomain(basename($_SERVER['SCRIPT_NAME'], '.php'));

$sendLabel = _('send label');
$toLabel = _('to label');
$to_tooltip = _('to tooltip');
$subjectLabel = _('subject label');
$deleteLabel = _('delete label');
$fileLabel = _('file browse label');
$archiveLabel = _('archive label');
$archive_tooltip = _('archive tooltip');
$compressLabel = _('compress label');
$compress_tooltip = _('compress tooltip');
$sslLabel = _('ssl label');
$ssl_tooltip = _('ssl tooltip');
$noticeLabel = _('download notice label');
$notice_tooltip = _('download notice tooltip');
$preservationLabel = _('preservation label');
$preservation_tooltip = _('preservation tooltip');
$password_label = _('password label');
$password_tooltip = _('password tooltip');

$send_tooltip = _('send tooltip');
$clear_tooltip = _('clear tooltip');
$paused_tooltip = _('paused tooltip');
$history_tooltip = _('history tooltip');
$addressbook_tooltip = _('addressbook tooltip');
$preferences_tooltip = _('preferences tooltip');
$help_tooltip = _('help tooltip');
$file_browse_tooltip = _('file browse tooltip');
$delete_tooltip = _('delete tooltip');

$invalid_email_msg = _('invalid email msg');
$invalid_password_msg = _('invalid password msg');
$no_attachments_msg = _('no attachments msg');
$message_exceeds_msg =  _('message exceeds msg');
$recipients_exceeds_msg = _('recipients exceeds msg');
$subject_exceeds_msg = _('subject exceeds msg');
$empty_subject_warn = _('empty subject warn');
$empty_message_warn = _('empty message warn');
$confirm_send = _('confirm send');
$confirm_clear = _('confirm clear');

$files_submit_info = _('files submit info');
$total_progress_label = _('total progress label');
$txing = _('txing');
$cancel_label = _('cancel send');
$pause = _('pause send');
$finished_label = _('finished');
$unable_label = _('unable');

$one = _('one');
$three = _('three');
$five = _('five');
$seven = _('seven');

$processing = _('processing');
$processing_done = _('processing complete');
$confirm_processing_abort = _('confirm processing abort');
$email_processing_conflict = _('no email during processing');
$emailing_warning = _('emailing warning');
$unsent_warning = _('unsent warning');

$java_required = sprintf(_('java required'), 'Inc', 'Java 1.5');

//shortcuts TODO
$send_key = 's';
$file_key = 'f';
$delete_key = 'd';
$clear_key = 'c';
$list_key = 'l';
$history_key = 'i';
$address_key = 'k';
$option_key = 'o';
$help_key = 'h';

?>
