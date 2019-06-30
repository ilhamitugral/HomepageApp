<section class="container-fluid content">
<?php
    $default = '[{Notes}{News}][{SearchEngine}][{Calendar}{Weather}{Currency}]';
    if(@$user["design"] == "" && @$_SESSION["login"]) {
        if(!@$user["u_id"] == 0) {
            $query = $db->query("UPDATE users SET design = '$default' WHERE u_id = ".$user["u_id"]);
            if($query) {
                mysqli_commit($db);
                echo Success("Varsayılan ayarlar başarıyla atandı. Sayfa yenileniyor...");
                header("refresh:2;  url=/");
            }else {
                mysqli_rollback($db);
                echo Warning("Varsayılan tema ayarlanırken sistemsel hata oluştu. Daha sonra tekrar deneyiniz.");
            }
        }
    }else {
        if(!@$_SESSION["login"]) {
            $user["design"] = $default;
            $log_in = '
            <section class="section">
                <span class="title"><i class="fa fa-user"></i>&nbsp;'.$lang["sign_in"].'</span>
                <div class="login-panel">
                    <p>'.$lang["signin_description"].'</p>
                </div>
            </section>
            ';
        }

        require_once(__DIR__.'/../../inc/classes/class.apps.php');
        $apps = new Apps();
        ?>
        <div class="row homepage fix-margin">
            <div class="draggable-area col-12 col-sm-12 col-md-2">
                <?php
                // Sol Panel
                $apps->CompileApp(0, $user["design"]);
                ?>
            </div>
            <div class="draggable-area col-12 col-sm-12 col-md-8">
                <?php
                // Orta Panel
                $apps->CompileApp(1, $user["design"]);
                ?>
            </div>
            <div class="draggable-area col-12 col-sm-12 col-md-2">
                <?php
                // Sağ Panel
                if(!isset($_SESSION["login"]))
                    echo $log_in;
                $apps->CompileApp(2, $user["design"]);
                ?>
            </div>
        </div>
        <?php
    }
?>
</section>