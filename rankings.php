<?php

require_once('vendor/autoload.php');

use Dch\Covid\Countries;
use Dch\Covid\Worldwide;

$options = getopt("", ['country:', 'days::']);
if (empty($options['country'])) {
    die("Please select a country. Example: php rankings.php --country='USA' --days='5,10,30'");
}

$countryCode = $options['country'] ?? null;
$days = explode(',', $options['days']) ?? [5, 10, 30, 60, 90, 120, 150];

$data = json_decode(
    file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'),
    true
);

$countries = new Countries($data);
$worldwide = new Worldwide($countries);

$ranks = $worldwide->getRankingsForCountryCode($countryCode);

echo "USA Rankings Per Day after initial case:\n";
foreach ($ranks as $day => $info) {
    if (!in_array($day, $days)) {
        continue;
    }

    echo $day . ") " . "cases: " . $info['cases']['rank'] . "/" . $info['cases']['total'];
    echo "; tests: " . $info['tests']['rank'] . "/" . $info['tests']['total'];
    echo "; deaths: " . $info['deaths']['rank'] . "/" . $info['deaths']['total'] . "\n";
}
