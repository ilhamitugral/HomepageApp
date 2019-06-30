<section class="access-panel login-panel">
    <span class="title"><?php echo $lang["sign_in"]; ?></span>
    <div class="login-result"></div>
    <div class="form-group">
        <label for="login-username"><?php echo $lang["username_or_email"]; ?>:</label>
        <div class="input-group">
            <input type="text" name="login-username" class="form-control" id="login-username" value="" placeholder="<?php echo $lang["username_or_email"]; ?>" maxlength="255"/>
        </div>
    </div>
    <div class="form-group">
        <label for="login-password"><?php echo $lang["password"]; ?>:</label>
        <div class="input-group">
            <input type="password" name="login-password" id="login-password" class="form-control" value="" placeholder="<?php echo $lang["password"]; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <button class="submit" onclick="postLogin(this);" name="login"><?php echo $lang["login"]; ?>&nbsp;<i class="fa fa-arrow-right"></i>&nbsp;</button>
    </div>
</section>