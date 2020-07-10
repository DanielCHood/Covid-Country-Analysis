<?php

namespace Dch\Covid;

use Throwable;

class Countries {
    private $countries = [];
    private $averages = [];

    public function __construct(array $data) {
        foreach ($data as $countryCode => $country) {
            $this->countries[$countryCode] = new Country($country);
        }
    }

    public function compileAveragesDaysAfterFirst(): void {
        foreach (array_keys($this->countries) as $countryCode) {
            $country = $this->getCountry($countryCode);
            $dayIndex = 0;

            try {
                while(true) {
                    $dayIndex++;
                    $dailyStat = $country->getDayByOffset($dayIndex);
                    if (is_null($dailyStat)) {
                        break;
                    }

                    $this->averages[$dayIndex]['cases'][] = $dailyStat->getTotalCasesPerMillion();
                    $this->averages[$dayIndex]['deaths'][] = $dailyStat->getTotalDeathsPerMillion();
                }
            } catch (Throwable $e) {
            }
        }
    }

    public function getCountry(string $countryCode): Country {
        if (!isset($this->countries[$countryCode])) {
            throw new Exception("No data found for " . $countryCode);
        }

        return $this->countries[$countryCode];
    }

    public function getDifferenceFromAverageForCountry(string $countryCode, string $type, int $offset): float {
        $countryStat = $this->getCountry($countryCode)->getDayByOffset($offset);
        
        $stats = [
            'cases' => $countryStat->getTotalCasesPerMillion(),
            'deaths' => $countryStat->getTotalDeathsPerMillion()
        ];
        
        $worldAverage = $this->getWorldWideAverages($type, $offset);

        return $stats[$type] - $worldAverage;
    }

    public function getWorldWideAverages(string $type, int $offset): float {
        $values = array_filter($this->averages[$offset][$type]);
        $average = array_sum($values) / count($values);

        return $average;
    }
}
