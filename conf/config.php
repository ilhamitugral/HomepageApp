<?php

// Veritabanı Konfigürasyonu
$conf["db_hostname"] = "localhost";     // Veritabanı Sunucu adı
$conf["db_username"] = "root";          // Veritabanı Kullanıcı adı
$conf["db_password"] = "";              // Veritabanı Şifresi
$conf["db_name"] = "homepage";          // Veritabanı Adı

// Site Konfigürasyonu
$conf["site_name"] = "Homepage";
$conf["site_url"] = "http://homepageApp.hostfree.pw";
$conf["site_developer"] = "İlhami TUĞRAL";
$conf["developer_email"] = "ilhamitugral[at]gmail.com";
$conf["contact_email"] = "ilhamitugral[at]gmail.com";

// Site Ayarları
$conf["theme"] = "ocean-blue";

// API Listesi
$conf["hurriyet_api"] = "b8ac006b07fa4bdf80d747c350b65280";
$conf["weather_api"] = "da9c522de43326cc1186ab964757792f";
$conf["google_api"] = "AIzaSyDOLi7etAhqihBVOIwL-TjCXEMFDxIBI4Y";

// URL Listesi
$conf["tcmb_currency_url"] = "https://www.tcmb.gov.tr/kurlar/today.xml";

// İçerik Ayarları
$conf["kelvin"] = 273.15;
$conf["currencyList"] = ["usd", "aud", "dkk", "eur", "gbp", "chf", "sek", "cad", "nok", "sar", "jpy", "bgn", "ron", "rub", "irr", "cny", "pkr", "qar"];

// Varsayılan kullanıcı ayarları
$conf["settings"] = json_encode([
    "calendar" => [],
    "currency" => [
        "currencyUnits" => "usd,eur,gbp"
    ],
    "news" => [
        "site" => "Hürriyet"
    ],
    "searchengine" => [
        "engine" => "Google"
    ],
    "weather" => [
        "cities" => "Istanbul,Izmir,Ankara"
    ]
]);
?>
