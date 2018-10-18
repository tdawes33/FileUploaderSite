<?php

$locale_file = ($locale == 'en') ? '' : '_j';

print <<<HTML
        <div class="footer">
            <div class="footer_content">
                <div class="address">
                    <div class="company">Inc, Inc.</div>
                    <div>WA 98101 USA</div>
                </div>
                <div class="about_us"><a href="{$incp_about_url}">{$inc_about_link}</a></div>
            </div>
        </div>
	<script type="text/javascript" src="js/lib/jquery.js"></script>
    <script type="text/javascript" src="js/lib/jquery.pngfix.js"></script>
	<script type="text/javascript" src="js/lib/jquery.ui.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
HTML;

?>
