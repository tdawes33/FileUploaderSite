<?php
require_once 'i18n.php';

setDomain(basename($_SERVER['SCRIPT_NAME'], '.php'));

$important_html = '<span class="important">';
$important_html_end = '</span>';

$pitch_one = sprintf(_('pitch one'), $product_html, $product_html, $important_html, $important_html_end);
$pitch_two = sprintf(_('pitch two'), $product_html);

define('FEATURE_COUNT', 11);
$feature_header = _('feature header');
for ($i=0; $i<FEATURE_COUNT; $i++)
	$feature[$i] = _('feature ' . $i);

define('REQUIREMENT_COUNT', 5);
$requirement_header = _('requirement header');
for ($i=0; $i<REQUIREMENT_COUNT; $i++) {
	$requirement_title[$i] = _('requirement ' . $i . ' title');
	$requirement_info[$i] = _('requirement ' . $i . ' info');
}

?>
