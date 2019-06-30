<section class="container-fluid content">
    <section class="row fix-margin">
        <div class="col-md-2">
            <div class="drag-panel-show">
                <div class="section drag-panel-area"></div>
            </div>
        </div>
        <div class="col-md-10">
            <?php
                if($user["design"] == "") {
                    $query = $db->query("UPDATE users SET design = '$default' WHERE u_id = ".$user["u_id"]);
                    if($query) {
                        mysqli_commit($db);
                        echo Success("Varsayılan ayarlar başarıyla atandı. Sayfa yenileniyor...");
                        header("refresh:2;  url=/");
                    }else {
                        mysqli_rollback($db);
                        echo Warning("Varsayılan tema ayarlanırken sistemsel hata oluştu. Daha sonra tekrar deneyiniz.");
                    }
                }else {
                    require_once(__DIR__.'/../../inc/classes/class.apps.php');
                    $apps = new Apps();
                    ?>
                    <div class="row homepage fix-margin">
                        <div class="draggable-area col-12 col-sm-12 col-md-2" data-id="1">
                            <?php
                            // Sol Panel
                            $apps->CompileApp(0, $user["design"]);
                            ?>
                        </div>
                        <div class="draggable-area col-12 col-sm-12 col-md-8" data-id="2">
                            <?php
                            // Orta Panel
                            $apps->CompileApp(1, $user["design"]);
                            ?>
                        </div>
                        <div class="draggable-area col-12 col-sm-12 col-md-2" data-id="3">
                            <?php
                            // Sağ Panel
                            $apps->CompileApp(2, $user["design"]);
                            ?>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </section>
</section>
<script src="<?php echo $conf["site_url"]; ?>/js/drag.js"></script>