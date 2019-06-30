<?php

/*
* Search Engine API
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 21/05/19
* @copyright: Ilhami Tugral
*/

require_once(__DIR__.'/../../inc/init.php');

class SearchEngine {
    private $engine = [
        "google" => [
            "name" => "Google",
            "address" => "https://www.google.com.tr",
            "searchQuery" => "searchOnGoogle();"
        ]
    ];
    private $lang;

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require_once(__DIR__.'/lang/'.USER_LANG.'/searchengine.php');
        }else {
            require_once(__DIR__.'/lang/en/searchengine.php');
        }
        return $lang;
    }

    public function ShowSearchEngine($value) {
        global $conf;
        $value = strtolower($value);
        $lang = $this->GetLanguageFile();
        ?>
        <section class="section bg-transparent search-engine" data-name="SearchEngine">
            <div class="search-engine-area">
                <div class="search-area-panel">
                    <a href="<?php echo $this->engine[$value]["address"]; ?>" rel="nofollow"><div class="search-engine-logo <?php echo strtolower($this->engine[$value]["name"]); ?>"></div></a>
                    <div class="search-engine-search">
                        <input type="text" id="google-search-input" class="search-engine-hidden-input" name="search" placeholder="<?php echo $lang["search_".$value."_desc"]; ?>" autocomplete="off" autofocus/>
                    </div>
                    <div class="search-engine-buttons">
                        <button class="search-engine-search"><i class="fa fa-search"></i>&nbsp;<?php echo $lang["search"]; ?></button>
                    </div>
                </div>
            </div>
        </section>
        <script src="<?php echo $conf["site_url"]; ?>/apps/searchengine/searchengine.js"></script>
        <?php
    }
}

?>