<?php
require_once("vendor/autoload.php");

use Dch\Covid\Countries;

$data = json_decode(
    file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'),
    true
);

$countries = new Countries($data);
$countries->compileAveragesDaysAfterFirst();

$countryCode = "ITA";

echo "Comparing " . $countryCode . " to World Averages after x days since first case:\n";
foreach ([5, 10, 30, 60, 90, 120, 150] as $offset) {
    $countryStats = $countries->getCountry($countryCode)->getDayByOffset($offset);

    $casesDifference = $countries->getDifferenceFromAverageForCountry(
        $countryCode,
        'cases',
        $offset
    );
    
    $deathsDifference = $countries->getDifferenceFromAverageForCountry(
        $countryCode,
        'deaths',
        $offset
    );

    echo "After " . $offset . " days:\n";
    echo " - Date: " . $countryStats->getDate()->format('Y-m-d') . "\n";
    echo " - Cases per million: " . $countryStats->getTotalCasesPerMillion() . "\n";
    echo " - Deaths per million: " . $countryStats->getTotalDeathsPerMillion() . "\n";
    echo " - Cases per million above avg: " . $casesDifference . "\n";
    echo " - Deaths per million above avg: " . $deathsDifference . "\n";
}

