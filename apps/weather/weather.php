<?php
/*
* Hava Durumu API
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 05/06/19
* @copyright: Ilhami Tugral 2019
*/

require_once(__DIR__.'/../../conf/config.php');

class Weather {
    private $_lang;

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require(__DIR__.'/../../conf/lang/'.USER_LANG.'/main.php');
        }else {
            require(__DIR__.'/../../conf/lang/en/main.php');
        }
        return $lang;
    }

    private function GetWeather($value) {
        global $conf;
        $province = explode(',', $value);

        $weather = '';
        foreach($province as $row) {
            $data = $this->GetWeatherData($row);
            $temp = explode('.', $data["main"]["temp"] - $conf["kelvin"]);

            $weather .= '
            <div class="weather">
                <div class="weather-image">
                    <span class="weather-icon" style="background-image: url(\'http://openweathermap.org/img/w/'.$data["weather"][0]["icon"].'.png\');"></span>
                </div>
                <div class="weather-info">
                    <span class="weather-value">'.$temp[0].'&nbsp;°C</span>
                    <span class="weather-province">'.$data["name"].', TR</span> 
                </div>
            </div>
            ';
        }
        return $weather;
    }

    function GetWeatherData($location) {
        global $conf;
        $url = 'http://api.openweathermap.org/data/2.5/weather?q='.$location.',tr&appid='.$conf["weather_api"];
        $content = file_get_contents($url);
        $data = json_decode($content, true);
        return $data;
    }

    public function DrawWeather($value) {
        $_lang = $this->GetLanguageFile();
        ?>
        <section class="section" data-name="Weather">
            <span class="title"><i class="fa fa-cloud-sun"></i>&nbsp;<?php echo $_lang["weather"]; ?></span>
            <?php echo $this->GetWeather($value); ?>
            <div class="section-footer">
                <a href="https://openweathermap.org" rel="nofollow"><i class="fa fa-bolt mr-1"></i>&nbsp;OpenWeatherMap.org</a>
            </div>
        </section>
        <?php
    }
}

?>