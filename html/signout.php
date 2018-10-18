<?php
session_start();
require '../include/utils.php';

if (isset($locale_display[$_SESSION['Language']]))
    $language = '?Language=' . $locale_display[$_SESSION['Language']];

session_destroy();

header('Location: ' . urlPath() . $language);

?>
