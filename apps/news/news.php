<?php

require_once(__DIR__.'/../../conf/config.php');

class News {
    private $news = [
        "Hürriyet" => [
            "name" => "Hürriyet",
            "site" => "https://hurriyet.com.tr",
            "lastNews" => ""
        ]
    ];
    private $_lang;

    function __construct() {
        global $conf;
        $this->news["Hürriyet"]["lastNews"] = "https://api.hurriyet.com.tr/v1/articles?apikey=".$conf["hurriyet_api"];
    }

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require(__DIR__.'/../../conf/lang/'.USER_LANG.'/main.php');
        }else {
            require(__DIR__.'/../../conf/lang/en/main.php');
        }
        return $lang;
    }

    public function CompareNews($value) {
        foreach($this->news as $news) {
            if($news["name"] == $value) {
                return true;
            }
        }
        return false;
    }

    private function GetNews($news) {
        global $conf;
        $url = $news["lastNews"];
        $content = file_get_contents($url);
        $data = json_decode($content, true);

        $news = '';
        for($i = 0; $i < 10; $i++) {
            $image = (@$data[$i]["Files"][0]["FileUrl"] == null) ? '' : $data[$i]["Files"][0]["FileUrl"];

            $news .= '
            <a href="'.$data[$i]["Url"].'?utm_source='.$conf["site_name"].'" rel="nofollow" target="_blank">
                <div class="news-info">
                    <div class="news-left-side">
                        <div class="news-image-panel">
                            <div class="news-picture" style="background-image:url('.$image.');"></div>
                        </div>
                    </div>
                    <div class="news-right-side">
                        <span class="news-title">'.$data[$i]["Title"].'</span>
                        <span class="news-desc">'.ShortDescription($data[$i]["Description"], 10).'</span>
                    </div>
                </div>
            </a>
            ';
        }

        return $news;
    }

    public function SelectNewsForm() {
        $selectbox = '';
        foreach($this->news as $news) {
            $selectbox .= '<option value="'.$news["name"].'">'.$news["name"].'</option>';
        }
        return $selectbox;
    }

    private function ShowNews($value) {
        foreach($this->news as $news) {
            if($news["name"] == $value) {
                return $this->GetNews($news);
            }
        }
    }

    public function DrawNews($value) {
        $_lang = $this->GetLanguageFile();
        ?>
        <section class="section" data-name="News">
            <span class="title"><i class="fa fa-newspaper"></i>&nbsp;<?php echo $_lang["news"]; ?></span>
            <div class="news">
                <?php echo $this->ShowNews($value); ?>
            </div>
            <div class="section-footer">
                <a href="<?php echo $this->news["Hürriyet"]["site"]; ?>" rel="nofollow"><i class="fa fa-bolt mr-1"></i>&nbsp;<?php echo $this->news["Hürriyet"]["name"]; ?></a>
            </div>
        </section>
        <?php
    }
}
?>