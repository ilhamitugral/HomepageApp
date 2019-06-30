</body>
<script src="<?php echo $conf["site_url"]; ?>/plugins/jquery/jquery-ui.min.js"></script>
<script src="<?php echo $conf["site_url"]; ?>/js/main.js"></script>
<script src="<?php echo $conf["site_url"]; ?>/plugins/popper/popper.min.js"></script>
<script src="<?php echo $conf["site_url"]; ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</html>
<?php

mysqli_close($db);

?>