<?php

require_once(__DIR__.'/../init.php');

class Apps {

    private $apps = '';

    private function AnalysisValue($order, $value) {
        if($order == 0)
            return explode('][', substr(substr($value, 1), 0, -1));
        else
            return explode('}{', substr(substr($value, 1), 0, -1));
    }

    private function GetAppsFormDB() {
        global $db;
        $query = mysqli_query($db, "SELECT name, is_active FROM apps WHERE is_active = 1");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $apps = '';
            while($row = mysqli_fetch_array($query)) {
                $apps .= $row["name"].',';
            }
            return substr($apps, 0, -1);
        }else {
            return '';
        }
    }

    private function SearchValueInApp($appName, $render = false) {
        $this->apps = $this->GetAppsFormDB();
        foreach(explode(',', $this->apps) as $app) {
            if($render) {
                $group = $this->AnalysisValue(0, $appName);
                for($i = 0; $i < count($group); $i++) {
                    $data = $this->AnalysisValue(1, $group[$i]);

                    for($j = 0; $j < count($data); $j++) {
                        $control = false;
                        if($app == $data[$j]) {
                            $control = true;
                            break;
                        }
                    }

                    if($control)
                        break;
                }

                if(!$control)
                    $this->ShowApps($app, $render);
            }else {
                if($app == $appName) {
                    $this->ShowApps($app, $render);
                    break;
                }
            }
            
        }
    }

    public function CompileApp($order = 0, $value) {
        if(strstr($value, '[') || strstr($value, '{')) {
            $group = $this->AnalysisValue(0, $value);
            $data = $this->AnalysisValue(1, $group[$order]);

            for($i = 0; $i < count($data); $i++) {
                $this->SearchValueInApp($data[$i], false);
            }
        }else {
            $this->SearchValueInApp($value, false);
        }
    }

    public function RenderApps($value) {
        $this->SearchValueInApp($value, true);
    }

    private function DrawApps($value = "", $renderDraggable = false) {
        return '<div class="draggable-panel drag-panel-'.strtolower($value).'" data-name="'.$value.'" data-drag="'.$renderDraggable.'"><i class="fa fa-bars"></i>&nbsp;'.$value.'</div>';
    }

    private function ShowApps($app = "", $render = false, $renderDraggable = false) {
        global $conf;
        if($app == "")
            return '';
        
        if($render)
            echo $this->DrawApps($app, $renderDraggable);
        else
            return require_once(__DIR__.'/../../apps/'.strtolower($app).'/index.php');
    }
}

?>