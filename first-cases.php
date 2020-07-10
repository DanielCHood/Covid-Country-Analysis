<?php

require_once('vendor/autoload.php');

use Dch\Covid\Countries;
use Dch\Covid\Worldwide;

$data = json_decode(
    file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'),
    true
);

$startDates = [];

$countries = new Countries($data);
foreach ($countries->getCountryCodes() as $countryCode) {
    $country = $countries->getCountry($countryCode);

    try {
        $firstDate = $country->getFirstCaseDate();
        $startDates[$firstDate->format('Y-m-d')][] = $countryCode;
    } catch (Throwable $e) { }
}

uksort($startDates, function($a, $b) {
    $a = strtotime($a);
    $b = strtotime($b);
    if ($a === $b) {
        return 0;
    }

    return $a > $b ? -1 : 1;
});

$currentDate = new DateTime;

foreach ($startDates as $date => $countryCodes) {
    $startDate = new DateTime($date);
    $daysLapsed = $startDate->diff($currentDate)->days;

    echo $date . " (" . $daysLapsed . " days): " . count($countryCodes) . "; " . implode(', ', $countryCodes) . "\n";
}
