<?php
require_once(__DIR__.'/inc/init.php');

define('SITE_TITLE', $lang["customize"]." | ".$conf["site_name"]);
define('SITE_DESCRIPTION', $conf["site_name"].'\'i özelleştirmek için sadece sürükleyin ve bırakın.');
define('SITE_KEYWORDS', '');
define('SITE_ROBOTS', 'NOINDEX, NOFOLLOW');

if(!@isset($_SESSION["login"])) {
    header("location: /");
}
// Header
require_once(__DIR__.'/inc/header.php');
require_once(__DIR__.'/themes/'.THEME.'/header-template.php');

// Content
require_once(__DIR__.'/themes/'.THEME.'/customize-template.php');

// Footer
require_once(__DIR__.'/inc/footer.php');
?>