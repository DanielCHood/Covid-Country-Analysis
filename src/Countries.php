<?php

namespace Dch\Covid;

class Countries
{
    private const OWID_WRL = 'OWID_WRL';

    private $countries = [];
    private $owid_wrl;
    private $averages = [];

    public function __construct(array $data)
    {
        foreach ($data as $countryCode => $country) {
            if ($countryCode === self::OWID_WRL) {
                $this->owid_wrl = new Country($countryCode, $country);
                continue;
            }

            $this->countries[$countryCode] = new Country($countryCode, $country);
        }
    }

    public function getCountryCodes(): array
    {
        return array_keys($this->countries);
    }

    public function getCountry(string $countryCode): Country
    {
        if (!isset($this->countries[$countryCode])) {
            throw new Exception("No data found for " . $countryCode);
        }

        return $this->countries[$countryCode];
    }

    public function getClosest(Country $country, string $method): Country
    {
        if (!is_callable([$country, $method])) {
            throw new Exception("Country::" . $method . ' does not exist.');
        }

        $dayEntries = $country->getLastCaseEntryIndex();
        $closestValue = null;
        $closestCountry = null;
        $baseline = $country->{$method}();

        foreach ($this->countries as $comparison) {
            if ($comparison === $country) {
                continue;
            }

            if (!($comparison->getDayByOffset(floor($dayEntries)/2) instanceof DailyStat)) {
                continue;
            }

            $difference = abs($baseline - $comparison->{$method}());
            if (is_null($closestValue) || $difference < $closestValue) {
                $closestValue = $difference;
                $closestCountry = $comparison;
            }
        }

        return $closestCountry;
    }

    public function getWorldWideAverages(string $type, int $offset): float
    {
        $values = array_filter($this->averages[$offset][$type]);
        $average = array_sum($values) / count($values);

        return $average;
    }
}
