<?php require_once(__DIR__."/init.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- SEO Tags -->
    <meta name="copyright" content="&copy; <?php echo $conf["site_developer"]; ?>"/>
    <meta name="description" content="<?php echo @SITE_DESCRIPTION; ?>"/>
    <meta name="keywords" content="<?php echo @SITE_KEYWORDS; ?>"/>
    <meta name="robots" content="<?php echo @SITE_ROBOTS; ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo @SITE_TITLE; ?></title>
    <script src="<?php echo $conf["site_url"]; ?>/plugins/jquery/jquery-3.3.1.min.js"></script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo $conf["site_url"]; ?>/css/style.css"/>
    <link rel="stylesheet" href="<?php echo $conf["site_url"]; ?>/themes/<?php echo $conf["theme"]; ?>/css/style.css"/>
    <link rel="stylesheet" href="<?php echo $conf["site_url"]; ?>/plugins/fontawesome/css/all.css">
    <link rel="stylesheet" href="<?php echo $conf["site_url"]; ?>/plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
    <span class="site-name d-none"><?php echo $conf["site_url"]; ?></span>