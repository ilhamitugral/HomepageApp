<section class="access-panel register-panel">
    <span class="title"><?php echo $lang["sign_in"] ?></span>
    <div class="register-result"></div>
    <div class="form-group">
        <label for="register-username"><?php echo $lang["email"]; ?>:</label>
        <div class="input-group">
            <input type="text" name="register-username" class="form-control" id="register-username" value="" placeholder="<?php echo $lang["email"]; ?>" maxlength="255"/>
        </div>
    </div>
    <div class="form-group">
        <label for="register-password"><?php echo $lang["password"]; ?>:</label>
        <div class="input-group">
            <input type="password" name="register-password" id="register-password" class="form-control" value="" placeholder="<?php echo $lang["password"]; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <label for="register-repassword"><?php echo $lang["re_password"]; ?>:</label>
        <div class="input-group">
            <input type="password" name="register-repassword" id="register-repassword" class="form-control" value="" placeholder="<?php echo $lang["re_password"]; ?>"/>
        </div>
    </div>
    <div class="form-group">
        <button class="submit" onclick="postRegister(this);"><?php echo $lang["register"]; ?>&nbsp;<i class="fa fa-arrow-right"></i>&nbsp;</button>
    </div>
    <label><?php echo $lang["accept_terms_signup"]; ?></label>
</section>
