<?php

function DB() {
    global $conf;
    $db = mysqli_connect($conf["db_hostname"], $conf["db_username"], $conf["db_password"], $conf["db_name"]);

    // Bağlantıyı Kuralım.
    if (mysqli_connect_errno()) {
        echo "Veritabanı bağlantısı başarısız.";
    }else {
        mysqli_query($db, "SET NAMES 'utf8'");
        return $db;
    }
}

function GetLanguage() {
    return substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
}

function GetLanguageDir($lang) {
    if(is_dir(__DIR__.'/../conf/lang/'.$lang))
        return true;
    else
        return false;
}

function Post($value) {
    return htmlspecialchars($_POST[$value]);
}

function Get($value) {
    return htmlspecialchars($_GET[$value]);
}

function Encrypt($value) {
    return md5($value);
}

function Error($value) {
    return '<div class="alert alert-danger" role="alert"><i class="fa fa-times-circle"></i>&nbsp;'.$value.'</div>';
}

function Success($value) {
    return '<div class="alert alert-success" role="alert"><i class="fa fa-check-circle"></i>&nbsp;'.$value.'</div>';
}

function Warning($value) {
    return '<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-circle"></i>&nbsp;'.$value.'</div>';
}

function Info($value) {
    return '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i>&nbsp;'.$value.'</div>';
}

function ShortDescription($text, $value = 30) {
    if(!$text == "") {
        $cut = explode(' ', $text);
        $count = count($cut);

        if($count < $value) {
           $value = $count;
        }

        $text = '';
        for($i = 0; $i < $value; $i++) {
            $text .= $cut[$i].' ';
        }
    }

    return $text.' ...';
}

function LoginSite($username = "", $password = "") {
    global $db, $lang, $date;
    if($username == "") {
        $data = [
            "status" => "error",
            "message" => $lang["username_or_email_cannot_blank"]
        ];
    }elseif($password == "") {
        $data = [
            "status" => "error",
            "message" => $lang["password_cannot_blank"]
        ];
    }else {
        $password = Encrypt($password);
        $query = mysqli_query($db, "SELECT u_id, token, username, email, password FROM users WHERE (username = '$username' || email = '$username') && password = '$password'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $row = mysqli_fetch_assoc($query);
            $query = mysqli_query($db, "UPDATE users SET date = '$date' WHERE u_id = ".$row["u_id"]);
            if($query) {
                mysqli_commit($db);
                $_SESSION["token"] = $row["token"];
                $_SESSION["login"] = true;
                SetAutoLogin($row["token"]);
                $data = [
                    "status" => "success",
                    "message" => $lang["sign_in_success"]
                ];
            }else {
                mysqli_rollback($db);
                $data = [
                    "status" => "warning",
                    "message" => $lang["sign_in_error"],
                ];
            }
        }else {
            $data = [
                "status" => "error",
                "message" => $lang["username_or_email_not_match"]
            ];
        }
    }
    return json_encode($data);
}

function RegisterSite($email = "", $password = "", $repassword) {
    global $db, $ip, $date, $lang;
    if($email == "") {
        $data = [
            "status" => "error",
            "message" => $lang["email_cannot_blank"]
        ];
    }elseif($password == "") {
        $data = [
            "status" => "error",
            "message" => $lang["password_cannot_blank"]
        ];
    }elseif(!$password == $repassword) {
        $data = [
            "status" => "error",
            "message" => $lang["passwords_not_match"]
        ];
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $data = [
            "status" => "error",
            "message" => $lang["invalid_email"]
        ];
    }else {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);

        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            $data = [
                "status" => "error",
                "message" => $lang["invalid_password"]
            ];
        }else {
            $password = Encrypt($password);
            $query = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $data = [
                    "status" => "error",
                    "message" => $lang["email_using_by_someone"]
                ];
            }else {
                $username = explode('@', $email);
                $query = mysqli_query($db, "SELECT username FROM users WHERE username = '".$username[0]."'");
                $count = mysqli_num_rows($query);

                if($count > 0) {
                    $username = "";   
                }else {
                    $username = $username[0];
                }

                $token = Encrypt(rand(1, 999999));
                $query = mysqli_query($db, "INSERT INTO users SET ip = '$ip', date = '$date', register_date = '$date', lang='".USER_LANG."', token = '$token', email = '$email', username = '$username', name = '', surname = '', password = '$password', is_active = 0, theme = 'default', design = '', search_engine = 1");
                $error = $db->error;
                if($query) {
                    mysqli_commit($db);
                    $_SESSION["login"] = true;
                    $_SESSION["token"] = $token;
                    SetAutoLogin($token);
                    $data = [
                        "status" => "success",
                        "message" => $lang["sign_up_sucess"]
                    ];
                }else {
                    mysqli_rollback($db);
                    $data = [
                        "status" => "warning",
                        "message" => $lang["sign_up_error"],
                        "code" => $error
                    ];
                }
            }
        }
    }
    return json_encode($data);
}

