<?php

/*
* Haberler Ayarları
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 09/06/19
* @copyright: Ilhami Tugral 2019
*/
require_once(__DIR__.'/news.php');

$news = new News();

if(@$_POST["saveSettings"]) {
    if(isset($_SESSION["login"])) {
        $value = @Post("news");

        if($value == "") {
            $json = [
                "status" => "error",
                "message" => $lang["news_source_cannot_blank"]
            ];

            echo json_encode($json);
        }elseif(!$news->CompareNews($value)) {
            $json = [
                "status" => "error",
                "message" => $lang["news_source_not_found"]
            ];
            echo json_encode($json);
        }else {
            $userData = GetUserSettings();
            $userData["news"]["site"] = $value;

            echo SaveUserSettings($userData);
        }
    }
}else {
    ?>
    <script src="<?php echo $conf["site_url"]; ?>/apps/news/news.js"></script>
    <div class="form-group">
        <label for="news-settings-source"><?php echo $lang["news_source"]; ?></label>
        <div class="input-group">
            <select name="news-settings-source" id="news-settings-source" class="form-control">
                <?php echo $news->SelectNewsForm(); ?>
            </select>
        </div>
    </div>
    <button class="btn submit ml-auto mr-1 d-block" onclick="saveNews();"><?php echo $lang["save"]; ?>&nbsp;<i class="fa fa-arrow-right"></i></button>
    <?php
}