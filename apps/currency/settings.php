<?php

/*
* Döviz Kuru Ayarları
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 08/06/19
* @copyright: Ilhami Tugral 2019
*/

require_once(__DIR__.'/currency.php');
require_once(__DIR__.'/lang/'.USER_LANG.'/currency.php');

$currency = new Currency();

if(@$_POST["save_settings"]) {
    if(isset($_SESSION["login"])) {
        $data = explode(',', @Post("data"));
        if(count($data) == count($conf["currencyList"])) {
            $currencyData = '';
            for($i = 0; $i < count($data); $i++) {
                if($data[$i] == 1) {
                    $currencyData .= $conf["currencyList"][$i].',';
                }
            }
            
            $currencyData = substr($currencyData, 0, -1);
            
            $userData = GetUserSettings();
            $userData["currency"]["currencyUnits"] = $currencyData;
            $userData = json_encode($userData, true);

            echo SaveUserSettings($userData);
        }else {
            $json = [
                "status" => "error",
                "message" => $lang["invalid_operation"]
            ];
        }
        echo json_encode($json);
    }
}else {
    $data = GetUserSettings();
    
    $currency_list = "";
    foreach($conf["currencyList"] as $curr) {
        $currency_list .= $curr.',';
    }
    $currency_list = substr($currency_list, 0, -1);

    ?>
    <script src="<?php echo $conf["site_url"]; ?>/apps/currency/currency.js"></script>
    <label class="font-weight-bold"><?php echo $lang["choose_currency"]; ?>:</label>
    <div class="row">
        <?php echo $currency->GetCurrencyCheckbox($data["currency"]["currencyUnits"]); ?>
    </div>
    <span class="currency-list d-none"><?php echo $currency_list; ?></span>
    <button class="btn submit d-block ml-auto mr-1" onclick="saveCurrency();"><?php echo $lang["save"]; ?>&nbsp;<i class="fa fa-arrow-right"></i></button>
    <?php
}
?>