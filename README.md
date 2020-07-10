# Overview

This library is used for processing the owid-covid-data.json feed from `covid.ourworldindata.org`.

# Installation

`composer require dch/covid`

# Usage

## Initialization

The contents of `https://covid.ourworldindata.org/data/owid-covid-data.json` should be passed into `Dch\Covid\Countries`, as an array, to initialize the data set such as below:
```php
use Dch\Covid\Countries;

$data = json_decode(file_get_contents('https://covid.ourworldindata.org/data/owid-covid-data.json'), true);

$countries = new Countries($data);
```

## Getting a country's information

```
$country = $countries->getCountry('USA'); // returns the `Dch\Covid\Country` instance for the USA.
$country->getFirstCaseDate(); // returns the DateTime instance of the first day that there were cases in the country
$country->getDaybyOffset(5); // gets the daily stats for the 5th day _after_ the first case so if `getFirstCaseDate() was Jan 1, this was will return Jan 6.
$country->getLocation(); // accessor for Country::$location
$country->getPopulationDensity(); // accessor for Country::$population_density
$country->getGdpPerCapita(); // accessor for Country::$population_density
$country->getHospitalBedsPerThousand(); // accessor for Country::$hospital_beds_per_thousand
```

## Getting stats for a day

`$country->getDayByOffset(int $offset)` returns an instance of `Dch\Covid\DailyStat`.

```
$country = $countries->getCountry('USA');
$dayStats = $country->getDayByOffset(5);

$dayStats->getNewCases(); // returns the number of new cases
$dayStats->getNewDeaths(); // returns the number of new deaths
$dayStats->getTotalCasesPerMillion(); // returns the number of cases per million residents
$dayStats->getTotalDeathsPerMillion(); // returns the number of deaths per million residents
$dayStats->getTotalTestsPerThousand(); // returns the number of tests done per thousand residents
```
