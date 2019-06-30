<section class="container content">
    <div class="row settings fix-margin">
        <div class="col-md-3">
            <section class="section">
                <ul class="navigation">
                    <li><a href="#" onclick="getUserInfoPage(this); changeActiveButton(this); return false;" class="active"><i class="fa fa-user"></i>&nbsp;<?php echo $lang["user_info"]; ?></a></li>
                    <li><a href="#" onclick="getAppsInfoPage(this); changeActiveButton(this); return false;"><i class="fa fa-cog"></i>&nbsp;<?php echo $lang["apps_info"]; ?></a></li>
                </ul>
            </section>
        </div>
        <div class="col-md-9">
            <section class="section">
                <section class="settings-content">
                    <h2 class="settings-title"><?php echo $lang["user_settings"]; ?></h2>
                    <hr>
                    <div class="settings-status"></div>
                    <div class="settings-content-area">
                        
                    </div>
                </section>
            </section>
        </div>
    </div>
</section>
<script src="<?php echo $conf["site_url"]; ?>/js/settings.js"></script>