function LoginControl($token = "") {
    global $db;
    if(!$token == "") {
        $query = mysqli_query($db, "SELECT * FROM users WHERE token = '$token'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            return mysqli_fetch_assoc($query);
        }else {
            return 0;
        }
    }
}

function AutoLogin($token) {
    global $db, $conf;
    $query = mysqli_query($db, "SELECT token FROM users WHERE token = '$token'");
    $count = mysqli_num_rows($query);

    if($count > 0) {
        $row = mysqli_fetch_assoc($query);
        $_SESSION["login"] = true;
        $_SESSION["token"] = $row["token"];
        header("location:".$conf["site_url"].$_SERVER[REQUEST_URI]);
    }
}

function Logout() {
    global $lang;
    session_destroy();
    ob_end_flush();
    SetAutoLogin("!");
    $data = [
        "status" => "success",
        "message" => $lang["sign_out_success"]
    ];
    return json_encode($data);
}

function SetAutoLogin($token = "", $time = 315360000) {
    if(!$token == "")
        setcookie("token", $token, time() + $time, "/");
    elseif($token == "!")
        setcookie("token", "", time() - 3600);
}

function GetSearchQuery($value = "", $engine = "") {
    if($value == "")
        return false;
    if($engine == "")
        $engine = "google";

    switch($engine) {
        case "google":
            $data = file_get_contents('http://suggestqueries.google.com/complete/search?client=firefox&q='.$value);

            return $data;
        break;
    }
    
}

function SelectLanguage($lang = "") {
    global $db;
    $query = mysqli_query($db, "SELECT code, name, is_active FROM language WHERE is_active = 1");
    $count = mysqli_num_rows($query);

    if($count > 0) {
        $langs = '';
        while($row = mysqli_fetch_array($query)) {
            if($row["code"] == $lang)
                $add = 'selected';
            else
                $add = '';
            $langs .= '<option value="'.$row["code"].'" '.$add.'>'.$row["name"].'</option>';
        }
        return $langs;
    }else {
        return '<option value="none">'.$lang["no_language"].'</value>';
    }
}

function SelectTheme($theme = "") {
    global $db, $lang;
    $query = mysqli_query($db, "SELECT theme, name, is_active FROM themes WHERE is_active = 1");
    $count = mysqli_num_rows($query);

    if($count > 0) {
        $themes = '';
        while($row = mysqli_fetch_array($query)) {
            if($row["theme"] == $theme)
                $add = 'selected';
            else
                $add = '';
            $themes .= '<option value="'.$row["theme"].'" '.$add.'>'.$lang[$row["name"]].'</option>';
        }
        return $themes;
    }else {
        return '<option value="none">'.$lang["no_theme"].'</value>';
    }
}

function GetUserSettings() {
    global $user, $conf;
    if(isset($_SESSION["login"])) {
        if($user["settings"] == "") {
            return json_decode($conf["settings"], true);
        }else {
            return json_decode($user["settings"], true);
        }
    }else {
        return json_decode($conf["settings"], true);
    }
}

function SaveUserSettings($data) {
    global $db, $user, $lang;
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $query = mysqli_query($db, "UPDATE users SET settings = '$data' WHERE u_id = ".$user["u_id"]);
    if($query) {
        mysqli_commit($db);
        $json = [
            "status" => "success",
            "message" => $lang["settings_updated"]
        ];
    }else {
        mysqli_rollback($db);
        $json = [
            "status" => "warning",
            "message" => $lang["settings_update_error"]
        ];
    }
    return json_encode($json);
}

?>