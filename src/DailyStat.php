<?php

namespace Dch\Covid;

use DateTime;

class DailyStat
{
    private $date;
    private $new_cases;
    private $new_deaths;
    private $total_cases_per_million;
    private $total_deaths_per_million;
    private $total_tests_per_thousand;
    private $unknown = [];

    public function __construct(array $stats)
    {
        foreach ($stats as $key => $value) {
            if (!property_exists($this, $key)) {
                $this->unknown[$key] = $value;
                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function getDate(): DateTime
    {
        if (is_string($this->date)) {
            $this->date = new DateTime($this->date);
        }

        return $this->date;
    }

    public function getNewCases(): int
    {
        return $this->new_cases ?? 0;
    }

    public function getNewDeaths(): int
    {
        return $this->new_deaths ?? 0;
    }

    public function getTotalCasesPerMillion(): float
    {
        return $this->total_cases_per_million ?? 0.00;
    }

    public function getTotalDeathsPerMillion(): float
    {
        return $this->total_deaths_per_million ?? 0.00;
    }

    public function getTotalTestsPerThousand(): float
    {
        return $this->total_tests_per_thousand ?? 0.00;
    }
}
