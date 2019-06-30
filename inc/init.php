<?php
date_default_timezone_set('UTC');
session_start();
ob_start();

require_once(__DIR__.'/../conf/config.php');
require_once(__DIR__.'/function.php');

$ip = $_SERVER["REMOTE_ADDR"];
$date = time();
$db = DB();

if(!isset($_SESSION["login"])) {
    if(!@$_COOKIE["token"] == "") {
        AutoLogin($_COOKIE["token"]);
    }
}

$user = LoginControl(@$_SESSION["token"]);
if($user == 0) {
    $user = null;
}

if(isset($_SESSION["login"])) {
    if(!@$user["lang"] == "")
        define('USER_LANG', $user["lang"]);
    if(!@$user["theme"] == "" || !@$user["theme"] == "default")
        define("THEME", $conf["theme"]);
}else {
    define('USER_LANG', GetLanguage());
    define('THEME', $conf["theme"]);
}

if(GetLanguageDir(USER_LANG)) {
    require_once(__DIR__.'/../conf/lang/'.USER_LANG.'/main.php');
}else {
    require_once(__DIR__.'/../conf/lang/en/main.php');
}

?>