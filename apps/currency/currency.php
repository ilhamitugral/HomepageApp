<?php
/*
* Döviz Kuru API
* @author: Ilhami Tugral <ilhamitugral@gmail.com>
* @date: 20/05/19
* @copyright: Ilhami Tugral 2019
*/

require_once(__DIR__.'/../../inc/init.php');

class Currency {

    private $usd, $aud, $dkk, $eur, $gbp, $chf, $sek, $cad, $nok, $sar, $jpy, $bgn, $ron, $rub, $irr, $cny, $pkr, $qar;
    private $currencyList = [];
    private $_lang;

    function __construct() {
        global $conf;
        $this->GetCurrency();
        $this->currencyList = $conf["currencyList"];
    }

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require(__DIR__.'/lang/'.USER_LANG.'/currency.php');
        }else {
            require(__DIR__.'/lang/en/currency.php');
        }
        return $lang;
    }

    public function GetCurrencyCheckbox($currency) {
        global $conf;
        $_lang = $this->GetLanguageFile();
        $data = simplexml_load_file($conf["tcmb_currency_url"]);
        $currency = strtolower($currency);
        $currency = explode(',', $currency);

        $checkbox = '';
        for($i = 0; $i <= 17; $i++) {
            $add = '';
            foreach($currency as $curr) {
                if($curr == $this->currencyList[$i]) {
                    $add = 'checked';
                }
            }
            $checkbox .= '
            <div class="col-3 col-xs-6">
                <label for="currency_'.$this->currencyList[$i].'"><input type="checkbox" name="currency_'.$this->currencyList[$i].'" id="currency_'.$this->currencyList[$i].'" '.$add.'/>&nbsp;'.$_lang[$this->currencyList[$i]."_text"].'</label>
            </div>';
        }
        return $checkbox;
    }

    public function SendCurrencyCheckboxData($data) {
        foreach($data as $currency) {
            
        }
    }

    public function GetCurrency() {
        global $conf;
        $data = simplexml_load_file($conf["tcmb_currency_url"]);

        $this->usd = $data->Currency[0];
        $this->aud = $data->Currency[1];
        $this->dkk = $data->Currency[2];
        $this->eur = $data->Currency[3];
        $this->gbp = $data->Currency[4];
        $this->chf = $data->Currency[5];
        $this->sek = $data->Currency[6];
        $this->cad = $data->Currency[7];
        $this->nok = $data->Currency[8];
        $this->sar = $data->Currency[9];
        $this->jpy = $data->Currency[10];
        $this->bgn = $data->Currency[11];
        $this->ron = $data->Currency[12];
        $this->rub = $data->Currency[13];
        $this->irr = $data->Currency[14];
        $this->cny = $data->Currency[15];
        $this->pkr = $data->Currency[16];
        $this->qar = $data->Currency[17];
    }

    private function RenderCurrency($value, $sign) {
        $_lang = $this->GetLanguageFile();
        if(@$value->ForexBuying == 0 && @$value->ForexSelling == 0) {
            return 0;
        }
        
        return '
        <tr>
            <td title="'.$_lang[$sign."_text"].'"><img src="http://www.tcmb.gov.tr/kurlar/kurlar_tr_dosyalar/images/'.strtoupper($sign).'.gif"/>&nbsp;'.strtoupper($sign).'</td>
            <td>'.$value->ForexBuying.'</td>
            <td>'.$value->ForexSelling.'</td>
        </tr>
        ';
    }

    private function ShowCurrency($currency) {
        return $this->RenderCurrency(@$this->$currency, $currency);
    }

    public function DrawCurrency($currency) {
        global $lang;
        $_lang = $this->GetLanguageFile();
        // Array değerlerine göre dövizleri listeleyeceğiz.
        $currency = explode(',', $currency);
        $text = "";
        foreach($currency as $value) {
            if(!$this->ShowCurrency($value) == 0)
                $text .= $this->ShowCurrency($value);
        }
        ?>
        <section class="section" data-name="Currency">
            <span class="title"><i class="fa fa-exchange-alt"></i>&nbsp;<?php echo $_lang["currency"]; ?></span>
            <table class="currency">
                <thead>
                    <tr>
                        <th><?php echo $_lang["exchange_rate"]; ?></th>
                        <th><?php echo $_lang["buying"]; ?></th>
                        <th><?php echo $_lang["selling"]; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $text; ?>
                </tbody>
            </table>
            <div class="section-footer">
                <a href="http://www.tcmb.gov.tr" rel="nofollow"><i class="fa fa-bolt mr-2"></i>&nbsp;T.C. Merkez Bankası</a>
            </div>
        </section>
        <?php
    }
}
?>
