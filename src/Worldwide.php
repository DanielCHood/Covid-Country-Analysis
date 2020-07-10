<?php

namespace Dch\Covid;

class Worldwide
{
    private $countries = [];
    private $averages = [];
    private $rankings = [];

    public function __construct(Countries $countries)
    {
        $this->countries = $countries;
        $this->compileStats();
        $this->compileRankings();
    }

    public function getRankingsForCountryCode(string $countryCode): array
    {
        return $this->rankings[$countryCode] ?? [];
    }

    public function getStatsForOffset(int $offset): array
    {
        return $this->averages[$offset] ?? [];
    }

    private function compileStats(): void
    {
        foreach ($this->countries->getCountryCodes() as $countryCode) {
            $country = $this->countries->getCountry($countryCode);
            $dayIndex = 0;

            while (true) {
                $dayIndex++;
                $dailyStat = $country->getDayByOffset($dayIndex);
                if (is_null($dailyStat)) {
                    break;
                }

                $this->averages[$dayIndex]['cases'][$countryCode] = $dailyStat->getTotalCasesPerMillion();
                $this->averages[$dayIndex]['deaths'][$countryCode] = $dailyStat->getTotalDeathsPerMillion();
                $this->averages[$dayIndex]['tests'][$countryCode] = $dailyStat->getTotalTestsPerThousand();
            }
        }
    }

    private function compileRankings(): void
    {
        foreach ($this->countries->getCountryCodes() as $countryCode) {
            $country = $this->countries->getCountry($countryCode);

            $dayIndex = 0;
            while (true) {
                $dayIndex++;
                $dailyStat = $country->getDayByOffset($dayIndex);
                if (is_null($dailyStat)) {
                    break;
                }
    
                $cases = $dailyStat->getTotalCasesPerMillion();
                $deaths = $dailyStat->getTotalDeathsPerMillion();
                $tests = $dailyStat->getTotalTestsPerThousand();

                foreach (['cases', 'deaths', 'tests'] as $type) {
                    $this->rankings[$countryCode][$dayIndex][$type] = [
                        'rank' => $this->getPositionForOffsetByType($type, $dayIndex, $$type),
                        'total' => $this->getTotalQualifyingForOffset($type, $dayIndex)
                    ];
                }
            }
        }
    }

    private function getPositionForOffsetByType(string $type, int $offset, float $stat): int
    {
        $worldwide = array_filter($this->averages[$offset][$type]);
        uasort($worldwide, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return ($a > $b) ? -1 : 1;
        });

        $rank = 0;
        foreach ($worldwide as $country => $value) {
            if ($value >= $stat) {
                $rank++;
            }
        }

        return $rank;
    }

    private function getTotalQualifyingForOffset(string $type, int $offset): int
    {
        $worldwide = array_filter($this->averages[$offset][$type]);
        return count($worldwide);
    }
}
