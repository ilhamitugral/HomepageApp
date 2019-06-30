<?php

require_once(__DIR__.'/weather.php');

$weather = new Weather();
$weather->DrawWeather("İstanbul, Ankara, İzmir");

?>