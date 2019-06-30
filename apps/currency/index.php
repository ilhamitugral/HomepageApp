<?php
require_once(__DIR__.'/../../inc/init.php');
require_once(__DIR__.'/currency.php');

$data = GetUserSettings();

$currency = new Currency();
$currency->DrawCurrency($data["currency"]["currencyUnits"]);

?>