<?php

namespace Dch\Covid;

use DateTime;

class Country {
    private $continent;
    private $location;
    private $population;
    private $population_density;
    private $median_age;
    private $aged_65_older;
    private $aged_70_older;
    private $gdp_per_capita;
    private $cvd_death_rate;
    private $diabetes_prevalence;
    private $handwashing_facilities;
    private $hospital_beds_per_thousand;
    private $life_expectancy;
    private $extreme_poverty;
    private $female_smokers;
    private $male_smokers;

    private $statCollection;

    public function __construct(array $data) {
        foreach ($data as $key => $value) {
            $setterMethod = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
            if (is_callable([$this, $setterMethod])) {
                $this->{$setterMethod}($value);
            }
            elseif (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getFirstCaseDate(): DateTime {
        return $this->statCollection->getFirstCaseEntry()->getDate();
    }

    public function getDayByOffset(int $offset): ?DailyStat {
        return $this->statCollection->getByOffset($offset);
    }

    private function setData($data) {
        $this->statCollection = new StatCollection($data);
    }
}
