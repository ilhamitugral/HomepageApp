<?php
require_once(__DIR__.'/inc/init.php');

if(GetLanguageDir(USER_LANG)) {
    require_once(__DIR__.'/conf/lang/'.USER_LANG.'/main.php');
}else {
    require_once(__DIR__.'/conf/lang/en/main.php');
}

$page = @Post("page");

switch($page) {
    case "":
    default:
        header("location:/", 404);
    break;

    case "GetLoginPage":
        require_once(__DIR__.'/themes/'.THEME.'/login-template.php');
    break;

    case "GetRegisterPage":
        require_once(__DIR__.'/themes/'.THEME.'/register-template.php');
    break;

    case "TryLogin":
        $username = @Post("username");
        $password = @Post("password");

        echo LoginSite($username, $password);
    break;

    case "TryRegister":
        $username = @Post("username");
        $password = @Post("password");
        $repassword = @Post("repassword");

        echo RegisterSite($username, $password, $repassword);
    break;

    case "LogoutSite":
        echo Logout();
    break;

    case "GetCustomizePanel":
        if(@isset($_SESSION["login"])) {
            require_once(__DIR__.'/inc/classes/class.apps.php');

            $apps = new Apps();
            echo $apps->RenderApps($user["design"]);
        }else {
            header("location: /");
        }
    break;

    case "GetCustomizePanelPage":
        $data = @Post("data");

        if($data == "") {
            echo 'Geçersiz istek!';
        }else {
            require_once(__DIR__.'/inc/classes/class.apps.php');
            $apps = new Apps();

            echo $apps->CompileApp(0, $data);
        }
    break;

    case "UpdateDesign":
        $data = @Post("data");

        if($data == "") {
            echo "Geçersiz istek.";
        }else {
            $data = str_replace('{}', '', $data);
            // Compile edilecek...
            $query = $db->query("UPDATE users SET design = '$data' WHERE u_id = ".$user["u_id"]);
            if($query) {
                mysqli_commit($db);
                echo "OK";
            }else {
                mysqli_rollback($db);
                echo "Veritabanına güncelleme yaparken bir hata oluştu. Daha sonra tekrar deneyniniz.";
            }
        }
    break;

    case "SaveSettings":
        $username = @Post("username");
        $name = @Post("name");
        $surname = @Post("surname");
        $email = @Post("email");
        $old_pass = @Post("old_password");
        $new_pass = @Post("new_password");
        $re_pass = @Post("re_password");
        $language = @Post("language");
        $theme = @Post("theme");

        $renew_pass = true;
        if($old_pass == "" || $new_pass == "" || $re_pass == "") {
            $old_pass = "";
            $new_pass = "";
            $re_pass = "";
            $renew_pass = false;
        }

        if($username == "") {
            $data = [
                "status" => "error",
                "message" => $lang["username_cannot_blank"]
            ];
        }elseif($email == "") {
            $data = [
                "status" => "error",
                "message" => $lang["email_cannot_blank"],
            ];
        }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data = [
                "status" => "error",
                "message" => $lang["invalid_email"]
            ];
        }else {
            $pass_query = "";
            if($renew_pass) {
                $uppercase = preg_match('@[A-Z]@', $new_pass);
                $lowercase = preg_match('@[a-z]@', $new_pass);
                $number = preg_match('@[0-9]@', $new_pass);

                if(!$uppercase || !$lowercase || !$number || strlen($new_pass) < 8) {
                    $data = [
                        "status" => "error",
                        "message" => $lang["invalid_password"]
                    ];
                    
                    echo json_encode($data);
                    exit();
                }elseif(!$new_pass == $old_pass) {
                    $data = [
                        "status" => "error",
                        "message" => $lang["passwords_not_match"]
                    ];

                    echo json_encode($data);
                    exit();
                }else {
                    $password = Encrypt($old_pass);
                    $query = mysqli_query($db, "SELECT u_id, password FROM users WHERE password = '$password' && u_id = ".$user["u_id"]);
                    $count = mysqli_num_rows($query);

                    if($count > 0) {
                        $password = Encrypt($new_pass);
                        $pass_query = ", password = '$password'";
                    }else {
                        $data = [
                            "status" => "error",
                            "message" => $lang["password_wrong"]
                        ];
                        echo json_encode($data);
                        exit();
                    }
                }
            }

            // Kullanıcı Adı kontrolü
            $query = mysqli_query($db, "SELECT u_id, username FROM users WHERE username = '$username' && u_id <> ".$user["u_id"]);
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $data = [
                    "status" => "error",
                    "message" => $lang["username_using_by_someone"]
                ];
                echo json_encode($data);
                exit();
            }

            // Dil Kontrolü
            if($language == '' || is_numeric($language)) {
                $language = 'en';
            }else {
                $query = mysqli_query($db, "SELECT code, is_active FROM language WHERE code = '$language' && is_active = 1");
                $count = mysqli_num_rows($query);

                if($count == 0) {
                    $language = "en";
                }
            }

            // Tema Kontrolü
            if($theme == '' || is_numeric($theme)) {
                $theme = 'default';
            }else {
                $query = mysqli_query($db, "SELECT theme, is_active FROM themes WHERE theme = '$theme' && is_active = 1");
                $count = mysqli_num_rows($query);

                if($count == 0) {
                    $theme = $conf["theme"];
                }
            }

            $query = mysqli_query($db, "UPDATE users SET username = '$username', name = '$name', surname = '$surname', email = '$email', lang = '$language', theme = '$theme'$pass_query WHERE u_id = ".$user["u_id"]);
            $error = $db->error;
            if($query) {
                mysqli_commit($db);
                $data = [
                    "status" => "success",
                    "message" => $lang["settings_updated"]
                ];
            }else {
                mysqli_rollback($db);
                $data = [
                    "status" => "warning",
                    "message" => $lang["settings_update_error"],
                    "code" => $error
                ];
            }
        }
        echo json_encode($data);
    break;

    case "GetSettingsPage":
        ?>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-username"><?php echo $lang["username"]; ?>:</label>
                    <div class="input-group">
                        <input type="text" name="settings-username" id="settings-username" class="form-control" value="<?php echo $user["username"]; ?>" placeholder="@<?php echo $lang["username"]; ?>" maxlength="16" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-name"><?php echo $lang["name"]; ?>:</label>
                    <div class="input-group">
                        <input type="text" name="settings-name" id="settings-name" class="form-control" value="<?php echo $user["name"]; ?>" placeholder="<?php echo $lang["name"]; ?>" maxlength="32" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-name"><?php echo $lang["surname"]; ?>:</label>
                    <div class="input-group">
                        <input type="text" name="settings-surname" id="settings-surname" class="form-control" value="<?php echo $user["surname"]; ?>" placeholder="<?php echo $lang["surname"]; ?>" maxlength="16" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="settings-name"><?php echo $lang["email"]; ?>:</label>
            <div class="input-group">
                <input type="email" name="settings-email" id="settings-email" class="form-control" value="<?php echo $user["email"]; ?>" placeholder="<?php echo $lang["email"]; ?>" maxlength="32" autocomplete="off"/>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-old-password"><?php echo $lang["old_password"]; ?>:</label>
                    <div class="input-group">
                        <input type="password" name="settings-old-password" id="settings-old-password" class="form-control" autocomplete="off" value="" placeholder="<?php echo $lang["old_password"]; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-new-password"><?php echo $lang["new_password"]; ?>:</label>
                    <div class="input-group">
                        <input type="password" name="settings-new-password" id="settings-new-password" class="form-control" autocomplete="off" value="" placeholder="<?php echo $lang["new_password"]; ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="settings-repassword"><?php echo $lang["re_password"]; ?>:</label>
                    <div class="input-group">
                        <input type="password" name="settings-repassword" id="settings-repassword" class="form-control" autocomplete="off" value="" placeholder="<?php echo $lang["re_password"]; ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="settings-language"><?php echo $lang["language"]; ?>:</label>
            <div class="input-group">
                <select name="settings-language" id="settings-language" class="form-control">
                    <?php echo SelectLanguage(USER_LANG); ?>
                </select>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="settings-theme"><?php echo $lang["theme"]; ?>:</label>
            <div class="input-group">
                <select name="settings-theme" id="settings-theme" class="form-control">
                    <option value="default"><?php echo $lang["default_theme"]; ?></option>
                    <?php echo SelectTheme($user["theme"]); ?>
                </select>
            </div>
        </div>
        <hr>
        <button class="btn submit ml-auto mr-3 d-block" onclick="saveSettings();"><?php echo $lang["save"]; ?>&nbsp;<i class="fa fa-arrow-right"></i></button>
        <?php
    break;

    case "GetAppsSettings":
        $query = mysqli_query($db, "SELECT name, description, is_active FROM apps WHERE is_active = 1");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $apps = '';
            while($row = mysqli_fetch_array($query)) {
                if(is_dir(__DIR__.'/apps/'.strtolower($row["name"]).'/lang/')) {
                    require_once(__DIR__.'/apps/'.strtolower($row["name"]).'/lang/'.USER_LANG.'/'.strtolower($row["name"]).'.php');
                }

                if(file_exists(__DIR__.'/apps/'.strtolower($row["name"]).'/settings.php')) {
                    $apps .= '
                    <div class="apps" onClick="getAppSettings(\''.strtolower($row["name"]).'\');">
                        <h3><i class="fa fa-bars"></i>&nbsp;'.$lang[strtolower($row["name"])].'</h3>
                        <p>'.$row["description"].'</p>
                    </div>
                    ';
                }
            }

            echo $apps;
        }
    break;

    case "AppSettings":
        ?>
        <button class="submit" onclick="returnApps();"><i class="fa fa-arrow-left"></i>&nbsp;<?php echo $lang["return_back"]; ?></button>
        <hr>
        <?php
        $page2 = @Post("settings");

        $query = mysqli_query($db, "SELECT name, is_active FROM apps WHERE is_active = 1");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            require_once(__DIR__.'/apps/'.strtolower($page2).'/settings.php');
        }else {
            echo Error($lang["settings_not_available"]);
        }
    break;

    case "saveCurrencySettings":
        require_once(__DIR__.'/apps/currency/settings.php');
    break;

    case "saveNewsSettings":
        require_once(__DIR__.'/apps/news/settings.php');
    break;
}

?>