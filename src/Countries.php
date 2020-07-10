<?php

namespace Dch\Covid;

use Throwable;

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
                $this->owid_wrl = new Country($country);
                continue;
            }

            $this->countries[$countryCode] = new Country($country);
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

    public function getWorldWideAverages(string $type, int $offset): float
    {
        $values = array_filter($this->averages[$offset][$type]);
        $average = array_sum($values) / count($values);

        return $average;
    }
}
