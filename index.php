<?php
require_once(__DIR__.'/inc/init.php');

define('SITE_TITLE', $conf["site_name"]);
define('SITE_DESCRIPTION', $conf["site_name"].'\'e hoşgeldiniz!');
define('SITE_KEYWORDS', '');
define('SITE_ROBOTS', 'INDEX, FOLLOW');

// Header
require_once(__DIR__.'/inc/header.php');
require_once(__DIR__.'/themes/'.THEME.'/header-template.php');

// Content
require_once(__DIR__.'/themes/'.THEME.'/homepage-template.php');

// Footer
require_once(__DIR__.'/inc/footer.php');
?>