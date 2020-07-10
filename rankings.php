<?php

require_once('vendor/autoload.php');

use Dch\Covid\Countries;
use Dch\Covid\Worldwide;

$data = json_decode(
    file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'),
    true
);

$countries = new Countries($data);
$worldwide = new Worldwide($countries);

$ranks = $worldwide->getRankingsForCountryCode('USA');

echo "USA Rankings Per Day after initial case:\n";
foreach ($ranks as $day => $info) {
    echo $day . ") " . "cases: " . $info['cases']['rank'] . "/" . $info['cases']['total'];
    echo "; deaths: " . $info['deaths']['rank'] . "/" . $info['deaths']['total'] . "\n";
}

$countriesCurrentPositions = $worldwide->getStatsForOffset($day);

var_dump($countriesCurrentPositions);