<nav class="header">
    <div class="page-links">
        <h1><a href="<?php echo $conf["site_url"]; ?>"><?php echo $conf["site_name"]; ?></a></h1>
    </div>
    <div class="empty-panel"></div>
    <div class="user-panel">
        <?php if(!isset($_SESSION["login"])) {?>
            <a href="#" onclick="loginPanel(); return false;"><i class="fa fa-user"></i>&nbsp;<?php echo $lang["sign_in"]; ?></a>
            <a href="#" onclick="registerPanel(); return false;"><i class="fa fa-sign-in-alt"></i>&nbsp;<?php echo $lang["sign_up"]; ?></a>
        <?php
        }else { ?>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle customize-link" id="homepage-header-dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i>&nbsp;<?php echo $lang["settings"]; ?></a>
                <div class="dropdown-menu homepage-dropdown" aria-labelledby="homepage-header-dropdown-menu">
                    <span class="dropdown-item"><?php echo (!$user["username"] == "") ? '<i class="fa fa-at"></i>&nbsp;'.$user["username"] : $user["email"]; ?></span>
                    <a href="<?php echo $conf["site_url"]; ?>/customize" class="dropdown-item customize-link"><i class="fa fa-eye"></i>&nbsp;<span class="customize-text"><?php echo $lang["customize"]; ?></span></a>
                    <a href="<?php echo $conf["site_url"]; ?>/settings" class="dropdown-item"><i class="fa fa-user"></i>&nbsp;<?php echo $lang["user_settings"]; ?></a>
                    <a href="#" onclick="logout(); return false;" class="dropdown-item"><i class="fa fa-sign-out-alt"></i>&nbsp;<?php echo $lang["sign_out"]; ?></a>
                </div>
            </div>
        <?php } ?>
    </div>
</nav>
<div class="access-panel"></div>