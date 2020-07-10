<?php
require_once("vendor/autoload.php");

use Dch\Covid\Countries;

$data = json_decode(
    file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'),
    true
);

$countries = new Countries($data);

$countryCodes = ['USA', 'ITA', 'CHE', 'GBR'];

foreach ($countryCodes as $countryCode) {
    $country = $countries->getCountry($countryCode);

    echo $countryCode . " after x days since first case:\n";

    foreach ([5, 10, 30, 60, 90, 120, 150] as $offset) {
        $countryStats = $country->getDayByOffset($offset);
        if ($countryStats === null) {
            continue;
        }

        echo "After " . $offset . " days:\n";
        echo " - Date: " . $countryStats->getDate()->format('Y-m-d') . "\n";
        echo " - Cases per million: " . $countryStats->getTotalCasesPerMillion() . "\n";
        echo " - Deaths per million: " . $countryStats->getTotalDeathsPerMillion() . "\n";
    }

    echo "\n";
}

