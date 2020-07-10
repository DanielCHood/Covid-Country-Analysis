<?php

namespace Dch\Covid;

use DateTime;

class Country
{
    private $countryCode;
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

    public function __construct(string $countryCode, array $data)
    {
        $this->countryCode = $countryCode;

        foreach ($data as $key => $value) {
            $setterMethod = "set" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
            if (is_callable([$this, $setterMethod])) {
                $this->{$setterMethod}($value);
            } elseif (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getFirstCaseDate(): DateTime
    {
        return $this->statCollection->getFirstCaseEntry()->getDate();
    }

    public function getDayByOffset(int $offset): ?DailyStat
    {
        return $this->statCollection->getByOffset($offset);
    }

    public function getLastCaseEntryIndex(): int
    {
        return $this->statCollection->getLastCaseEntryIndex();
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getPopulationDensity(): float
    {
        return $this->population_density ?? 0.00;
    }

    public function getGdpPerCapita(): float
    {
        return $this->gdp_per_capita ?? 0.00;
    }

    public function getHospitalBedsPerThousand(): float
    {
        return $this->hospital_beds_per_thousand ?? 0.00;
    }

    private function setData($data)
    {
        $this->statCollection = new StatCollection($data);
    }
}
